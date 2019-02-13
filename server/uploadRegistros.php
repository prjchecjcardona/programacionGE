<?php

include 'lib.php';

$api = new gestionEducativa();

$errors = false;
$exists = false;
$errores_archivos = array();
$sql_file = array();
$id_plan = $_POST['id_plan'];

if (isset($_POST)) {

    if (isset($_FILES['actas'])) {
        $actas = $_FILES['actas'];

        $actas_cant = count($actas['name']);

        for ($i = 0; $i < $actas_cant; $i++) {
            $url = '../registros/actas/' . $actas['name'][$i] . '_' . $id_plan;

            if (file_exists('../registros/actas/' . $actas['name'][$i] . '_' . $id_plan)) {

                $exists = true;
                move_uploaded_file($actas['tmp_name'][$i], $url);

            } else {
                move_uploaded_file($actas['tmp_name'][$i], $url);

                if (file_exists($url) && !$exists) {

                    $sql = $api->insertRegistros(4, $id_plan, $url);

                    if ($sql['error'] == 1) {
                        array_push($sql_file, $actas['name'][$i]);
                    }
                }

            }

        }
    }

    if (isset($_FILES['asistencias'])) {
        $asistencias = $_FILES['asistencias'];

        /* Asistencias */
        $num_asis = count($asistencias['name']);

        for ($i = 0; $i < $num_asis; $i++) {
            $url = '../registros/asistencias/' . $asistencias['name'][$i] . '_' . $id_plan;

            if (file_exists('../registros/asistencias/' . $asistencias['name'][$i] . '_' . $id_plan)) {

                $exists = true;
                move_uploaded_file($asistencias['tmp_name'][$i], $url);

            } else {
                move_uploaded_file($asistencias['tmp_name'][$i], $url);

                if (file_exists($url) && !$exists) {

                    $sql = $api->insertRegistros(3, $id_plan, $url);

                    if ($sql['error'] == 1) {
                        array_push($sql_file, $asistencias['name'][$i]);
                    }
                }

            }

        }
    }

    if (isset($_FILES['evidencias'])) {
        $evidenciasFot = $_FILES['evidencias'];

        /* Evidencias Fotograficas */
        $num_ev = count($evidenciasFot['name']);

        for ($i = 0; $i < $num_ev; $i++) {
            $url = '../registros/evidencias/' . $evidenciasFot['name'][$i] . '_' . $id_plan;

            if (file_exists('../registros/evidencias/' . $evidenciasFot['name'][$i] . '_' . $id_plan)) {

                $exists = true;
                move_uploaded_file($evidenciasFot['tmp_name'][$i], $url);

            } else {
                move_uploaded_file($evidenciasFot['tmp_name'][$i], $url);

                if (file_exists($url) && !$exists) {

                    $sql = $api->insertRegistros(1, $id_plan, $url);

                    if ($sql['error'] == 1) {
                        array_push($sql_file, $evidenciasFot['name'][$i]);
                        $error_message = $sql['error_message'];
                    }
                }
            }

        }
    }

    if ($errors) {
        $json = array('error' => 0, 'mensaje' => "Estos archivos ya existen",
            'archivo' => implode(', ', $errores_archivos), 'sql_error' =>
            $sql_error = array('error' => 0, 'mensaje' =>
                'Error en la consulta de carga', 'archivos' => implode(', ', $sql_file)));
    } else {
        $json = array('error' => 1, 'mensaje' => "Guardado con éxito!");
    }

}

echo json_encode($json);
