<?php

include 'lib.php';

$api = new gestionEducativa();

if(isset($_POST)){
  $json = $api->getTrabajosAdministrativosCalendar();

  foreach ($json as $key => $value) {

    $json[$key] = array(
        'id' => $value['id_trabajo_administrativo'],
        'title' =>'T. Admin - '. $value['municipio'],
        'start' => $value['fecha'],
        'hora_in' => $value['hora_inicio'],
        'hora_fin' => $value['hora_fin'],
        'editable' => false,
        'color' => 'blue',
        'textColor' => "white",
        'zona' => $value['id_zona']
    );
}
}else{
  $json = "No se recibieron los dato adecuadamente";
}

echo json_encode($json);