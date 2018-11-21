<?php

include 'lib.php';

$api = new gestionEducativa();

if (isset($_POST)) {

    if (isset($_POST['no_ejec'])) {
        $json = $api->getNovedadesNoEjecucion();

        foreach ($json as $key => $value) {

            $json[$key] = array(
                'id' => $value['id_planeacion'],
                'title' => 'No ejecucion / ' . $value['comportamientos'] . ' - ' . $value['competencia'],
                'start' => $value['fecha_plan'],
                'proximo' => $value['fecha_aplazamiento'],

                'description' =>
                'Municipio : ' . $value['municipio'] . '</br>' .
                'Fecha de la actividad no ejecutada : ' . $value['fecha_plan'] . '</br>' .
                'Nueva fecha para la ejecución de la actividad : ' . $value['fecha_aplazamiento'] . '</br>' .
                'Zona : ' . $value['zonas'] . '</br>' .
                'Gestor : ' . $value['nombre'] . '</br>' .
                'Tema : ' . $value['temas'] . '</br>' .
                'Estrategia : ' . $value['nombre_estrategia'] . '</br>',

                'editable' => false,
                'color' => '#a2a1a0',
                'textColor' => "white",
                'zona' => $value['zonas'],
            );
        }

    }

    if (isset($_POST['planEjec_cal'])) {
        $json = $api->getPlaneacionesEjecutadosOEnEjecucion();

        $newArray = array();
        $n = 0;

        foreach ($json as $key => $value) {

            if (!isset($newArray[$value['id_planeacion']])) {

                $newArray[$value['id_planeacion']] = [
                    "id_planeacion" => $value['id_planeacion'],
                    "fecha_plan" => $value['fecha_plan'],
                    "jornada" => $value['jornada'],
                    "lugar_encuentro" => $value['lugar_encuentro'],
                    "municipio" => $value['municipio'],
                    "comportamientos" => $value['comportamientos'],
                    "competencia" => $value['competencia'],
                    "zonas" => $value['zonas'],
                    "nombre_estrategia" => $value['nombre_estrategia'],
                    "tacticos" => [],
                    "temas" => $value['temas'],
                    "gestor" => $value['nombre']
                ];
            }

            if (empty($newArray[$value['id_planeacion']]['tacticos'])) {
                array_push($newArray[$value['id_planeacion']]['tacticos'], $value['nombre_tactico']);
            } else {
                foreach ($newArray[$value['id_planeacion']]['tacticos'] as $k => $val) {
                    if ($val != $value['nombre_tactico']) {
                        array_push($newArray[$value['id_planeacion']]['tacticos'], $value['nombre_tactico']);
                    }
                }
            }

        }

        foreach ($newArray as $key => $value) {

            $tacticos = implode(", ", $value['tacticos']);

            $newArray[$key] = array(

                'id' => $value['id_planeacion'],
                'title' => $value['comportamientos'] . ' - ' . $value['municipio'],
                'start' => $value['fecha_plan'],

                'description' =>
                'Fecha : ' . $value['fecha_plan'] . '</br>' .
                'Jornada : ' . $value['jornada'] . '</br>' .
                'Lugar de encuentro : ' . $value['lugar_encuentro'] . '</br>' .
                'Municipio : ' . $value['municipio'] . '</br>' .
                'Estrategias : ' . $value['nombre_estrategia'] . '</br>' .
                'Tacticos : ' . $tacticos . '</br>' .
                'Temas : ' . $value['temas'] . '</br>' .
                'Zona : ' . $value['zonas'] . '</br>' .
                'Gestor : ' . $value['gestor'] . '</br>',

                'editable' => false,
                'color' => '#edbe00',
                'textColor' => "white",
                'zona' => $value['zonas'],
            );
        }

        $json = array_values($newArray);

    }

    if (isset($_POST['plan_cal'])) {

        $arrayPlaneaciones = $_POST['plan_cal'];

        $json = $api->getPlaneacionesCalendar($arrayPlaneaciones);

        $newArray = array();
        $n = 0;

        foreach ($json as $key => $value) {

            if (!isset($newArray[$value['id_planeacion']])) {

                $newArray[$value['id_planeacion']] = [
                    "id_planeacion" => $value['id_planeacion'],
                    "fecha_plan" => $value['fecha_plan'],
                    "jornada" => $value['jornada'],
                    "lugar_encuentro" => $value['lugar_encuentro'],
                    "municipio" => $value['municipio'],
                    "comportamientos" => $value['comportamientos'],
                    "competencia" => $value['competencia'],
                    "zonas" => $value['zonas'],
                    "nombre_estrategia" => $value['nombre_estrategia'],
                    "tacticos" => [],
                    "temas" => $value['temas'],
                    "gestor" => $value['nombre']
                ];
            }

            if (empty($newArray[$value['id_planeacion']]['tacticos'])) {
                array_push($newArray[$value['id_planeacion']]['tacticos'], $value['nombre_tactico']);
            } else {
                foreach ($newArray[$value['id_planeacion']]['tacticos'] as $k => $val) {
                    if ($val != $value['nombre_tactico']) {
                        array_push($newArray[$value['id_planeacion']]['tacticos'], $value['nombre_tactico']);
                    }
                }
            }

        }

        foreach ($newArray as $key => $value) {

            $tacticos = implode(", ", $value['tacticos']);



            $newArray[$key] = array(

                'id' => $value['id_planeacion'],
                'title' => $value['comportamientos'] . ' - ' . $value['municipio'],
                'start' => $value['fecha_plan'],

                'description' =>
                'Fecha : ' . $value['fecha_plan'] . '</br>' .
                'Jornada : ' . $value['jornada'] . '</br>' .
                'Lugar de encuentro : ' . $value['lugar_encuentro'] . '</br>' .
                'Municipio : ' . $value['municipio'] . '</br>' .
                'Estrategias : ' . $value['nombre_estrategia'] . '</br>' .
                'Tacticos : ' . $tacticos . '</br>' .
                'Temas : ' . $value['temas'] . '</br>' .
                'Zona : ' . $value['zonas'] . '</br>' .
                'Gestor : ' . $value['gestor'] . '</br>',

                'editable' => false,
                'color' => 'red',
                'textColor' => "white",
                'zona' => $value['zonas'],
            );
        }

        $json = array_values($newArray);
    }

} else {
    $json = "No se recibieron los datos de manera adecuada";
}

echo json_encode($json);
