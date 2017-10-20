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
    	$sql = "SELECT p.NumeroIdentificacion,p.Nombres,z.Id_Zona,z.Zonas,Id_Personas_por_Zonacol
			  FROM personas as p, zonas as z, personas_por_zona as pz
			  WHERE p.NumeroIdentificacion = pz.Personas_NumeroIdentificacion
			  AND z.Id_Zona = pz.Zonas_Id_Zona";
  		
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
							$data['html'].='<h4 class="card-title">'.$datos['Zonas'].'</h4>';
							$data['html'].='<h6 class="card-subtitle mb-2 text-muted">'.$datos['Nombres'].'</h6>';
							$data['html'].='<div class="list-group">';
							
							// traerIntervencionGestora();
							$llamarIntervecion=traerIntervencionGestora($datos['Id_Zona'],$datos['Id_Personas_por_Zonacol']);
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
		 i.id_Intervenciones, i.Fecha_Intervencion, ti.Tipo_Intervencion, m.Municipio, ppz.Zonas_Id_Zona
		from intervenciones i inner join entidades e 
		on i.Entidades_Id_Entidad = e.Id_Entidad 
		inner join tipo_intervencion ti 
		on i.Tipo_intervencion_id_Tipo_intervencion = ti.id_Tipo_intervencion 
		left join barrios b on e.Id_Barrio = b.Id_Barrio left join veredas v 
		on v.id_Veredas = e.Id_Veredas left join comunas c on c.Id_Comuna = b.Id_Comuna 
		left join municipios m on m.Id_Municipio = c.Id_Municipio or v.Id_Municipio = m.Id_Municipio 
		inner join personas_por_zona ppz on
		 ppz.id_Personas_por_Zonacol = i.Personas_por_Zona_id_Personas_por_Zonacol
		 where ppz.Zonas_Id_Zona = '".$idZona."'";
		 // where ppz.Zonas_Id_Zona = '1'";
	$resultados_zona = $con->query($intervencion_por_zona);
	$contador=0;
	while($row = $resultados_zona->fetch(PDO::FETCH_ASSOC)) {

	     $intervencion[$contador]["id_Intervenciones"] =  $row["id_Intervenciones"];
	     $intervencion[$contador]["Fecha_Intervencion"] =  $row["Fecha_Intervencion"];
	     $intervencion[$contador]["Tipo_Intervencion"] =  $row["Tipo_Intervencion"];
	     $intervencion[$contador]["Municipio"] =  $row["Municipio"];
	     $contador++;
	  }
	 $cantidad_intervenciones_por_zona = $contador;
	$intervenciones_por_comportamiento = "SELECT c.Comportamientos 
	from indicadores_chec_por_intervenciones ici inner join intervenciones i on
	ici.Intervenciones_id_Intervenciones = i.id_Intervenciones inner join indicadores_chec ic on ici.Indicadores_CHEC_id_Indicadores_CHEC = ic.id_Indicadores_CHEC
	inner join comportamientos c on ic.Comportamientos_Id_Comportamientos = c.Id_Comportamientos 
	where i.Personas_por_Zona_id_Personas_por_Zonacol = '".$idPersonasPorZona."' 
	group by c.Comportamientos";

		//where i.Personas_por_Zona_id_Personas_por_Zonacol = '".$idPersonasPorZona."' 
	  	$resultados_comportamiento = $con->query($intervenciones_por_comportamiento);
	  	$contador=0;
	  // Parse returned data, and displays them
	  while($row = $resultados_comportamiento->fetch(PDO::FETCH_ASSOC)) {
	  		$intervencion[$contador]["Comportamientos"] =  $row["Comportamientos"];
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