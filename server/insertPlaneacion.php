<?php

include 'lib.php';

$api = new gestionEducativa();

if (isset($_POST)) {

    $fechaReg = (string) date("d-m-Y");

    if (isset($_POST['fecha']) && isset($_POST['jornada']) && isset($_POST['lugarEncuentro'])
        && isset($_POST['entidad']) && isset($_POST['tema']) && isset($_POST['id_foc'])) {

        $fechaPlan = $_POST['fecha'];
        $jornada = $_POST['jornada'];
        $lugarEncuentro = $_POST['lugarEncuentro'];
        $entidad = $_POST['entidad'];
        $tema = $_POST['tema'];
        $id_foc = $_POST['id_foc'];
    }

    if (!empty($_POST['vereda'])) {
        $vereda = $_POST['vereda'];
    } else {
        $vereda = "null";
    }

    if (!empty($_POST['barrio'])) {
        $barrio = $_POST['barrio'];
    } else {
        $barrio = "null";
    }

    $json = $api->insertPlaneacion($jornada, $lugarEncuentro, $barrio,
    $vereda, $entidad, $tema, $fechaPlan, $fechaReg, $id_foc);

} else {
    $json = 'No se recibieron adecuadamente los datos.';
}

echo json_encode($json);
