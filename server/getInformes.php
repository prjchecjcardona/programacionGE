<?php

require 'lib.php';

$api = new gestionEducativa();

if(isset($_POST)){

    if(isset($_POST['municipio'])){
        $json = $api->getMunicipioInforme();
    }

    if(isset($_POST)){

        if(!isset($_POST['comportamiento'])){
            $comportamiento = "";
        }else{
            $comportamiento = $_POST['comportamiento'];
        }

        if(!isset($_POST['municipio'])){
            $municipio = "";
        }else{
            $municipio = $_POST['municipio'];
        }

        if(!isset($_POST['estrategia'])){
            $estrategia = "";
        }else{
            $estrategia = $_POST['estrategia'];
        }

        if(!isset($_POST['tactico'])){
            $tactico = "";
        }else{
            $tactico = $_POST['tactico'];
        }

        if(!isset($_POST['tipo'])){
            $tipo = "";
        }else{
            $tipo = $_POST['tipo'];
        }

        if(!isset($_POST['zona'])){
            $zona = "";
        }else{
            $zona = $_POST['zona'];
        }

        $json = $api->getInformes($comportamiento, $municipio, $estrategia, $tactico, $tipo, $zona);
    }

    if(isset($_POST['cobEstrategia'])){
        $json = $api->coberturaEstrategia();
        $informe = array();
        foreach ($json as $key => $value) {
            $informe[$key] = [
                "name" => $value['estrategia'],
                "y" => intval($value['sum'])
            ];
        }
        $json = $informe;
    }

    if(isset($_POST['cobZona'])){
        $json = $api->coberturaZona();
        $informe = array();
        foreach ($json as $key => $value) {
            $informe[$key] = [
                "name" => $value['zona'],
                "data" => intval($value['sum'])
            ];
        }
        $json = $informe;
    }

    if(isset($_POST['cobMun'])){
        $json = $api->coberturaMun($_POST['cobMun']);
        $informe = array();
        foreach ($json as $key => $value) {
            $informe[$key] = [
                "name" => $value['municipio'],
                "y" => intval($value['sum'])
            ];
        }
        $json = $informe;
    }

    if(isset($_POST['cobComp'])){
        $json = $api->coberturaCompetencia();
        $informe = array();
        foreach ($json as $key => $value) {
            $informe[$key] = [
                "name" => $value['competencia'],
                "y" => intval($value['sum'])
            ];
        }
        $json = $informe;
    }

    if(isset($_POST['cobAct'])){
        $json = $api->coberturaActividad();
        $informe = array();
        foreach ($json as $key => $value) {
            $informe[$key] = [
                "name" => $value['tipo_actividad'],
                "y" => intval($value['sum'])
            ];
        }
        $json = $informe;
    }
}else{
    $json = 'No se recibieron los datos adecuadamente';
}

echo json_encode($json);