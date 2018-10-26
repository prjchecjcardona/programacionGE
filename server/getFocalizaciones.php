<?php

include 'lib.php';

$api = new gestionEducativa();

if (isset($_POST['zona'])) {

    $id_zona = $_POST['zona'];
    $json = $api->getFocalizacionesXZona($id_zona);

} else {
    $json = "No se recibieron los datos de manera adecuada";
}

echo json_encode($json);