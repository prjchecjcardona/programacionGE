<?php

include 'lib.php';
$api = new gestionEducativa();

if(isset($_POST['id_plan'])) {
  $json = $api->getNodos($_POST['id_plan']);
}

if(isset($_POST['add'])) {

  $nodo = $_POST['nodo'];

  if (isset($_POST['latitud'])) {
    $latitud = $_POST['latitud'];
  }else {
    $latitud = '';
  }

  if (isset($_POST['longitud'])) {
    $longitud = $_POST['longitud'];
  }else {
    $longitud = '';
  }

  $municipio = $api->getMunicipioIdPlan($_POST['id_plan']);

  $json = $api->addNodos($nodo, $latitud, $longitud, $municipio[0]['id_municipio']);
}

echo json_encode($json);