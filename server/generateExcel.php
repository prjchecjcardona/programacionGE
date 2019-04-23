<?php
include 'lib.php';
require '../phpspreadsheet/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$api = new gestionEducativa();

if (isset($_POST['zona'])) {
  $zona = $_POST['zona'];
}

if (isset($_POST['municipio'])) {
  $municipio = $_POST['municipio'];
}

if (isset($_POST['estrategia'])) {
  $estrategia = $_POST['estrategia'];
}

if (isset($_POST['zona'])) {
  $tema = $_POST['zona'];
}

if (isset($_POST['tipo'])) {
  $tipo_gestion = $_POST['tipo'];
}

if (isset($_POST['estado'])) {
  $estado_planeacion = $_POST['estado'];
}

$json = $api->getConsultaExcel($zona, $municipio, $estrategia, $tema, $tipo_gestion, $estado_planeacion);

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->fromArray($json, NULL, 'A2');

  $sheet->setCellValue('A1', 'Fecha Planeacion');
  $sheet->setCellValue('B1', 'Municipio');
  $sheet->setCellValue('C1', 'Zona');
  $sheet->setCellValue('D1', 'Lugar de encuentro');
  $sheet->setCellValue('E1', 'nombre entidad');
  $sheet->setCellValue('F1', 'comportamientos');
  $sheet->setCellValue('G1', 'competencias');
  $sheet->setCellValue('H1', 'temas');
  $sheet->setCellValue('I1', 'nombre estrategia');
  $sheet->setCellValue('J1', 'nombre tactico');
  $sheet->setCellValue('K1', 'nombre gestor');
  $sheet->setCellValue('L1', 'estado');

  foreach (range('A','L') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
  }

  $sheet->getStyle( 'A', 'L' )->getFont()->setBold( true );


header('Content-disposition: attachment; filename=consulta.xlsx');
header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

// $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
// $writer->save('php://output');
$writer = new Xlsx($spreadsheet);
$writer->save('hello world.xlsx');