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
    if ($_POST["accion"] == "eliminarAsistente"){
        eliminarAsistente($_POST["id_asistente"]);
    }
    if ($_POST["accion"] == "eliminarIntervencion"){
        eliminarIntervencion($_POST["idIntervencion"]);
    }
    if ($_POST["accion"] == "cargarAsistenteFormulario"){
        cargarAsistenteFormulario($_POST["idAsistente"]);
    }
    if ($_POST["accion"] == "actualizarAsistente"){
      actualizarAsistente($_POST["datos"]);
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

/* Cargar los datos de un usuario ya creado en el formulario para editar */
function cargarAsistenteFormulario($idAsistente){
    include "conexion.php";
    $data = array('error' => 0, 'mensaje' => '', 'html' => '');
    $sql = "SELECT * FROM asistentes WHERE id_asistente = $idAsistente";

    $array = array();
    if ($rs = $con->query($sql)) {
        if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
            $data['html']=$filas;
        }
    } else {
        print_r($con->errorInfo());
        $data['mensaje'] = "No se realizo la consulta";
        $data['error'] = 1;
    }
    echo json_encode($data);
}

function eliminarAsistente($id_asistente){
    //Establecer una segunda conexion por pg_connect para la ejecucion de multiples SQL's simultaneamente
    include "conexion.php";
    $con2 = pg_connect("host=$host port=5432 dbname=$database user=$uid password=$pwd");
    $data = array('error' => 0, 'mensaje' => '', 'html' => '');
    if($con2){
        $sql = "DELETE FROM ejecucion_asistentes WHERE id_asistente = $id_asistente;";
        $sql .= "DELETE FROM asistentes WHERE id_asistente = $id_asistente;";
        if($rs = pg_query($con2, $sql) == TRUE){
            $data['mensaje'] = "El asistente se ha eliminado con exito";
        }else{
            print_r($con2->errorInfo());
            $data['mensaje'] = "No se pudo eliminar asistente";
            $data['error'] = 1;
        }
    }
    echo json_encode($data);
    pg_close($con2);
}

function guardarAsistente($datos, $idEjecucion)
{
    include 'conexion.php';
    $data = array('error' => 0, 'mensaje' => '', 'html' => '');

    if ($con) {

        $sql = "INSERT INTO public.asistentes(id_asistente, tipo_documento_id_tipo_documento, numerodocumento, nombres, apellidos, 
        genero, cuentachec, telefonofijo, celular, direccion, correoelectronico, rol, fecha_nacimiento, manejodatos, sesionesformacion) 
        VALUES (nextval('sec_asistentes'), " . $datos['tipo_documento'] . ", '" . $datos['numero_documento'] . "', 
        '" . $datos['nombres'] . "', '" . $datos['apellidos'] . "', '" . $datos['genero'] . "', 
        '" . $datos['cuenta_CHEC'] . "', '" . $datos['telefono'] . "', '" . $datos['movil'] . "', 
        '" . $datos['direccion'] . "', '" . $datos['correo_electronico'] . "', '" . $datos['rol'] . "', 
        '" . $datos['edad'] . "', " . $datos['manejo_datos'] . ", " . $datos['sesiones'] . ");";
        
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


function actualizarAsistente($datos)
{
    include 'conexion.php';
    $data = array('error' => 0, 'mensaje' => '', 'html' => '');

    if ($con) {

        $sql = "UPDATE asistentes
        SET tipo_documento_id_tipo_documento = ".$datos['tipo_documento'].", numerodocumento = '" . $datos['numero_documento'] . "', 
        nombres = '".$datos['nombres']."', apellidos = '".$datos['apellidos']."', genero = '".$datos['genero']."', 
        cuentachec = ".$datos['cuenta_CHEC'].", telefonofijo = '".$datos['telefono']."', celular = '". $datos['movil']."', 
        direccion = '".$datos['direccion'] . "', correoelectronico = '".$datos['correo_electronico']."', rol = '".$datos['rol']."', 
        fecha_nacimiento = '".$datos['edad']."', manejodatos = ".$datos['manejo_datos'].", sesionesformacion = ".$datos['sesiones']."
        WHERE id_asistente = ".$datos['id_asistente'].";";

        if ($rs = $con->query($sql)) {
          $data['mensaje'] = "Actualizado Existosamente";
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
    pg_close($con2);
}

function eliminarIntervencion($idIntervencion){
	include "conexion.php";
	$con2 = pg_connect("host=$host port=5432 dbname=$database user=$uid password=$pwd");
	$data = array('error' => 0, 'mensaje' => '', 'html' => '');
	if($con2){
		$sqlejec = "SELECT epa.ejecucion_id_ejecucion
					FROM ejecuciones_por_planeacion epa
					JOIN planeaciones_por_intervencion ppi ON ppi.id_planeaciones_por_intervencion = epa.id_planeaciones_por_intervencion
					JOIN planeacion pln ON ppi.planeacion_id_planeacion = pln.id_planeacion
					WHERE pln.id_planeacion IN(SELECT planeacion_id_planeacion FROM planeaciones_por_intervencion WHERE intervenciones_id_intervenciones = $idIntervencion)";
		$sqlplan1 = "SELECT planeacion_id_planeacion FROM planeaciones_por_intervencion WHERE intervenciones_id_intervenciones = $idIntervencion";
		$sqlplan2 = "SELECT planeacion_id_planeacion FROM planeaciones_por_intervencion";

		$sql = " DELETE FROM ejecucion_asistentes WHERE id_ejecucion IN ($sqlejec);";
		$sql .= " DELETE FROM asistentes WHERE id_asistente NOT IN (SELECT id_asistente FROM ejecucion_asistentes);";
		$sql .= " DELETE FROM detallenivelcumplimiento_por_ejecucion WHERE ejecucion_id_ejecucion IN ($sqlejec);";
		$sql .= " DELETE FROM ejecucion_adjuntos WHERE id_ejecucion IN ($sqlejec);";
		$sql .= " DELETE FROM adjuntos WHERE id_adjunto NOT IN (SELECT id_adjunto FROM ejecucion_adjuntos);";
		$sql .= " DELETE FROM ejecuciones_por_planeacion WHERE ejecucion_id_ejecucion IN ($sqlejec);";
		$sql .= " DELETE FROM ejecucion WHERE id_ejecucion IN ($sqlejec);";
		$sql .= " DELETE FROM indicadores_por_planeacion WHERE planeacion_id_planeacion IN ($sqlplan1);";
		$sql .= " DELETE FROM tactico_por_planeacion WHERE planeacion_id_planeacion IN ($sqlplan1);";
		$sql .= " DELETE FROM subtemas_por_planeacion WHERE planeacion_id_planeacion IN ($sqlplan1);";
		$sql .= " DELETE FROM registro_ubicacion WHERE id_planeacion IN ($sqlplan1);";
		$sql .= " DELETE FROM planeaciones_por_intervencion ppi WHERE planeacion_id_planeacion IN ($sqlplan1);";
		$sql .= " DELETE FROM planeacion WHERE id_planeacion NOT IN ($sqlplan2);";
		$sql .= " DELETE FROM evolucion_estado_comportamientos WHERE intervenciones_id_intervenciones = $idIntervencion;";
        $sql .= " DELETE FROM intervenciones WHERE id_intervenciones = $idIntervencion;";
        
		if($rs = pg_query($con2, $sql) == TRUE){
			$data['mensaje'] = 'Se ha eliminado con exito la intervención';
		}else{
            print_r(pg_result_error($rs));
            $data['mensaje'] = 'No se pudo eliminar asistente';
            $data['error'] = 1;
		}
        echo json_encode($data);
        pg_close($con2);
	}
}