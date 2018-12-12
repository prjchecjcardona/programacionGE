<?php

if (isset($_POST)) {
    if (isset($_FILES['asistencias']) && isset($_FILES['evidencias'])) {
        $asistencias = $_FILES['asistencias'];
        $evidenciasFot = $_FILES['evidencias'];
    }

    /* Asistencias */
    $num_asis = count($asistencias['name']);

    for ($i = 0; $i < $num_asis; $i++) {
        if (file_exists('../registros/asistencias/' . $asistencias['name'][$i])) {
            $json = "El archivo ya existe";
        } else {
            move_uploaded_file($asistencias['tmp_name'][$i], '../registros/asistencias/' . $asistencias['name'][$i]);
            $json = "Cargado con exito";
        }
    }

    /* Evidencias Fotograficas */
}

echo json_encode($json);
