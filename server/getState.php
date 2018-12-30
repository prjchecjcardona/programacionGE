<?php

include 'lib.php';

$api = new gestionEducativa();

if(isset($_POST)){

  $estado = $api->getEtapaPlaneacion($id_plan);

  if(count($estado) < 1){
    $etapa_plan = "Iniciada";
  }else{
    $etapa_plan = "Finalizada";
  }

}

echo json_encode($etapa_plan);