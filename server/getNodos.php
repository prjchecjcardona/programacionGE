<?php

include 'lib.php';
$api = new gestionEducativa();

if(isset($_POST['id_plan'])) {
  $json = $api->getNodos($_POST['id_plan']);
}
else if(isset($_POST['add'])) {

}

echo json_encode($json);