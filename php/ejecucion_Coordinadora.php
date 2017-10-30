<?php
 include('conexion.php');

if(isset($_POST["accion"]))
{

	if($_POST["accion"]=="cargarCompetencia")
	{
		cargarCompetencia();
	}
	if($_POST["accion"]=="cargarDatosPlaneacion")
	{
		cargarDatosPlaneacion();
	}
	if($_POST["accion"]=="guardarEjecucion")
	{
		guardarEjecucion($_POST["fecha"],$_POST["hora"],$_POST["asistentes"],$_POST["detalleCumplimiento"],$_POST["nCumplimiento"],$_POST["idPlaneacion"]);
	}

}


function cargarCompetencia(){
	include "conexion.php";
	$data = array('error'=>0,'mensaje'=>'','html'=>'');
	$sql = "SELECT id_nivelcumplimiento, nivel_cumplimiento FROM nivelcumplimiento";

			$array=array();
			if ($rs = $con->query($sql)) {
				if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
					$data['html']= '<option value="0">Selecciona una opción</option>';
					for ($i=0;$i<count($filas);$i++){

					
					
					$data['html'].= '<div class="form-check"><label class="form-check-label grisTexto"><input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios1" value="'.$filas[$i]['id_nivelcumplimiento'].'" > '.$filas[$i]['nivel_cumplimiento'].'</label></div>';
					
						// $data['html'].= '<option value="'.$filas[$i]['id_jornada'].'">'.$filas[$i]['jornada'].'</option>';
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

function cargarDatosPlaneacion(){
	include "conexion.php";
	$data = array('error'=>0,'mensaje'=>'','html'=>'');
	// $sql = "";

			// $array=array();
			// if ($rs = $con->query($sql)) {
				// if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
					
					// $data['html']['fecha']= $filas[0]['fecha'];
					// $data['html']['lugar']= $filas[0]['lugar'];
					// $data['html']['municipio']= $filas[0]['municipio'];
					// $data['html']['comportamiento']= $filas[0]['comportamiento'];
					// $data['html']['competencia']= $filas[0]['competencia'];
					// $data['html']['estrategia']= $filas[0]['estrategia'];
					// $data['html']['tactico']= $filas[0]['tactico'];
					
					// for ($i=0;$i<count($filas);$i++)
					// {				
						// $array.= '<div class="row"><div class="col-md-5">
						// <label class="mr-sm-2" id="lblIndicadorChec1"><li>'.$filas[$i]['indicador'].'</li></label></div></div>';
					// }
				// }
			// }
			// else
			// {
				// print_r($conexion->errorInfo());
				// $data['mensaje']="No se realizo la consulta";
				// $data['error']=1;
			// }
			
		  echo json_encode($data);
}

function guardarEjecucion($fecha,$hora,$asistentes,$detalleCumplimiento,$nCumplimiento,$idPlaneacion){

	include('conexion.php');
	$data = array('error'=>0,'mensaje'=>'','html'=>'');
	
	if( $con )
 	{
 		
			//Insertar en ejecucion
			$sql = "INSERT INTO ejecucion (id_ejecucion, nivelcumplimiento,fecha,horafinalizacion,numeroasistentes,observaciones)
			VALUES (nextval('sec_ejecucion'),'".$nCumplimiento."', '".$fecha."', '".$hora."', '".$asistentes."','');";
			  
				if ($rs = $con->query($sql)) {
					
					 //obtener el ultimo id insertado
					$sql = "SELECT MAX(id_ejecucion) as id_ejecucion FROM ejecucion 
						";
					  
					if ($rs = $con->query($sql)) {
							if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
								
								$id_ejecucion=$filas[0]['id_ejecucion'];
								
								if($id_ejecucion!=""){
								
								// SE INSERTA EN ejecuciones por planeacion								
								
									$sql = "INSERT INTO ejecuciones_por_planeacion (id_ejecuciones_por_planeacion, ejecucion_id_ejecucion,id_planeaciones_por_intervencion)
									VALUES (nextval('sec_planeaciones_por_intervencion'),'".$id_ejecucion."', '".$idPlaneacion."'); 
									  ";
									  
									if ($rs = $con->query($sql)) 
									{
										$data['mensaje']="Guardado Exitosamente";
										$data['idEjecucion']=$id_ejecucion;
										$data['idPlaneacion']=$idPlaneacion;
										
									} //
									else
									{
										print_r($con->errorInfo());
										$data['mensaje']="No se inserto la ejecuciones por planeacion";
										$data['error']=1;
									}
									
									//se inserta en detalle nivel cumplimiento por ejecucion
									for ($i=0;$i<count($detalleCumplimiento);$i++){
											if($detalleCumplimiento[$i] == 1){$nivelCumplimiento="Completa";}
											if($detalleCumplimiento[$i] == 2){$nivelCumplimiento="Parcial";}
											if($detalleCumplimiento[$i] == 3){$nivelCumplimiento="No se cumplio";}

											//Insertar la detallenivelcumplimiento_por_ejecucion  FALTA CREA LA SECENCIA
											$sql = "INSERT INTO detallenivelcumplimiento_por_ejecucion (id_detallenivelcumplimiento_por_ejecucioncl, id_detalle_nivelcumplimiento, ejecucion_id_ejecucion,nivel_cumplimiento)
												VALUES (nextval('sec_detallenivelcumplimiento_por_ejecucion'),'".$detalleCumplimiento[$i]."', '".$id_ejecucion."','".$nivelCumplimiento."'); 
												  ";
												  
													if ($rs = $con->query($sql)) {
														
														 
													}
													else
													{
														print_r($con->errorInfo());
														$data['mensaje']="No se realizo el insert detallenivelcumplimiento_por_ejecucion";
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
					
					//FALTA INSERTAR EN ADJUNTOS, EJECUCION ADJUNTOS
				}
				else
				{
					print_r($con->errorInfo());
					$data['mensaje']="No se realizo el insert ejecucion";
					$data['error']=1;
				}
	
						

			
		  echo json_encode($data);
	}
}

?>
