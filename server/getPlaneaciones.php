<?php

include 'lib.php';

$api = new gestionEducativa();

if (isset($_POST['foc'])) {
    $json = $api->getPlaneacionesXFocalizacion($_POST['foc']);

    $newArray = array();
    $n = 0;

    foreach ($json as $key => $value) {

        if (!isset($newArray[$value['id_planeacion']])) {

            $newArray[$value['id_planeacion']] = [
                "id_planeacion" => $value['id_planeacion'],
                "tipo_gestion" => $value['tipo_gestion'],
                "nombre_estrategia" => [],
                "temas" => $value['temas'],
                "fecha_plan" => $value['fecha_plan'],
            ];
        }

        if (empty($newArray[$value['id_planeacion']]['nombre_estrategia'])) {
            array_push($newArray[$value['id_planeacion']]['nombre_estrategia'], $value['nombre_estrategia']);
        } else {
            foreach ($newArray[$value['id_planeacion']]['nombre_estrategia'] as $k => $val) {
                if ($val != $value['nombre_estrategia']) {
                    array_push($newArray[$value['id_planeacion']]['nombre_estrategia'], $value['nombre_estrategia']);
                }
            }
        }

    }
}

echo json_encode($newArray);
