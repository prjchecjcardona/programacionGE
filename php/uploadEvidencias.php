<?php

include('conexion.php');
 $sql = "SELECT MAX(id_ejecucion) as id_ejecuciones FROM ejecucion";

if ($rs = $con->query($sql)) {
    if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
        $idEjecucion=$filas[0]['id_ejecuciones'];
    }
}

$data = array();

if(isset($_FILES['file_fotograficas'])){
    //MODIFICAR ESTA RUTA SI ES NECESARIO AL SUBIR A AMBIENTE DE PRODUCCIÓN
    $dir_subida = dirname(__DIR__).'/archivos'.'/';

    $fichero_subido = $dir_subida .'evidencia_'.$idEjecucion.'_'.basename($_FILES['file_fotograficas']['name']);

    //Se asigna la siguiente ruta relativa para almacenar en BD y recuperar desde ella en el front sin problemas (local)
    $img_url_paraBD = "./archivos/".'evidencia_'.$idEjecucion.'_'.basename($_FILES['file_fotograficas']['name']);


    //TODO
    if (move_uploaded_file($_FILES['file_fotograficas']['tmp_name'], $fichero_subido)) {
        $sql = "INSERT INTO adjuntos VALUES (nextval('sec_adjuntos'), '$img_url_paraBD', 'evidencia');";
        
        if ($rs = $con->query($sql)) {
            $sql= "INSERT INTO ejecucion_adjuntos VALUES (
                nextval('sec_ejecuciones_adjuntos'),
                (SELECT MAX(id_adjunto) as id_adjuntos FROM adjuntos),
                $idEjecucion
                );";
             if ($rs = $con->query($sql)){

             }else{
                $data['error'] ="Ocurrió un error al enlazar el archivo con la ejecucion";
             }
           
        }else {
            $data['error'] ="Ocurrió un error al ingresar a la BD el archivo";
        }
    } else {
        $data['error'] = "Ocurrió un error al subir los archivos";
    }

    
}


echo json_encode($data);