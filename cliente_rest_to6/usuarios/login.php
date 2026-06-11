<!-- --------------------------------------------------------------
VISTA CLIENTE. Login. 

Cliente hace peticion POST /usuarios/login
Envía datos del formulario en JSON.

Si respuesta ERROR, recarga mostrando datos JSON obtenidos.
Si respuesta OK, redirige a ruta protegida listado.php, almacena en cookie 
el token de usuario generado por servicio
-------------------------------------------------------------- -->


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login Administrador</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>

<header class="header">
    <img src="../images/almeria.png" alt="Escudo">
    <h1>Panel de Administración</h1>
</header>

<div class="container">

    <?php
    // -----------------------------------------------------------------------------
    // PETICION POST - REALIZAR LOGIN + TOKEN SANCTUM
    
    // 1º Obtiene datos de POST
    $erroresF = array();
    $dataF = array();
    
    $dataF['username'] = '';
    $dataF['password'] = '';
    
    if(filter_input_array(INPUT_POST)) {

        $dataF['username'] = filter_input(INPUT_POST, 'username');
        $dataF['password'] = filter_input(INPUT_POST, 'password');

        
        try {

            // 2º Inicia conexión - preparar la petición cURL
            $ws = curl_init();

            // 3º Configuración de la petición
            curl_setopt_array($ws, array(
                CURLOPT_URL => 'http://localhost/TO6/compra_abonos_to6/public/api/usuarios/login',
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
            if ($respuesta === false) { throw new Exception(curl_error($ws)); }
            
            // 5º Convertir respuesta a JSON (array assoc, de los campos del registro abono en BD)
            $array_respuesta = json_decode($respuesta, true);
            if ($array_respuesta === null) { throw new Exception("Problema interno del servidor. Inténtelo más tarde"); }


            // 6º Procesa respuesta del servicio API:
            if(!empty($array_respuesta) && $array_respuesta['status']==true) {
                // almacena token personal sanctum, generado en la api, en una cookie
                setcookie('API_TOKEN', $array_respuesta['token'], time() + 3600, "/"); // 1 hora
                
                // redirige a listado
                header("Status: 301 Moved Permanently");
                header("Location: ../abonos/listado.php");
                exit;
            }
            else {
                // muestra errores de campos
                $erroresF = $array_respuesta['errors'];
                // $dataF = $array_respuesta['data']; el servicio no devuelve data de vuelta, solo errores 
            }
        } 
        catch (Exception $e) {
            echo '<h3>' . $e->getMessage() . '</h3>';
            echo '</div>';
            echo '</body>';
            echo '</html>';
            exit;
            
        }
        // 7º Cierra conexión
        finally {
            if (isset($ws)) { curl_close($ws); } 
        }
    }  

    ?>
    <form class="formulario login" action="login.php" method="post">

        <h2>Iniciar sesión</h2>

        <div class="campo">
            <label for="username">Nombre de usuario</label>
            <input id="username" name="username" type="text" placeholder="Introduce tu usuario">
        </div>
        </br>

        <div class="campo">
            <label for="password">Contraseña</label>
            <input id="password" name="password" type="password" placeholder="Introduce tu contraseña">
        </div>

        <!-- MENSAJE DE ERROR -->
        <?php  if(isset($erroresF['password'][0]) && !empty($erroresF['password'][0])) { ?>
            <p class="error"><?= $erroresF['password'][0] ?></p>

        <?php } elseif(isset($erroresF['username'][0]) && !empty($erroresF['username'][0])){  ?>
            <p class="error"><?= $erroresF['username'][0] ?></p>
        <?php } ?>

        </br>
        <button type="submit" class="btn-comprar">Acceder</button>

        <a href="../abonos/compra_abonos.php" class="admin-link">Volver a compra de abonos</a>

    </form>
</div>

</body>
</html>