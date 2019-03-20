<?php

require 'lib.php';

$api = new gestionEducativa();

if (isset($_POST['getEjecucion'])) {
    $json = array('detalle' => '', 'poblacion' => '', 'caracteristicas' => '');
    $json['detalle'] = $api->getDetalleEjecucion($_POST['id_plan']);
    $json['poblacion'] = $api->getPoblacionXEjecucion($_POST['id_plan']);
    $json['caracteristicas'] = $api->getCaracteristicasXEjecucion($_POST['id_plan']);
} else {
    if (isset($_POST)) {

        $id_plan = $_POST['id_plan'];
        $arg = $_POST['val'];

        switch ($_POST['name']) {
            case 'fecha':
                $column_name = 'fecha';
                break;
            case 'hora_inicio':
                $column_name = 'hora_inicio';
                break;
            case 'hora_fin':
                $column_name = 'hora_fin';
                break;
            case 'resultado_ejecucion':
                $column_name = 'id_resultado_ejecucion';
                break;
            case 'descripcion':
                $column_name = 'descripcion_resultado';
                break;
            case 'tipo_ejecucion':
                $column_name = 'tipo_ejecucion';
                break;
        }

        $json = $api->editarEjecucion($id_plan, $column_name, $arg);
    } else {
        $json = 'No se recibieron los datos de manera adecuada';
    }
}

echo json_encode($json);
