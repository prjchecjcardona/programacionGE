<?php
include('conexion.php');
$data = array();

if(isset($_POST['id_zona'])){
    $idZona = $_POST['id_zona'];

    $sql = "SELECT pl.fecha, jor.jornada, mun.municipio, reg.id_registro, ent.nombreentidad, est.nombreestrategia, tac.nombretactico, compor.comportamientos, compe.competencia, tem.temas
    FROM planeacion pl
    JOIN jornada jor ON jor.id_jornada = pl.id_jornada
    JOIN planeaciones_por_intervencion ppi ON ppi.planeacion_id_planeacion = pl.id_planeacion
    JOIN intervenciones inte ON inte.id_intervenciones = ppi.intervenciones_id_intervenciones
    LEFT OUTER JOIN barrios bar ON bar.id_barrio = inte.id_barrio
    LEFT OUTER JOIN veredas ver ON ver.id_veredas = inte.id_vereda
    LEFT OUTER JOIN comunas com ON com.id_comuna = bar.id_comuna
    JOIN municipios mun ON mun.id_municipio = com.id_municipio OR mun.id_municipio = ver.id_municipio
    JOIN zonas zon ON zon.id_zona = mun.id_zona
    LEFT OUTER JOIN registro_ubicacion reg ON reg.id_planeacion = pl.id_planeacion
    LEFT OUTER JOIN tactico_por_planeacion tpp ON tpp.planeacion_id_planeacion = pl.id_planeacion
    LEFT OUTER JOIN tactico tac ON tac.id_tactico = tpp.tactico_id_tactico
    LEFT OUTER JOIN estrategias est ON est.id_estrategia = tac.id_estrategia
    LEFT OUTER JOIN subtemas_por_planeacion spp ON spp.planeacion_id_planeacion = pl.id_planeacion
    LEFT OUTER JOIN subtemas sub ON sub.id_subtema = spp.subtemas_id_subtema
    LEFT OUTER JOIN temas tem ON tem.id_temas = sub.id_temas
    LEFT OUTER JOIN competencias_por_comportamiento cpc ON cpc.competencias_id_competencia = tem.compe_por_compo_compe_id_compe AND cpc.comportamientos_id_comportamientos = tem.compe_por_compo_compo_id_compo
    LEFT OUTER JOIN comportamientos compor ON compor.id_comportamientos = cpc.comportamientos_id_comportamientos
    LEFT OUTER JOIN competencias compe ON compe.id_competencia = cpc.competencias_id_competencia
    LEFT OUTER JOIN entidades ent ON ent.id_entidad = pl.id_entidad
    WHERE zon.id_zona = $idZona
    GROUP BY pl.fecha, jornada, municipio, id_registro, nombreentidad, nombreestrategia, nombretactico, comportamientos, competencia, temas
    ORDER BY pl.fecha DESC";

    if ($rs = $con->query($sql)) {
        if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
            foreach ($filas as $key => $value) {
                $allDay=false;
                switch ($value['jornada']) {
                    case 'Mañana':
                        $start=$value['fecha']."T08:00:00-05:00";
                        $end=$value['fecha']."T12:00:00-05:00";
                        break;
                    case 'Tarde':
                        $start=$value['fecha']."T14:00:00-05:00";
                        $end=$value['fecha']."T18:00:00-05:00";
                        break;
                    case 'Noche':
                        $start=$value['fecha']."T18:00:00-05:00";
                        $end=$value['fecha']."T22:00:00-05:00";
                        break; 
                    case 'Todo el día':
                        $allDay = true;
                        $start=$value['fecha']."T08:00:00-05:00";
                        $end=$value['fecha']."T18:00:00-05:00";
                        break;   
                    default:
                        break;
                }

                if(is_null($value['id_registro'])){
                    $color = "rgb(156, 193, 43)";
                }else {
                    $color = "rgb(139, 141, 142)";
                }

                if(is_null($value['nombreentidad'])){
                    $lugar = $value['municipio'];
                }else {
                    $lugar = $value['municipio'].' : '.$value['nombreentidad'];
                }

                $data[$key] = array(
                    'id' => $key,
                    'title' => $value['jornada'].' - '.$value['municipio'],
                    'allDay' => $allDay,
                    'start' => $start,
                    'end' => $end,
                    'editable' => false,
                    'color' => $color,
                    'textColor' => "white",
                    'lugar' => $lugar,
                    'descripcion' => $value['nombreestrategia'].': '.$value['nombretactico'].'. '.$value['comportamientos'].'-'.$value['competencia'].': '.$value['temas']
                );
            }
        }else{
			$data['error']=1;
        }
    }else{
		$data['error']=1;
    }



}

echo json_encode($data);




?>
