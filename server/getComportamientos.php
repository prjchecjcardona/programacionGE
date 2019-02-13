<?php
include 'lib.php';

$api = new gestionEducativa();

if (isset($_POST)) {
    $json = $api->getComportamientos();
} else {
    $json = "No se recibieron los datos de manera adecuada";
}

if(isset($_POST['checkComportamientos'])){
    $json = $api->checkCompetenciasFocalizacion($_POST['checkComportamientos']);
}

echo json_encode($json);
