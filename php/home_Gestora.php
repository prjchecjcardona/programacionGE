<?php
session_start();
ini_set('memory_limit', '4024M');
set_time_limit(0);
include('conexion.php');



if(isset($_POST["accion"]) && isset($_POST["id_zona"]))
{

	if($_POST["accion"]=="intervencionesPorZona")
	{
		intervencionesPorZona($_POST["id_zona"]);
	}
	

}

function intervencionesPorZona($id_zona){

	include('conexion.php');
	$data = array('error'=>0,'mensaje'=>'','html'=>'');


	if( $con )
 	{
 		//Obtener los dats del gestor de la zona indicada
    	$sql = "SELECT p.numeroidentificacion,p.nombres,z.id_zona,z.zonas,Id_Personas_por_Zonacol
			  FROM personas as p, zonas as z, personas_por_zona as pz
			  WHERE p.numeroidentificacion = pz.personas_numeroidentificacion
			  AND z.id_zona = pz.zonas_id_zona AND z.id_zona=".$id_zona;

		$array=array();
		if ($rs = $con->query($sql)) {
				if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
					foreach ($filas as $fila) {
						$array[] = $fila;
					}
					$data['html']="";
					foreach ($array as $datos) {
						$data['html'].='<div class="card">';
						  $data['html'].='<div class="card-body">';
							$data['html'].='<h4 class="card-title">'.$datos['zonas'].'</h4>';
							$data['html'].='<h6 class="card-subtitle mb-2 text-muted">'.$datos['nombres'].'</h6>';
							$data['html'].='<div class="list-group">';
							$llamarIntervencion=traerIntervencionGestora($datos['id_zona'],$datos['id_personas_por_zonacol']);
								
								if (count($llamarIntervencion) >0){
									foreach($llamarIntervencion as $datosGestora)
									{
											
											$data['html'].='<button id="intrevension_'.$datosGestora['id_intervenciones'].'" class="list-group-item list-group-item-action" onclick="mostrarDetalleIntervencion('.$datosGestora['id_intervenciones'].')">'.$datosGestora['municipio'].'-'.$datosGestora['comportamientos'].' <span class="float-right badge badge-primary">1</span></button>';
									}
								}
								
							$data['html'].='</div>';
							$data['html'].='<div class="card-actions">';
							$data['html'].='<a href="listado_Intervenciones_Gestora.html?zona='.$datos['id_zona'].'" class="card-link">Ver más</a>';
							$data['html'].='<a id="'.$datos['id_zona'].'" class="card-link float-right" onclick="agregarIntervencion('.$datos['id_zona'].');"><i class="fa fa-plus-circle fa-2x"></i></a>';
							$data['html'].='</div>';
						  $data['html'].='</div>';
						$data['html'].='</div>';
					}

					// $data['html']=$array;
					// print_r($data['html']);

				}
			}
			else
			{
				
				$data['mensaje']="No se realizo la consulta gestoras por zona";
				$data['error']=1;
			}
	}
	 echo json_encode($data);
}

function traerIntervencionGestora($idZona,$idPersonasPorZona)
{
	include "conexion.php";
	$intervencion=array();

	$intervencion_por_zona= "SELECT inter.id_intervenciones, mun.municipio, compor.comportamientos
			FROM intervenciones inter
			JOIN personas_por_zona pxz ON pxz.id_personas_por_zonacol = inter.personas_por_zona_id_personas_por_zonacol
			JOIN indicadores_chec_por_intervenciones indxinter ON indxinter.intervenciones_id_intervenciones = inter.id_intervenciones
			JOIN indicadores_chec ind ON ind.id_indicadores_chec = indxinter.indicadores_chec_id_indicadores_chec
			JOIN comportamientos compor ON compor.id_comportamientos = ind.comportamientos_id_comportamientos
			LEFT OUTER JOIN barrios bar ON bar.id_barrio = inter.id_barrio
			LEFT OUTER JOIN comunas com ON com.id_comuna = bar.id_comuna
			LEFT OUTER JOIN veredas ver ON ver.id_veredas = inter.id_vereda
			JOIN municipios mun ON mun.id_municipio = com.id_municipio OR mun.id_municipio = ver.id_municipio
			WHERE pxz.zonas_id_zona = ".$idZona."
			GROUP BY id_intervenciones, municipio, comportamientos, inter.fecha ORDER BY inter.fecha DESC LIMIT 4";

	$resultados_zona = $con->query($intervencion_por_zona);
	if(!$resultados_zona)
	{
	  die("Execute query error, because: ". print_r($con->errorInfo(),true) );
	}
	//success case
	else{
		 //continue flow
	}


	$contador=0;
	while($row = $resultados_zona->fetch(PDO::FETCH_ASSOC)) {

	     $intervencion[$contador]["id_intervenciones"] =  $row["id_intervenciones"];
	     $intervencion[$contador]["comportamientos"] =  $row["comportamientos"];
	     $intervencion[$contador]["municipio"] =  $row["municipio"];
	     $contador++;
	  }

	
	return $intervencion;
}




?>
