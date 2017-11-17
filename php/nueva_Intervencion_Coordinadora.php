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
		cargarComunasPorIdMunicipio($_POST["idMunicipio"],$_POST["ubicacion"]);
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
		cargarEntidadPorVereda($_POST["idVereda"]);
	}
	if($_POST["accion"]=="cargarTipoIntervencion")
	{
		cargarTipoIntervencion();
	}
	if($_POST["accion"]=="cargarTipoEntidad")
	{
		cargarTipoEntidad();
	}
	if($_POST["accion"]=="cargarComportamientos")
	{
		cargarComportamientos();
	}
	if($_POST["accion"]=="cargarIndicadoresChec")
	{
		cargarIndicadoresChec($_POST["idIndicador"]);
	}
	if($_POST["accion"]=="guardarIntervencion")
	{
		guardarIntervencion($_POST["idZona"],$_POST["idEntidad"],$_POST["idTipoIntervencion"],$_POST["indicadores"]);
	}
	if($_POST["accion"]=="guardarNuevaComuna")
	{
		guardarNuevaComuna($_POST["municipio"],$_POST["comuna"]);
	}
	if($_POST["accion"]=="guardarNuevoBarrio")
	{
		guardarNuevoBarrio($_POST["comuna"], $_POST["barrio"], $_POST["latitud"], $_POST["longitud"]);
	}
	if($_POST["accion"]=="guardarNuevaVereda")
	{
		guardarNuevaVereda($_POST["municipio"], $_POST["vereda"], $_POST["latitud"], $_POST["longitud"]);
	}
	if($_POST["accion"]=="guardarNuevaEntidad")
	{
		guardarNuevaEntidad($_POST["nombreEntidad"], $_POST["direccion"], $_POST["telefono"], $_POST["tipo_entidad"], $_POST["barrio"], $_POST["vereda"], $_POST["nodo"], $_POST["ubicacion"]);
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
					
					$data['html']= $filas[0]['zonas'];
					
				}
			}
			else
			{
				print_r($con->errorInfo());
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
				print_r($con->errorInfo());
				$data['mensaje']="No se realizo la consulta de zonas";
				$data['error']=1;
			}
			
			
		  echo json_encode($data);
	}
}

function cargarComunasPorIdMunicipio($idMunicipio,$ubicacion){

	include('conexion.php');
	$data = array('error'=>0,'mensaje'=>'','html'=>'');
	
	if( $con )
 	{
 		if($ubicacion ==2){
			
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
					print_r($con->errorInfo());
					$data['mensaje']="No se realizo la consulta de comunas";
					$data['error']=1;
				}
		}
		else{
			
			//Datos de la vereda segun el municipio
			$sql = "SELECT id_veredas, vereda 
				FROM veredas 
				where id_municipio = ".$idMunicipio."
			  ";
			  
			if ($rs = $con->query($sql)) {
				if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
						$data['html']= '<option value="0">Selecciona tu opción</option>';
					
						for ($i=0;$i<count($filas);$i++){
							$data['html'].= '<option value="'.$filas[$i]['id_veredas'].'">'.$filas[$i]['vereda'].'</option>';
					 }

				}
			}
			else
			{
				print_r($con->errorInfo());
				$data['mensaje']="No se realizo la consulta de veredas";
				$data['error']=1;
			}
		}
	}//con
			
			
			
		  echo json_encode($data);
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
				print_r($con->errorInfo());
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
				print_r($con->errorInfo());
				$data['mensaje']="No se realizo la consulta de entidades";
				$data['error']=1;
			}
			
			
		  echo json_encode($data);
	}
}

function cargarTipoEntidad(){
	include('conexion.php');
	$data = array('error'=>0,'mensaje'=>'','html'=>'', 'tipo'=>'');
	
	if( $con )
 	{
 		//Datos de la zona que se selecciono 
    	$sql = "SELECT * FROM public.tipoentidad";
			  
			if ($rs = $con->query($sql)) {
				if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {					
					$data['html']= '<option value="0">Selecciona tu opción</option>';
					
						for ($i=0;$i<count($filas);$i++){
							$data['html'].= '<option value="'.$filas[$i]['id_tipoentidad'].'">'.$filas[$i]['tipoentidad'].'</option>';
					 }
				}
			}
			else
			{
				print_r($con->errorInfo());
				$data['mensaje']="No se realizo la consulta de tipo entidades";
				$data['error']=1;
			}
			
			
		  echo json_encode($data);
	}
}

function cargarEntidadPorVereda($idVereda){

	include('conexion.php');
	$data = array('error'=>0,'mensaje'=>'','html'=>'', 'tipo'=>'');
	
	if( $con )
 	{
 		//Datos de la zona que se selecciono 
    	$sql = "SELECT e.id_entidad,e.nombreentidad,t.id_tipoentidad,t.tipoentidad
				FROM entidades as e,tipoentidad as t
				WHERE veredas_id_veredas= '".$idVereda."'
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
				print_r($con->errorInfo());
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
				print_r($con->errorInfo());
				$data['mensaje']="No se realizo la consulta de tipo intervencion";
				$data['error']=1;
			}
			
			
		  echo json_encode($data);
	}
}




function cargarComportamientos(){

	include('conexion.php');
	$data = array('error'=>0,'mensaje'=>'','html'=>'');
	
	if( $con )
 	{
 		//Datos de la zona que se selecciono 
    	$sql = "SELECT id_comportamientos,comportamientos
				FROM comportamientos
			  ";
			  
			if ($rs = $con->query($sql)) {
				if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
					
					$data['html']= '<option value="0">Selecciona tu opción</option>';
					
						for ($i=0;$i<count($filas);$i++){
							$data['html'].= '<option value="'.$filas[$i]['id_comportamientos'].'">'.$filas[$i]['comportamientos'].'</option>';
					 }
					 
					
				}
			}
			else
			{
				print_r($con->errorInfo());
				$data['mensaje']="No se realizo la consulta de comportamientos";
				$data['error']=1;
			}
			
			
		  echo json_encode($data);
	}
}

function cargarIndicadoresChec($idIndicador){

	include('conexion.php');
	$data = array('error'=>0,'mensaje'=>'','html'=>'');
	
	if( $con )
 	{
 		//Datos de la indicador
    	$sql = "SELECT id_indicadores_chec,indicador 
				from indicadores_chec 
				WHERE comportamientos_id_comportamientos = '".$idIndicador."'
			  ";
			  
			if ($rs = $con->query($sql)) {
				if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
					
					// $data['html']= '<option value="0">Selecciona tu opción</option>';
					
						for ($i=0;$i<count($filas);$i++){
							$data['html'].= '<option value="'.$filas[$i]['id_indicadores_chec'].'">'.$filas[$i]['indicador'].'</option>';
					 }
					 
					
				}
			}
			else
			{
				print_r($con->errorInfo());
				$data['mensaje']="No se realizo la consulta de indicadores";
				$data['error']=1;
			}
			
			
		  echo json_encode($data);
	}
}

function guardarIntervencion($idZona,$idEntidad,$idTipoIntervencion,$indicadores){

	include('conexion.php');
	$data = array('error'=>0,'mensaje'=>'','html'=>'');
	
	if( $con )
 	{
 		
		//consultar el id persona
		$sql = "SELECT id_personas_por_zonacol 
				from personas_por_zona 
				WHERE zonas_id_zona = '".$idZona."'
			  ";
			  
			if ($rs = $con->query($sql)) {
				if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
					
					$idPersonaPorZona=$filas[0]['id_personas_por_zonacol'];
					
				}
			}
			else
			{
				print_r($con->errorInfo());
				$data['mensaje']="No se realizo la consulta de id persona por zona";
				$data['error']=1;
			}
			
			//consultar el id operador
			$sql = "SELECT id_operadores
				from operadores 
				";
			  
			if ($rs = $con->query($sql)) {
				if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
					
					$idOperador=$filas[0]['id_operadores'];
					
				}
			}
			else
			{
				print_r($con->errorInfo());
				$data['mensaje']="No se realizo la consulta de operadores";
				$data['error']=1;
			}
			
			
			
		
		//Insertar la intervencion
    	$sql = "INSERT INTO intervenciones (id_intervenciones, entidades_id_entidad, operadores_id_operadores, personas_por_zona_id_personas_por_zonacol, tipo_intervencion_id_tipo_intervencion)
			VALUES (nextval('sec_intervenciones'),'".$idEntidad."', '".$idOperador."', '".$idPersonaPorZona."', '".$idTipoIntervencion."'); 
			  ";
			  
			if ($rs = $con->query($sql)) {
				
					
					 //obtener el ultimo id insertado
					$sql = "SELECT MAX(id_intervenciones) as id_intervenciones FROM intervenciones 
						";
					  
					if ($rs = $con->query($sql)) {
							if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
								
								$idIntervencion=$filas[0]['id_intervenciones'];
								
								if($idIntervencion!=""){
									
								foreach($indicadores as $idIndicador)
								{
									$sql = "INSERT INTO indicadores_chec_por_intervenciones (indicadores_chec_id_indicadores_chec, intervenciones_id_intervenciones)
									VALUES ('".$idIndicador."', '".$idIntervencion."'); 
									  ";
									  
									if ($rs = $con->query($sql)) 
									{
										$data['mensaje']="Guardado Exitosamente";
										$data['idIntervencion']=$idIntervencion;
										
									} //
									else
									{
										print_r($con->errorInfo());
										$data['mensaje']="No se inserto la intervencion correctamente";
										$data['error']=1;
									}
			
								}
							}
						}
						else
						{
							print_r($con->errorInfo());
							$data['mensaje']="No se realizo la consulta de id intervencion";
							$data['error']=1;
						}
					
					}
			}
			else
			{
				print_r($con->errorInfo());
				$data['mensaje']="No se inserto la intervencion";
				$data['error']=1;
			}
			
			
		  echo json_encode($data);
	}
}

function guardarNuevaComuna($municipio, $comuna){
	include('conexion.php');
	$sql = "INSERT INTO public.comunas(
		id_comuna, comuna, id_municipio)
		VALUES (
			(SELECT MAX(id_comuna)+1 FROM comunas), 
			'".$comuna."', 
			".$municipio.");";
  
	if ($rs = $con->query($sql)) {
		$data['mensaje']="Guardado Exitosamente";
	}
	else
	{
		print_r($con->errorInfo());
		$data['mensaje']="No se pudo insertar la comuna";
		$data['error']=1;
	}
	echo json_encode($data);
}

function guardarNuevoBarrio($comuna, $barrio, $latitud, $longitud){
	include('conexion.php');
	$sql = "INSERT INTO public.barrios(
		id_barrio, barrio, id_comuna, lat, long)
		VALUES (
			(SELECT MAX(id_barrio)+1 FROM barrios), 
			'".$barrio."', 
			".$comuna.",
			'".$latitud."',
			'".$longitud."');";
  
	if ($rs = $con->query($sql)) {
		$data['mensaje']="Guardado Exitosamente";
	}
	else
	{
		print_r($con->errorInfo());
		$data['mensaje']="No se pudo insertar el barrio";
		$data['error']=1;
	}
	echo json_encode($data);
}

function guardarNuevaVereda($municipio, $vereda, $latitud, $longitud){
	include('conexion.php');
	$sql = "INSERT INTO public.veredas(
		id_veredas, vereda, id_municipio, lat, long)
		VALUES (
			(SELECT MAX(id_veredas)+1 FROM veredas), 
			'".$vereda."', 
			".$municipio."),
			'".$latitud."',
			'".$longitud."');";
  
	if ($rs = $con->query($sql)) {
		$data['mensaje']="Guardado Exitosamente";
	}
	else
	{
		print_r($con->errorInfo());
		$data['mensaje']="No se pudo insertar la vereda";
		$data['error']=1;
	}
	echo json_encode($data);
}

function guardarNuevaEntidad($nombreEntidad, $direccion, $telefono, $tipo_entidad, $barrio, $vereda, $nodo, $ubicacion){
	include('conexion.php');
	if ($ubicacion==1) { //Entidad rural
		$sql="INSERT INTO public.entidades(
			id_entidad, nombreentidad, id_barrio, veredas_id_veredas, direccion, telefono, id_tipoentidad, nodo)
			VALUES ((SELECT MAX(id_entidad)+1 FROM entidades),
					'".$nombreEntidad."',
					null,
					".$vereda.",
					'".$direccion."',
					'".$telefono."',
					".$tipo_entidad.",
					'".$nodo."');";
	}else{ //Entidad urbana
		$sql="INSERT INTO public.entidades(
			id_entidad, nombreentidad, id_barrio, veredas_id_veredas, direccion, telefono, id_tipoentidad, nodo)
			VALUES ((SELECT MAX(id_entidad)+1 FROM entidades),
					'".$nombreEntidad."',
					".$barrio.",
					null,
					'".$direccion."',
					'".$telefono."',
					".$tipo_entidad.",
					'".$nodo."');";
	}
	if ($rs = $con->query($sql)) {
		$data['mensaje']="Guardado Exitosamente";
	}
	else
	{
		print_r($con->errorInfo());
		$data['mensaje']="No se pudo insertar la entidad";
		$data['error']=1;
	}
	echo json_encode($data);
}