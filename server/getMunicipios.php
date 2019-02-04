<?php
include 'lib.php';

$api = new gestionEducativa();

if (isset($_POST)) {
    if (isset($_POST['zona'])) {

        $id_zona = $_POST['zona'];

        if($_POST['zona'] == 0){
            $id_zona = "all";
        }

        $json = $api->getMunicipiosXZona($id_zona);
    }
} else {
    $json = "No se recibieron los datos de manera adecuada";
}

echo json_encode($json);
