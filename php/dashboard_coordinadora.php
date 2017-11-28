<?php
require('dashboard_class.php');

$dashboard = new Dashboard();



$type = $_POST['type'];
$json = "";

switch ($type) {
    case 'general gestion redes':
    $json = $dashboard->getGeneralGestionRedes();
    break;
    case 'mes gestion redes':
    $json = $dashboard->getMesGestionRedes($_POST['mes'], $_POST['anio']);
    break;
    case 'getDataCombos':
    $json = $dashboard->getDataCombos();
    break;
    
    default:
    $json['err'] = 1;
    break;
}




echo json_encode($json);