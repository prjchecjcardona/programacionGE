<?php
 include('conexion.php'); 

if(isset($_POST["accion"]))
{		
 	
	if($_POST["accion"]=="cargarDetalleIntervencion")
	{
		cargarDetalleIntervencion();
	}
	if($_POST["accion"]=="cargarPlaneacionesPorIntrevencion")
	{
		cargarPlaneacionesPorIntrevencion();
	}
	
}

function cargarDetalleIntervencion(){
	include "conexion.php";
	$data = array('error'=>0,'mensaje'=>'','html'=>''); 
	$idIntrevencion = $_POST['idIntervencion']; //para la consulta
	$sql = ""; //consulta
	  		
			$array=array();
			if ($rs = $con->query($sql)) {
				if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
					foreach ($filas as $fila) {
						$array[] = $fila;
					}
					$data['html']=$array;
					
				}
			}
			else
			{
				// print_r($con->getPDO()->errorInfo());
				$data['mensaje']="No se realizo la consulta";
				$data['error']=1;
			}
			// $arr = array();
		  echo json_encode($data);
}

function cargarPlaneacionesPorIntrevencion(){
	include "conexion.php";
	$resultado = array();
    $registro = array();
	$data = array('error'=>0,'mensaje'=>'','html'=>''); 
	$idIntrevencion = $_POST['idIntervencion']; //para la consulta
	$sql = ""; //consulta
	  		
			$array=array();
			if ($rs = $con->query($sql)) {
				if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
					foreach ($filas as $fila) {
						foreach ($fila as $key => $value) {
							array_push($registro, $value);
						}
						array_push($resultado, $registro);
						$registro = array();
					}
					
				}
			}
			else
			{
				// print_r($con->getPDO()->errorInfo());
				$resultado =0;
			}
			
		  echo json_encode($resultado);
}
?>