<?php

include 'conexion.php';

if (isset($_POST["accion"])) {

    if ($_POST["accion"] == "cargarIdEjecucion") {
        cargarIdEjecucion($_POST["idPlaneacion"]);
    }
    if ($_POST["accion"] == "cargarAsistenciaRegistrada") {
        cargarAsistenciaRegistrada($_POST["idEjecucion"]);
    }
    if ($_POST["accion"] == "guardarAsistente") {
        guardarAsistente($_POST["datos"], $_POST["idEjecucion"]);
    }
    if ($_POST["accion"] == "cargarTipoCedula") {
        cargarTipoCedula();
    }

}

function cargarIdEjecucion($idPlaneacion)
{
    include "conexion.php";
    $data = array('error' => 0, 'mensaje' => '', 'html' => '');
    $sql = "SELECT ejecucion_id_ejecucion FROM ejecuciones_por_planeacion WHERE id_planeaciones_por_intervencion = (SELECT id_planeaciones_por_intervencion FROM planeaciones_por_intervencion WHERE planeacion_id_planeacion = $idPlaneacion)";

    $array = array();
    if ($rs = $con->query($sql)) {
        if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
            $data['html']=$filas[0]['ejecucion_id_ejecucion'];
        }
    } else {
        print_r($conexion->errorInfo());
        $data['mensaje'] = "No se realizo la consulta";
        $data['error'] = 1;
    }

    echo json_encode($data);
}

function cargarAsistenciaRegistrada($idEjecucion){
    include "conexion.php";
    $data = array('error' => 0, 'mensaje' => '', 'html' => '');
    $sql = "SELECT * FROM asistentes WHERE id_asistente IN (
        SELECT id_asistente FROM ejecucion_asistentes WHERE id_ejecucion = $idEjecucion
    )";

    $array = array();
    if ($rs = $con->query($sql)) {
        if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
            $data['html']=$filas;
        }
    } else {
        print_r($conexion->errorInfo());
        $data['mensaje'] = "No se realizo la consulta";
        $data['error'] = 1;
    }

    echo json_encode($data);
}



function guardarAsistente($datos, $idEjecucion)
{
    include 'conexion.php';
    $data = array('error' => 0, 'mensaje' => '', 'html' => '');

    if ($con) {

        $sql = "INSERT INTO public.asistentes(id_asistente, tipo_documento_id_tipo_documento, numerodocumento, nombres, apellidos, genero, cuentachec, telefonofijo, celular, direccion, correoelectronico, rol, fecha_nacimiento, manejodatos, sesionesformacion) VALUES (nextval('sec_asistentes'), " . $datos['tipo_documento'] . ", '" . $datos['numero_documento'] . "', '" . $datos['nombres'] . "', '" . $datos['apellidos'] . "', '" . $datos['genero'] . "', '" . $datos['cuenta_CHEC'] . "', '" . $datos['telefono'] . "', '" . $datos['movil'] . "', '" . $datos['direccion'] . "', '" . $datos['correo_electronico'] . "', '" . $datos['rol'] . "', '" . $datos['edad'] . "', " . $datos['manejo_datos'] . ", " . $datos['sesiones'] . ");";
        if ($rs = $con->query($sql)) {
            $sql = "SELECT MAX(id_asistente) as id_asistente FROM asistentes";
            if ($rs = $con->query($sql)) {
                if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
                    $id_asistente = $filas[0]['id_asistente'];
                    $sql = "INSERT INTO public.ejecucion_asistentes(
                                    id_ejecucion_asistentes, id_ejecucion, id_asistente)
                                    VALUES (nextval('sec_ejecucion_asistentes'), " . $idEjecucion . ", " . $id_asistente . ");";
                    if ($rs = $con->query($sql)) {
                        $data['mensajeAsist'] = "Guardado Exitosamente";
                    } else {
                        print_r($con->errorInfo());
                        $data['mensaje'] = "No se inserto la ejecuciones por planeacion";
                        $data['error'] = 1;
                    }
                }
            }
        }else{
            print_r($con->errorInfo());
        $data['mensaje'] = "No se realizo el registro de asistente";
        $data['error'] = 1;
        }
    


        echo json_encode($data);
    }
}

function cargarTipoCedula(){
    include 'conexion.php';
    $sql = "SELECT id_tipo_documento, tipo_documento FROM public.tipo_documento;";
    if ($rs = $con->query($sql)) {
        if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
            $data['response'] = $filas;
        }
    } else {
        print_r($con->errorInfo());
        $data['mensaje'] = "No se realizo la consulta de los tipos de documento";
        $data['error'] = 1;
    }

    echo json_encode($data);
}

