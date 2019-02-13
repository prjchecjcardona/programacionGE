<?php

require 'lib.php';

$api = new gestionEducativa();

if (isset($_POST['getTact'])) {
    if (!is_array($_POST['getTact'])) {
        $json = $api->getTacticosInformes($_POST['getTact']);
    }
} else {
    if (isset($_POST)) {

        if (!isset($_POST['mes'])) {
            $mes = "";
        } else {
            $mes = $_POST['mes'];
        }

        if (!isset($_POST['comportamiento'])) {
            $comportamiento = "";
        } else {
            $comportamiento = $_POST['comportamiento'];
        }

        if (!isset($_POST['municipio'])) {
            $municipio = "";
        } else {
            $municipio = $_POST['municipio'];
        }

        if (!isset($_POST['estrategia'])) {
            $estrategia = "";
        } else {
            $estrategia = $_POST['estrategia'];
        }

        if (!isset($_POST['tactico'])) {
            $tactico = "";
        } else {
            $tactico = $_POST['tactico'];
        }

        if (!isset($_POST['tipo'])) {
            $tipo = "";
        } else {
            $tipo = $_POST['tipo'];
        }

        if (!isset($_POST['zona'])) {
            $zona = "";
        } else {
            $zona = $_POST['zona'];
        }

        if (isset($_POST['zona']) && isset($_POST['competencia'])) {

            $xAxis = array();

            if (is_array($_POST['competencia'])) {

                if (in_array('1', $_POST['competencia'])) {
                    echo 'true';
                    array_push($xAxis, "Preservación");
                }

                if (in_array('2', $_POST['competencia'])) {
                    array_push($xAxis, "Corresponsabilidad");
                }

                if (in_array('3', $_POST['competencia'])) {
                    array_push($xAxis, "Confianza");
                }

            } else {
                $xAxis = $api->getTemasPorComportamientoCobertura($_POST['competencia']);
            }
            $json = $api->getInformes($_POST['competencia'], '', '', '', '', $tipo, $_POST['zona'], $mes);

            $newArray = array();
            $i = 0;
            foreach ($json as $key => $value) {
                if (!isset($newArray[$i]['name'])) {
                    $newArray[$i]['name'] = $value['zona'];
                    $newArray[$i]['data'] = [$value['sum']];
                } elseif (($newArray[$i]['name'] != $value['zona'])) {
                    $i++;
                    $newArray[$i]['name'] = $value['zona'];
                    $newArray[$i]['data'] = [$value['sum']];
                } else {
                    array_push($newArray[$i]['data'], $value['sum']);
                }
            }

            $array = array();

            foreach ($newArray as $key => $value) {
                array_push($array, $value);
            }
        }

        if (isset($_POST['zona']) && isset($_POST['estrategia'])) {
            if (!is_array($_POST['estrategia'])) {
                $xAxis = $api->getTacticosPorEstrategiaCobertura($_POST['estrategia']);
            } else {
                $xAxis = ['Aprendiendo con energía en Comunidad', 'Aprendiendo con energía en el Cole',
                    'Aprendiendo con energía en Gobierno', 'Aprendiendo con energía en Familia',
                    'Aprendiendo con energía en mi Empresa'];
            }

            $json = $api->getInformes('', '', '', $_POST['estrategia'], '', $tipo, $_POST['zona'], $mes);
        }

    } else {
        $xAxis = 'No se recibieron los datos adecuadamente';
        $json = 'No se recibieron los datos adecuadamente';
    }

    $data = array($xAxis, $array);
}

echo json_encode($array);
