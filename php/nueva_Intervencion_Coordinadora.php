<?php
include('conexion.php');


if(isset($_POST["accion"]))
{

	if($_POST["accion"]=="cargarZonasPorId")
	{
		cargarZonasPorId($_POST["idZona"]);
	}
	if($_POST["accion"]=="cargarMunicipiosPorIdZona")
	{
		cargarMunicipiosPorIdZona($_POST["idZona"]);
	}
	if($_POST["accion"]=="cargarComunasPorIdMunicipio")
	{
		cargarComunasPorIdMunicipio($_POST["idMunicipio"]);
	}
	if($_POST["accion"]=="cargarBarriosPorComuna")
	{
		cargarBarriosPorComuna($_POST["idComuna"]);
	}
	if($_POST["accion"]=="cargarEntidadesPorBarrio")
	{
		cargarEntidadesPorBarrio($_POST["idBarrio"]);
	}
	if($_POST["accion"]=="cargarEntidadPorVereda")
	{
		cargarEntidadPorVereda($_POST["idComuna"]);
	}
	if($_POST["accion"]=="cargarTipoIntervencion")
	{
		cargarTipoIntervencion();
	}
	

}

function cargarZonasPorId($idZona){

	include('conexion.php');
	$data = array('error'=>0,'mensaje'=>'','html'=>'');
	
	if( $con )
 	{
 		//Datos de la zona que se selecciono
    	$sql = "SELECT id_zona, zonas
			  FROM zonas
			  WHERE id_zona = ".$idZona."
			  ";
			  
			if ($rs = $con->query($sql)) {
				if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
					// foreach ($filas as $fila) {
						// $array[] = $fila;
					// }
					$data['html']= $filas[0]['zonas'];
					// $data['html']= '<option value="0">Selecciona tu opción</option>';
					// $data['html'].= '<option value="'.$filas[0]['id_zona'].'">'.$filas[0]['zonas'].'</option>';
				}
			}
			else
			{
				print_r($conexion->getPDO()->errorInfo());
				$data['mensaje']="No se realizo la consulta de zonas";
				$data['error']=1;
			}
			
			
		  echo json_encode($data);
	}
}

function cargarMunicipiosPorIdZona($idZona){

	include('conexion.php');
	$data = array('error'=>0,'mensaje'=>'','html'=>'');
	
	if( $con )
 	{
 		//Datos de la zona que se selecciono 
    	$sql = "SELECT id_municipio, municipio
			  FROM municipios
			  WHERE id_zona = ".$idZona."
			  ";
			  
			if ($rs = $con->query($sql)) {
				if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
					
					$data['html']= '<option value="0">Selecciona tu opción</option>';
					// foreach ($filas as $fila) {
						// $array[] = $fila; 
						for ($i=0;$i<count($filas);$i++){
							$data['html'].= '<option value="'.$filas[$i]['id_municipio'].'">'.$filas[$i]['municipio'].'</option>';
					 }
					
					
					
				}
			}
			else
			{
				print_r($conexion->getPDO()->errorInfo());
				$data['mensaje']="No se realizo la consulta de zonas";
				$data['error']=1;
			}
			
			
		  echo json_encode($data);
	}
}

function cargarComunasPorIdMunicipio($idMunicipio){

	include('conexion.php');
	$data = array('error'=>0,'mensaje'=>'','html'=>'');
	
	if( $con )
 	{
 		//Datos de la comuna segun el municipio
    	$sql = "SELECT id_comuna, comuna 
				FROM comunas 
				where id_municipio = ".$idMunicipio."
			  ";
			  
			if ($rs = $con->query($sql)) {
				if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
					
					$data['html']= '<option value="0">Selecciona tu opción</option>';
					// foreach ($filas as $fila) {
						// $array[] = $fila; 
						for ($i=0;$i<count($filas);$i++){
							$data['html'].= '<option value="'.$filas[$i]['id_comuna'].'">'.$filas[$i]['comuna'].'</option>';
					 }
					
					
					
				}
			}
			else
			{
				print_r($conexion->getPDO()->errorInfo());
				$data['mensaje']="No se realizo la consulta de comunas";
				$data['error']=1;
			}
			
			//Datos de la vereda segun el municipio
    	$sql = "SELECT id_veredas, vereda 
				FROM veredas 
				where id_municipio = ".$idMunicipio."
			  ";
			  
			if ($rs = $con->query($sql)) {
				if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
					
					
						for ($i=0;$i<count($filas);$i++){
							$data['html'].= '<option value="'.$filas[$i]['id_veredas'].'">'.$filas[$i]['vereda'].'</option>';
					 }

				}
			}
			else
			{
				print_r($conexion->getPDO()->errorInfo());
				$data['mensaje']="No se realizo la consulta de veredas";
				$data['error']=1;
			}
			
			
			
			
		  echo json_encode($data);
	}
}

function cargarBarriosPorComuna($idComuna){

	include('conexion.php');
	$data = array('error'=>0,'mensaje'=>'','html'=>'');
	
	if( $con )
 	{
 		//Datos de la zona que se selecciono 
    	$sql = "SELECT id_barrio, barrio 
				FROM barrios 
				where id_comuna = '".$idComuna."'
			  ";
			  
			if ($rs = $con->query($sql)) {
				if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
					
					$data['html']= '<option value="0">Selecciona tu opción</option>';
					
						for ($i=0;$i<count($filas);$i++){
							$data['html'].= '<option value="'.$filas[$i]['id_barrio'].'">'.$filas[$i]['barrio'].'</option>';
					 }
					
					
					
				}
			}
			else
			{
				print_r($conexion->getPDO()->errorInfo());
				$data['mensaje']="No se realizo la consulta de barrios";
				$data['error']=1;
			}
			
			
		  echo json_encode($data);
	}
}

function cargarEntidadesPorBarrio($idBarrio){

	include('conexion.php');
	$data = array('error'=>0,'mensaje'=>'','html'=>'', 'tipo'=>'');
	
	if( $con )
 	{
 		//Datos de la zona que se selecciono 
    	$sql = "SELECT e.id_entidad,e.nombreentidad,t.id_tipoentidad,t.tipoentidad
				FROM entidades as e,tipoentidad as t
				WHERE id_barrio= '".$idBarrio."'
				AND e.id_tipoentidad = t.id_tipoentidad
			  ";
			  
			if ($rs = $con->query($sql)) {
				if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
					
					$data['html']= '<option value="0">Selecciona tu opción</option>';
					
						for ($i=0;$i<count($filas);$i++){
							$data['html'].= '<option value="'.$filas[$i]['id_entidad'].'">'.$filas[$i]['nombreentidad'].'</option>';
					 }
					
					$data['tipo']= '<option value="0">Selecciona tu opción</option>';
					
						for ($i=0;$i<count($filas);$i++){
							$data['tipo'].= '<option value="'.$filas[$i]['id_tipoentidad'].'">'.$filas[$i]['tipoentidad'].'</option>';
					 }
					
				}
			}
			else
			{
				print_r($conexion->getPDO()->errorInfo());
				$data['mensaje']="No se realizo la consulta de entidades";
				$data['error']=1;
			}
			
			
		  echo json_encode($data);
	}
}

function cargarEntidadPorVereda($idComuna){

	include('conexion.php');
	$data = array('error'=>0,'mensaje'=>'','html'=>'', 'tipo'=>'');
	
	if( $con )
 	{
 		//Datos de la zona que se selecciono 
    	$sql = "SELECT e.id_entidad,e.nombreentidad,t.id_tipoentidad,t.tipoentidad
				FROM entidades as e,tipoentidad as t
				WHERE veredas_id_veredas= '".$idComuna."'
				AND e.id_tipoentidad = t.id_tipoentidad
			  ";
			  
			if ($rs = $con->query($sql)) {
				if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
					
					$data['html']= '<option value="0">Selecciona tu opción</option>';
					
						for ($i=0;$i<count($filas);$i++){
							$data['html'].= '<option value="'.$filas[$i]['id_entidad'].'">'.$filas[$i]['nombreentidad'].'</option>';
					 }
					 
					 $data['tipo']= '<option value="0">Selecciona tu opción</option>';
					
						for ($i=0;$i<count($filas);$i++){
							$data['tipo'].= '<option value="'.$filas[$i]['id_tipoentidad'].'">'.$filas[$i]['tipoentidad'].'</option>';
					 }
					
					
					
				}
			}
			else
			{
				print_r($conexion->getPDO()->errorInfo());
				$data['mensaje']="No se realizo la consulta de entidades por vereda";
				$data['error']=1;
			}
			
			
		  echo json_encode($data);
	}
}

function cargarTipoIntervencion(){

	include('conexion.php');
	$data = array('error'=>0,'mensaje'=>'','html'=>'');
	
	if( $con )
 	{
 		//Datos de la zona que se selecciono 
    	$sql = "SELECT id_tipo_intervencion,tipo_intervencion
				FROM tipo_intervencion
			  ";
			  
			if ($rs = $con->query($sql)) {
				if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
					
					$data['html']= '<option value="0">Selecciona tu opción</option>';
					
						for ($i=0;$i<count($filas);$i++){
							$data['html'].= '<option value="'.$filas[$i]['id_tipo_intervencion'].'">'.$filas[$i]['tipo_intervencion'].'</option>';
					 }
					 
					
				}
			}
			else
			{
				print_r($conexion->getPDO()->errorInfo());
				$data['mensaje']="No se realizo la consulta de tipo intervencion";
				$data['error']=1;
			}
			
			
		  echo json_encode($data);
	}
}



