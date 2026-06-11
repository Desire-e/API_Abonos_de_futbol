<!-- --------------------------------------------------------------
PROCESA LOGOUT.

Cliente hace petición GET /usuarios/logout
Manda token de autenticacion para eliminarlo en sercivio.
Cliente redirige a login.php
-------------------------------------------------------------- -->

<?php

$token_acceso = '';
if(isset($_COOKIE['API_TOKEN']) && !empty($_COOKIE['API_TOKEN'])) {
    $token_acceso = $_COOKIE['API_TOKEN'];
}

$ws = curl_init('http://localhost/TO6/compra_abonos_to6/public/api/usuarios/logout');

curl_setopt($ws,CURLOPT_RETURNTRANSFER,true);
curl_setopt($ws, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Authorization: Bearer ' . $token_acceso
));

curl_exec($ws);

curl_close($ws);

header("Status: 301 Moved Permanently");
header("Location: login.php");
exit();