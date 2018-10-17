<?php
include 'lib.php';

$api = new gestionEducativa();

if (isset($_POST)) {
  $json = $api->getBarrios();
} else {
  $json = "No se recibieron los datos de manera adecuada";
}

echo json_encode($json);