<?php

include('conexion.php');
if(isset($_POST['idIntervencion'])){
    $idIntervencion = $_POST['idIntervencion'];
}

$sql = "SELECT count(evo.id_evolucion)
FROM intervenciones inter
LEFT OUTER JOIN evolucion_estado_comportamientos evo ON evo.intervenciones_id_intervenciones = inter.id_intervenciones
WHERE inter.id_intervenciones = ".$idIntervencion;

if ($rs = $con->query($sql)) {
    if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
        $numEvoluciones=$filas[0];
    }
}


$data = array();

if(isset($_FILES['archivo'])){
    //MODIFICAR ESTA RUTA SI ES NECESARIO AL SUBIR A AMBIENTE DE PRODUCCIÓN
    $dir_subida = dirname(__DIR__)."\archivos\\";
    $fichero_subido = $dir_subida .'interEvolucion'.$idIntervencion.'_'.(intval($numEvoluciones)+1).'_'.basename($_FILES['archivo']['name']);

    //Se asigna la siguiente ruta relativa para almacenar en BD y recuperar desde ella en el front sin problemas (local)
    $img_url_paraBD = "./archivos/".'interEvolucion'.$idIntervencion.'_'.(intval($numEvoluciones)+1).'_'.basename($_FILES['archivo']['name']);

    if (move_uploaded_file($_FILES['archivo']['tmp_name'], $fichero_subido)) {
        $sql = "INSERT INTO evolucion_estado_comportamientos VALUES (nextval('sec_evolucion_estado'), '$img_url_paraBD', $idIntervencion, CURRENT_DATE);";
        
        if ($rs = $con->query($sql)) {
           
        }else {
            $data['error'] ="Ocurrió un error al subir los archivos";
        }
    } else {
        $data['error'] = "Ocurrió un error al subir los archivos";
    }

    
}


echo json_encode($data);