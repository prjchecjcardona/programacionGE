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
            $json = "Asistencias cargadas con exito";
        }
    }

    /* Evidencias Fotograficas */
    $num_ev = count($evidenciasFot['name']);

    for ($i = 0; $i < $num_asis; $i++) {
      if (file_exists('../registros/evidencias/' . $evidenciasFot['name'][$i])) {
          $json = "El archivo ya existe";
      } else {
          move_uploaded_file($evidenciasFot['tmp_name'][$i], '../registros/evidencias/' . $evidenciasFot['name'][$i]);
          $json .= "Evidencias cargadas con exito";
    }
  }
}

echo json_encode($json);
