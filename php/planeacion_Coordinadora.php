<?php
 include('conexion.php'); 

if(isset($_POST["accion"]))
{		
 	
	if($_POST["accion"]=="cargarJornadas")
	{
		cargarJornadas();
	}
	if($_POST["accion"]=="cargarPoblacion")
	{
		cargarPoblacion();
	}
	if($_POST["accion"]=="cargarEstrategias")
	{
		cargarEstrategias();
	}
	if($_POST["accion"]=="cargarTacticos")
	{
		cargarTacticos();
	}
	
}

function cargarJornadas(){
	include "conexion.php";
	$data = array('error'=>0,'mensaje'=>'','html'=>''); 
	$sql = "SELECT Id_Jornada, Jornada FROM Jornada";
	  		
			$array=array();
			if ($rs = $con->query($sql)) {
				if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
					$data['html']= '<option value="0">Selecciona una opción</option>';
					foreach ($filas as $fila) {
						// $array[] = $fila;
					
					
						
						$data['html'].= '<option value="'.$filas[0]['Id_Jornada'].'">'.$filas[0]['Jornada'].'</option>';
					}
				}
			}
			else
			{
				// print_r($conexion->getPDO()->errorInfo());
				$data['mensaje']="No se realizo la consulta";
				$data['error']=1;
			}
			// $arr = array();
		  echo json_encode($data);
}

function cargarPoblacion(){
	include "conexion.php";
	$data = array('error'=>0,'mensaje'=>'','html'=>''); 
	$sql = "SELECT Id_TipoPoblacion, TipoPoblacion FROM tipopoblacion";
	  		
			$array=array();
			if ($rs = $con->query($sql)) {
				if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
					$data['html']= '<option value="0">Selecciona una opción</option>';
					foreach ($filas as $fila) {
						// $array[] = $fila;
					
					
						
						$data['html'].= '<option value="'.$filas[0]['Id_TipoPoblacion'].'">'.$filas[0]['TipoPoblacion'].'</option>';
					}
				}
			}
			else
			{
				// print_r($conexion->getPDO()->errorInfo());
				$data['mensaje']="No se realizo la consulta";
				$data['error']=1;
			}
			// $arr = array();
		  echo json_encode($data);
}

function cargarEstrategias(){
	include "conexion.php";
	$data = array('error'=>0,'mensaje'=>'','html'=>''); 
	$sql = "SELECT Id_Estrategia, NombreEstrategia FROM Estrategias";
	  		
			$array=array();
			if ($rs = $con->query($sql)) {
				if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
					$data['html']= '<option value="0">Selecciona una opción</option>';
					foreach ($filas as $fila) {
						// $array[] = $fila;
					
					
						
						$data['html'].= '<option value="'.$filas[0]['Id_Estrategia'].'">'.$filas[0]['NombreEstrategia'].'</option>';
					}
				}
			}
			else
			{
				// print_r($conexion->getPDO()->errorInfo());
				$data['mensaje']="No se realizo la consulta";
				$data['error']=1;
			}
			// $arr = array();
		  echo json_encode($data);
}

function cargarTacticos(){
	include "conexion.php";
	$data = array('error'=>0,'mensaje'=>'','html'=>''); 
	$sql = "SELECT Id_Tactico, NombreTactico 
			FROM Tactico
			WHERE Id_Estrategia = ".$_POST["idEstrategia"]."";
	  		
			$array=array();
			if ($rs = $con->query($sql)) {
				if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
					$data['html']= '<option value="0">Selecciona una opción</option>';
					foreach ($filas as $fila) {
						// $array[] = $fila;
					
					
						
						$data['html'].= '<option value="'.$filas[0]['Id_Tactico'].'">'.$filas[0]['NombreTactico'].'</option>';
					}
				}
			}
			else
			{
				// print_r($conexion->getPDO()->errorInfo());
				$data['mensaje']="No se realizo la consulta";
				$data['error']=1;
			}
			// $arr = array();
		  echo json_encode($data);
}


?>