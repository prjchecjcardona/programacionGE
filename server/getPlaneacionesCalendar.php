<?php

include 'lib.php';

$api = new gestionEducativa();

if (isset($_POST)) {

    $planeaciones = array('no_ejecutados' => '', 'en_ejecucion_ejecutados' => '', 'en_planeacion' => '');

    if (isset($_POST['getPlans'])) {
        $json = $api->getNovedadesNoEjecucion($_POST['id_zona']);

        foreach ($json as $key => $value) {

            $json[$key] = array(
                'id' => $value['id_planeacion'],
                'title' => 'No ejecucion / ' . $value['comportamientos'] . ' - ' . $value['competencia'],
                'start' => $value['fecha_no_ejecutada'],
                'proximo' => $value['fecha_plan'],

                'description' =>
                'Municipio : ' . $value['municipio'] . '</br>' .
                'Fecha de la actividad no ejecutada : ' . $value['fecha_no_ejecutada'] . '</br>' .
                'Nueva fecha para la ejecución de la actividad : ' . $value['fecha_plan'] . '</br>' .
                'Zona : ' . $value['zonas'] . '</br>' .
                'Gestor : ' . $value['nombre'] . '</br>' .
                'Tema : ' . $value['temas'] . '</br>' .
                'Estrategia : ' . $value['nombre_estrategia'] . '</br>',

                'editable' => false,
                'color' => '#a2a1a0',
                'textColor' => "white",
                'zona' => $value['zonas'],
                'id_zona' => $value['id_zona'],
                'municipio' => $value['municipio'],
                'tema' => $value['temas'],
                'estrategia' => $value['nombre_estrategia'],
            );
        }

        $planeaciones['no_ejecutados'] = $json;

        /* ------------------------ En ejecución o ejecutados ---------------------------- */

        $json = $api->getPlaneacionesEjecutadosOEnEjecucion($_POST['id_zona']);
        $newArray = array();
        $requisitos = array();
        $eliminar_ejec = "";
        $list = "";
        $array = array();

        foreach ($json as $key => $value) {

            if (!isset($newArray[$value['id_planeacion']])) {

                

                $newArray[$value['id_planeacion']] = array(

                    'id' => $value['id_planeacion'],
                    'title' => $value['comportamientos'] . ' - ' . $value['municipio'],
                    'municipio' => $value['municipio'],
                    'start' => $value['fecha_plan'],
                    'id_zona' => $value['id_zona'],

                    'descripcion' => [
                        'fecha' => $value['fecha_plan'],
                        'zona' => $value['zonas'],
                        'jornada' => $value['jornada'],
                        'comportamiento' => $value['comportamientos'],
                        'competencia' => $value['competencia'],
                        'estrategia' => $value['nombre_estrategia'],
                        'gestion' => $value['tipo_gestion'],
                        'tema' => $value['temas'],
                        'gestor' => $value['nombre'],
                        'jornada' => $value['jornada'],
                        'lugar' => $value['lugar_encuentro'],
                    ],

                    'total_participantes' => '',

                    'tacticos' => array(),
                    'tipo_gestion' => $value['id_tipo_gestion'],
                    'hora' => array(),
                    'url_solicitud' => $value['url'],
                    'status' => $value['estado'],
                    'evidencias' => array(),
                    'actas' => array(),
                    'asistencias' => array(),
                    'etapa_planeacion' => $value['etapa_planeacion'],
                    'valid_ejec' => true,
                    'color' => '',
                    'icon' => '',
                    'editable' => false,
                    'requisitos' => array(),
                );

            }



            if (empty($newArray[$value['id_planeacion']]['tacticos'])) {
                $newArray[$value['id_planeacion']]['tacticos'] = array();
                array_push($newArray[$value['id_planeacion']]['tacticos'], $value['nombre_tactico']);
            } else {
                $valid = true;
                foreach ($newArray[$value['id_planeacion']]['tacticos'] as $k => $val) {
                    if ($val == $value['nombre_tactico']) {
                        $valid = false;
                        break;
                    }
                }

                if ($valid) {
                    array_push($newArray[$value['id_planeacion']]['tacticos'], $value['nombre_tactico']);
                }
            }

            if (!empty($value['etapa_planeacion'])) {
                if (!empty($value['etapa_planeacion'])) {
                    if ($value['etapa_planeacion'] == "Iniciada") {
                        $newArray[$value['id_planeacion']]['hora']['hora_inicio'] = $value['hora'];
                    } else {
                        $newArray[$value['id_planeacion']]['hora']['hora_fin'] = $value['hora'];
                    }
                } elseif (!empty($value['hora_inicio'])) {
                    $newArray[$value['id_planeacion']]['hora']['hora_inicio'] = $value['hora_inicio'];
                    $newArray[$value['id_planeacion']]['hora']['hora_fin'] = $value['hora_fin'];
                } else {
                    $newArray[$value['id_planeacion']]['hora']['hora_inicio'] = '--:--:--';
                    $newArray[$value['id_planeacion']]['hora']['hora_fin'] = '--:--:--';
                }
            } else {
                $newArray[$value['id_planeacion']]['hora']['hora_inicio'] = '--:--:--';
                $newArray[$value['id_planeacion']]['hora']['hora_fin'] = '--:--:--';
            }

            if (!isset($requisitos[$value['id_planeacion']])) {
                $requisitos[$value['id_planeacion']] = [];
                $registros = [];
                $rgtros_array = [];
                $validReg = true;

                if (is_null($value['id_ejecucion'])) {
                    $valid_ejec = 0;
                    $newArray[$value['id_planeacion']]['valid_ejec'] = 0;
                    $validReg = false;
                    //$newArray[$value['id_planeacion']] = false;
                    array_push($requisitos[$value['id_planeacion']], '<li> Registrar la ejecución de la actividad </li>');
                }

                if($newArray[$value['id_planeacion']]['valid_ejec'] && $newArray[$value['id_planeacion']]['tipo_gestion'] == 1){
                    $total = $api->getTotalAsistentes($value['id_planeacion']);
                    $newArray[$value['id_planeacion']]['total_participantes'] = $total;
                }

                if ($value['id_tipo_gestion'] == 2) {
                    $registros = $api->checkRegistros($value['id_planeacion'], 4);

                    if (empty($registros)) {
                        $validReg = false;
                        array_push($requisitos[$value['id_planeacion']], '<li> Adjuntar acta </li>');
                    }
                } else {
                    $registros = $api->checkRegistros($value['id_planeacion'], [1, 3]);

                    if (empty($registros)) {
                        $validReg = false;
                        array_push($requisitos[$value['id_planeacion']], '<li> Adjuntar evidencias </li>');
                        array_push($requisitos[$value['id_planeacion']], '<li> Adjuntar asistencias </li>');
                    } else {
                        for ($i = 0; $i < count($registros); $i++) {
                            array_push($rgtros_array, $registros[$i]['id_tipo_registro']);
                        }

                        $unique = array_unique($rgtros_array);

                        if (!in_array(1, $unique)) {
                            $validReg = false;
                            array_push($requisitos[$value['id_planeacion']], '<li> Adjuntar evidencias </li>');
                        }

                        if (!in_array(3, $unique)) {
                            $validReg = false;
                            array_push($requisitos[$value['id_planeacion']], '<li> Adjuntar asistencias </li>');
                        }
                    }
                }

                $newArray[$value['id_planeacion']]['requisitos'] = $requisitos[$value['id_planeacion']];

                if ($validReg) {
                    $newArray[$value['id_planeacion']]['color'] = '#269226';
                    $newArray[$value['id_planeacion']]['icon'] = 'fas fa-check-circle';
                    if ($value['estado'] != 'Ejecutado') {
                        $update = $api->updateEstadoPlaneacion('Ejecutado', $value['id_planeacion']);
                    }
                } else {
                    $newArray[$value['id_planeacion']]['color'] = '#edbe00';
                    $newArray[$value['id_planeacion']]['icon'] = 'fas fa-minus-circle';
                    if ($value['estado'] != 'En Ejecución') {
                        $update = $api->updateEstadoPlaneacion('En Ejecución', $value['id_planeacion']);
                    }
                }
            }

        }

        $json = array_values($newArray);

        $planeaciones['en_ejecucion_ejecutados'] = $json;

        /* ---------------------------- Planeados -----------------------------------  */

        $json = $api->getPlaneacionesCalendar($_POST['id_zona']);

        $newArray = array();

        foreach ($json as $key => $value) {

            if (!isset($newArray[$value['id_planeacion']])) {

                $newArray[$value['id_planeacion']] = array(

                    'id' => $value['id_planeacion'],
                    'title' => $value['comportamientos'] . ' - ' . $value['municipio'],
                    'municipio' => $value['municipio'],
                    'start' => $value['fecha_plan'],

                    'descripcion' => [
                        'fecha' => $value['fecha_plan'],
                        'zona' => $value['zonas'],
                        'jornada' => $value['jornada'],
                        'comportamiento' => $value['comportamientos'],
                        'competencia' => $value['competencia'],
                        'estrategia' => $value['nombre_estrategia'],
                        'gestion' => $value['tipo_gestion'],
                        'tema' => $value['temas'],
                        'gestor' => $value['nombre'],
                        'jornada' => $value['jornada'],
                        'lugar' => $value['lugar_encuentro'],
                    ],

                    'id_zona' => $value['id_zona'],
                    'tacticos' => array(),
                    'tipo_gestion' => $value['id_tipo_gestion'],
                    'url_solicitud' => $value['url'],
                    'status' => $value['estado'],
                    'color' => '#ff0000',
                    'icon' => 'fas fa-minus-circle',
                    'editable' => false,
                );

            } else {

                if (empty($newArray[$value['id_planeacion']]['tacticos'])) {
                    array_push($newArray[$value['id_planeacion']]['tacticos'], $value['nombre_tactico']);
                } else {
                    $valid = true;
                    foreach ($newArray[$value['id_planeacion']]['tacticos'] as $k => $val) {
                        if ($val == $value['nombre_tactico']) {
                            $valid = false;
                            break;
                        }
                    }

                    if ($valid) {
                        array_push($newArray[$value['id_planeacion']]['tacticos'], $value['nombre_tactico']);
                    }
                }
            }
        }

        $json = array_values($newArray);
        $planeaciones['en_planeacion'] = $json;
    }

} else {
    $json = "No se recibieron los datos de manera adecuada";
}

echo json_encode($planeaciones);
