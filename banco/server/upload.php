<?php

include "lib.php";

$api = new geBanco();

$errors = array();
$data = array();

// Validate the variables

if(empty($_POST['archivo'])){
  $errors['archivo'] = 'No has seleccionado el archivo';
}else{
  $archivo = $_POST['archivo'];
}

if(empty($_POST['recurso'])){
  $errors['recurso'] = 'No has seleccionado el recurso a la cual deseas subir';
}else{
  $recurso = $_POST['recurso'];
}

// Return response 

if(! empty($errors)){

  $data['success'] = false;
  $data['errors'] = $errors;

}else{

  $json = $api->subirArchivo($archivo, $recurso);

}

echo json_encode($data);