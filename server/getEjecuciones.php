<?php

include 'lib.php';

$api = new gestionEducativa();

if(isset($_POST['id_plan'])){
  $id_plan = $_POST['id_plan'];
  $json = $api->getDetalleEjecucion($id_plan);

  $new_array = array();

  foreach ($json as $key => $value) {
    if(!isset($new_array[$value['id_ejecucion']])){
      $new_array[$value['id_ejecucion']] = [
        'id_ejecucion' => $value['id_ejecucion'],
        'fecha' => $value['fecha'],
        'hora_inicio' => $value['hora_inicio'],
        'hora_fin' => $value['hora_fin'],
        'tipo_ejecucion' => $value['tipo_ejecucion'],
        'resultado_ejecucion' => $value['resultado_ejecucion'],
        'tipo' => ['tipo_pob' => array(), 'total' => array()],
        'caracteristica' => ['caract' => array(), 'total' => array()]
      ];
    }

    
    if(empty($new_array[$value['id_ejecucion']]['tipo']['tipo_pob'])){
      array_push($new_array[$value['id_ejecucion']]['tipo']['tipo_pob'], $value['tipo']);
      array_push($new_array[$value['id_ejecucion']]['tipo']['total'], $value['total_tipo']);
    }else{
      $valid = true;
      foreach ($new_array[$value['id_ejecucion']]['tipo']['tipo_pob'] as $k => $v) {
        if($v == $value['tipo']){
          $valid = false;
          break;
        }
      }

      if($valid){
        array_push($new_array[$value['id_ejecucion']]['tipo']['tipo_pob'], $value['tipo']);
        array_push($new_array[$value['id_ejecucion']]['tipo']['total'], $value['total_tipo']);
      }
    }

    if(empty($new_array[$value['id_ejecucion']]['caracteristica']['caract'])){
      array_push($new_array[$value['id_ejecucion']]['caracteristica']['caract'], $value['caracteristica']);
      array_push($new_array[$value['id_ejecucion']]['caracteristica']['total'], $value['total_caract']);
    }else{
      $valid = true;
      foreach ($new_array[$value['id_ejecucion']]['caracteristica']['caract'] as $ky => $llave) {
        if($llave == $value['caracteristica']){
          $valid = false;
          break;
        }
      }

      if($valid){
        array_push($new_array[$value['id_ejecucion']]['caracteristica']['caract'], $value['caracteristica']);
        array_push($new_array[$value['id_ejecucion']]['caracteristica']['total'], $value['total_caract']);
      }
    } 
  }

  $json = array_values($new_array);

}else{
  $json = "No se recibieron los datos de manera adecuada";
}

echo json_encode($json);