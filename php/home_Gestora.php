<?php
session_start();
ini_set('memory_limit', '4024M');
set_time_limit(0);
include('conexion.php');



if(isset($_POST["accion"]) && isset($_POST["id_zona"]))
{

	if($_POST["accion"]=="interevensionesPorZona")
	{
		interevensionesPorZona($_POST["id_zona"]);
	}

}

function interevensionesPorZona($id_zona){

	include('conexion.php');
	$data = array('error'=>0,'mensaje'=>'','html'=>'');


	if( $con )
 	{
 		//TRAER TODAS LAS GESTORAS POR ZONAS
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

							// traerIntervencionGestora();
							$llamarIntervecion=traerIntervencionGestora($datos['id_zona'],$datos['id_personas_por_zonacol']);
								
								if (count($llamarIntervecion >0)){
									foreach($llamarIntervecion as $datosGestora)
									{
											
											$data['html'].='<button id="intrevension_'.$datosGestora['id_intervenciones'].'" class="list-group-item list-group-item-action" onclick="mostrarDetalleIntervencion('.$datosGestora['id_intervenciones'].')">'.$datosGestora['municipio'].'-'.$datosGestora['comportamientos'].' <span class="float-right badge badge-primary">1</span></button>';
									}
								}
								
							$data['html'].='</div>';
							$data['html'].='<div class="card-actions">';
							  $data['html'].='<a href="#" class="card-link">Ver más</a>';
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
				print_r($conexion->getPDO()->errorInfo());
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

	//$id_Personas_por_Zona = $_SESSION["IdPersonasPorZona"];
	$intervencion_por_zona= "SELECT inter.id_intervenciones, mun.municipio, compor.comportamientos
			FROM intervenciones inter
			JOIN personas_por_zona pxz ON pxz.id_personas_por_zonacol = inter.personas_por_zona_id_personas_por_zonacol
			JOIN indicadores_chec_por_intervenciones indxinter ON indxinter.intervenciones_id_intervenciones = inter.id_intervenciones
			JOIN indicadores_chec ind ON ind.id_indicadores_chec = indxinter.indicadores_chec_id_indicadores_chec
			JOIN comportamientos compor ON compor.id_comportamientos = ind.comportamientos_id_comportamientos
			JOIN entidades ent ON ent.id_entidad = inter.entidades_id_entidad
			LEFT OUTER JOIN barrios bar ON bar.id_barrio = ent.id_barrio
			LEFT OUTER JOIN comunas com ON com.id_comuna = bar.id_comuna
			LEFT OUTER JOIN veredas ver ON ver.id_veredas = ent.veredas_id_veredas
			JOIN municipios mun ON mun.id_municipio = com.id_municipio OR mun.id_municipio = ver.id_municipio
			WHERE pxz.zonas_id_zona = ".$idZona."
			GROUP BY id_intervenciones, municipio, comportamientos";
		 // where ppz.Zonas_Id_Zona = '1'";
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
	     // $intervencion[$contador]["Fecha_Intervencion"] =  $row["fecha_intervencion"];
	     $intervencion[$contador]["comportamientos"] =  $row["comportamientos"];
	     $intervencion[$contador]["municipio"] =  $row["municipio"];
	     $contador++;
	  }
	 // $cantidad_intervenciones_por_zona = $contador;
	// $intervenciones_por_comportamiento = "SELECT c.comportamientos
	// from indicadores_chec_por_intervenciones ici inner join intervenciones i on
	// ici.intervenciones_id_intervenciones = i.id_intervenciones inner join indicadores_chec ic on ici.indicadores_chec_id_indicadores_chec = ic.id_indicadores_chec
	// inner join comportamientos c on ic.comportamientos_id_comportamientos = c.id_comportamientos
	// where i.personas_por_zona_id_personas_por_zonacol = '".$idPersonasPorZona."'
	// group by c.comportamientos";

		// //where i.Personas_por_Zona_id_Personas_por_Zonacol = '".$idPersonasPorZona."'
	  	// $resultados_comportamiento = $con->query($intervenciones_por_comportamiento);
	  	// $contador=0;
	  // // Parse returned data, and displays them
	  // while($row = $resultados_comportamiento->fetch(PDO::FETCH_ASSOC)) {
	  		// $intervencion[$contador]["Comportamientos"] =  $row["comportamientos"];
	     // $contador++;

	  // }
		// if($cantidad_intervenciones_por_zona == $contador)//si la cantidad de las intervenciones son las mismas, se guarda la cantidad en una variable para el ciclo
		// {
			// $cantidad_intervenciones = $cantidad_intervenciones_por_zona;
		// }
	  //PENDIENTE HACER UN FOR
		// for($cont=0;$cont<$cantidad_intervenciones;$cont++)
		// {
	  		// $fecha_intervencion = str_replace("-", "/", $intervencion[$cont]["Fecha_Intervencion"]);

	  		// echo "<a id=".$intervencion[$cont]["id_Intervenciones"]." href='#' class='list-group-item active'>".$intervencion[$cont]["Municipio"]." - ".$fecha_intervencion." - ".$intervencion[$cont]["Tipo_Intervencion"]." - ".$intervencion[$cont]["Comportamientos"]."</a>";
	  	// }
	
	return $intervencion;
}


?>
