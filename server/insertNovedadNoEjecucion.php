<?php

include 'lib.php';

$api = new gestionEducativa();

if (isset($_POST)) {

      $id_planeacion = $_POST['id_plan'];
      $descripcion = $_POST['descripcionNovedad'];
      $fecha_aplazamiento = $_POST['fechaAplazada'];

      $json = $api->insertNovedadNoEjecucion($id_planeacion, $descripcion, $fecha_aplazamiento);

} else {
    $json = "No se recibieron adecuadamente los datos";
}

echo json_encode($json);
