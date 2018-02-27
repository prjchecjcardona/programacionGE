<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

 include('conexion.php'); 

if(isset($_POST["accion"]))
{		
 	
	if($_POST["accion"]=="traerNombreGestora")
	{
		traerNombreGestora();
	}
	
}

function traerNombreGestora(){
	include "conexion.php";
	$data = array('error'=>0,'mensaje'=>'','html'=>''); 
	$sql = "SELECT per.numeroidentificacion, per.nombres, per.apellidos, pz.zonas_id_zona FROM personas as per
	JOIN personas_por_zona as pz ON pz.personas_numeroidentificacion = per.numeroidentificacion
	where id_cargo= '4'";
	  		// $result = $con->query($sql);
			$array=array();
			if ($rs = $con->query($sql)) {
				if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
					// foreach ($filas as $fila) {
						// $array[] = $fila;
					// }
					
					$data['html']= '<option value="0">Selecciona tu opción</option>';
					foreach ($filas as $key => $value) {
						$data['html'].= '<option id_zona="'.$value['zonas_id_zona'].'" value="'.$value['numeroidentificacion'].'">'.$value['nombres'].' '.$value['apellidos'].'</option>';
					}
				}
			}
			else
			{
				print_r($con->getPDO()->errorInfo());
				$data['mensaje']="No se realizo la consulta";
				$data['error']=1;
			}
			// $arr = array();
		  echo json_encode($data);
}
?>