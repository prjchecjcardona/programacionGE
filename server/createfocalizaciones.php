<?php

include 'lib.php';

$api = new gestionEducativa();
date_default_timezone_set("America/Bogota");
$fecha = (string) date("m-d-Y");

$municipios = $api->getMunicipiosXZona("all");

foreach ($municipios as $key => $value) {
  $foc = $api->insertFocalizacion($value['id_municipio'], 1, $fecha);
  $focMax = $api->getMaxIdFoc();
  $indFoc = $api->insertIndicadoresXFocalizacion($focMax[0]['max'], 1);
  $indFoc = $api->insertIndicadoresXFocalizacion($focMax[0]['max'], 2);

  $foc = $api->insertFocalizacion($value['id_municipio'], 1, $fecha);
  $focMax = $api->getMaxIdFoc();
  $indFoc = $api->insertIndicadoresXFocalizacion($focMax[0]['max'], 3);
  $indFoc = $api->insertIndicadoresXFocalizacion($focMax[0]['max'], 4);
  $indFoc = $api->insertIndicadoresXFocalizacion($focMax[0]['max'], 5);
  $indFoc = $api->insertIndicadoresXFocalizacion($focMax[0]['max'], 6);
  $indFoc = $api->insertIndicadoresXFocalizacion($focMax[0]['max'], 7);

  $foc = $api->insertFocalizacion($value['id_municipio'], 1, $fecha);
  $focMax = $api->getMaxIdFoc();
  $indFoc = $api->insertIndicadoresXFocalizacion($focMax[0]['max'], 14);
  $indFoc = $api->insertIndicadoresXFocalizacion($focMax[0]['max'], 15);
  $indFoc = $api->insertIndicadoresXFocalizacion($focMax[0]['max'], 16);

  $foc = $api->insertFocalizacion($value['id_municipio'], 2, $fecha);
}

$json = array('foc' => $foc, 'focmac' => $focMax, 'indfoc' => $indFoc);

echo json_encode($json);