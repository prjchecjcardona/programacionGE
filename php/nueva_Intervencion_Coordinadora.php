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
	if($_POST["accion"]=="cargarComportamientos")
	{
		cargarComportamientos();
	}
	if($_POST["accion"]=="cargarIndicadoresChec")
	{
		cargarIndicadoresChec($_POST["idIndicador"]);
	}
	if($_POST["accion"]=="guararIntervencion")
	{
		guararIntervencion($_POST["idZona"],$_POST["idEntidad"],$_POST["idTipoIntervencion"],$_POST["indicadores"], $_POST["nombreEntidad"],$_POST["idBarrio"],$_POST["direccion"],$_POST["telefono"],$_POST["idTipoEntidad"]);
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
				print_r($con->errorInfo());
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
				print_r($con->errorInfo());
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

function guararIntervencion($idZona,$idEntidad,$idTipoIntervencion,$indicadores,$nombreEntidad,$idBarrio,$direccion,$telefono,$idTipoEntidad){

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
			
			$idVereda=0;
			$idBarrio=0;
			//consulta para saber si es barrio o vereda FALTA
			$sql = "SELECT id_barrio
				from barrios
				WHERE id_barrio ='".$idBarrio."'	
				";
			  
			if ($rs = $con->query($sql)) {
				if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
					
					$idBarrio=$filas[0]['id_barrio'];
					
					
					if($idBarrio == ""){ //se consulta si es vereda
						$sql = "SELECT id_veredas
								from veredas 
								WHERE id_veredas ='".$idBarrio."'
								";
							  
							if ($rs = $con->query($sql)) {
								if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
									
									$idVereda=$filas[0]['id_veredas'];
									
								}
							}
							else
							{
								print_r($con->errorInfo());
								$data['mensaje']="No se realizo la consulta de veredas";
								$data['error']=1;
							}
					}
					
				}
			}
			else
			{
				print_r($con->errorInfo());
				$data['mensaje']="No se realizo la consulta de barrios";
				$data['error']=1;
			}
			
			
			//insertar en entidad 
			/*$sql = "INSERT INTO entidades(
					id_entidad, nombreentidad, id_barrio, veredas_id_veredas, direccion, telefono, id_tipoentidad, nodo)
					VALUES ('".$idEntidad."', '".$nombreEntidad."', '".$idBarrio."', '".$idVereda."', '".$direccion."', '".$telefono."', '".$idTipoEntidad."', '');
				";
			  
			if ($rs = $con->query($sql)) {
				
			}
			else
			{
				print_r($con->errorInfo());
				$data['mensaje']="No se realizo el insert de entidades";
				$data['error']=1;
			}
			*/
			
		
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



