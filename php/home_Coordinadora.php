
<?php
ini_set('memory_limit', '4024M');
set_time_limit(0);
include 'conexion.php';

if (isset($_POST["accion"])) {

    if ($_POST["accion"] == "intervencionesPorZona") {
        intervencionesPorZona();
    }
    if ($_POST["accion"] == "getUbicaciones") {
        getUbicaciones();
    }

}

function intervencionesPorZona()
{

    include 'conexion.php';
    $data = array('error' => 0, 'mensaje' => '', 'html' => '');

    if ($con) {
        //TRAER TODAS LAS GESTORAS POR ZONAS
        $sql = "SELECT p.numeroidentificacion,p.nombres,z.id_zona,z.zonas,Id_Personas_por_Zonacol
			  FROM personas as p, zonas as z, personas_por_zona as pz
			  WHERE p.numeroidentificacion = pz.personas_numeroidentificacion
			  AND z.id_zona = pz.zonas_id_zona";

        $array = array();
        if ($rs = $con->query($sql)) {
            if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
                foreach ($filas as $fila) {
                    $array[] = $fila;
                }
                $data['html'] = "";
                foreach ($array as $datos) {
                    $data['html'] .= '<div class="card">';
                    $data['html'] .= '<div class="card-body">';
                    $data['html'] .= '<h4 class="card-title">' . $datos['zonas'] . ' // Zona '.$datos['id_zona'].'</h4>';
                    $data['html'] .= '<h6 class="card-subtitle mb-2 text-muted">' . $datos['nombres'] . '</h6> <span id="gestor'.$datos['id_zona'].'" class="dot"></span>';
                    $data['html'] .= '<div class="list-group">';
                    
                    // traerIntervencionGestora();
                    $llamarIntervecion = traerIntervencionGestora($datos['id_zona'], $datos['id_personas_por_zonacol']);
                    if (count($llamarIntervecion) > 0) {
                        foreach ($llamarIntervecion as $datosGestora) {
                            $data['html'] .= '<button id="intervencion_' . $datosGestora['id_intervenciones'] . '" class="list-group-item list-group-item-action" onclick="mostrarDetalleIntervencion(' . $datosGestora['id_intervenciones'] . ')">' . $datosGestora['municipio'] . '-' . $datosGestora['comportamientos'] . ' <span class="float-right badge badge-primary">1</span></button>';
                        }
                    }

                    $data['html'] .= '</div>';
                    $data['html'] .= '<div class="card-actions">';
                    $data['html'] .= '<a href="listado_Intervenciones_Coordinadora.html?zona=' . $datos['id_zona'] . '" class="card-link">Ver más</a>';
                    $data['html'] .= '<a id="' . $datos['id_zona'] . '" class="card-link float-right" onclick="agregarIntervencion(' . $datos['id_zona'] . ');"><i class="fa fa-plus-circle fa-2x"></i></a>';
                    $data['html'] .= '</div>';
                    $data['html'] .= '</div>';
                    $data['html'] .= '</div>';
                }

                // $data['html']=$array;
                // print_r($data['html']);

            }
        } else {
            print_r($con->getPDO()-a>errorInfo());
            $data['mensaje'] = "No se realizo la consulta gestoras por zona";
            $data['error'] = 1;
        }
    }
    echo json_encode($data);
}

function traerIntervencionGestora($idZona, $idPersonasPorZona)
{
    include "conexion.php";
    $intervencion = array();

    //$id_Personas_por_Zona = $_SESSION["IdPersonasPorZona"];
    $intervencion_por_zona = "SELECT inter.id_intervenciones, mun.municipio, compor.comportamientos
			FROM intervenciones inter
			JOIN personas_por_zona pxz ON pxz.id_personas_por_zonacol = inter.personas_por_zona_id_personas_por_zonacol
			JOIN indicadores_chec_por_intervenciones indxinter ON indxinter.intervenciones_id_intervenciones = inter.id_intervenciones
			JOIN indicadores_chec ind ON ind.id_indicadores_chec = indxinter.indicadores_chec_id_indicadores_chec
			JOIN comportamientos compor ON compor.id_comportamientos = ind.comportamientos_id_comportamientos
			LEFT OUTER JOIN barrios bar ON bar.id_barrio = inter.id_barrio
			LEFT OUTER JOIN comunas com ON com.id_comuna = bar.id_comuna
			LEFT OUTER JOIN veredas ver ON ver.id_veredas = inter.id_vereda
			JOIN municipios mun ON mun.id_municipio = com.id_municipio OR mun.id_municipio = ver.id_municipio
			WHERE pxz.zonas_id_zona = " . $idZona . "
			GROUP BY id_intervenciones, municipio, comportamientos, inter.fecha ORDER BY inter.fecha DESC LIMIT 3";
    // where ppz.Zonas_Id_Zona = '1'";
    $resultados_zona = $con->query($intervencion_por_zona);
    if (!$resultados_zona) {
        die("Execute query error, because: " . print_r($con->errorInfo(), true));
    }

    $contador = 0;
    while ($row = $resultados_zona->fetch(PDO::FETCH_ASSOC)) {

        $intervencion[$contador]["id_intervenciones"] = $row["id_intervenciones"];
        // $intervencion[$contador]["Fecha_Intervencion"] =  $row["fecha_intervencion"];
        $intervencion[$contador]["comportamientos"] = $row["comportamientos"];
        $intervencion[$contador]["municipio"] = $row["municipio"];
        $contador++;
    }

    return $intervencion;
}
function getUbicaciones()
{

    include 'conexion.php';
    $data = array('error' => 0, 'mensaje' => '', 'html' => array());
    $sql = "SELECT DISTINCT ON (psna.nombres) pl.lugarencuentro, mun.municipio, ent.nombreentidad,ru.latitud, 
    ru.longitud, pl.fecha, ru.hora::time(0), ru.tipo_registro, jor.jornada,
    tct.nombretactico, etg.nombreestrategia, COUNT(spp.subtemas_id_subtema), 
    temas, compo.comportamientos, compe.competencia, (psna.nombres || ' ' || psna.apellidos) as nombre, psna.foto_url
    FROM registro_ubicacion ru
    LEFT JOIN planeacion pl ON ru.id_planeacion = pl.id_planeacion
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
    GROUP BY pl.id_planeacion,ru.id_registro, ru.latitud, ru.longitud, pl.fecha, ru.hora, ru.tipo_registro, 
    jor.jornada,pl.id_planeacion, tct.nombretactico, etg.nombreestrategia, 
    tm.temas, compo.comportamientos, compe.competencia, zna.zonas, zna.id_zona, pl.lugarencuentro, mun.municipio, 
    ent.nombreentidad, psna.nombres, psna.apellidos, psna.foto_url, ru.fecha
    ORDER BY psna.nombres, ru.fecha DESC, ru.hora DESC";

    if ($rs = $con->query($sql)) {
        if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
            foreach ($filas as $key => $value) {
                if($value['tipo_registro'] == 'Inicio'){
                    $value['tipo_registro'] = 'Iniciado';
                }else{
                    $value['tipo_registro'] = 'Finalizado';
                }

                if($value['fecha'] == date("Y-m-d")){
                    $value['fecha'] = 'Hoy';   
                }else{
                    $diff = date_diff(date_create($value['fecha']), date_create(date("Y-m-d")));
                    $value['fecha'] = $diff->format("Desde el " .$value['fecha'] . " (Hace %a días) " );
                }

                if($value['competencia'] == null){
                    $value['competencia'] = "";
                    $value['temas'] = "Pendiente";
                    $value['nombreentidad'] = "Pendiente";
                    $value['comportamientos'] = ' ';
                }

                $data['html'][$key] = array(
                    'latitud' => $value['latitud'],
                    'longitud' => $value['longitud'],
                    'nombre' => $value['nombre'],
                    'fecha' => $value['fecha'],
                    'hora' => $value['hora'],
                    'municipio' => $value['municipio'],
                    'tipo_registro' => $value['tipo_registro'],
                    'entidad' => $value['nombreentidad'],
                    'tactico' => $value['nombretactico'],
                    'competencia' => $value['competencia'],
                    'comportamiento' => $value['comportamientos'],
                    'tema' => $value['temas'],
                    'estrategia' => $value['nombreestrategia'],
                    'icon' => $value['foto_url']);
                    
            }
        } else {
            $data['mensaje'] = "Aún no hay datos para mostrar en el mapa";
            $data['error'] = 1;
        }
    } else {
        $data['mensaje'] = "No se realizo la consulta";
        $data['error'] = 1;
    }

    echo json_encode($data);

}

?>
