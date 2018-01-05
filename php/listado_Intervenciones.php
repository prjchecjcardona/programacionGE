<?php
ini_set('memory_limit', '4024M');
set_time_limit(0);
include('conexion.php');
$data = array('error'=>0,'mensaje'=>'','content'=>'');

if( isset($_POST['idZona']) )
 {
	$idZona = $_POST['idZona'];
	$intervencion=array();

	$sql= "SELECT inter.id_intervenciones, mun.municipio, ent.nombreentidad, compor.comportamientos, tipoint.tipo_intervencion, inter.fecha
			FROM intervenciones inter
			JOIN personas_por_zona pxz ON pxz.id_personas_por_zonacol = inter.personas_por_zona_id_personas_por_zonacol
			JOIN indicadores_chec_por_intervenciones indxinter ON indxinter.intervenciones_id_intervenciones = inter.id_intervenciones
			JOIN indicadores_chec ind ON ind.id_indicadores_chec = indxinter.indicadores_chec_id_indicadores_chec
			JOIN comportamientos compor ON compor.id_comportamientos = ind.comportamientos_id_comportamientos
			JOIN entidades ent ON ent.id_entidad = inter.entidades_id_entidad
			JOIN tipo_intervencion tipoint ON tipoint.id_tipo_intervencion = inter.tipo_intervencion_id_tipo_intervencion
			LEFT OUTER JOIN barrios bar ON bar.id_barrio = ent.id_barrio
			LEFT OUTER JOIN comunas com ON com.id_comuna = bar.id_comuna
			LEFT OUTER JOIN veredas ver ON ver.id_veredas = ent.veredas_id_veredas
			JOIN municipios mun ON mun.id_municipio = com.id_municipio OR mun.id_municipio = ver.id_municipio
			WHERE pxz.zonas_id_zona = ".$idZona."
			GROUP BY id_intervenciones, municipio, ent.nombreentidad, comportamientos, tipoint.tipo_intervencion, inter.fecha ORDER BY inter.fecha DESC";
	$resultados_zona = $con->query($intervencion_por_zona);
	if(!$resultados_zona)
	{
		$data['error']=1;
	  	$data['mensaje']="Execute query error, because: ". print_r($con->errorInfo(),true);
	}
	
	$contador=0;
	while($row = $resultados_zona->fetch(PDO::FETCH_ASSOC)) {
	
		$data['content'][$contador]["id_intervenciones"] =  $row["id_intervenciones"];
		$data['content'][$contador]["municipio"] =  $row["municipio"];
		$data['content'][$contador]["nombreentidad"] =  $row["nombreentidad"];
		$data['content'][$contador]["comportamientos"] =  $row["comportamientos"];
		$data['content'][$contador]["tipo_intervencion"] =  $row["tipo_intervencion"];
		$data['content'][$contador]["fecha"] =  $row["fecha"];
		$contador++;
	}


	}else{
		$data['error']=1;
		$data['mensaje']="Error en el envío de datos";
	}
	
	
	echo json_encode($data);





?>
