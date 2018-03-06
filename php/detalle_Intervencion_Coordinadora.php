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
	if($_POST["accion"]=="checkPlaneacionesEjecutadas")
	{
		checkPlaneacionesEjecutadas();
	}
	
	
}

function cargarDetalleIntervencion($idIntervencion){
	include "conexion.php";
	$data = array('error'=>0,'mensaje'=>'','html'=>array()); 
	$sql = "SELECT mun.municipio, compor.comportamientos, compe.competencia, ind.indicador, tipo.tipo_intervencion, compe.id_competencia, compor.id_comportamientos, inter.img_url, inter.fecha
			FROM intervenciones inter
			JOIN tipo_intervencion tipo ON tipo.id_tipo_intervencion = inter.tipo_intervencion_id_tipo_intervencion
			JOIN indicadores_chec_por_intervenciones indxinter ON indxinter.intervenciones_id_intervenciones = inter.id_intervenciones
			JOIN indicadores_chec ind ON ind.id_indicadores_chec = indxinter.indicadores_chec_id_indicadores_chec
			JOIN comportamientos compor ON compor.id_comportamientos = ind.comportamientos_id_comportamientos
			JOIN competencias_por_comportamiento compexcompor ON compexcompor.comportamientos_id_comportamientos = compor.id_comportamientos
			JOIN competencias compe ON compe.id_competencia = compexcompor.competencias_id_competencia
			LEFT OUTER JOIN barrios bar ON bar.id_barrio = inter.id_barrio
			LEFT OUTER JOIN comunas com ON com.id_comuna = bar.id_comuna
			LEFT OUTER JOIN veredas ver ON ver.id_veredas = inter.id_vereda
			JOIN municipios mun ON mun.id_municipio = com.id_municipio OR mun.id_municipio = ver.id_municipio
			WHERE inter.id_intervenciones = ".$idIntervencion; 
			  
			 
			$string_indicadores="";
			if ($rs = $con->query($sql)) {
				if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
					for ($i=0;$i<count($filas);$i++){
						$data['html']['municipio']= $filas[0]["municipio"];
						$data['html']['comportamientos']= $filas[0]['comportamientos'];
						$data['html']['competencia']= $filas[0]['competencia'];
						$data['html']['tipo_intervencion']= $filas[0]['tipo_intervencion'];
						$data['html']['img_url'] = $filas[0]['img_url'];
						$data['html']['fecha'] = $filas[0]['fecha'];
						$data['html']['id_competencia'] = $filas[0]['id_competencia'];
						$data['html']['id_comportamientos'] = $filas[0]['id_comportamientos'];
						
						$string_indicadores.= '<div class="row"><div class="col-md-5">
						<label class="mr-sm-2" id="lblIndicadorChec1"> <li>'.$filas[$i]['indicador'].'</li></label></div></div>';
						
					}
					$data['html']['indicador']= $string_indicadores;

					//TODO: Aqui hacer la consulta para obtener los registros de evolucion de estado por intervencion. Ejecutarla
					// y luego añadirla al objeto html de respuesta
					$sql = "SELECT evo.img_url, evo.fecha
					FROM intervenciones inter
					LEFT OUTER JOIN evolucion_estado_comportamientos evo ON evo.intervenciones_id_intervenciones = inter.id_intervenciones
					WHERE inter.id_intervenciones = ".$idIntervencion;

					if ($rs = $con->query($sql)){
						if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
							//si hay imagenes de evolucion
							if(count($filas)>0){
								$data['html']['evolucion'] = array();
								for ($i=0;$i<count($filas);$i++){
									$data['html']['evolucion'][$i] = array('img_url'=>$filas[$i]['img_url'], 'fecha'=>$filas[$i]['fecha']);
								}
							}else{ //no hay imagenes de evolucion
								$data['html']['evolucion'] = null;
							}
						}
					}else{
						$data['mensaje']="No se realizo la consulta";
						$data['error']=1;
					}

				}
			}
			else
			{
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
	$sql = "SELECT pl.id_planeacion, ep.etapaproceso, est.nombreestrategia, tact.nombretactico, tem.temas, pl.fecha
			FROM planeacion pl
			JOIN etapaproceso ep ON pl.etapaproceso_id_etapaproceso = ep.id_etapaproceso
			JOIN tactico_por_planeacion tactxpl ON pl.id_planeacion = tactxpl.planeacion_id_planeacion
			JOIN tactico tact ON tact.id_tactico = tactxpl.tactico_id_tactico
			JOIN estrategias est ON tact.id_estrategia = est.id_estrategia
			LEFT OUTER JOIN subtemas_por_planeacion subxpl ON subxpl.planeacion_id_planeacion = pl.id_planeacion
			LEFT OUTER JOIN subtemas sub ON subxpl.subtemas_id_subtema = sub.id_subtema
			LEFT OUTER JOIN temas tem ON sub.id_temas = tem.id_temas
			JOIN planeaciones_por_intervencion plxint ON plxint.planeacion_id_planeacion = pl.id_planeacion
			WHERE plxint.intervenciones_id_intervenciones = ".$idIntrevencion."
			GROUP BY pl.id_planeacion, etapaproceso, nombreestrategia, nombretactico, temas, fecha
			ORDER BY pl.id_planeacion"; //consulta
			

	  		
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

function checkPlaneacionesEjecutadas(){
	include "conexion.php";
	$resultado = array();
	$sql = "SELECT pi.planeacion_id_planeacion FROM planeaciones_por_intervencion pi
			JOIN ejecuciones_por_planeacion ep ON ep.id_planeaciones_por_intervencion = pi.id_planeaciones_por_intervencion"; //consulta
			
			$array=array();
			if ($rs = $con->query($sql)) {
				if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
					foreach ($filas as $fila) {
						array_push($resultado, $fila["planeacion_id_planeacion"]);
					}
				}
			}
			else
			{
				$resultado =-1;
			}
			
		  echo json_encode($resultado);
}

?>