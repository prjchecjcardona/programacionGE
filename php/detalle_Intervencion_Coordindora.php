<?php
 include('conexion.php'); 

if(isset($_POST["accion"]))
{		
 	
	if($_POST["accion"]=="cargarDetalleIntervencion")
	{
		cargarDetalleIntervencion($_POST['idIntervencion']);
	}
	if($_POST["accion"]=="cargarPlaneacionesPorIntrevencion")
	{
		cargarPlaneacionesPorIntrevencion($_POST['idIntervencion']);
	}
	
}

function cargarDetalleIntervencion($idIntervencion){
	include "conexion.php";
	$data = array('error'=>0,'mensaje'=>'','html'=>''); 
	//$idIntrevencion = $_POST['idIntervencion']; //para la consulta
	$sql = "SELECT mun.municipio, ent.nombreentidad, compor.comportamientos, compe.competencia, ind.indicador, tipo.tipo_intervencion,compor.id_comportamientos,compe.id_competencia,ent.id_entidad
			FROM intervenciones inter
			JOIN tipo_intervencion tipo ON tipo.id_tipo_intervencion = inter.tipo_intervencion_id_tipo_intervencion
			JOIN indicadores_chec_por_intervenciones indxinter ON indxinter.intervenciones_id_intervenciones = inter.id_intervenciones
			JOIN indicadores_chec ind ON ind.id_indicadores_chec = indxinter.indicadores_chec_id_indicadores_chec
			JOIN comportamientos compor ON compor.id_comportamientos = ind.comportamientos_id_comportamientos
			JOIN competencias_por_comportamiento compexcompor ON compexcompor.comportamientos_id_comportamientos = compor.id_comportamientos
			JOIN competencias compe ON compe.id_competencia = compexcompor.competencias_id_competencia
			JOIN entidades ent ON ent.id_entidad = inter.entidades_id_entidad
			LEFT OUTER JOIN barrios bar ON bar.id_barrio = ent.id_barrio
			LEFT OUTER JOIN comunas com ON com.id_comuna = bar.id_comuna
			LEFT OUTER JOIN veredas ver ON ver.id_veredas = ent.veredas_id_veredas
			JOIN municipios mun ON mun.id_municipio = com.id_municipio OR mun.id_municipio = ver.id_municipio
			WHERE inter.id_intervenciones = ".$idIntervencion.""; 
	  		
			$array="";
			if ($rs = $con->query($sql)) {
				if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
					for ($i=0;$i<count($filas);$i++){
						$data['html']['municipio']= $filas[0]['municipio'];
						$data['html']['nombreentidad']= $filas[0]['nombreentidad'];
						$data['html']['comportamientos']= $filas[0]['comportamientos'];
						$data['html']['competencia']= $filas[0]['competencia'];
						$data['html']['id_comportamientos']= $filas[0]['id_comportamientos'];
						$data['html']['id_competencia']= $filas[0]['id_competencia'];
						$data['html']['id_entidad']= $filas[0]['id_entidad'];
						$data['html']['tipo_intervencion']= $filas[0]['tipo_intervencion'];
						
						// $array.= '<div class="row"><div class="col-md-5">
						// <li><label class="mr-sm-2" id="lblIndicadorChec1">'.$filas[$i]['indicador'].'</label></li></div></div>';
						$array.= '<div class="row"><div class="col-md-5">
						<label class="mr-sm-2" id="lblIndicadorChec1"><li>'.$filas[$i]['indicador'].'</li></label></div></div>';
						
					}
					$data['html']['indicador']= $array;
					
					
				}
			}
			else
			{
				// print_r($con->getPDO()->errorInfo());
				$data['mensaje']="No se realizo la consulta";
				$data['error']=1;
			}
			//print_r($data);
		  echo json_encode($data);
}

function cargarPlaneacionesPorIntrevencion($idIntrevencion){
	include "conexion.php";
	$resultado = array();
    $registro = array();
	$data = array('error'=>0,'mensaje'=>'','html'=>''); 
	//$idIntrevencion = $_POST['idIntervencion']; //para la consulta
	$sql = "SELECT pl.id_planeacion, ep.etapaproceso, est.nombreestrategia, tact.nombretactico, tem.temas, pl.fecha
			FROM planeacion pl
			JOIN etapaproceso ep ON pl.etapaproceso_id_etapaproceso = ep.id_etapaproceso
			JOIN tactico_por_planeacion tactxpl ON pl.id_planeacion = tactxpl.planeacion_id_planeacion
			JOIN tactico tact ON tact.id_tactico = tactxpl.tactico_id_tactico
			JOIN estrategias est ON tact.id_estrategia = est.id_estrategia
			JOIN subtemas_por_planeacion subxpl ON subxpl.planeacion_id_planeacion = pl.id_planeacion
			JOIN subtemas sub ON subxpl.subtemas_id_subtema = sub.id_subtema
			JOIN temas tem ON sub.id_temas = tem.id_temas
			JOIN planeaciones_por_intervencion plxint ON plxint.planeacion_id_planeacion = pl.id_planeacion
			WHERE plxint.intervenciones_id_intervenciones = ".$idIntrevencion."
			GROUP BY pl.id_planeacion, etapaproceso, nombreestrategia, nombretactico, temas, fecha"; //consulta
			
		// $sql = "SELECT pl.id_planeacion, ep.etapaproceso,  tem.temas, pl.fecha
			// FROM planeacion pl
			// JOIN etapaproceso ep ON pl.etapaproceso_id_etapaproceso = ep.id_etapaproceso
			
			
			
			// JOIN subtemas_por_planeacion subxpl ON subxpl.planeacion_id_planeacion = pl.id_planeacion
			// JOIN subtemas sub ON subxpl.subtemas_id_subtema = sub.id_subtema
			// JOIN temas tem ON sub.id_temas = tem.id_temas
			// JOIN planeaciones_por_intervencion plxint ON plxint.planeacion_id_planeacion = pl.id_planeacion
			// WHERE plxint.intervenciones_id_intervenciones = 1
			// GROUP BY pl.id_planeacion, etapaproceso, temas, fecha";	
	  		
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