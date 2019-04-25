<?php
include 'lib.php';
require '../phpspreadsheet/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
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

$sheet->fromArray($json, null, 'A2');
$sheet->setCellValue('A1', 'Fecha Planeacion');
$sheet->setCellValue('B1', 'Municipio');
$sheet->setCellValue('C1', 'Zona');
$sheet->setCellValue('D1', 'Lugar de encuentro');
$sheet->setCellValue('E1', 'nombre entidad');
$sheet->setCellValue('F1', 'tipo gestion');
$sheet->setCellValue('G1', 'comportamientos');
$sheet->setCellValue('H1', 'competencias');
$sheet->setCellValue('I1', 'temas');
$sheet->setCellValue('J1', 'nombre estrategia');
$sheet->setCellValue('K1', 'nombre tactico');
$sheet->setCellValue('L1', 'nombre gestor');
$sheet->setCellValue('M1', 'estado');

if ($_POST['estado'] == 0) {

    $sheet->setCellValue('N1', 'total participantes');
    foreach (range('A', 'N') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    $sheet->getStyle('A', 'N')->getFont()->setBold(true);

} else if ($_POST['estado'] == 'Ejecutado' || $_POST['estado'] == 'En Ejecución') {
    $sheet->setCellValue('N1', 'total participantes');
    foreach (range('A', 'N') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    $sheet->getStyle('A', 'N')->getFont()->setBold(true);
} else {
    foreach (range('A', 'M') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    $sheet->getStyle('A', 'M')->getFont()->setBold(true);
}

header('Content-disposition: attachment; filename=consulta.xlsx');
header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
// $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
// $writer->save('php://output');
$writer = new Xlsx($spreadsheet);
$name = 'consulta' . time() . '.xlsx';
$writer->save($name);
$filename = "server/$name";

$json = array("name"=>$name, "file"=>$filename);

echo json_encode($json);