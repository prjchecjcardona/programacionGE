<?php

include('conexion.php');
 $sql = "SELECT MAX(id_intervenciones) as id_intervenciones FROM intervenciones";

if ($rs = $con->query($sql)) {
    if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
        $idIntervencion=$filas[0]['id_intervenciones'];
    }
}

$data = array();

if(isset($_FILES['archivo'])){
    //MODIFICAR ESTA RUTA SI ES NECESARIO AL SUBIR A AMBIENTE DE PRODUCCIÓN
    $dir_subida = dirname(__DIR__)."\archivos\\";
    $fichero_subido = $dir_subida .'intervBaseid_'.$idIntervencion.'_'.basename($_FILES['archivo']['name']);

    if (move_uploaded_file($_FILES['archivo']['tmp_name'], $fichero_subido)) {
        $sql = "UPDATE intervenciones SET img_url = '$fichero_subido' WHERE id_intervenciones = $idIntervencion";
        
        if ($rs = $con->query($sql)) {
           
        }else {
            $data['error'] ="Ocurrió un error al subir los archivos";
        }
    } else {
        $data['error'] = "Ocurrió un error al subir los archivos";
    }

    
}


echo json_encode($data);