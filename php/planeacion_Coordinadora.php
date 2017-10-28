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
	if($_POST["accion"]=="guararPlaneacion")
	{
		guararPlaneacion($_POST['nombreContacto'],$_POST['cargoContacto'],$_POST['telefonoContacto'],$_POST['correoContacto'],$_POST['fecha'],$_POST['lugar'],$_POST['jornada'],$_POST['comunidad'],$_POST['poblacion'],$_POST['observaciones'],$_POST['idIntervencion'],$_POST['idEtapa'],$_POST['idEntidad']);
	}
	if($_POST["accion"]=="cargarEtapas")
	{
		cargarEtapas();
	}
	if($_POST["accion"]=="guardarGestionRedes")
	{
		guardarGestionRedes($_POST['idPlaneacion'],$_POST['idTema'],$_POST['indicadores']);
	}
	if($_POST["accion"]=="guardarGestionEducativa")
	{
		guardarGestionEducativa($_POST['idPlaneacion'],$_POST['idTema'],$_POST['indicadores'],$_POST['tactico']);
	}
	if($_POST["accion"]=="consultarTemas")
	{
		consultarTemas($_POST['idComportamientos'],$_POST['idCompetencia']);
	}
	if($_POST["accion"]=="consultarIndicadoresGE")
	{
		consultarIndicadoresGE();
	}
	

}

function cargarJornadas(){
	include "conexion.php";
	$data = array('error'=>0,'mensaje'=>'','html'=>'');
	$sql = "SELECT id_jornada, jornada FROM jornada";

			$array=array();
			if ($rs = $con->query($sql)) {
				if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
					$data['html']= '<option value="0">Selecciona una opción</option>';
					for ($i=0;$i<count($filas);$i++){



						$data['html'].= '<option value="'.$filas[$i]['id_jornada'].'">'.$filas[$i]['jornada'].'</option>';
					}
				}
			}
			else
			{
				print_r($conexion->errorInfo());
				$data['mensaje']="No se realizo la consulta";
				$data['error']=1;
			}
			
		  echo json_encode($data);
}

function cargarPoblacion(){
	include "conexion.php";
	$data = array('error'=>0,'mensaje'=>'','html'=>'');
	$sql = "SELECT id_tipoPoblacion, tipoPoblacion FROM tipopoblacion";

			$array=array();
			if ($rs = $con->query($sql)) {
				if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
					$data['html']= '<option value="0">Selecciona una opción</option>';
					for ($i=0;$i<count($filas);$i++){
						// $array[] = $fila;



						$data['html'].= '<option value="'.$filas[$i]['id_tipopoblacion'].'">'.$filas[$i]['tipopoblacion'].'</option>';
					}
				}
			}
			else
			{
				print_r($conexion->errorInfo());
				$data['mensaje']="No se realizo la consulta";
				$data['error']=1;
			}
			// $arr = array();
		  echo json_encode($data);
}

function cargarEstrategias(){
	include "conexion.php";
	$data = array('error'=>0,'mensaje'=>'','html'=>'');
	$sql = "SELECT id_estrategia, nombreestrategia FROM estrategias";

			$array=array();
			if ($rs = $con->query($sql)) {
				if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
					$data['html']= '<option value="0">Selecciona una opción</option>';
					for ($i=0;$i<count($filas);$i++){
						// $array[] = $fila;



						$data['html'].= '<option value="'.$filas[$i]['id_estrategia'].'">'.$filas[$i]['nombreestrategia'].'</option>';
					}
				}
			}
			else
			{
				print_r($conexion->errorInfo());
				$data['mensaje']="No se realizo la consulta";
				$data['error']=1;
			}
			// $arr = array();
		  echo json_encode($data);
}

function cargarTacticos(){
	include "conexion.php";
	$data = array('error'=>0,'mensaje'=>'','html'=>'');
	$sql = "SELECT id_tactico, nombretactico
			FROM tactico
			WHERE id_estrategia = ".$_POST["idEstrategia"]."";

			$array=array();
			if ($rs = $con->query($sql)) {
				if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
					$data['html']= '<option value="0">Selecciona una opción</option>';
					for ($i=0;$i<count($filas);$i++){
						// $array[] = $fila;



						$data['html'].= '<option value="'.$filas[$i]['id_tactico'].'">'.$filas[$i]['nombretactico'].'</option>';
					}
				}
			}
			else
			{
				print_r($conexion->errorInfo());
				$data['mensaje']="No se realizo la consulta";
				$data['error']=1;
			}
			// $arr = array();
		  echo json_encode($data);
}

function cargarEtapas(){
	include "conexion.php";
	$data = array('error'=>0,'mensaje'=>'','html'=>'');
	$sql = "SELECT id_etapaproceso, etapaproceso
			FROM etapaproceso
			";

			$array=array();
			if ($rs = $con->query($sql)) {
				if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
					
					for ($i=0;$i<count($filas);$i++){
						// $array[] = $fila;



						// $data['html'].= '<option value="'.$filas[$i]['id_tactico'].'">'.$filas[$i]['nombretactico'].'</option>';
						$data['html'].= '<button id="btnGestion_'.$filas[$i]['id_etapaproceso'].'" name="button1id" class="btn btn-success" onclick="seleccionarEtapa('.$filas[$i]['id_etapaproceso'].');">'.$filas[$i]['etapaproceso'].'</button>';
					}
				}
			}
			else
			{
				print_r($conexion->errorInfo());
				$data['mensaje']="No se realizo la consulta";
				$data['error']=1;
			}
			
		  echo json_encode($data);
}

function guararPlaneacion($nombreContacto,$cargoContacto,$telefonoContacto,$correoContacto,$fecha,$lugar,$jornada,$comunidad,$poblacion,$observaciones,$idIntervencion,$idEtapa,$idEntidad){

	include('conexion.php');
	$data = array('error'=>0,'mensaje'=>'','html'=>'');
	
	if( $con )
 	{
 		
		//guardarContacto traer el id entidad de la intervencion
						$sql = "INSERT INTO contactos (id_contacto, nombre_contacto, cargo,telefono,corero,confirmado,entidades_id_entidad)
							VALUES (nextval('sec_contactos'),'".$nombreContacto."', '".$cargoContacto."', '".$telefonoContacto."', '".$correoContacto."', '0','".$idEntidad."'); 
							  ";
							  
								if ($rs = $con->query($sql)) {
									
									 
								}
								else
								{
									print_r($con->errorInfo());
									$data['mensaje']="No se realizo el insert de contacto";
									$data['error']=1;
								}
		
		
		//Insertar la planeacion
    	$sql = "INSERT INTO planeacion (id_planeacion, etapa_proceso_id_etapaproceso, fecha, lugarencuentro, jornada, comunidadespecial,id_tipopoblacion,observaciones)
			VALUES (nextval('sec_planeacion'),'".$idEtapa."', '".$fecha."', '".$lugar."', '".$jornada."', '".$comunidad."', '".$poblacion."', '".$observaciones."'); 
			  ";
			  
			if ($rs = $con->query($sql)) {
				
					
					 //obtener el ultimo id insertado
					$sql = "SELECT MAX(id_planeacion) as id_planeacion FROM planeacion 
						";
					  
					if ($rs = $con->query($sql)) {
							if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
								
								$idPlaneacion=$filas[0]['id_planeacion'];
								
								if($idPlaneacion!=""){
								
								// SE INSERTA EN planeaciones_por_intervencion								
								foreach($indicadores as $idIndicador)
								{
									$sql = "INSERT INTO planeaciones_por_intervencion (indicadores_chec_id_indicadores_chec, intervenciones_id_intervenciones)
									VALUES (nextval('sec_planeaciones_por_intervencion'),'".$idPlaneacion."', '".$idIntervencion."'); 
									  ";
									  
									if ($rs = $con->query($sql)) 
									{
										$data['mensaje']="Guardado Exitosamente";
										$data['idIntervencion']=$idIntervencion;
										$data['idPlaneacion']=$idPlaneacion;
										
									} //
									else
									{
										print_r($con->errorInfo());
										$data['mensaje']="No se inserto la planeaciones_por_intervencion";
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


function guardarGestionRedes($idPlaneacion,$idTema,$indicadores){

	include('conexion.php');
	$data = array('error'=>0,'mensaje'=>'','html'=>'');
	
	if( $con )
 	{
 		
		//consultar subtemas por temas
		$sql = "SELECT id_subtema, subtemas
				FROM subtemas
				WHERE id_temas = '".$idTema."'
			";

			$array=array();
			if ($rs = $con->query($sql)) {
				if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
					
					for ($i=0;$i<count($filas);$i++){
						// $array[] = $fila;  

						//Insertar la subtemas_por_planeacion
						$sql = "INSERT INTO subtemas_por_planeacion (id_subtemas_por_planeacion, subtemas_id_subtemas, planeacionIdplaneacion)
							VALUES (nextval('sec_subtemas_por_planeacion'),'".$filas[$i]['id_subtema']."', '".$idPlaneacion."'); 
							  ";
							  
								if ($rs = $con->query($sql)) {
									
									 
								}
								else
								{
									print_r($con->errorInfo());
									$data['mensaje']="No se realizo el insert subtemas_por_planeacion";
									$data['error']=1;
								}
						
					}
				}
			}
			else
			{
				print_r($conexion->errorInfo());
				$data['mensaje']="No se realizo la consulta";
				$data['error']=1;
			}
		
		
		
				//se recorren los indicadores
				foreach($indicadores as $idIndicador)
						{
							//Insertar la indicadores_por_planeacion
							$sql = "INSERT INTO indicadores_por_planeacion (indicadores_id_indicador, planeacion_id_planeacion)
							VALUES ('".$idIndicador."', '".$idPlaneacion."'); 
							  ";
							  
								if ($rs = $con->query($sql)) {
									
									 
								}
								else
								{
									print_r($con->errorInfo());
									$data['mensaje']="No se realizo el insert indicadores_por_planeacion";
									$data['error']=1;
								}
	
						}

			
		  echo json_encode($data);
	}
}

function guardarGestionEducativa($idPlaneacion,$idTema,$indicadores,$tactico){

	include('conexion.php');
	$data = array('error'=>0,'mensaje'=>'','html'=>'');
	
	if( $con )
 	{
 		
		//consultar subtemas por temas
		$sql = "SELECT id_subtema, subtemas
				FROM subtemas
				WHERE id_temas = '".$idTema."'
			";

			$array=array();
			if ($rs = $con->query($sql)) {
				if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
					
					for ($i=0;$i<count($filas);$i++){
						// $array[] = $fila;  

						//Insertar la subtemas_por_planeacion
						$sql = "INSERT INTO subtemas_por_planeacion (id_subtemas_por_planeacion, subtemas_id_subtemas, planeacionIdplaneacion)
							VALUES (nextval('sec_subtemas_por_planeacion'),'".$filas[$i]['id_subtema']."', '".$idPlaneacion."'); 
							  ";
							  
								if ($rs = $con->query($sql)) {
									
									 
								}
								else
								{
									print_r($con->errorInfo());
									$data['mensaje']="No se realizo el insert subtemas_por_planeacion";
									$data['error']=1;
								}
						
					}
				}
			}
			else
			{
				print_r($conexion->errorInfo());
				$data['mensaje']="No se realizo la consulta";
				$data['error']=1;
			}
		
		
		
				//se recorren los indicadores
				foreach($indicadores as $idIndicador)
						{
							//Insertar la indicadores_por_planeacion
							$sql = "INSERT INTO indicadores_por_planeacion (indicadores_id_indicador, planeacion_id_planeacion)
							VALUES ('".$idIndicador."', '".$idPlaneacion."'); 
							  ";
							  
								if ($rs = $con->query($sql)) {
									
									 
								}
								else
								{
									print_r($con->errorInfo());
									$data['mensaje']="No se realizo el insert indicadores_por_planeacion";
									$data['error']=1;
								}
	
						}

		
		
		//Insertar la tactico_por_planeacion
    	$sql = "INSERT INTO tactico_por_planeacion (tactico_id_tactico, planeacion_id_planeacion)
			VALUES ('".$tactico."', '".$idPlaneacion."'); 
			  ";
			  
			if ($rs = $con->query($sql)) {
					
					 
				}
				else
				{
					print_r($con->errorInfo());
					$data['mensaje']="No se realizo el insert tactico_por_planeacion";
					$data['error']=1;
				}
					
			
		  echo json_encode($data);
		
	}
} 

	
function consultarTemas($idComportamientos,$idCompetencia){

	include('conexion.php');
	$data = array('error'=>0,'mensaje'=>'','html'=>'');
	
	if( $con )
 	{
 		//Datos de la indicador
    	$sql = "SELECT id_temas,temas 
				from temas 
				WHERE compe_por_compo_compe_id_compe = '".$idCompetencia."'
				AND compe_por_compo_compo_id_compo = '".$idComportamientos."'
			  ";
			  
			if ($rs = $con->query($sql)) {
				if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
					
					$data['html']= '<option value="0">Selecciona tu opción</option>';
					
						for ($i=0;$i<count($filas);$i++){
							$data['html'].= '<option value="'.$filas[$i]['id_temas'].'">'.$filas[$i]['temas'].'</option>';
					 }
					 
					
				}
			}
			else
			{
				print_r($con->errorInfo());
				$data['mensaje']="No se realizo la consulta de temas";
				$data['error']=1;
			}
			
			
		  echo json_encode($data);
	}

}

function consultarIndicadoresGE(){

	include('conexion.php');
	$data = array('error'=>0,'mensaje'=>'','html'=>'');
	
	if( $con )
 	{
 		//Datos de la indicador
    	$sql = "SELECT id_indicador,nombreindicador 
				from indicadores_ge
			  ";
			  
			if ($rs = $con->query($sql)) {
				if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
					
					// $data['html']= '<option value="0">Selecciona tu opción</option>';
					$data['html']="";
						for ($i=0;$i<count($filas);$i++){
							// $data['html'].= '<option value="'.$filas[$i]['id_temas'].'">'.$filas[$i]['temas'].'</option>';
							$data['html'].= '<div class="checkbox">
					<label class="grisTexto"><input type="checkbox" value="'.$filas[$i]['id_indicador'].'"> '.$filas[$i]['nombreindicador'].'</label>
				  </div>';
					 }
					 
					
				}
			}
			else
			{
				print_r($con->errorInfo());
				$data['mensaje']="No se realizo la consulta de temas";
				$data['error']=1;
			}
			
			
		  echo json_encode($data);
	}

}


?>
