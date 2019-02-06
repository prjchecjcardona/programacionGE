<?php

include 'lib.php';

$api = new gestionEducativa();

if (isset($_POST)) {

    $fechaReg = (string) date("Y-m-d");

    if (isset($_POST['fecha']) && isset($_POST['jornada']) && isset($_POST['lugarEncuentro'])
        && isset($_POST['entidad']) && isset($_POST['id_foc'])) {

        #Create datetime of $_POST['fecha']
        $newDate = date_create($_POST['fecha']);
        #Change new date format
        $fechaPlan = date_format($newDate, "Y-m-d");
        $jornada = $_POST['jornada'];
        $lugarEncuentro = $_POST['lugarEncuentro'];
        $entidad = $_POST['entidad'];
        $id_foc = $_POST['id_foc'];
        $solicitud = $_POST['solicitudEducativa'];
        $estado = "Planeado";
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
        $vereda, $entidad, $fechaPlan, $fechaReg, $id_foc, $solicitud, $estado);

    if ($json['error'] == 0) {

        if ($solicitud == "true") {
            $file_solicitud = $_FILES['solicitud'];
            $max = $api->getMaxPlanFoc();

            $url = "./registros/solicitudes/" . $file_solicitud['name'];

            move_uploaded_file($file_solicitud['tmp_name'], $url);

            if (file_exists($url)) {
                $json = $api->insertRegistros(5, $max[0]['max'], $url);

            }else{
                $json = array('error' => 1,
                'message' => 'No se pudo subir el archivo de la solicitud',
                'error_message' => $file_solicitud['error']);
            }

        }
    }

} else {
    $json = 'No se recibieron adecuadamente los datos.';
}

echo json_encode($json);
