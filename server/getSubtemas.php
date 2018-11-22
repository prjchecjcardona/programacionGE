<?php

include 'lib.php';

$api = new gestionEducativa();

if (isset($_POST)) {
    if (isset($_POST['tema'])) {
        $json = $api->getSubtemasXTema($_POST['tema']);
    } else {
        $json = "No se recibieron los datos adecuadamente";
    }
}else{
  $json = "No se recibieron los datos adecuadamente";
}
