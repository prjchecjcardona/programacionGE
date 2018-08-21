<?php

require "../phpExcel/Classes/PHPExcel/IOFactory.php";

require "conexion.php";

$nombre_archivo = "../phpExcel/recursos.xlsx";

$objPHPExcel = PHPEXCEL_IOFactory::load($nombre_archivo);

$active_sheet = $objPHPExcel->setActiveSheetIndex(0);

$numRow = $active_sheet->getHighestRow();

echo '<table border=1><tr><td>id_archivo</td>
<td>nombre_archivo</td><td>id_recurso</td>';

$getActiveSheet = $objPHPExcel->getActiveSheet();

for($i=2; $i<=$numRow; $i++){
  
  $id_archivo = $getActiveSheet->getCell('A'.$i)->getCalculatedValue();
  $nombre_archivo = $getActiveSheet->getCell('B'.$i)->getCalculatedValue();
  $id_recurso = $getActiveSheet->getCell('C'.$i)->getCalculatedValue();

  echo '<tr>';
  echo '<td>'.$id_archivo.'</td>';
  echo '<td>'.$nombre_archivo.'</td>';
  echo '<td>'.$id_recurso.'</td>';

  $sql = "INSERT INTO archivos_recursos (id_archivo, nombre_archivo, id_recurso) 
  VALUES ('$id_archivo', '$nombre_archivo', '$id_recurso')";

  $rs = $con->query($sql);
  if(!$rs){
    print_r($con->errorInfo());
    echo "No se realizo la consulta";
  }
}

echo '</table>';