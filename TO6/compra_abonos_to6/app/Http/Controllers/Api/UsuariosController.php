<?php
/**** LOGIN / LOGOUT (SANCTUM) ****/

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller; 
use Illuminate\Support\Facades\Auth;


use Illuminate\Http\Request;
use App\Models\Usuario;

use Illuminate\Support\Facades\Validator;


class UsuariosController extends Controller{
    
    /*********************************
     * POST
     * PROCESAR LOGIN
     *********************************/
    public function login(Request $request) {
        try {
            // Validar campos
            $validUser = Validator::make($request->all(), [
                'username' => 'required',
                'password' => 'required'
            ]);

            if($validUser->fails()){
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => null,
                    'errors' => $validUser->errors(),
                    'token' => null
                ], 401); // NOT AUTHORIZED
            }

            
            // Comprobar usuario existente y contraseña coincidente
            if(Auth::attempt($request->only(['username', 'password']))) {
                
                return response()->json([
                    'status' => true,
                    'data' => null,
                    'message' => 'Administrador logueado correctamente',
                    'errors' => null,    
                    // Mandar token recien creado para el usuario
                    'token' => Auth::user()->createToken("API_TOKEN")->plainTextToken
                ], 200); // 200 OK
            }

            // Respuesta fallida ante credenciales inválidas
            else {
                $validUser->errors()->add('password', 'Nombre de usuario y/o contraseña incorrectos');
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => null,
                    'errors' => $validUser->errors(),
                    'token' => null
                ], 401);  // NOT AUTHORIZED
            }

        }
        // Respuesta fallida ante excepciones
        catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => null,
                'errors' => $e->getMessage(),
                'token' => null
            ], 500); // INTERNAL SERVER ERROR
        }

    }

    

    /*********************************
     * GET
     * PROCESAR LOGOUT
     *********************************/
    public function logout() {
        try {
            // Borra el token de autenticación
            // * Se vuelve a crear en cada login y operación que requiere 
            // tener cuenta (regenerar para mantener sesión abierta).
            Auth::user()->tokens()->delete();

            return response()->json([
                'status' => true,
                'data' => null,
                'message' => 'Sesión cerrada correctamente',
                'errors' => null,
                'token' => null
            ], 200); // OK
        }
        catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => $e->getMessage(),
                'errors' => null,
            ], 500); // INTERNAL SERVER ERROR
        }
    }

}