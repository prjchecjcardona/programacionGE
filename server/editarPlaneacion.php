<?php

require 'lib.php';

$api = new gestionEducativa();

if (isset($_POST)) {

  $id_plan = $_POST['id_planeacion'];

  if (isset($_POST['jornada'])) {
    $column_name = 'jornada';
    $arg = $_POST['jornada'];
  }
  else if (isset($_POST['lugar_encuentro'])) {
    $column_name = 'lugar_encuentro';
    $arg = $_POST['lugar_encuentro'];
  }
  else if (isset($_POST['id_barrio'])) {
    $column_name = 'id_barrio';
    $arg = $_POST['id_barrio'];
  }
  else if (isset($_POST['id_vereda'])) {
    $column_name = 'id_vereda';
    $arg = $_POST['id_vereda'];
  }
  else if (isset($_POST['id_entidad'])) {
    $column_name = 'id_entidad';
    $arg = $_POST['id_entidad'];
  }
  else if (isset($_POST['solicitud_interventoria'])) {
    $calumn_name = 'solicitud_interventoria';
    $arg = $_POST['solicitud_interventoria'];
  }
  else if (isset($_POST['fecha_plan'])) {
    $calumn_name = 'fecha_plan';
    $arg = $_POST['fecha_plan'];
  }
  else if (isset($_POST['tipo_focalizacion'])) {
    $calumn_name = 'tipo_focalizacion';
    $arg = $_POST['tipo_focalizacion'];
  }

  $json = $api->editarPlaneacion($id_plan, $column_name, $arg);
} else {
  $json = 'No se recibieron los datos de manera adecuada';
}