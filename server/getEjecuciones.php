<?php

include 'lib.php';

$api = new gestionEducativa();

if(isset($_POST['id_plan'])){
  $id_plan = $_POST['id_plan'];
  $json = getDetalleEjecucion($id_plan);

  $newArray[]
}