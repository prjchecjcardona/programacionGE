<?php

include 'lib.php';

$api = new gestionEducativa();

if(isset($_POST)){
  if(isset($_POST['id_plan']) && isset($_POST['type'])){
    $id_plan = $_POST['id_plan'];
    $type = $_POST['type'];

    if($type == 1){
      $json = $api->eliminarEjecucion($id_plan);
      if($json['error'] == 0){
        $update = $api->updateEstadoPlaneacion('En Ejecución', $id_plan);
      }
    }else{
      $json = $api->eliminarPlaneacion($id_plan);
    }
  }else{
    $json = "No se recibieron los datos de manera adecuada";
  }
}else{
  $json = "No se recibieron los datos de manera adecuada";
}

echo json_encode($json);