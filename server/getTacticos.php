<?php
include 'lib.php';

$api = new gestionEducativa();

if (isset($_POST)) {
    if (isset($_POST['estrategia']) && isset($_POST['cercania'])) {
        $id_estrat = $_POST['estrategia'];
        $cercania = $_POST['cercania'];

        if($cercania == 1) {
            $cercania = 'true';
        } else {
            $cercania = 'false';
        }

        $json = $api->getTacticos($id_estrat, $cercania);

    } else {
        $json = "No se recibieron los datos de manera adecuada";
    }
} else {
    $json = "No se recibieron los datos de manera adecuada";
}

echo json_encode($json);
