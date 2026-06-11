
<!-- --------------------------------------------------------------
VISTA CLIENTE. Listado de abonos protegido 
(LOGIN con token de autenticacion Sanctum)

Cliente obtiene peticion GET con el id mandado desde compra_abono.php 

Cliente hace peticion GET abonos/ticket/{id}
Si respuesta OK, muestra registro del id
Si respuesta ERROR, muestra mensaje
Si no hay ticket, muestra mensaje
-------------------------------------------------------------- -->


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ticket de Compra</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/ticket.css">
</head>
<body>

<header class="header">
    <img src="../images/almeria.png" alt="Escudo">
    <h1>Compra realizada</h1>
</header>

<div class="container">
    
    <div class="ticket">

    <?php 
    // -----------------------------------------------------------------------------
    // PETICIÓN GET - OBTENER EL ABONO COMPRADO

    // 1º Obtiene id mandado desde compra_abono.php por la URL (GET)
    $id = $_GET['id'] ?? null;
    
    $dataT = array();
    
    if ($id) {
        
        // 2º Pasa id a servicio API REST por petición GET
        try {

            // Inicia conexión - preparar la petición cURL + dato id en URL
            $ws = curl_init('http://localhost/TO6/compra_abonos_to6/public/api/abonos/ticket/' . $id);

            // Configuración de la petición
            curl_setopt($ws,CURLOPT_RETURNTRANSFER,true);
            curl_setopt($ws, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            
            // Ejecuta peticion a servicio y recibe respuesta
            $respuesta = curl_exec($ws);
            if ($respuesta === false) { throw new Exception(curl_error($ws)); } // curl_error() devuelve mensaje de error

            // Convertir respuesta JSON a array (asociativo con true)
            $array_respuesta = json_decode($respuesta, true);
            if ($array_respuesta === null) { throw new Exception("Problema interno del servidor. Inténtelo más tarde"); }

            // Procesa respuesta del servicio API:
            if(!empty($array_respuesta) && $array_respuesta['status']==true) {
                $dataT = $array_respuesta['data'];
            } 
            else { throw new Exception("Problema interno del servidor. " . $array_respuesta['errors']); }

        } 
        catch (Exception $e) {
            echo '<h3>' . $e->getMessage() . '</h3>';
            echo '<a href="compra_abonos.php">Compre su ticket</a>';
            echo '</div>';
            echo '</div>';
            echo '</body>';
            echo '</html>';
            exit;
        }
        // Cierra conexión
        finally { if (isset($ws)) { curl_close($ws); } }   

    }
    // Imprime error, id no obtenido desde compra_abonos.php
    else {
        echo '<h3>Error: ticket no encontrado </h3>';
        echo '<a href="compra_abonos.php">Compre su ticket</a>';
        echo '
        </div>
        </div>
        </body>
        </html>';
        exit;
    }

    ?>

        <p class="success">¡Venta realizada con éxito!</p>
        <h2>Ticket de Compra</h2>

        <div class="ticket-info">

            <div class="fila">
                <span>Fecha de compra</span>
                <strong><?= $dataT['fecha'] ?></strong>
            </div>

            <div class="fila">
                <span>Nombre</span>
                <strong><?= $dataT['nombre'] ?></strong>
            </div>

            <div class="fila">
                <span>DNI</span>
                <strong><?= $dataT['dni'] ?></strong>
            </div>

            <div class="fila">
                <span>Teléfono</span>
                <strong><?= $dataT['telefono'] ?></strong>
            </div>

            <div class="fila">
                <span>Tipo de abono</span>
                <strong><?= $dataT['tipo'] ?></strong>
            </div>

            <div class="fila">
                <span>Asiento</span>
                <strong><?= $dataT['asiento'] ?></strong>
            </div>

        </div>

        <div class="precio">
            <span>IMPORTE TOTAL</span>
            <strong><?= $dataT['precio'] ?></strong>
        </div>

        <p class="tarifa">* Tarifa especial aplicada - 
            <?php 
            if($dataT['edad'] < 12 ){ 
                echo 'Niños/as menores de 12 años: Rebaja de 80€.';
            } elseif ($dataT['edad'] > 65 ){ 
                echo 'Jubilados y mayores de 65 años: Rebaja del 50%.'; 
            } else  echo 'Sin rebaja.'; ?>
        </p>

        <a class="admin-link" href="compra_abonos.php">Volver</a>
    </div>


</div>

</body>
</html>