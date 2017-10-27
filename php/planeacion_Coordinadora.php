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
		guararPlaneacion($_POST['nombreContacto'],$_POST['cargoContacto'],$_POST['telefonoContacto'],$_POST['correoContacto'],$_POST['fecha'],$_POST['lugar'],$_POST['jornada'],$_POST['comunidad'],$_POST['poblacion'],$_POST['observaciones'],$_POST['idIntervencion'],$_POST['idEtapa']);
	}
	if($_POST["accion"]=="cargarEtapas")
	{
		cargarEtapas();
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

function guararPlaneacion($nombreContacto,$cargoContacto,$telefonoContacto,$correoContacto,$fecha,$lugar,$jornada,$comunidad,$poblacion,$observaciones,$idIntervencion,$idEtapa){

	include('conexion.php');
	$data = array('error'=>0,'mensaje'=>'','html'=>'');
	
	if( $con )
 	{
 		
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


?>
