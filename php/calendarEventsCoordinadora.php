<?php

include 'conexion.php';
$data = array();

$sql = "SELECT pl.lugarencuentro, mun.municipio, ent.nombreentidad,ru.id_registro, ru.latitud, ru.longitud, pl.fecha, ru.hora::time(0), 
ru.tipo_registro, jor.jornada,pl.id_planeacion, tct.nombretactico, etg.nombreestrategia, COUNT(spp.subtemas_id_subtema), temas, 
compo.comportamientos, compe.competencia, zna.id_zona, zna.zonas, (psna.nombres || ' ' || psna.apellidos) as nombre
FROM planeacion pl 
LEFT JOIN registro_ubicacion ru ON ru.id_planeacion = pl.id_planeacion
LEFT JOIN jornada jor ON jor.id_jornada = pl.id_jornada
LEFT JOIN entidades ent ON ent.id_entidad = pl.id_entidad
LEFT JOIN tactico_por_planeacion tpp ON tpp.planeacion_id_planeacion = pl.id_planeacion
LEFT JOIN tactico tct ON tct.id_tactico = tpp.tactico_id_tactico
LEFT JOIN estrategias etg ON etg.id_estrategia = tct.id_estrategia
LEfT JOIN subtemas_por_planeacion spp ON spp.planeacion_id_planeacion = pl.id_planeacion
LEFT JOIN subtemas sbt ON sbt.id_subtema = spp.subtemas_id_subtema
LEFT JOIN temas tm ON tm.id_temas = sbt.id_temas
LEFT JOIN competencias_por_comportamiento AS cpc ON cpc.competencias_id_competencia = tm.compe_por_compo_compe_id_compe
AND cpc.comportamientos_id_comportamientos = tm.compe_por_compo_compo_id_compo
LEFT JOIN comportamientos compo ON compo.id_comportamientos = cpc.comportamientos_id_comportamientos
LEFT JOIN competencias compe ON compe.id_competencia = cpc.competencias_id_competencia
JOIN planeaciones_por_intervencion ppi ON ppi.planeacion_id_planeacion = pl.id_planeacion
JOIN intervenciones itv ON itv.id_intervenciones = ppi.intervenciones_id_intervenciones
JOIN personas_por_zona ppz ON ppz.id_personas_por_zonacol = itv.personas_por_zona_id_personas_por_zonacol
JOIN zonas zna ON zna.id_zona = ppz.zonas_id_zona
JOIN personas psna ON psna.numeroidentificacion = ppz.personas_numeroidentificacion
LEFT OUTER JOIN barrios bar ON bar.id_barrio = itv.id_barrio
LEFT OUTER JOIN veredas ver ON ver.id_veredas = itv.id_vereda
LEFT OUTER JOIN comunas com ON com.id_comuna = bar.id_comuna
LEFT JOIN municipios mun ON mun.id_municipio = com.id_municipio OR mun.id_municipio = ver.id_municipio
GROUP BY pl.id_planeacion,ru.id_registro, ru.latitud, ru.longitud, pl.fecha, ru.hora, ru.tipo_registro, jor.jornada,pl.id_planeacion, 
tct.nombretactico, etg.nombreestrategia, tm.temas, compo.comportamientos, compe.competencia, zna.zonas, zna.id_zona, nombre, pl.lugarencuentro, 
mun.municipio, ent.nombreentidad
ORDER BY pl.id_planeacion";

if ($rs = $con->query($sql)) {
    if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
        foreach ($filas as $key => $value) {
            $allDay = false;
            switch ($value['jornada']) {
                case 'Mañana':
                    $start = $value['fecha'] . "T08:00:00-05:00";
                    $end = $value['fecha'] . "T12:00:00-05:00";
                    break;
                case 'Tarde':
                    $start = $value['fecha'] . "T14:00:00-05:00";
                    $end = $value['fecha'] . "T18:00:00-05:00";
                    break;
                case 'Noche':
                    $start = $value['fecha'] . "T18:00:00-05:00";
                    $end = $value['fecha'] . "T22:00:00-05:00";
                    break;
                case 'Todo el día':
                    $start = $value['fecha'] . "T08:00:00-05:00";
                    $end = $value['fecha'] . "T18:00:00-05:00";
                    break;
                default:
                    break;
            }

            switch ($value['id_zona']) {
                case 4:
                    $color = "rgb(176, 53, 222)";
                    break;
                case 5:
                    $color = "rgb(52, 155, 251)";
                    break;
                case 1:
                    $color = "rgb(77, 233, 133)";
                    break;
                case 2:
                    $color = "rgb(231, 122, 104)";
                    break;
                case 3:
                    $color = "rgb(227, 227, 39)";
                    break;
            }

            /* if (is_null($value['nombreentidad'])) {
            $lugar = $value['municipio'];
            } else {
            $lugar = $value['municipio'] . ' : ' . $value['nombreentidad'];
            } */

            if(is_null($value['tipo_registro'])) {
              $value['tipo_registro'] = 'Planeado';
            }

            $lugar = $value['municipio'];
            $zona = $value['id_zona'];
            $hora = $value['hora'];
            $gestor = $value['nombre'];
            $title = $gestor . ' (' . strtoupper($value['zonas']). ') - ' . $value['tipo_registro'] . ' : ' . $value['jornada'] . ' - ' . $value['municipio'];

            $data[$key] = array(
                'id' => $key,
                'title' => $title,
                'allDay' => $allDay,
                'start' => $start,
                'end' => $end,
                'editable' => false,
                'color' => $color,
                'textColor' => "white",
                'lugar' => $lugar,
                'hora' => $hora,
                'zona' => $zona,
                'gestor' => $gestor,
                'descripcion' => $value['nombreestrategia'] . ': ' . $value['nombretactico'] . '<br>' . $value['comportamientos'] . '<br>' . $value['competencia'] . ': ' . $value['temas'],
            );
        }
    } else {
        $data['error'] = 1;
    }
} else {
    $data['error'] = 1;
}

echo json_encode($data);
