<?php
session_start();
ini_set('memory_limit', '4024M');
set_time_limit(0);
include('conexion.php');



if(isset($_POST["accion"]))
{

    if($_POST["accion"]=="intervencionesPorZona")
	{
        app_intervencionesPorZona($_POST["id_zona"]);
	}
    if($_POST["accion"]=="detallesPlaneacion")
    {
        detallesPlaneacion($_POST["id_planeacion"]);
    }
    if($_POST["accion"]=="guardarUbicacion")
    {
        guardarUbicacion($_POST["latitud"], $_POST["longitud"], $_POST["id_planeacion"], $_POST['tipo_registro']);
    }

}

function app_intervencionesPorZona($idZona)
{
	include "conexion.php";
	$data=array('error'=> 0, 'mensaje'=> '', 'html'=>array());

	$sql= "SELECT inter.id_intervenciones, mun.municipio, compor.comportamientos, ti.tipo_intervencion, compe.competencia
			FROM intervenciones inter
			JOIN personas_por_zona pxz ON pxz.id_personas_por_zonacol = inter.personas_por_zona_id_personas_por_zonacol
			JOIN indicadores_chec_por_intervenciones indxinter ON indxinter.intervenciones_id_intervenciones = inter.id_intervenciones
			JOIN indicadores_chec ind ON ind.id_indicadores_chec = indxinter.indicadores_chec_id_indicadores_chec
			JOIN comportamientos compor ON compor.id_comportamientos = ind.comportamientos_id_comportamientos
			LEFT OUTER JOIN planeaciones_por_intervencion ppi ON ppi.intervenciones_id_intervenciones = inter.id_intervenciones
			LEFT OUTER JOIN planeacion plan ON ppi.planeacion_id_planeacion = plan.id_planeacion
			LEFT OUTER JOIN barrios bar ON bar.id_barrio = inter.id_barrio
			LEFT OUTER JOIN comunas com ON com.id_comuna = bar.id_comuna
			LEFT OUTER JOIN veredas ver ON ver.id_veredas = inter.id_vereda
			JOIN municipios mun ON mun.id_municipio = com.id_municipio OR mun.id_municipio = ver.id_municipio
			LEFT OUTER JOIN tipo_intervencion ti ON ti.id_tipo_intervencion = inter.tipo_intervencion_id_tipo_intervencion
			LEFT OUTER JOIN competencias_por_comportamiento cxc ON cxc.comportamientos_id_comportamientos = compor.id_comportamientos
			LEFT OUTER JOIN competencias compe ON compe.id_competencia = cxc.competencias_id_competencia
			WHERE pxz.zonas_id_zona = $idZona AND plan.fecha = current_date
			GROUP BY id_intervenciones, municipio, comportamientos, inter.fecha, ti.tipo_intervencion, compe.competencia, inter.fecha ORDER BY inter.fecha DESC";

	if($rs = $con->query($sql)){
		if($filas = $rs->fetchAll(PDO::FETCH_ASSOC)){
			foreach ($filas as $key => $value) {
				$data['html'][$key] = array(
					'id_intervenciones' => $value['id_intervenciones'], 
					'municipio' => $value['municipio'], 
					'comportamientos' => $value['comportamientos'],
					'tipo_intervencion' => $value['tipo_intervencion'],
					'competencia' => $value['competencia'],
					'planeaciones' => array()
				);

				$sql="SELECT pl.id_planeacion, pl.fecha, jor.jornada, reg.id_registro, reg.tipo_registro
				FROM planeacion pl
				JOIN planeaciones_por_intervencion plxint ON plxint.planeacion_id_planeacion = pl.id_planeacion
				JOIN jornada jor ON jor.id_jornada = pl.id_jornada
				LEFT OUTER JOIN registro_ubicacion reg ON reg.id_planeacion = pl.id_planeacion
				WHERE pl.fecha = current_date AND plxint.intervenciones_id_intervenciones = ".$value['id_intervenciones']." AND (reg.id_registro IS NULL OR reg.id_planeacion IN (SELECT id_planeacion FROM (SELECT count(reg.id_planeacion), reg.id_planeacion FROM registro_ubicacion reg GROUP BY reg.id_planeacion HAVING count(reg.id_planeacion) < 2) as SUB1))
				GROUP BY pl.id_planeacion, pl.fecha, jornada, reg.id_registro, reg.tipo_registro ORDER BY pl.fecha DESC";

				if($rs = $con->query($sql)){
					if($filas = $rs->fetchAll(PDO::FETCH_ASSOC)){
						foreach ($filas as $subkey => $subvalue) {
							$data['html'][$key]['planeaciones'][$subkey] = array('id_planeacion'=> $subvalue['id_planeacion'], 'fecha'=> $subvalue['fecha'], 'jornada'=> $subvalue['jornada']);
						}
					}
				}
			}
		}else{
			/* $data['mensaje']="No se realizo la consulta";
			$data['error']=1; */
		}
	}else{
		$data['mensaje']="No se realizo la consulta";
		$data['error']=1;
	}


	

	
	echo json_encode($data);
}


function detallesPlaneacion($id_planeacion){
	include "conexion.php";
    $data=array('error'=> 0, 'mensaje'=> '', 'html'=>array());
    
    $sql= "SELECT mun.municipio, ent.nombreentidad, tipoint.tipo_intervencion, compor.comportamientos, pl.fecha, jor.jornada, estra.nombreestrategia, tac.nombretactico, reg.tipo_registro
    FROM planeacion pl
    JOIN planeaciones_por_intervencion ppi ON ppi.planeacion_id_planeacion = pl.id_planeacion
    JOIN intervenciones inte ON inte.id_intervenciones = ppi.intervenciones_id_intervenciones
    LEFT OUTER JOIN entidades ent ON ent.id_entidad = pl.id_entidad
    LEFT OUTER JOIN barrios bar ON bar.id_barrio = inte.id_barrio
    LEFT OUTER JOIN veredas ver ON ver.id_veredas = inte.id_vereda
    LEFT OUTER JOIN comunas com ON com.id_comuna = bar.id_comuna
    LEFT OUTER JOIN municipios mun ON mun.id_municipio = com.id_municipio OR mun.id_municipio = ver.id_municipio
    JOIN tipo_intervencion tipoint ON tipoint.id_tipo_intervencion = inte.tipo_intervencion_id_tipo_intervencion
    JOIN indicadores_chec_por_intervenciones icpi ON icpi.intervenciones_id_intervenciones = inte.id_intervenciones
    JOIN indicadores_chec ic ON ic.id_indicadores_chec = icpi.indicadores_chec_id_indicadores_chec
    JOIN comportamientos compor ON compor.id_comportamientos = ic.comportamientos_id_comportamientos
    JOIN jornada jor ON jor.id_jornada = pl.id_jornada
    JOIN tactico_por_planeacion tpp ON tpp.planeacion_id_planeacion = pl.id_planeacion
    JOIN tactico tac ON tac.id_tactico = tpp.tactico_id_tactico
    JOIN estrategias estra ON estra.id_estrategia = tac.id_estrategia
	LEFT OUTER JOIN registro_ubicacion reg ON reg.id_planeacion = pl.id_planeacion
    WHERE pl.id_planeacion = $id_planeacion";

    if($rs = $con->query($sql)){
        if($filas = $rs->fetchAll(PDO::FETCH_ASSOC)){
            $data['html'] = $filas[0];
        }
    }else {
        $data['mensaje']="No se realizo la consulta";
		$data['error']=1;
    }

    echo json_encode($data);

}

function guardarUbicacion($latitud, $longitud, $idPlaneacion, $tipo){
    include "conexion.php";
    $data=array('error'=> 0, 'mensaje'=> '', 'html'=>array());

    $sql="INSERT INTO registro_ubicacion VALUES (
        nextval('sec_registro_ubicacion'),
        $latitud,
        $longitud,
        CURRENT_DATE AT TIME ZONE 'GMT-5',
        CURRENT_TIME AT TIME ZONE 'GMT-5',
        '$tipo',
        $idPlaneacion);";
    if($rs = $con->query($sql)){

    }else{
        $data['mensaje']=$con->errorInfo();
		$data['error']=1;
    }

    echo json_encode($data);
    
}


?>
