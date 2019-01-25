<?php

include 'lib.php';

$api = new gestionEducativa();

if (isset($_POST['foc'])) {
    $json = $api->getPlaneacionesXFocalizacion($_POST['foc']);

    $newArray = $json;
}

if (isset($_POST['detallePlaneacion'])) {
    $json = $api->getDetallePlaneacionEjecucion($_POST['detallePlaneacion']);

    $newArray = array();
    $entro = true;
    foreach ($json as $key => $value) {

        if ($entro) {

            $newArray[1] = [
                "fecha" => $value['fecha_plan'],
                "mun" => $value['municipio'],
                "gestor" => $value['nombre'],
                "zona" => $value['zonas'],
                "entidad" => $value['nombre_entidad'],
                "compor" => $value['comportamientos'],
                "compe" => $value['competencia'],
                "estrategias" => $value['nombre_estrategia'],
                "tacticos" => [],
                "temas" => $value['temas']

            ];

            $entro = false;
        }

        array_push($newArray[1]['tacticos'], $value['nombre_tactico']);

    }

}

if (isset($_POST['geoAppPlan'])) {
    $zona = $_POST['geoAppPlan'];
    $i = 0;
    $newArray = array();

    $json = $api->getPlaneacionesGeoApp($zona);

    if(is_array($json)){
        foreach ($json as $key => $value) {

            if (!isset($newArray[$value['id_planeacion']])) {

                $estado = $api->getEtapaPlaneacion($value['id_planeacion']);

                if (count($estado) == 0) {
                    $etapa_plan = "Planeado";
                } else if (count($estado) == 1){
                    $etapa_plan = "Iniciado";
                }else{
                    $etapa_plan = "Finalizado";
                }

                $newArray[$value['id_planeacion']] = [
                    "id_planeacion" => $value['id_planeacion'],
                    "fecha_plan" => $value['fecha_plan'],
                    "municipio" => $value['municipio'],
                    "nombre" => $value['nombre'],
                    "zonas" => $value['zonas'],
                    "nombre_entidad" => $value['nombre_entidad'],
                    "comportamientos" => $value['comportamientos'],
                    "competencia" => $value['competencia'],
                    "nombre_estrategia" => $value['nombre_estrategia'],
                    "temas" => $value['temas'],
                    "nombre_tactico" => array('array' => [], 'string' => ""),
                    "estado" => $etapa_plan
                ];
            }

            if (empty($newArray[$value['id_planeacion']]['nombre_tactico']['array'])) {
                array_push($newArray[$value['id_planeacion']]['nombre_tactico']['array'], $value['nombre_tactico']);
            } else {
                foreach ($newArray[$value['id_planeacion']]['nombre_tactico']['array'] as $k => $val) {
                    if ($val != $value['nombre_tactico']) {
                        array_push($newArray[$value['id_planeacion']]['nombre_tactico']['array'], $value['nombre_tactico']);
                    }
                }
            }

            /* Set implode to string variable in tacticos from array tacticos */
            $newArray[$value['id_planeacion']]['nombre_tactico']['string'] = implode(' •• ', $newArray[$value['id_planeacion']]['nombre_tactico']['array']);

        }
    }

    $newArray = array_values($newArray);

}

echo json_encode($newArray);
