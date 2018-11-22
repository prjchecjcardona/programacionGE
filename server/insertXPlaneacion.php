<?php

include 'lib.php';

$api = new gestionEducativa();

if(isset($_POST)){
  if(isset($_POST['op']) && isset($_POST['id_plan']) && isset($_POST['contacto'])){

    $id_plan = $_POST['id_plan'];

    foreach ($_POST['op'] as $key => $value) {
      $name = $_POST['op'][$key]['name'];
      $value = $_POST['op'][$key]['value'];

      $json = $api->insertXPlaneacion($value, $id_plan, $name);
    }

    foreach ($_POST['contacto'] as $key => $value) {
      $json = $api->insertContactoXPlaneacion($value, $id_plan);
    }

  }else{
    $json = $api->getMaxPlanFoc();
  }
}else{
  $json = "No se recibieron los datos de manera adecuada";
}

echo json_encode($json);