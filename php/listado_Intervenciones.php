<?php

include('conexion.php');
$data = array('error'=>0,'mensaje'=>'','content'=>'');

if( isset($_GET['idZona']) )
 {
	$idZona = $_GET['idZona'];
	$sql= "SELECT inter.id_intervenciones, mun.municipio, compor.comportamientos, tipoint.tipo_intervencion, inter.fecha
			FROM intervenciones inter
			JOIN personas_por_zona pxz ON pxz.id_personas_por_zonacol = inter.personas_por_zona_id_personas_por_zonacol
			JOIN indicadores_chec_por_intervenciones indxinter ON indxinter.intervenciones_id_intervenciones = inter.id_intervenciones
			JOIN indicadores_chec ind ON ind.id_indicadores_chec = indxinter.indicadores_chec_id_indicadores_chec
			JOIN comportamientos compor ON compor.id_comportamientos = ind.comportamientos_id_comportamientos
			JOIN tipo_intervencion tipoint ON tipoint.id_tipo_intervencion = inter.tipo_intervencion_id_tipo_intervencion
			LEFT OUTER JOIN barrios bar ON bar.id_barrio = inter.id_barrio
			LEFT OUTER JOIN comunas com ON com.id_comuna = bar.id_comuna
			LEFT OUTER JOIN veredas ver ON ver.id_veredas = inter.id_vereda
			JOIN municipios mun ON mun.id_municipio = com.id_municipio OR mun.id_municipio = ver.id_municipio
			WHERE pxz.zonas_id_zona = $idZona
			GROUP BY id_intervenciones, municipio, comportamientos, tipoint.tipo_intervencion, inter.fecha ORDER BY inter.fecha DESC";
	$resultados_zona = $con->query($sql);
	if(!$resultados_zona)
	{
		$data['error']=1;
	  	$data['mensaje']="Execute query error, because: ". print_r($con->errorInfo(),true);
	}
	$contador=0;
	$data['content'] = array();
	while($row = $resultados_zona->fetch(PDO::FETCH_ASSOC)) {
		array_push($data['content'], array(
			'id_intervenciones' => $row['id_intervenciones'],
			'municipio' => $row['municipio'],
			'comportamientos' => $row['comportamientos'],
			'tipo_intervencion' => $row['tipo_intervencion'],
			'fecha' => $row['fecha']
		));
		$contador++;
	}

}else{
	$data['error']=1;
	$data['mensaje']="Error en el envío de datos";
}
	
	
	echo json_encode($data);





?>
