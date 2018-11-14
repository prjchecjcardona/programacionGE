<?php

include 'lib.php';

$api = new gestionEducativa();

if (isset($_POST)) {

    if (isset($_POST['no_ejec'])) {
        $json = $api->getNovedadesNoEjecucion();

        foreach ($json as $key => $value) {

            $json[$key] = array(
                'id' => $value['id_planeacion'],
                'title' => 'No ejecucion - ' . $value['comportamientos'],
                'start' => $value['fecha_plan'],
                'proximo' => $value['fecha_aplazamiento'],
                'editable' => false,
                'color' => '#a2a1a0',
                'textColor' => "white",
                'zona' => $value['zonas'],
            );
        }

    }

    if (isset($_POST['planEjec_cal'])) {
        $json = $api->getPlaneacionesEjecutadosOEnEjecucion();

        foreach ($json as $key => $value) {

            $json[$key] = array(
                'id' => $value['id_planeacion'],
                'title' => $value['municipio'] . ' - ' . $value['comportamientos'],
                'start' => $value['fecha_plan'],
                'editable' => false,
                'color' => '#eebc00',
                'textColor' => "black",
                'zona' => $value['zonas'],
            );
        }

    }

    if (isset($_POST['plan_cal'])) {

        $arrayPlaneaciones = $_POST['plan_cal'];

        $json = $api->getPlaneacionesCalendar($arrayPlaneaciones);

        foreach ($json as $k => $val) {

            $json[$k] = array(
                'id' => $val['id_planeacion'],
                'title' => $val['municipio'] . ' - ' . $val['comportamientos'],
                'start' => $val['fecha_plan'],
                'editable' => false,
                'color' => 'red',
                'textColor' => "white",
                'zona' => $val['zonas'],
            );
        }
    }
} else {
    $json = "No se recibieron los datos de manera adecuada";
}

echo json_encode($json);
