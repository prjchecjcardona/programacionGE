<?php

require 'lib.php';

$api = new gestionEducativa();

if (isset($_POST)) {
  if (isset($_POST['eliminar_cont'])) {
    $json = $api->eliminarContacto($_POST['id_contacto']);
  }
  
  if (isset($_POST['getContactos'])) {
    $json = $api->getContactosXPlaneacion($_POST['id_plan']);
  }

  if (isset($_POST['getAll'])) {
    $json = array('contact' => '', 'entidades' => "");
    $json['contact'] = $api->getContactoEditar($_POST['contacto']);
    $json['entidades'] = $api->getEntidades($json['contact'][0]['id_municipio']);
  }
}else {
  $json = 'No se recibieron los datos de manera adecuada';
}

echo json_encode($json);

