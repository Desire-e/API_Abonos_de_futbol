<!-- --------------------------------------------------------------
VISTA CLIENTE. Formulario de compra de abonos.

Cliente hace petición GET /abonos/tipoAbonos

Si respuesta OK, muestra los tipos de abono en formulario
Si respuesta ERROR, muestra mensaje



Cliente hace petición POST /abonos/insert
Envía datos del formulario en JSON.

Si respuesta ERROR, recarga mostrando datos JSON obtenidos.
Si respuesta OK, se crea abono en BD y redirige a ticket.php.
Manda id por GET a ticket.php.
-------------------------------------------------------------- -->

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Compra de Abonos</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/ticket.css">
</head>

<body>

<header class="header">
    <img src="../images/almeria.png" alt="Escudo">
    <h1>Compra de Abonos</h1>
</header>

<div class="container">


    <?php 
    // -----------------------------------------------------------------------------
    // PETICIÓN GET. OBTENER LOS TIPO ABONOS PARA MOSTRAR EN FORMULARIO
        
    try {

        // 1º Inicia conexión - preparar la petición cURL
        // ruta definida en servicio REST (/routes/api.php)
        // añade '/public' porque laravel rutea todo 1º desde /public/index.php
        $ws = curl_init('http://localhost/TO6/compra_abonos_to6/public/api/abonos/tipoAbonos');

        
        // 2º Configuración de la petición
        // recibir la respuesta (RETURNTRANSFER)
        curl_setopt($ws,CURLOPT_RETURNTRANSFER,true);
        // headers
        curl_setopt($ws, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));


        // 3º Ejecuta peticion a servicio y recibe respuesta
        // var_dump(curl_exec($ws));
        // exit;
        $respuesta = curl_exec($ws);

        if ($respuesta === false) { throw new Exception(curl_error($ws)); } // curl_error() devuelve mensaje de error


        // 4º Convertir respuesta JSON a array (asociativo con true)
        $array_respuesta= json_decode($respuesta, true);

        if ($array_respuesta === null) { throw new Exception("Problema interno del servidor. Inténtelo más tarde"); }
        
        
        // 5º Procesa respuesta del servicio API:
        if(!empty($array_respuesta) && $array_respuesta['status'] == true) {
            // obtiene los registros de tipo_abonos
            $tipoAbonos = $array_respuesta['data'];
        }
        else { throw new Exception("Problema interno del servidor. Inténtelo más tarde"); }


        // curl_close($ws);
    } 
    catch (Exception $e) {
        echo '<h3>' . $e->getMessage() . '</h3>';
        echo '</div>';
        echo '</body>';
        echo '</html>';
        // if (isset($ws)) { curl_close($ws); }
        exit;
    }
    finally {
        // 6º Cierra conexión
       if (isset($ws)) { curl_close($ws); } 
    }
    ?>

    <?php 
    // -----------------------------------------------------------------------------
    // PETICIÓN POST. COMPRA DE ABONO

    // 1º Obtiene datos de POST
    $erroresF = array();
    $dataF = array();
    
    $dataF['nombre'] = '';
    $dataF['dni'] = '';
    $dataF['nacimiento'] = '';
    $dataF['telefono'] = '';
    $dataF['cuentaBancaria'] = '';
    $dataF['abonoTipo'] = '';
    $dataF['terminosCheck'] ='';
    
    if(filter_input_array(INPUT_POST)) {
    
        $dataF['nombre'] = filter_input(INPUT_POST, 'nombre');
        
        $dataF['dni'] = filter_input(INPUT_POST, 'dni');
        $dataF['nacimiento'] = filter_input(INPUT_POST, 'nacimiento');
        $dataF['telefono'] = filter_input(INPUT_POST, 'telefono');
        $dataF['cuentaBancaria'] = filter_input(INPUT_POST, 'cuentaBancaria');
        $dataF['abonoTipo'] = filter_input(INPUT_POST, 'abonoTipo');
        $dataF['terminosCheck'] = filter_input(INPUT_POST, 'terminosCheck');



        try {
            // 2º Inicia conexión - preparar la petición cURL
            $ws = curl_init();


            // 3º Configuración de la petición
            curl_setopt_array($ws, array(
                // ruta definida en servicio REST (/routes/api.php)
                CURLOPT_URL => 'http://localhost/TO6/compra_abonos_to6/public/api/abonos/insert',

                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30000,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                // manda a servicio los datos obtenidos de POST
                CURLOPT_POSTFIELDS => json_encode($dataF),
                // headers 
                CURLOPT_HTTPHEADER => array(    
                    "accept: */*",
                    "accept-language: en-US,en;q=0.8",
                    "content-type: application/json",
                ),
            ));


            // 4º Ejecuta peticion a servicio y recibe respuesta
            $respuesta = curl_exec($ws);

            if ($respuesta === false) { throw new Exception(curl_error($ws)); } // curl_error() devuelve mensaje de error


            // 5º Convertir respuesta JSON a array (asociativo con true)
            $array_respuesta = json_decode($respuesta, true);

            if ($array_respuesta === null) { throw new Exception("Problema interno del servidor. Inténtelo más tarde"); }

            // 6º Procesa respuesta del servicio API:
            if(!empty($array_respuesta) && $array_respuesta['status'] == true) {
                // Redirige a su ticket con el id

                // Pasar id del abono a ticket.php por URL (GET)
                // Acceso desde ticket: $_GET['id'];
                $id = $array_respuesta['data']['id'];
                
                header("Status: 301 Moved Permanently");
                header("Location: ticket.php?id=" . $id);
                exit;
            }
            else {
                // Muestra errores de campos
                $erroresF = $array_respuesta['errors'];
                $dataF = $array_respuesta['data'];
            }
        } 
        catch (Exception $e) {
            echo '<h3>' . $e->getMessage() . '</h3>';
            echo '</div>';
            echo '</body>';
            echo '</html>';
            exit;
        }
        // 6º Cierra conexión
        finally { if (isset($ws)) { curl_close($ws); } }

    }  
    ?>
    


    <form class="formulario" action="compra_abonos.php" method="post">

        <div class="grid">

            <div class="campo">
                <label for="nombre">Nombre y apellidos</label>
                <input id="nombre" name="nombre" type="text" placeholder="Nombre"
                value="<?= $dataF['nombre'];?>">

                <!-- Muestra 1er mensaje si hay -->
                <?php  if(isset($erroresF['nombre'][0]) && !empty($erroresF['nombre'][0])) { ?>
                    <p class="error"><?= $erroresF['nombre'][0] ?></p>
                <?php } ?>
            </div>

            <div class="campo">
                <label for="dni">DNI</label>
                <input id="dni" name="dni" type="text" placeholder="12345678A"
                value="<?php echo $dataF['dni'];?>">
            
                <?php if(isset($erroresF['dni'][0]) && !empty($erroresF['dni'][0])) {?>
                    <p class="error"><?= $erroresF['dni'][0] ?></p>
                <?php } ?>
            </div>

            <div class="campo">
                <label for="nacimiento" >Fecha de nacimiento</label>
                <input id="nacimiento" name="nacimiento" type="date"
                value="<?php echo $dataF['nacimiento'];?>">

                <?php  if(isset($erroresF['nacimiento'][0]) && !empty($erroresF['nacimiento'][0])) { ?>
                    <p class="error"><?= $erroresF['nacimiento'][0] ?></p>
                <?php } ?>
            </div>

            <div class="campo">
                <label for="telefono">Teléfono</label>
                <input id="telefono" name="telefono" type="tel" placeholder="600 000 000"
                value="<?php echo $dataF['telefono'];?>">

                <?php  if(isset($erroresF['telefono'][0]) && !empty($erroresF['telefono'][0])) { ?>
                    <p class="error"><?= $erroresF['telefono'][0] ?></p>
                <?php } ?>
            </div>

            <div class="campo">
                <label for="cuentaBancaria">Cuenta bancaria</label>
                <input id="cuentaBancaria" name="cuentaBancaria" type="text" placeholder="ES00 0000 0000 0000"
                value="<?php echo $dataF['cuentaBancaria'];?>">

                <?php  if(isset($erroresF['cuentaBancaria'][0]) && !empty($erroresF['cuentaBancaria'][0])) { ?>
                    <p class="error"><?= $erroresF['cuentaBancaria'][0] ?></p>
                <?php } ?>
            </div>

            <div class="campo">
                <label for="abonoTipo">Tipo de abono</label>
                <select id="abonoTipo" name="abonoTipo">
                    <option disabled selected>- Selecciona -</option>
                    
                    <?php foreach($tipoAbonos as $ta) { ?>  
                    
                    <!-- 
                    $tipoAbonos -- abonos en BD
                    $dataF['abonoTipo'] -- abono seleccionado y mandado por POST al intentar comprar  
                    -->
                    <option value="<?= $ta['id'] ?>" 
                    <?php if ( $dataF['abonoTipo'] === $ta['id']) echo 'selected'; ?>>
                        <?= $ta['descripcion'] . '(' .  $ta['precio'] . '€)'; ?>
                    </option>

                    <?php } ?>
                </select>

                <?php  if(isset($erroresF['abonoTipo'][0]) && !empty($erroresF['abonoTipo'][0])) { ?>
                    <p class="error"><?= $erroresF['abonoTipo'][0] ?></p>
                <?php } ?>
            </div>

        </div>

        <div class="terminos">
            <input name="terminosCheck" id="terminosCheck" type="checkbox"
            <?php if (!empty($dataF['terminosCheck'])) echo 'checked'; ?>>
            <label for="terminosCheck">Acepto los términos y condiciones</label>

            <?php if(isset($erroresF['terminosCheck'][0]) && !empty($erroresF['terminosCheck'][0])) { ?>
                <p class="error"><?= $erroresF['terminosCheck'][0] ?></p>
            <?php } ?>
        </div>

        <button type="submit" class="btn-comprar">Comprar Abono</button>

        
        <!-- LOGIN (SANCTUM) -->
        <a href="../usuarios/login.php" class="admin-link">Iniciar sesión como administrador</a>

    </form>

</div>

</body>
</html>


<!------------------------------------------- 
Formas de pasar datos de script a script:

1. Por URL (GET)
- Hace una nueva petición HTTP al php indicado
- Las variables del script anterior desaparecen

header("Location: destino.php?nombre=Juan");
exit;

// destino.php
echo $_GET['nombre']; // Juan

----------------------------------------------
2. Usando sesiones

// origen.php
session_start();
$_SESSION['nombre'] = "Juan";
header("Location: destino.php");
exit;

// destino.php
session_start();
echo $_SESSION['nombre'];

----------------------------------------------
3. Con formularios (POST)
No se puede directamente con header()
A. Usar un form con method="POST"
B. hacer un auto-submit

----------------------------------------------
4. BD o almacenamiento
Para datos más complejos o persistentes.

---------------------------------------------->