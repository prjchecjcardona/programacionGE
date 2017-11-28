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
		cargarDatosPlaneacion($_POST["idPlaneacion"], $_POST["isEjecutada"]);
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

function cargarDatosPlaneacion($idPlaneacion, $isEjecucion){
	include "conexion.php";
	$data = array('error'=>0,'mensaje'=>'','html'=>array());
	$sql = "
	SELECT pl.fecha, pl.lugarEncuentro, mun.municipio, compor.comportamientos, compe.competencia, est.nombreestrategia, tac.nombretactico, ind.nombreindicador
	FROM planeacion pl
	JOIN planeaciones_por_intervencion plxint ON plxint.planeacion_id_planeacion = pl.id_planeacion
	JOIN intervenciones int ON int.id_intervenciones = plxint.intervenciones_id_intervenciones
	JOIN entidades ent ON ent.id_entidad = int.entidades_id_entidad
	LEFT OUTER JOIN barrios bar ON bar.id_barrio = ent.id_barrio
	LEFT OUTER JOIN comunas com ON com.id_comuna = bar.id_comuna
	LEFT OUTER JOIN veredas ver ON ver.id_veredas = ent.veredas_id_veredas
	JOIN municipios mun ON mun.id_municipio = com.id_municipio OR mun.id_municipio = ver.id_municipio
	JOIN subtemas_por_planeacion subxpl ON subxpl.planeacion_id_planeacion = pl.id_planeacion
	JOIN subtemas sub ON sub.id_subtema = subxpl.subtemas_id_subtema
	JOIN temas tem ON tem.id_temas = sub.id_temas
	JOIN competencias_por_comportamiento compexcompor ON compexcompor.competencias_id_competencia = tem.compe_por_compo_compe_id_compe AND compexcompor.comportamientos_id_comportamientos = tem.compe_por_compo_compo_id_compo
	JOIN comportamientos compor ON compor.id_comportamientos = compexcompor.comportamientos_id_comportamientos
	JOIN competencias compe ON compe.id_competencia = compexcompor.competencias_id_competencia
	JOIN tactico_por_planeacion tacxpl ON tacxpl.planeacion_id_planeacion = pl.id_planeacion
	JOIN tactico tac ON tac. id_tactico = tacxpl.tactico_id_tactico
	JOIN estrategias est ON est.id_estrategia = tac.id_estrategia
	JOIN indicadores_por_planeacion indxpl ON indxpl.planeacion_id_planeacion = pl.id_planeacion
	JOIN indicadores_ge ind ON ind.id_indicador = indxpl.indicadores_id_indicador
	WHERE pl.id_planeacion = $idPlaneacion
	GROUP BY fecha, lugarencuentro, municipio, comportamientos, competencia, nombreestrategia, nombretactico, nombreindicador
	";
	
			$array="";
			if ($rs = $con->query($sql)) {
				if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
					$data['html']['fecha']= $filas[0]['fecha'];
					$data['html']['lugar']= $filas[0]['lugarencuentro'];
					$data['html']['municipio']= $filas[0]['municipio'];
					$data['html']['comportamiento']= $filas[0]['comportamientos'];
					$data['html']['competencia']= $filas[0]['competencia'];
					$data['html']['estrategia']= $filas[0]['nombreestrategia'];
					$data['html']['tactico']= $filas[0]['nombretactico'];
					for ($i=0;$i<count($filas);$i++)
					{				
						$array.= '<div class="row"><div class="col-md-12">
						<label class="mr-sm-2" id="lblIndicadorChec1"><li>'.$filas[$i]['nombreindicador'].'</li></label></div></div>';
					}
					$data['html']['indicador']= $array;
				}
			}
			else
			{
				print_r($conexion->errorInfo());
				$data['mensaje']="No se realizo la consulta";
				$data['error']=1;
			}

			if($isEjecucion){
				
			}
			
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

									//Se obtiene el id_planeaciones_por_intervencion
									$sql = "SELECT id_planeaciones_por_intervencion FROM planeaciones_por_intervencion WHERE planeacion_id_planeacion = $idPlaneacion";
									if($rs = $con->query($sql)){
										if($filas = $rs->fetchAll(PDO::FETCH_ASSOC)){
											$id_planeaciones_por_intervencion = $filas[0]['id_planeaciones_por_intervencion'];
											// SE INSERTA EN ejecuciones por planeacion								
											
												$sql = "INSERT INTO ejecuciones_por_planeacion (id_ejecuciones_por_planeacion, ejecucion_id_ejecucion,id_planeaciones_por_intervencion)
												VALUES (nextval('sec_planeaciones_por_intervencion'),'".$id_ejecucion."', '".$id_planeaciones_por_intervencion."'); 
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
																	$data['mensaje']="exito";
																	$data['error']=0;
																	 
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
