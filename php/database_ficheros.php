<?php

require "../phpExcel/Classes/PHPExcel/IOFactory.php";

require "conexion.php";

$nombre_archivo = "../phpExcel/ficheros.xlsx";

$objPHPExcel = PHPEXCEL_IOFactory::load($nombre_archivo);

$objPHPExcel->setActiveSheetIndex(0); 

$numRow = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();

echo '<table border=1><tr><td>Competencoa</td>
<td>Tema</td><td>Zona</td><td>Fichero</td><td>Codigo</td>
<td>Recurso</td><td>Indicador</td>';

for($i=2; $i<=$numRow; $i++){

  $competencia = $objPHPExcel->getActiveSheet()->getCell('A'.$i)->getCalculatedValue();
  $tema = $objPHPExcel->getActiveSheet()->getCell('B'.$i)->getCalculatedValue();
  $zona = $objPHPExcel->getActiveSheet()->getCell('C'.$i)->getCalculatedValue();
  $fichero = $objPHPExcel->getActiveSheet()->getCell('D'.$i)->getCalculatedValue();
  $codigo = $objPHPExcel->getActiveSheet()->getCell('E'.$i)->getCalculatedValue();
  $recurso = $objPHPExcel->getActiveSheet()->getCell('F'.$i)->getCalculatedValue();
  $indicador = $objPHPExcel->getActiveSheet()->getCell('G'.$i)->getCalculatedValue();

  echo '<tr>';
  echo '<td>'.$competencia.'</td>';
  echo '<td>'.$tema.'</td>';
  echo '<td>'.$zona.'</td>';
  echo '<td>'.$fichero.'</td>';
  echo '<td>'.$codigo.'</td>';
  echo '<td>'.$recurso.'</td>';
  echo '<td>'.$indicador.'</td>';

  $sql = "INSERT INTO ficheros (id_competencia, id_tema, id_zona, nombre_fichero, codigo, id_recurso, id_indicador) 
  VALUES ('$competencia', '$tema', '$zona', 
  '$fichero', '$codigo', '$recurso', '$indicador')";

  $rs = $con->query($sql);
  if (!$rs) {
    print_r($con->errorInfo());
    echo "No se realizo la consulta";
  }
}

echo '</table>';