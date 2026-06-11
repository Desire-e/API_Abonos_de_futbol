<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller; 
use Illuminate\Support\Facades\Auth;


use Illuminate\Http\Request;
use App\Models\Abono;
use App\Models\TipoAbono;

// Reglas personalizadas para los validate([])
use App\Rules\DniRule;
use App\Rules\NacimientoRule;
use App\Rules\CuentaBancariaRule;

// Validator para validar formularios o datos recibidos (POST, JSON, ...)
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

// Ampliado - uso de storage para servir imagenes almacenadas
use Illuminate\Support\Facades\Storage;


/**
 * CODIGOS DE ESTADO:
 * 200	Ok
 * 400 Bad Request	    *Demasiado genérico
 * 404	Not found   	Cliente (pidió algo inexistente)
 * 401	No autorizado	Cliente (no autenticado) 
 * 422 Campos invalidos
 * 500	Error interno	Servidor
 */

class AbonosController extends Controller {

    /*********************************
     * GET
     * REGISTROS DE TABLA tipo_abonos
     *********************************/
    public function tipoAbonos() {
        try {
            $tiposAbono = TipoAbono::all();

            return response()->json([
                'status' => true,
                'data' => $tiposAbono,
                'message' => null,
                'errors' => null,
            ], 200); // OK

        } 
        catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => null,
                'errors' => $e->getMessage()
            ], 404); // NOT FOUND
        }
    }



    /*********************************
     * POST
     * INSERT EN TABLA abonos
     *********************************/
    public function insert(Request $request) {
        try{
            // 1º Valida campos de nuevo abono comprado
            $validacion = Validator::make($request->all(), [
                    'nombre'=> [
                        'required', 'string', 'regex:/^[A-Za-zÁÉÍÓÚÑáéíóúñ ]+$/u'
                    ],
                    'dni'=> [ 
                        'required', new DniRule  // regla personalizada
                    ],  
                    'nacimiento'=> [
                        'required', new NacimientoRule 
                    ],
                    'telefono'=> [ 
                        'required', 'regex:/^[679]\d{8}$/'
                    ],
                    'cuentaBancaria'=> [ 
                        'required', new CuentaBancariaRule 
                    ],
                    'abonoTipo'=> [
                        'required', 'exists:tipo_abonos,id' // valida que el valor existe en columna id de tabla tipo_abonos
                    ],           
                    'terminosCheck'=>'required',

                    // Autogenerados, no necesitan validación
                    'id'=>'', 'fecha'=>'', 'abonado'=>'', 'edad'=>'', 'asiento'=>'', 'precio'=>'', 'abonoTipoId'=>''
                ], 

                // Otros mensajes de error personalizados
                [
                    'terminosCheck.required' => 'Debe aceptar los términos.',
                    'nombre.regex' => 'El nombre solo puede contener letras.',
                    'telefono.regex' => 'El teléfono debe ser numérico, comenzar por 6, 7 o 9 y contener 8 números .',
                ]
            );


            // Si validacion falla
            if($validacion->fails()) {
                return response()->json([
                    'status' => false,
                    'data' => $request->all(),
                    'message' => null,
                    'errors' => $validacion->errors(),
                ], 422); // INVALID DATA
            }
            else {
                // 2º Prepara campos (autogenerados + rellenados por usuario)

                // Obtener objeto TipoAbono del seleccionado
                $tipo = TipoAbono::where('id', $request->abonoTipo)->first();

                // Generar asiento y validarlo
                $codigoAsiento = null;
                $intentos = 0;
                do {
                    $codigo = $this->setAsiento($tipo->descripcion);
                    $existe = Abono::where('asiento', $codigo)->exists(); // exists() da bool si encuentra o no registro
                    
                    // Si ya existe, regenera nuevo asiento
                    if (!$existe) {
                        $codigoAsiento = $codigo;
                        break;
                    }

                    $intentos++;
                    
                    // Si se re-generó ya 5 veces, termina, respuesta fallida
                } while($intentos < 5);

                if ($codigoAsiento === null) {
                    // inyecta error manual para el campo autogenerado
                    $validacion->errors()->add('asiento', 'No hay asientos disponibles en este momento.');

                    return response()->json([
                        'status' => false,
                        'data' => $request->all(),
                        'message' => null,
                        'errors' => $validacion->errors(),
                    ], 400); // BAD REQUEST
                }

                // Preparar campos para insertar en BD
                $datosAbono = [
                    'fecha'=> now(), 
                    'abonado'=> $this->setAbonado($request->nombre, $request->dni), 
                    'edad'=> $edad = $this->setEdad($request->nacimiento),
                    'telefono'=> $request->telefono, 
                    'cuenta_bancaria' => str_replace([' ', '-'], '', $request->cuentaBancaria), // quita espacios/guiones 
                    'tipo'=> $tipo->id,
                    'asiento'=> $codigoAsiento,
                    'precio'=> $this->setPrecio($edad, $tipo->precio, Abono::all()) 
                ];


                // 4º Insertar en BD
                $abono = Abono::create($datosAbono);


                // 5º Manda respuesta
                return response()->json([
                    'status' => true,
                    // manda id en respuesta para obtener datos del abono después si se quisiera
                    'data' => [ 'id'=> $abono->id ],
                    'message' => 'Compra realizada correctamente',
                    'errors' => null,
                ], 200); // OK
            }

        }
        // Respuesta fallida ante excepciones
        catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => null,
                'errors' => $e->getMessage()
            ], 500); // INTERNAL SERVER ERROR
        }
    }



    /*********************************
     * GET
     * OBTENER UN ABONO
     *********************************/
    public function ticket($id) {
        try {
            // Obtener abono por id
            $abono = Abono::findOrFail($id); 

            // Reformatear los datos de la BD para mostrarlos en la vista del ticket
            $abonado = explode(" - ", $abono->abonado);
            $nombre = trim($abonado[0]);
            $dni = trim($abonado[1]);

            $dataT = [ 
                // Convierte string de fecha, en un objeto de fecha Carbon de laravel, dando formato
                // Al enviar JSON se pasa como un string
                'fecha' => \Carbon\Carbon::parse($abono->fecha)->format('d/m/Y H:i'),

                'nombre' => $nombre,
                'dni' => $dni,
                'telefono' => $abono->telefono,
                'tipo' => $abono->tipoAbono->descripcion,
                'asiento' => $abono->asiento,
                'precio' => number_format($abono->precio, 2, ',', '.'),
                'edad' => $abono->edad
            ];

            return response()->json([
                'status' => true,
                'data' => $dataT,
                'message' => null,
                'errors' => null,
            ], 200); // OK
        } 
        catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => null,
                'errors' => $e->getMessage()
            ], 404); // NOT FOUND
        }
    }



    /*********************************
     * GET
     * OBTENER ABONOS
     * Necesita usuario autentificado 
     * (middleware en /routes/api.php)
     *********************************/
    public function listado() {
        try{
            // Borra token anterior para regenerarlo 
            // (mantener sesión activa, evitar uso fraudulento de tokens)
            Auth::user()->tokens()->delete();
            
            // Manda abonos ordenados por asiento descenciente
            $abonos = Abono::orderByDesc('asiento')->get();

            return response()->json([
                'status' => true,
                // Datos
                'data' => $abonos,
                'message' => null,
                'errors' => null,
                // Token regenerado
                'token' => Auth::user()->createToken("API_TOKEN")->plainTextToken
            ], 200); // OK

        } 
        catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => null,
                'errors' => $e->getMessage()
            ], 500); // INTERNAL SERVER ERROR
        }
    }



    /*********************************
     * GET
     * SERVICIO DE IMÁGENES
     *********************************/
    // - Sirve imágenes de medallas e iconos para el panel admin (listado)
    // - Almacenadas en disco publico local storage/app/public
    // - Se exponen con un symlink por defecto: public/storage - storage/app/public
    public function imageResources(){

        // http://localhost/TO6/compra_abonos_to6/ -- APP_URL .env

        try {
            $urlsMedals = [];
            $urlsIcons = [];

            // 1º Obtiene array de strings de rutas relativas de los archivos
            $filesMedals = Storage::disk('public')->files('imagenes/medals');  // [ "imagenes/medals/foto1.jpg", ... ]
            $filesIcons = Storage::disk('public')->files('imagenes/icons');


            // 2º Genera array de strings de rutas absolutas hacia las imagenes, para dar al cliente
            foreach ($filesMedals as $f) {
                // solo nombre sin extensión (claves del array)
                $fileName = pathinfo($f, PATHINFO_FILENAME);

                // genera: "foto1" => "http://localhost/TO6/compra_abonos_to6/public/storage/" + "imagenes/medals/foto1.png"
                /* asset() genera URLs de archivos públicos
                    url() genera URLS de cualquier recurso (rutas, endpoints...)*/
                $urlsMedals[$fileName] = asset('storage/' . $f);        
            }

            foreach ($filesIcons as $f) {
                $fileName = pathinfo($f, PATHINFO_FILENAME);
                // $urlsIcons[$fileName] = Storage::disk('public')->url($f);
                $urlsIcons[$fileName] = asset('storage/' . $f);
            }
        

            // 3º Envía respuesta con datos
            return response()->json([
                'status' => true,
                'data' => ['medals' => $urlsMedals, 'icons' => $urlsIcons],
                'message' => null,
                'errors' => null,
            ], 200); // OK

        } 
        catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => null,
                'errors' => $e->getMessage()
            ], 404); // NOT FOUND
        }
    }



    /* ------------- FUNCIONES AUXILIARES. Campos autogenerados ------------- */

    /* Campo 'abonado' con formato "Nombre Apellidos - DNI" */
    private function setAbonado($nombre, $dni){
        $abonado = '';
        $abonadoNombre = '';
        $abonadoApellidos = '';
        
        $abonadoNombreApellidos = explode(" ", $nombre);
        $abonadoNombre = $abonadoNombreApellidos[0];
        for ($i = 1; $i < count($abonadoNombreApellidos); $i++){
            $abonadoApellidos .= $abonadoNombreApellidos[$i].' ';
        }
        $abonadoApellidos = trim($abonadoApellidos);
        $abonadoDni = $dni;
        
        return $abonado = $abonadoNombre . ' ' . $abonadoApellidos . ' - ' . $abonadoDni;
    }


    /* Calcula la edad */
    private function setEdad($nacimiento) {
        $fechaNac = Carbon::createFromFormat('Y-m-d', $nacimiento);
        $edad = $fechaNac->age;
        return $edad;
    }


    /* Calcula 'precio' total, con rebajas si las hay */
    private function setPrecio($edad, $tipoPrecio) {
        $rebaja = 0;
        $importeTotal = 0;
        
        // Calcula rebaja segun edad
        if($edad < 12) $rebaja = 80;
        if ($edad > 65) $rebaja = (50*$tipoPrecio)/100;

        // Aplica rebaja al precio base del abono
        $importeTotal = $tipoPrecio - $rebaja;
        return $importeTotal;
    }


    /* Genera campo de código de 'asiento' */
    private function setAsiento($tipoDesc){

        // ----- Primera letra del tipo de abono:
        $letra = strtoupper(substr($tipoDesc, 0, 1));         


        // ----- Bloque de asientos (1-5 inclusives):
        // rand(min, max): Genera int aleatorio entre un int minimo y máximo inclusives.
        $bloque = 'B' . rand(1,5);
        

        // ----- Fila dentro del bloque (0-29 inclusives):
        // Los números de fila menores de 10 serán rellenados con 0s a la izquierda.
        $filaNum = rand(0,29);
        if ($filaNum < 10){
            $filaNumCadena = "0" . "$filaNum";
        } 
        else {
            $filaNumCadena = "$filaNum";
        }
        $fila = 'F' . $filaNumCadena;


        // ----- Asiento dentro de la fila (0-199 inclusives):
        $maxAsientosPorFila = 140 + ($filaNum*2);
        $asientoNum = rand(0, $maxAsientosPorFila);
        // Los números de asiento menores de 100 serán rellenados con 0s a la izquierda.
        if ($asientoNum < 10){       // por ejemplo 009
            $asientoNumCadena = "00" . "$asientoNum";
        } 
        else if ($asientoNum < 100) { // por ejemplo 099
            $asientoNumCadena = "0" . "$asientoNum";
        }
        else { // en cualquier otro caso, por ejemplo 100
            $asientoNumCadena = "$asientoNum";
        } 
        $asiento = 'A' . $asientoNumCadena;


        return $codigoAsiento = $letra . $bloque . '/' . $fila . "-" . $asiento;
    }

}