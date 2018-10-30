<?php

include 'lib.php';

$api = new gestionEducativa();

if(isset($_POST['foc'])){
  $json = $api->getPlaneacionesXFocalizacion($_POST['foc']);
}else{
  $json = 'No se recibieron los datos de manera adecuada';
}

echo json_encode($json);