<?php
include('conexion.php');


if(isset($_POST["accion"]))
{

	if($_POST["accion"]=="interevensionesPorZona")
	{
		interevensionesPorZona();
	}

}

function interevensionesPorZona(){

	include('conexion.php');
	$data = array('error'=>0,'mensaje'=>'','html'=>'');


	if( $con )
 	{
 		//TRAER TODAS LAS GESTORAS POR ZONAS
    	$sql = "SELECT p.numeroidentificacion,p.nombres,z.id_zona,z.zonas,Id_Personas_por_Zonacol
			  FROM personas as p, zonas as z, personas_por_zona as pz
			  WHERE p.numeroidentificacion = pz.personas_numeroidentificacion
			  AND z.id_zona = pz.zonas_id_zona";

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
								// $data['html'].='<a href="#" id="'.$datos['NumeroIdentificacion'].'_'.$datos['Id_Zona'].'" class="list-group-item list-group-item-action"> {Municipio - 08/09/2017 - Estrategia} <span class="float-right badge badge-primary">2</span></a>';
								if (count($llamarIntervecion >0)){
									foreach($llamarIntervecion as $datosGestora)
									{
											// $data['html'].='<a href="" id="intrevension_'.$datosGestora['id_Intervenciones'].'" class="list-group-item list-group-item-action">'.$datosGestora['Municipio'].'-'.$datosGestora['Fecha_Intervencion'].'-'.$datosGestora['Comportamientos'].' <span class="float-right badge badge-primary">1</span></a>';
											$data['html'].='<button id="intrevension_'.$datosGestora['id_Intervenciones'].'" class="list-group-item list-group-item-action" onclick="mostrarDetalleIntervencion('.$datosGestora['id_Intervenciones'].')">'.$datosGestora['Municipio'].'-'.$datosGestora['Fecha_Intervencion'].'-'.$datosGestora['Comportamientos'].' <span class="float-right badge badge-primary">1</span></button>';
									}
								}
								// $data['html'].='<a href="#" class="list-group-item list-group-item-action"> {Municipio - 08/09/2017 - Estrategia} </a>';
								// $data['html'].='<a href="#" class="list-group-item list-group-item-action"> {Municipio - 08/09/2017 - Estrategia} </a>';
							$data['html'].='</div>';
							$data['html'].='<div class="card-actions">';
							  $data['html'].='<a href="#" class="card-link">Ver más</a>';
							  $data['html'].='<a href="#" class="card-link float-right"><i class="fa fa-plus-circle fa-2x"></i></a>';
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
	$intervencion_por_zona= "SELECT
		 i.id_intervenciones, ti.tipo_intervencion, m.municipio, ppz.zonas_id_zona
		from intervenciones i inner join entidades e
		on i.entidades_id_entidad = e.id_entidad
		inner join tipo_intervencion ti
		on i.tipo_intervencion_id_tipo_intervencion = ti.id_tipo_intervencion
		left join barrios b on e.id_barrio = b.id_barrio left join veredas v
		on v.id_veredas = e.veredas_id_veredas left join comunas c on c.id_comuna = b.id_comuna
		left join municipios m on m.id_municipio = c.id_municipio or v.id_municipio = m.id_municipio
		inner join personas_por_zona ppz on
		 ppz.id_personas_por_zonacol = i.personas_por_zona_id_personas_por_zonacol
		 where ppz.zonas_id_zona = '".$idZona."'";
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

	     $intervencion[$contador]["id_Intervenciones"] =  $row["id_intervenciones"];
	     $intervencion[$contador]["Fecha_Intervencion"] =  $row["fecha_intervencion"];
	     $intervencion[$contador]["Tipo_Intervencion"] =  $row["tipo_intervencion"];
	     $intervencion[$contador]["Municipio"] =  $row["municipio"];
	     $contador++;
	  }
	 $cantidad_intervenciones_por_zona = $contador;
	$intervenciones_por_comportamiento = "SELECT c.comportamientos
	from indicadores_chec_por_intervenciones ici inner join intervenciones i on
	ici.intervenciones_id_intervenciones = i.id_intervenciones inner join indicadores_chec ic on ici.indicadores_chec_id_indicadores_chec = ic.id_indicadores_chec
	inner join comportamientos c on ic.comportamientos_id_comportamientos = c.id_comportamientos
	where i.personas_por_zona_id_personas_por_zonacol = '".$idPersonasPorZona."'
	group by c.comportamientos";

		//where i.Personas_por_Zona_id_Personas_por_Zonacol = '".$idPersonasPorZona."'
	  	$resultados_comportamiento = $con->query($intervenciones_por_comportamiento);
	  	$contador=0;
	  // Parse returned data, and displays them
	  while($row = $resultados_comportamiento->fetch(PDO::FETCH_ASSOC)) {
	  		$intervencion[$contador]["Comportamientos"] =  $row["comportamientos"];
	     $contador++;

	  }
		if($cantidad_intervenciones_por_zona == $contador)//si la cantidad de las intervenciones son las mismas, se guarda la cantidad en una variable para el ciclo
		{
			$cantidad_intervenciones = $cantidad_intervenciones_por_zona;
		}
	  //PENDIENTE HACER UN FOR
		// for($cont=0;$cont<$cantidad_intervenciones;$cont++)
		// {
	  		// $fecha_intervencion = str_replace("-", "/", $intervencion[$cont]["Fecha_Intervencion"]);

	  		// echo "<a id=".$intervencion[$cont]["id_Intervenciones"]." href='#' class='list-group-item active'>".$intervencion[$cont]["Municipio"]." - ".$fecha_intervencion." - ".$intervencion[$cont]["Tipo_Intervencion"]." - ".$intervencion[$cont]["Comportamientos"]."</a>";
	  	// }

	return $intervencion;
}


?>
