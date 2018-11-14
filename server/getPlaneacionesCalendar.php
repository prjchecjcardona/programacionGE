<?php

include 'lib.php';

$api = new gestionEducativa();

if (isset($_POST)) {
    $json = $api->getPlaneacionesCalendar();

    foreach ($json as $key => $value) {

        $json[$key] = array(
            'id' => $value['id_planeacion'],
            'title' => $value['municipio'] . ' - ' . $value['comportamientos'],
            'start' => $value['fecha_plan'],
            'editable' => false,
            'color' => 'red',
            'textColor' => "white",
            'zona' => $value['id_zona']
        );
    }
} else {
    $json = "No se recibieron los datos de manera adecuada";
}

echo json_encode($json);
