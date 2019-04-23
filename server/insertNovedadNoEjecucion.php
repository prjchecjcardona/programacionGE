<?php

include 'lib.php';

$api = new gestionEducativa();

if (isset($_POST)) {

    $id_planeacion = $_POST['id_plan'];
    $descripcion = $_POST['descripcionNovedad'];
    if(isset($_POST['fechaAplazada'])) {
        $fecha_aplazamiento = $_POST['fechaAplazada'];
    }else {
        $fecha_aplazamiento = "";
    }


    if (isset($_POST['no_ejec'])) {

        $fecha = date_create($_POST['fecha_plan']);
        $fecha_plan = date_format($fecha, 'd-m-Y');

        $json = $api->insertNovedadNoEjecucion($id_planeacion, $descripcion, $fecha_aplazamiento, $fecha_plan);

        if($json['error'] == 0){
            $json = "Novedad guardada!";
        }else{
            $json = "La novedad no se pudo guardar!";
        }

    }

    if (isset($_POST['aplazar_plan'])) {
        if (isset($_POST['fechaAplazada'])) {
            $json = $api->aplazarPlaneacion($id_planeacion, $fecha_aplazamiento);

            if($json['error'] == 0){
                $json = "Se aplazó la planeación existosamente";
            }else{
                $json = "No se pudo aplazar la planeación";
            }
        }else {
            $json = "No hay fecha para aplazar la planeación";
        }
    }

} else {
    $json = "No se recibieron adecuadamente los datos";
}

echo json_encode($json);
