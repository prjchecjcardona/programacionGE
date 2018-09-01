<?php

include "lib.php";

$api = new geBanco();

$errors = array("archivo" => "", "recurso" => "");
$data = array('success' => "", 'errors' => "");

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

if(empty($errors['archivo'] == "" || $errors['recurso'] == "")){

  $data['success'] = false;
  $data['errors'] = $errors;

}else{

  if(is_array($archivo)){
    $archivoLength = sizeof($archivo);
    echo $archivoLength;
    for($i = 0; $i < $archivoLength; $i++){
      $json = $api->subirArchivo($archivo[$i], $recurso);
    }
  }; 

}

echo json_encode($data);