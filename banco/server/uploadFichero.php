<?php

include "lib.php";

$api = new geBanco();

$data = array('success' => 0, 'message' => '');

$competencia = $_POST['competencia'];
$indicador = $_POST['indicador '];
$tema = $_POST['tema'];
$zona = $_POST['zona'];
$codigo = $_POST['codigo'];

if(empty($competencia) || empty($indicador) || empty($tema) || empty($zona) || empty($codigo)){
  $data['success'] = 1;
  $data['message'] = "No se pudo resnhairdfasdfla´f";
}

return $data;
