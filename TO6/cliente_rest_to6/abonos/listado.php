
<!-- --------------------------------------------------------------
VISTA CLIENTE. Listado de abonos protegido 
(LOGIN con token de autenticacion Sanctum)

Cliente hace peticion GET /abonos/tipoAbonos
Si respuesta OK, muestra los tipos de abono en formulario
Si respuesta ERROR, muestra mensaje


Cliente hace peticion GET /abonos/listado
Manda token generado en login y almacenado en cookie
Si respuesta OK, obtiene nuevo token y lo almacena en cookie, muestra los registros de abonos
Si respuesta ERROR, muestra mensaje de error, o redirige a prohibido.php si login caducó / acceso denegado


Cliente hace peticion GET /abonos/imageResources
Si respuesta OK, obtiene rutas de imagenes almacenadas en disco local del servicio
Si respuesta ERROR, muestra mensaje
-------------------------------------------------------------- -->

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Abonos</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/listado.css">
</head>

<body>

    <header class="header">
        <img src="../images/almeria.png" alt="Escudo">
        <h1>Panel de Administración</h1>
    </header>

    <div class="container">

        <div class="panel">

        <?php 
        // -----------------------------------------------------------------------------
        // PETICIÓN GET - OBTENER LOS TIPO ABONOS PARA MOSTRAR EN FORMULARIO
        
        try {
            // 1º Inicia conexión - preparar la petición cURL
            $ws = curl_init('http://localhost/TO6/compra_abonos_to6/public/api/abonos/tipoAbonos');
            
            // 2º Configuración de la petición
            // recibir la respuesta (RETURNTRANSFER)
            curl_setopt($ws,CURLOPT_RETURNTRANSFER,true);
            // headers
            curl_setopt($ws, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            
            // 3º Ejecuta peticion a servicio y recibe respuesta
            $respuesta = curl_exec($ws);
    
            if ($respuesta === false) { throw new Exception(curl_error($ws)); } // curl_error() devuelve mensaje de error


            // 4º Convertir respuesta JSON a array (asociativo con true)
            $array_respuesta= json_decode($respuesta, true);
            if ($array_respuesta === null) { throw new Exception("Problema interno del servidor. Inténtelo más tarde"); }


            // 5º Procesa respuesta del servicio API:
            if(!empty($array_respuesta) && $array_respuesta['status'] == true) {
                // obtiene los registros de tipo_abonos
                $tipoAbonos = $array_respuesta['data'];
                $dataTipos = $array_respuesta['data'];
            }
            else { throw new Exception("Problema interno del servidor. Inténtelo más tarde"); }
            
        } 
        catch (Exception $e) {
            echo '<h3>' . $e->getMessage() . '</h3>';
            echo '</div>';
            echo '</div>';
            echo '</body>';
            echo '</html>';
            exit;
        }
        // 6º Cierra conexión
        finally { if (isset($ws)) { curl_close($ws); } }
        ?>


        <?php 
        // -----------------------------------------------------------------------------
        // PETICIÓN GET - OBTENER REGISTROS DE TABLA ABONO
        
        try {
            // 1º Obtener token desde la cookie guardada durante login
            $token_acceso = '';

            if(isset($_COOKIE['API_TOKEN']) && !empty($_COOKIE['API_TOKEN'])){
                $token_acceso = $_COOKIE['API_TOKEN'];
            }


            // 2º Inicia conexión - preparar la petición cURL
            $ws = curl_init('http://localhost/TO6/compra_abonos_to6/public/api/abonos/listado');
        

            // 3º Configuración de la petición
            
            // recibir la respuesta (RETURNTRANSFER)
            curl_setopt($ws,CURLOPT_RETURNTRANSFER,true); 
            
            // headers + token de usuario
            curl_setopt($ws, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token_acceso
            ));


            // 4º Ejecuta peticion a servicio y recibe respuesta
            $respuesta = curl_exec($ws);

            if ($respuesta === false) { throw new Exception(curl_error($ws)); }


            // 5º Convertir respuesta JSON a array (asociativo con true)
            $array_respuesta = json_decode($respuesta, true);

            if ($array_respuesta === null) { throw new Exception("Problema interno del servidor. Inténtelo más tarde"); }

            // var_dump($array_respuesta);
            // var_dump($token_acceso);
            // var_dump($respuesta);
            // exit();                

            // 5º Procesa respuesta del servicio API:
            if(!empty($array_respuesta) && $array_respuesta['status']==true) {
                // Guarda en cookie nuevo token para mantener sesión activa
                setcookie('API_TOKEN', $array_respuesta['token'], time() + 3600, "/"); // 1 hora
                // registros devueltos
                $dataAbonos = $array_respuesta['data'];
            }
            else {
                // Redirige a prohibido.php (login caducó / acceso denegado)
                header("Status: 301 Moved Permanently");
                header("Location: prohibido.php");
                exit;
            }

        }
        catch (Exception $e) {
            echo '<h3>' . $e->getMessage() . '</h3>';
            echo '</div>';
            echo '</div>';
            echo '</body>';
            echo '</html>';
            exit;

        }
        // 6º Cierra conexión
        finally { if (isset($ws)) { curl_close($ws); } }
        ?>


        <?php 
        // -----------------------------------------------------------------------------
        // PETICIÓN GET - IMAGENES
        try {
            $ws = curl_init("http://localhost/TO6/compra_abonos_to6/public/api/abonos/imageResources");
            
            curl_setopt($ws,CURLOPT_RETURNTRANSFER,true);
            curl_setopt($ws, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            
            $respuesta = curl_exec($ws);
            if ($respuesta === false) { throw new Exception(curl_error($ws)); }

            $array_respuesta = json_decode($respuesta, true);
            if ($array_respuesta === null) {throw new Exception("Problema interno del servidor. Inténtelo más tarde");}
            

            if(!empty($array_respuesta) && $array_respuesta['status']==true) {
                // rutas a las imagenes en los discos locales
                $medallas = $array_respuesta['data']['medals'];
                $iconos = $array_respuesta['data']['icons'];
                // var_dump($array_respuesta['data']['medals']['silver']);
                // exit;
            } 
            else { throw new Exception("Problema interno del servidor. Inténtelo más tarde"); }
            
        } 
        catch (Exception $e) {
            echo '<h3>' . $e->getMessage() . '</h3>';
            echo '</div>';
            echo '</div>';
            echo '</body>';
            echo '</html>';
            exit;

        }
        finally { if (isset($ws)) { curl_close($ws); } }
        ?>

            <h2>Abonos registrados</h2>

            <div class="tabla-container">
                <table class="tabla">
                    <thead>
                        <tr>
                            <th>Tipo</th>
                            <th>Asiento</th>
                            <th>Abonado</th>
                            <th>Tipo especial</th>
                            <th>Importe</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach($dataAbonos as $abono) { ?>
                        <tr>
                            
                            <?php
                            $descripcion = '';
                            $medalla = '';

                            foreach($dataTipos as $tipo){
                                if($abono['tipo'] === $tipo['id']){ 
                                    
                                    $descripcion = $tipo['descripcion'];
                                    
                                    if($tipo['precio'] > 300) { $medalla = $medallas['gold']; } 
                                    elseif ($tipo['precio'] > 150 && $tipo['precio'] < 500 ) { $medalla = $medallas['silver']; } 
                                    elseif ($tipo['precio'] < 150 ) { $medalla = $medallas['bronze']; } 

                                    break;
                                }
                            }?>

                            <td>
                                <img src="<?= $medalla ?>" width="50"/>
                                <p><?= $descripcion ?></p>
                            </td>
                            
                            <td><?= $abono['asiento']; ?></td>

                            <td>
                                <div class="abonado">
                                    <span><?= $abono['abonado']; ?></span>
                                    <div class="iconos">
                                        <img src="<?= $iconos['phone-call']; ?>" title="<?= $abono['telefono']; ?>">
                                        <img src="<?= $iconos['bank-building']; ?>" title="<?= $abono['cuenta_bancaria']; ?>">
                                    </div>
                                </div>
                            </td>

                            <td><span class="badge jubilado">
                                <?php 
                                if($abono['edad'] < 12){ echo 'Menor de 12 años'; } 
                                else if($abono['edad'] > 65){ echo 'Jubilado'; } 
                                else echo 'Sin abono especial';
                                ?>
                            </span></td>

                            <td><strong><?= $abono['precio']; ?></strong></td>

                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <a href="../usuarios/logout.php" class="btn-logout">Cerrar sesión</a>

        </div>

    </div>

</body>
</html>
