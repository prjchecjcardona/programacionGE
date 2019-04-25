<?php

function executeQuery($con, $sql)
{
    $result = $con->query($sql);
    if ($result) {
        $data = array();
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            array_push($data, $row);
        }
        return $data;
    } else {
        return $con->errorInfo()[2];
    }
}

function logIn($con, $sql, $pass)
{
    if (empty($sql)) {
        session_start();

        $_SESSION['guest'] = array("nombre" => "invitado", "rol" => 4, "zona" => "all");
        $data = array("error" => 1, "user" => $_SESSION['guest']);

        header("Location: ../opciones.html?user=" . $_SESSION['guest']['rol'] . "&id_zona=" . $_SESSION['guest']['zona']);
        exit();
    } else {
        if ($result = $con->query($sql)) {
            if ($row = $result->fetch(PDO::FETCH_ASSOC)) {

                $pswdCheck = password_verify($pass, $row['passwd']);
                if ($pswdCheck == false) {
                    $data = array("error" => 0, "error_type" => "wrgpswd");

                } elseif ($pswdCheck == true) {
                    session_start();

                    $_SESSION['user'] = array('uid' => $row['cedula'],
                        'pass' => $row['passwd'], 'nombre' => $row['nombres'], 'rol' => $row['id_rol'],
                        'zona' => $row['id_zona']);

                    $data = array("error" => 1, "user" => $_SESSION['user']);

                }
            } else {
                $data = array("error" => 0, "error_type" => "nouser");
            }
        }
    }

    return $data;
}

function insertQuery($con, $sql)
{
    $result = $con->query($sql);
    if ($result) {
        return $data = array("message" => "Guardado con exito!", "error" => 0);
    } else {
        return $data = array("message" => "No se guardó", "error" => 1, "error_message" => $con->errorInfo()[2]);
    }
}

function eliminateQuery($con, $sql)
{
    $result = pg_query($con, $sql);
    if ($result) {
        return $data = array("message" => "Guardado con exito!", "error" => 0);
    } else {
        return $data = array("message" => "No se guardó", "error" => 1, "error_message" => pg_result_error($result));
    }
}

/* CONSULTAS */

function getMunicipiosXZonaQuery($con, $zona)
{
    $sql = "SELECT mn.id_municipio, municipio, zna.zonas, zna.id_zona, count(foc.id_focalizacion) as total
    FROM municipios mn
    JOIN focalizacion foc ON foc.id_municipio = mn.id_municipio
    JOIN zonas zna ON mn.id_zona = zna.id_zona";

    if ($zona != "all") {
        $sql .= " WHERE mn.id_zona = $zona OR mn.id_municipio IN (SELECT id_municipio FROM municipio_x_cercania WHERE " . '"año"' . " = 2019)";
    }

    $sql .= " GROUP BY mn.id_municipio, municipio, zna.zonas, zna.id_zona
    ORDER BY municipio;";

    return executeQuery($con, $sql);
}

function getZonasQuery($con)
{
    $sql = "SELECT zon.id_zona, zonas, nombres || ' ' || apellidos as nombre
    FROM zonas zon
    JOIN asignar_zonas asz ON asz.id_zona = zon.id_zona
    JOIN personas per ON asz.cedula_asignado = per.cedula
    ORDER BY zon.id_zona ASC";

    return executeQuery($con, $sql);
}

function getIndicadoresGEXSubtemaQuery($con, $subtemas)
{
    $ids = implode(', ', $subtemas);

    $sql = "SELECT ige.id_indicador, nombre_indicador, id_subtema
    FROM indicadores_ge ige
    JOIN indicadores_ge_x_subtemas igexs ON igexs.id_indicador = ige.id_indicador
    WHERE id_subtema IN ($ids)";

    return executeQuery($con, $sql);
}

function getEntidadesQuery($con, $id_mun)
{
    $sql = "SELECT id_entidad, nombre_entidad
    FROM entidades
    WHERE id_municipio = $id_mun
    ORDER BY nombre_entidad ASC";

    return executeQuery($con, $sql);
}

function getComportamientosQuery($con)
{
    $sql = "SELECT id_comportamientos, comportamientos, competencia
    FROM comportamientos cpc
    JOIN competencias comp ON cpc.id_competencia = comp.id_competencia";

    return executeQuery($con, $sql);
}

function getFocalizacionesXZonaQuery($con, $mun)
{
    $sql = "SELECT foc.id_focalizacion, mun.id_municipio, id_tipo_gestion, mun.municipio, mun.id_zona, compor.id_comportamientos,
    compor.comportamientos, foc.fecha, compe.competencia, (SELECT count(id_planeacion) FROM planeacion plan WHERE plan.id_focalizacion = foc.id_focalizacion) as total
    FROM focalizacion foc
    LEFT JOIN indicadores_chec_x_focalizacion icxf ON icxf.id_focalizacion= foc.id_focalizacion
    LEFT JOIN indicadores_chec ind ON ind.id_indicador= icxf.id_indicador
    LEFT JOIN comportamientos compor ON compor.id_comportamientos = ind.id_comportamiento
    LEFT JOIN competencias compe ON compe.id_competencia = compor.id_competencia
    JOIN municipios mun ON mun.id_municipio = foc.id_municipio
    WHERE mun.id_municipio = $mun
    GROUP BY foc.id_focalizacion, mun.id_municipio, id_tipo_gestion, mun.municipio, mun.id_zona, compor.id_comportamientos, compor.comportamientos, foc.fecha, compe.competencia
    ORDER BY foc.fecha DESC";

    return executeQuery($con, $sql);
}

function getInformesQuery($con, $comportamiento, $temas, $municipio, $estrategia, $tactico, $tipo, $zona, $month)
{
    if (!empty($comportamiento) && !empty($zona)) {

        $comportamientos = implode(', ', $comportamiento);

        if (is_array($zona)) {
            $sql = "SELECT competencia, zona, SUM(cantidad_participantes) ";
            $group = "GROUP BY zona, competencia ";
            $order = "ORDER BY zona, competencia ";
            $sql .= "FROM cobertura
                    WHERE competencia IN ($comportamientos)
                    AND zona IN (". implode(', ', $zona) .") ";
        } else {
            $sql = "SELECT municipio, SUM(cantidad_participantes) ";
            $group = "GROUP BY municipio, competencia";
            $order = "ORDER BY zona, competencia ";
        }




        if (!empty($temas)) {
            $tem = implode(', ', $temas);
            $sql .= "AND contenido_tematico IN ($tem) ";
        }

        if (!empty($tipo)) {
            $tipo = implode(', ', $tipo);
            $sql .= "AND tipo_actividad IN ($tipo) ";
        }

        if (!empty($month)) {
            $month = implode(', ', $month);
            $sql .= "AND date_part('month', fechas) IN ($month) ";
        }

        $sql .= $group;
        $sql .= $order;
    }

    if (!empty($estrategia) && !empty($zona)) {
        $estrategia = implode(', ', $estrategia);
        $sql = "SELECT municipio, SUM(cantidad_participantes)
        FROM cobertura
        WHERE estrategia IN ($estrategia)";

        if (!empty($tactico)) {
            $tact = implode(', ', $tactico);
            $sql .= "AND tactico IN ($tact) ";
        }

        $sql .= "GROUP BY " . $group;
    }

    return executeQuery($con, $sql);
}

function getMunicipioInformeQuery($con)
{
    $sql = "SELECT DISTINCT municipio
    FROM cobertura
    ORDER BY municipio ASC";

    return executeQuery($con, $sql);
}

function getPlaneacionesXFocalizacionQuery($con, $foc)
{
    $sql = "SELECT DISTINCT pl.id_planeacion, tg.tipo_gestion, estrat.nombre_estrategia, pl.lugar_encuentro,
    tem.temas, pl.fecha_plan, pl.fecha_registro, zon.id_zona, ent.nombre_entidad, foc.id_tipo_gestion,
    pl.id_focalizacion, COUNT(eje.id_ejecucion) as ejecucion, competencia
	FROM planeacion pl
    LEFT JOIN focalizacion foc ON pl.id_focalizacion = foc.id_focalizacion
    LEFT JOIN subtemas_x_planeacion sxp ON sxp.id_planeacion = pl.id_planeacion
	LEFT JOIN subtemas sutem ON sutem.id_subtema = sxp.id_subtema
    LEFT JOIN temas tem ON tem.id_temas = sutem.id_temas
    LEFT JOIN tacticos_x_planeacion txp ON txp.id_planeacion = pl.id_planeacion
    LEFT JOIN tactico tact ON txp.id_tactico = tact.id_tactico
    LEFT JOIN estrategias estrat ON estrat.id_estrategia = tact.id_estrategia
    LEFT JOIN tipo_gestion tg ON tg.id_tipo_gestion = foc.id_tipo_gestion
	LEFT JOIN municipios mun ON mun.id_municipio = foc.id_municipio
    LEFT JOIN entidades ent ON pl.id_entidad = ent.id_entidad
	LEFT JOIN zonas zon ON mun.id_zona = zon.id_zona
	LEFT JOIN ejecucion eje ON eje.id_planeacion = pl.id_planeacion
    LEFT JOIN planeacion_institucional pli ON pli.id_planeacion = pl.id_planeacion
	LEFT JOIN comportamientos compor ON pli.id_comportamiento = compor.id_comportamientos
	LEFT JOIN competencias compe ON compor.id_competencia = compe.id_competencia
    WHERE pl.id_focalizacion = '$foc'
	GROUP BY pl.id_planeacion, tg.tipo_gestion, estrat.nombre_estrategia,
    tem.temas, pl.fecha_plan, pl.fecha_registro, zon.id_zona, pl.id_focalizacion, ent.nombre_entidad,
    pl.lugar_encuentro, foc.id_tipo_gestion, competencia
    ORDER BY pl.fecha_plan DESC";

    return executeQuery($con, $sql);
}

function getBarriosQuery($con, $id_mun)
{
    $sql = "SELECT id_barrio, barrio
    FROM barrios bar
    JOIN comunas com ON com.id_comuna = bar.id_comuna
    WHERE com.id_municipio = $id_mun";

    return executeQuery($con, $sql);
}

function getTipoGestionQuery($con, $id_foc)
{
    $sql = "SELECT id_tipo_gestion
    FROM focalizacion
    WHERE id_focalizacion = $id_foc";

    return executeQuery($con, $sql);
}

function getVeredasQuery($con, $id_mun)
{
    $sql = "SELECT id_veredas, vereda
    FROM veredas
    WHERE id_municipio = $id_mun";

    return executeQuery($con, $sql);
}

function getEstrategiasQuery($con)
{
    $sql = "SELECT * FROM estrategias
    WHERE id_estrategia <> 6";

    return executeQuery($con, $sql);
}

function getContactosQuery($con, $mun)
{
    $sql = "SELECT con.id_contacto, cedula, nombres as nombre, con.telefono, cargo, nombre_entidad, con.id_municipio
    FROM contacto con
    LEFT JOIN entidades ent ON con.id_entidad = ent.id_entidad
    LEFT JOIN municipios mun ON mun.id_municipio = ent.id_municipio
    WHERE mun.id_municipio = $mun OR con.id_municipio = $mun";

    return executeQuery($con, $sql);
}

function getFicherosQuery($con)
{
    $sql = "SELECT codigo FROM ficheros";

    return executeQuery($con, $sql);
}

function getTacticosQuery($con, $estrat, $cercania)
{
    $sql = "SELECT id_tactico, nombre_tactico
    FROM tactico
    WHERE id_estrategia = $estrat
    AND id_tactico NOT IN(2,8,9,13,16,17,18,20,
    21,23,24,30,31,32,33,34,3,15)
    AND cercania = $cercania";

    return executeQuery($con, $sql);
}

function getTacticosCercania($con, $estrat)
{
    $sql = "SELECT id_tactico, nombre_tactico
    FROM tactico
    WHERE id_estrategia = $estrat
    AND id_tactico NOT IN(2,8,9,13,16,17,18,20,
    21,23,24,30,31,32,33,34,3,15)
    AND cercania = true";

    return executeQuery($con, $sql);
}

function getTemasQuery($con, $compor)
{
    $sql = "SELECT id_temas, temas
    FROM temas ";

    if (!empty($compor)) {
        $sql .= "WHERE id_comportamiento = $compor";
    }

    return executeQuery($con, $sql);
}

function loginQuery($con, $uid, $psswd)
{
    if (empty($uid)) {
        $sql = "";
    } else {
        $sql = "SELECT per.cedula, per.id_rol, rol.cargo, per.email, per.usuario, per.passwd, per.foto_url, asz.id_zona, per.nombres
        FROM personas per
        LEFT JOIN asignar_zonas asz ON asz.cedula_asignado = per.cedula
        JOIN roles rol ON rol.id_cargo = per.id_rol
        WHERE email = '" . $uid . "' OR usuario = '" . $uid . "' ";
    }

    return logIn($con, $sql, $psswd);
}

function getIndicadoresChecQuery($con, $comp)
{
    $sql = "SELECT id_indicador, indicador
    FROM indicadores_chec
    WHERE id_comportamiento = $comp";

    return executeQuery($con, $sql);
}

function eliminarEjecucionQuery($con, $id_plan)
{
    $sqlbase = "SELECT id_ejecucion
    FROM ejecucion
    WHERE id_planeacion = $id_plan";

    $sql = "DELETE FROM tipo_poblacion_x_ejecucion tpxe WHERE tpxe.id_ejecucion IN ($sqlbase);
    DELETE FROM caracteristicas_poblacion_x_ejecucion cpxe WHERE cpxe.id_ejecucion IN ($sqlbase);
    DELETE FROM ejecucion WHERE id_ejecucion IN ($sqlbase);";

    return eliminateQuery($con, $sql);
}

function eliminarContactoQuery($con, $id_contacto)
{
    $sql = "DELETE FROM contactos_x_planeacion WHERE id_contacto = $id_contacto;
    DELETE FROM contacto WHERE id_contacto = $id_contacto;";

    return eliminateQuery($con, $sql);
}

function eliminarPlaneacionQuery($con, $id_plan)
{
    $sql = "DELETE FROM registro_ubicacion WHERE id_planeacion IN ($id_plan);
    DELETE FROM tacticos_x_planeacion WHERE id_planeacion IN ($id_plan);
    DELETE FROM subtemas_x_planeacion WHERE id_planeacion IN ($id_plan);
    DELETE FROM planeacion_institucional WHERE id_planeacion IN ($id_plan);
    DELETE FROM contactos_x_planeacion WHERE id_planeacion IN ($id_plan);
    DELETE FROM registros_x_planeacion WHERE id_planeacion IN ($id_plan);
    DELETE FROM ejecucion WHERE id_planeacion IN ($id_plan);
    DELETE FROM planeacion WHERE id_planeacion IN ($id_plan)";

    return eliminateQuery($con, $sql);
}


function getDetallePlaneacionEjecucionQuery($con, $id_plan)
{
    $sql = "SELECT DISTINCT fecha_plan, municipio, nombres || ' ' || apellidos as nombre, zonas, nombre_entidad, comportamientos, competencia, nombre_estrategia, temas, nombre_tactico
	FROM planeacion pl
    JOIN focalizacion foc ON pl.id_focalizacion = foc.id_focalizacion
    LEFT JOIN subtemas_x_planeacion sxp ON sxp.id_planeacion = pl.id_planeacion
	LEFT JOIN subtemas sutem ON sutem.id_subtema = sxp.id_subtema
    LEFT JOIN temas tem ON tem.id_temas = sutem.id_temas
    LEFT JOIN tacticos_x_planeacion txp ON txp.id_planeacion = pl.id_planeacion
    LEFT JOIN tactico tact ON txp.id_tactico = tact.id_tactico
    LEFT JOIN estrategias estrat ON estrat.id_estrategia = tact.id_estrategia
    LEFT JOIN tipo_gestion tg ON tg.id_tipo_gestion = foc.id_tipo_gestion
	LEFT JOIN municipios mun ON mun.id_municipio = foc.id_municipio
	LEFT JOIN zonas zon ON mun.id_zona = zon.id_zona
	LEFT JOIN asignar_zonas az ON az.id_zona = zon.id_zona
	LEFT JOIN personas per ON per.cedula = az.cedula_asignado
	LEFT JOIN entidades ent ON pl.id_entidad = ent.id_entidad
	LEFT JOIN indicadores_chec_x_focalizacion ixf ON ixf.id_focalizacion = foc.id_focalizacion
	LEFT JOIN indicadores_chec ic ON ic.id_indicador = ixf.id_indicador
	LEFT JOIN comportamientos compor ON ic.id_comportamiento = compor.id_comportamientos OR tem.id_comportamiento = compor.id_comportamientos
	LEFT JOIN competencias compe ON compor.id_competencia = compe.id_competencia
    WHERE pl.id_planeacion = $id_plan";

    return executeQuery($con, $sql);
}

function getMaxIdFocQuery($con)
{
    $sql = "SELECT MAX(id_focalizacion) FROM focalizacion";

    return executeQuery($con, $sql);
}

function getMaxIdEjecQuery($con)
{
    $sql = "SELECT MAX(id_ejecucion) FROM ejecucion";

    return executeQuery($con, $sql);
}

function getMaxIdPlanQuery($con)
{
    $sql = "SELECT MAX(id_planeacion) FROM planeacion";

    return executeQuery($con, $sql);
}

function getMaxIdTAdminQuery($con)
{
    $sql = "SELECT MAX(id_trabajo_administrativo) FROM trabajo_administrativo";

    return executeQuery($con, $sql);
}

/* INFORMES  */
function getTacticosPorEstrategiaCoberturaQuery($con, $estrategia)
{
    $sql = "SELECT estrategia FROM cobertura WHERE estrategia = '$estrategia'";

    return executeQuery($con, $sql);
}

function getTemasPorComportamientoCoberturaQuery($con, $competencia)
{
    $sql = "SELECT tema FROM cobertura WHERE competencia = '$competencia'";

    return executeQuery($con, $sql);
}

function getPoblacionXEjecucionQuery($con, $id_plan) {

    $sql = "SELECT id_tipo, total
    FROM tipo_poblacion_x_ejecucion tpxe
    JOIN ejecucion eje ON eje.id_ejecucion = tpxe.id_ejecucion
    WHERE eje.id_planeacion = $id_plan";

    return executeQuery($con, $sql);
}

function getCaracteristicasXEjecucionQuery($con, $id_plan) {
    $sql = "SELECT id_caracteristica, total
    FROM caracteristicas_poblacion_x_ejecucion cpxe
    JOIN ejecucion eje ON eje.id_ejecucion = cpxe.id_ejecucion
    WHERE eje.id_planeacion = $id_plan";

    return executeQuery($con, $sql);
}

/* ////////// */

function getPlaneacionesCalendarQuery($con, $zona)
{
    $where = FALSE;

    $sql = "SELECT DISTINCT plan.id_planeacion, fecha_plan, jornada, lugar_encuentro, mun.municipio, plan.estado, tg.tipo_gestion,
	foc.id_focalizacion, bar.barrio, ver.vereda, compor.comportamientos, compe.competencia, zon.zonas, zon.id_zona, nombre_estrategia, nombre_tactico, temas,
	per.nombres || ' ' || per.apellidos as nombre, solicitud_interventora, rxp.url, foc.id_tipo_gestion
    FROM planeacion plan
    LEFT JOIN planeacion_institucional plani ON plani.id_planeacion = plan.id_planeacion
    LEFT JOIN barrios bar ON bar.id_barrio = plan.id_barrio
    LEFT JOIN comunas com ON bar.id_comuna = com.id_comuna
    LEFT JOIN veredas ver ON ver.id_veredas = plan.id_vereda
    LEFT JOIN municipios mun ON mun.id_municipio = com.id_municipio OR mun.id_municipio = ver.id_municipio
    LEFT JOIN focalizacion foc ON foc.id_focalizacion = plan.id_focalizacion
    LEFT JOIN indicadores_chec_x_focalizacion icxf ON icxf.id_focalizacion = foc.id_focalizacion
    LEFT JOIN indicadores_chec ic ON ic.id_indicador = icxf.id_indicador
    LEFT JOIN zonas zon ON zon.id_zona = mun.id_zona
	LEFT JOIN asignar_zonas az ON az.id_zona = zon.id_zona
    LEFT JOIN personas per ON per.cedula = az.cedula_asignado
    LEFT JOIN subtemas_x_planeacion sxp ON sxp.id_planeacion = plan.id_planeacion
	LEFT JOIN subtemas sutem ON sutem.id_subtema = sxp.id_subtema
    LEFT JOIN temas tem ON tem.id_temas = sutem.id_temas
    LEFT JOIN comportamientos compor ON compor.id_comportamientos = ic.id_comportamiento
    OR compor.id_comportamientos = tem.id_comportamiento OR plani.id_comportamiento = compor.id_comportamientos
    LEFT JOIN competencias compe ON compe.id_competencia = compor.id_competencia
    LEFT JOIN tacticos_x_planeacion txp ON txp.id_planeacion = plan.id_planeacion
    LEFT JOIN tactico tact ON txp.id_tactico = tact.id_tactico
    LEFT JOIN estrategias estrat ON estrat.id_estrategia = tact.id_estrategia
    LEFT JOIN tipo_gestion tg ON tg.id_tipo_gestion = foc.id_tipo_gestion
    LEFT JOIN registros_x_planeacion rxp ON rxp.id_planeacion = plan.id_planeacion";

    if ($zona != 'all') {
        $sql .= " WHERE zon.id_zona = $zona";
        $where = TRUE;
    }

    if($where) {
        $sql .= " AND fecha_plan BETWEEN '2019-01-01' AND now() + interval '1 year' AND plan.estado = 'Planeado' OR rxp.id_tipo_registro = 2";
    }else {
        $sql .= " WHERE fecha_plan BETWEEN '2019-01-01' AND now() + interval '1 year' AND plan.estado = 'Planeado' OR rxp.id_tipo_registro = 2";
    }


    $sql .= " ORDER BY plan.id_planeacion ASC";

    return executeQuery($con, $sql);
}

function contactoExisteQuery($con, $cedula)
{
    $sql = "SELECT id_contacto, cedula
    FROM contacto
    WHERE cedula = $cedula";

    return executeQuery($con, $sql);
}

function getSubtemasXTemaQuery($con, $id_tema)
{
    $sql = "SELECT id_subtema, subtemas, id_temas
    FROM subtemas
    WHERE id_temas = $id_tema";

    return executeQuery($con, $sql);
}

function checkCompetenciasFocalizacionQuery($con, $mun)
{
    $sql = "SELECT DISTINCT id_comportamientos, comportamientos, competencia
    FROM comportamientos compor
    JOIN competencias compe ON compe.id_competencia = compor.id_comportamientos
    JOIN indicadores_chec ic ON ic.id_comportamiento = compor.id_comportamientos
    JOIN indicadores_chec_x_focalizacion icxf ON icxf.id_indicador = ic.id_indicador
    JOIN focalizacion foc ON foc.id_focalizacion = icxf.id_focalizacion
    WHERE compor.id_comportamientos NOT IN (SELECT compor.id_comportamientos
                                            FROM focalizacion foc
                                            JOIN indicadores_chec_x_focalizacion icxf ON foc.id_focalizacion = icxf.id_focalizacion
                                            JOIN indicadores_chec ic ON icxf.id_indicador = ic.id_indicador
                                            JOIN comportamientos compor ON ic.id_comportamiento = compor.id_comportamientos
                                            WHERE id_municipio = $mun)";

    return executeQuery($con, $sql);
}

function getPlaneacionesEjecutadosOEnEjecucionQuery($con, $zona)
{
    $sql = "SELECT DISTINCT plan.id_planeacion, fecha_plan, jornada, lugar_encuentro, mun.municipio,
	foc.id_focalizacion, bar.barrio, ver.vereda, compor.comportamientos, compe.competencia, zon.zonas, zon.id_zona, nombre_estrategia, nombre_tactico, temas, tg.tipo_gestion,
	per.nombres || ' ' || per.apellidos as nombre, plan.estado, hora, etapa_planeacion, plan.solicitud_interventora, rxp.url, foc.id_tipo_gestion, ejec.hora_inicio, ejec.hora_fin, ejec.id_ejecucion
    FROM planeacion plan
    LEFT JOIN planeacion_institucional plani ON plani.id_planeacion = plan.id_planeacion
    LEFT JOIN barrios bar ON bar.id_barrio = plan.id_barrio
    LEFT JOIN comunas com ON bar.id_comuna = com.id_comuna
    LEFT JOIN veredas ver ON ver.id_veredas = plan.id_vereda
    LEFT JOIN focalizacion foc ON foc.id_focalizacion = plan.id_focalizacion
    LEFT JOIN indicadores_chec_x_focalizacion icxf ON icxf.id_focalizacion = foc.id_focalizacion
    LEFT JOIN municipios mun ON mun.id_municipio = com.id_municipio OR mun.id_municipio = ver.id_municipio OR foc.id_municipio = mun.id_municipio
    LEFT JOIN indicadores_chec ic ON ic.id_indicador = icxf.id_indicador
    LEFT JOIN zonas zon ON zon.id_zona = mun.id_zona
	LEFT JOIN asignar_zonas az ON az.id_zona = zon.id_zona
    LEFT JOIN personas per ON per.cedula = az.cedula_asignado
	LEFT JOIN subtemas_x_planeacion sxp ON sxp.id_planeacion = plan.id_planeacion
	LEFT JOIN subtemas sutem ON sutem.id_subtema = sxp.id_subtema
    LEFT JOIN temas tem ON tem.id_temas = sutem.id_temas
    LEFT JOIN comportamientos compor ON compor.id_comportamientos = ic.id_comportamiento
    OR compor.id_comportamientos = tem.id_comportamiento OR plani.id_comportamiento = compor.id_comportamientos
    LEFT JOIN competencias compe ON compe.id_competencia = compor.id_competencia
    LEFT JOIN tacticos_x_planeacion txp ON txp.id_planeacion = plan.id_planeacion
    LEFT JOIN tactico tact ON txp.id_tactico = tact.id_tactico
    LEFT JOIN estrategias estrat ON estrat.id_estrategia = tact.id_estrategia
    LEFT JOIN tipo_gestion tg ON tg.id_tipo_gestion = foc.id_tipo_gestion
    LEFT JOIN registro_ubicacion ru ON ru.id_planeacion = plan.id_planeacion
	LEFT JOIN registros_x_planeacion rxp ON rxp.id_planeacion = plan.id_planeacion
    LEFT JOIN ejecucion ejec ON ejec.id_planeacion = plan.id_planeacion
    WHERE fecha_plan BETWEEN '2019-01-01' AND now() + interval '1 year' AND plan.estado = 'En Ejecución'
    OR plan.estado = 'Ejecutado' OR rxp.id_tipo_registro = 2";

    if ($zona != 'all') {
        $sql .= " AND zon.id_zona = $zona";
    }

    $sql .= " ORDER BY plan.id_planeacion ASC";

    return executeQuery($con, $sql);
}

function getDetalleEjecucionQuery($con, $id_plan)
{
    $sql = "SELECT DISTINCT ejec.id_ejecucion, ejec.fecha, hora_inicio, hora_fin, tipo_ejecucion, resultado_ejecucion, descripcion_resultado, tipo_gestion, foc.id_tipo_gestion
    FROM ejecucion ejec
    LEFT JOIN resultado_ejecucion rejec ON rejec.id_resultado_ejecucion = ejec.id_resultado_ejecucion
    JOIN planeacion plan ON plan.id_planeacion = ejec.id_planeacion
    JOIN focalizacion foc ON foc.id_focalizacion = plan.id_focalizacion
    LEFT JOIN tipo_gestion tg ON tg.id_tipo_gestion = foc.id_tipo_gestion
    WHERE plan.id_planeacion = $id_plan";

    return executeQuery($con, $sql);
}

function editarContactoQuery($con, $id_contacto, $cedula, $nombres, $apellidos, $email, $telefono, $celular, $cargo, $entidad)
{
    $sql = "UPDATE public.contacto
	SET cedula='$cedula', nombres='$nombres', apellidos='$apellidos', correo='$email', telefono='$telefono', celular='$telefono', cargo='$cargo', id_entidad=$entidad
    WHERE id_contacto = $id_contacto; ";

    return executeQuery($con, $sql);
}

function getContactosXPlaneacionQuery($con, $id_plan)
{
    $sql = "SELECT con.id_contacto, nombres, apellidos, correo, celular, cargo
    FROM contacto con
    JOIN contactos_x_planeacion cxp ON con.id_contacto = cxp.id_contacto
    WHERE id_planeacion = $id_plan";

    return executeQuery($con, $sql);
}

function getContactoEditarQuery($con, $id_contacto) {
    $sql = "SELECT con.*, ent.nombre_entidad
    FROM contacto con
    LEFT JOIN entidades ent ON ent.id_entidad = con.id_entidad
    WHERE con.id_contacto = $id_contacto";

    return executeQuery($con, $sql);
}

function getNovedadesNoEjecucionQuery($con, $zona)
{
    $sql = "SELECT DISTINCT ON (plan.id_planeacion) plan.id_planeacion, nne.fecha_no_ejecutada, nne.fecha_aplazamiento, jornada, lugar_encuentro, mun.municipio,
    foc.id_focalizacion, bar.barrio, ver.vereda, compor.comportamientos,
    compe.competencia, zon.zonas, zon.id_zona, per.nombres || ' ' || per.apellidos as nombre,
    temas, nombre_estrategia
        FROM planeacion plan
        LEFT JOIN planeacion_institucional plani ON plani.id_planeacion = plan.id_planeacion
        LEFT JOIN novedad_no_ejecucion nne ON plan.id_planeacion = nne.id_planeacion
        LEFT JOIN barrios bar ON bar.id_barrio = plan.id_barrio
        LEFT JOIN comunas com ON bar.id_comuna = com.id_comuna
        LEFT JOIN veredas ver ON ver.id_veredas = plan.id_vereda
        LEFT JOIN municipios mun ON mun.id_municipio = com.id_municipio OR mun.id_municipio = ver.id_municipio
        LEFT JOIN focalizacion foc ON foc.id_focalizacion = plan.id_focalizacion
        LEFT JOIN indicadores_chec_x_focalizacion icxf ON icxf.id_focalizacion = foc.id_focalizacion
        LEFT JOIN indicadores_chec ic ON ic.id_indicador = icxf.id_indicador
        LEFT JOIN zonas zon ON zon.id_zona = mun.id_zona
        JOIN asignar_zonas az ON az.id_zona = zon.id_zona
        JOIN personas per ON per.cedula = az.cedula_asignado
        LEFT JOIN subtemas_x_planeacion sxp ON sxp.id_planeacion = plan.id_planeacion
	    LEFT JOIN subtemas sutem ON sutem.id_subtema = sxp.id_subtema
        LEFT JOIN temas tem ON tem.id_temas = sutem.id_temas
        LEFT JOIN comportamientos compor ON compor.id_comportamientos = ic.id_comportamiento
        OR compor.id_comportamientos = tem.id_comportamiento OR plani.id_comportamiento = compor.id_comportamientos
        JOIN competencias compe ON compe.id_competencia = compor.id_competencia
        JOIN tacticos_x_planeacion txp ON txp.id_planeacion = plan.id_planeacion
        JOIN tactico tact ON txp.id_tactico = tact.id_tactico
        JOIN estrategias estrat ON estrat.id_estrategia = tact.id_estrategia
        JOIN tipo_gestion tg ON tg.id_tipo_gestion = foc.id_tipo_gestion
        WHERE plan.id_planeacion IN(SELECT id_planeacion FROM novedad_no_ejecucion WHERE estado_novedad = 'No ejecutado')
        AND plan.id_planeacion NOT IN (SELECT id_planeacion FROM ejecucion)";

    if ($zona != 'all') {
        $sql .= " AND zon.id_zona = $zona";
    }

    $sql .= " ORDER BY plan.id_planeacion, fecha_no_ejecutada DESC NULLS LAST";

    return executeQuery($con, $sql);
}

function getTrabajosAdministrativosCalendarQuery($con)
{
    $sql = "SELECT ta.id_trabajo_administrativo, municipio, ta.fecha, hora_inicio, hora_fin, zonas, zon.id_zona, labor, id_labor, per.nombres || ' ' || per.apellidos as nombre
    FROM trabajo_administrativo ta
    JOIN municipios mun ON mun.id_municipio = ta.id_municipio
    JOIN zonas zon ON zon.id_zona = mun.id_zona
	JOIN labores_x_trabajo_administrativo lxta ON lxta.id_trabajo_administrativo = ta.id_trabajo_administrativo
	JOIN tipo_labor tl ON tl.id_labor = lxta.id_tipo_labor
	JOIN asignar_zonas az ON az.id_zona = zon.id_zona
	JOIN personas per ON per.cedula = az.cedula_asignado";

    return executeQuery($con, $sql);
}

function getTotalAsistentesQuery($con, $id_plan)
{
    $sql = "SELECT tipo, total
	FROM tipo_poblacion_x_ejecucion tpxe
	JOIN tipo_poblacion tp ON tp.id_tipo = tpxe.id_tipo
    WHERE id_ejecucion IN (SELECT id_ejecucion FROM ejecucion WHERE id_planeacion = $id_plan)";

    return executeQuery($con, $sql);
}

function getPlaneacionesGeoAppQuery($con, $zona)
{

    date_default_timezone_set('America/Bogota');

    $current_date = date('Y-m-d');

    $sql = "SELECT DISTINCT pl.id_planeacion, fecha_plan, municipio, nombres || ' ' || apellidos as nombre, zonas, nombre_entidad, comportamientos, competencia, nombre_estrategia, temas, nombre_tactico, pl.estado
	FROM planeacion pl
    LEFT JOIN planeacion_institucional plani ON plani.id_planeacion = pl.id_planeacion
    JOIN focalizacion foc ON pl.id_focalizacion = foc.id_focalizacion
    LEFT JOIN subtemas_x_planeacion sxp ON sxp.id_planeacion = pl.id_planeacion
	LEFT JOIN subtemas sutem ON sutem.id_subtema = sxp.id_subtema
    LEFT JOIN temas tem ON tem.id_temas = sutem.id_temas
    LEFT JOIN tacticos_x_planeacion txp ON txp.id_planeacion = pl.id_planeacion
    LEFT JOIN tactico tact ON txp.id_tactico = tact.id_tactico
    LEFT JOIN estrategias estrat ON estrat.id_estrategia = tact.id_estrategia
    LEFT JOIN tipo_gestion tg ON tg.id_tipo_gestion = foc.id_tipo_gestion
	LEFT JOIN municipios mun ON mun.id_municipio = foc.id_municipio
	LEFT JOIN zonas zon ON mun.id_zona = zon.id_zona
	LEFT JOIN asignar_zonas az ON az.id_zona = zon.id_zona
	LEFT JOIN personas per ON per.cedula = az.cedula_asignado
	LEFT JOIN entidades ent ON pl.id_entidad = ent.id_entidad
	LEFT JOIN indicadores_chec_x_focalizacion ixf ON ixf.id_focalizacion = foc.id_focalizacion
	LEFT JOIN indicadores_chec ic ON ic.id_indicador = ixf.id_indicador
	LEFT JOIN comportamientos compor ON ic.id_comportamiento = compor.id_comportamientos
    OR tem.id_comportamiento = compor.id_comportamientos OR plani.id_comportamiento = compor.id_comportamientos
	LEFT JOIN competencias compe ON compor.id_competencia = compe.id_competencia
    WHERE fecha_plan = '$current_date' ";

    if (!empty($zona)) {
        $sql .= "AND zon.id_zona = $zona";
    }

    return executeQuery($con, $sql);
}

function getTacticosInformesQuery($con, $estrategia)
{
    $sql = "SELECT DISTINCT tactico
    FROM cobertura";

    if (!empty($estrategia)) {
        $sql .= " WHERE estrategia IN ('$estrategia')";
    }

    $sql .= " ORDER BY tactico ASC";

    return executeQuery($con, $sql);
}

function getUserRolQuery($con)
{
    $sql = "SELECT email, usuario, id_rol
    FROM personas";

    return executeQuery($con, $sql);
}

function checkGestionQuery($con, $id_foc)
{
    $sql = "SELECT id_tipo_gestion
    FROM focalizacion
    WHERE id_focalizacion = $id_foc";

    return executeQuery($con, $sql);
}

function checkFocalizacionQuery($con, $id_mun, $comp)
{
    $sql = "SELECT DISTINCT mun.municipio, compe.competencia
    FROM focalizacion foc
    LEFT JOIN indicadores_chec_x_focalizacion icxf ON icxf.id_focalizacion= foc.id_focalizacion
    LEFT JOIN indicadores_chec ind ON ind.id_indicador= icxf.id_indicador
    LEFT JOIN comportamientos compor ON compor.id_comportamientos = ind.id_comportamiento
    LEFT JOIN competencias compe ON compe.id_competencia = compor.id_competencia
    JOIN municipios mun ON mun.id_municipio = foc.id_municipio
    WHERE mun.id_municipio = $id_mun AND compor.id_comportamientos = $comp";

    return executeQuery($con, $sql);
}

function ejecucion_planeacionQuery($con, $id_plan)
{
    $sql = "SELECT id_ejecucion
    FROM ejecucion
    WHERE id_planeacion = $id_plan";

    return executeQuery($con, $sql);
}

function checkRegistrosQuery($con, $id_plan, $tipo)
{
    if (is_array($tipo)) {
        $tipo = implode(', ', $tipo);
    }

    $sql = "SELECT DISTINCT rp.id_planeacion, tr.id_tipo_registro, url
    FROM registros_x_planeacion rp
    JOIN tipo_registro tr ON tr.id_tipo_registro = rp.id_tipo_registro
    WHERE id_planeacion = $id_plan AND rp.id_tipo_registro IN ($tipo)";

    return executeQuery($con, $sql);
}

function getGuiasPlaneacionQuery($con, $subtema)
{
    $sql = "SELECT id_guia, 'banco' || '/' || rec.recurso_url || '/' || nombre ||'.pdf' AS fichero_url, nombre
    FROM guias as gui
    JOIN recursos rec ON rec.id_recurso = gui.id_recurso
    WHERE gui.id_subtema IN ($subtema) ";

    return executeQuery($con, $sql);
}

/* function getGuias($con, $id_plan)
{
    $sql = ""
}
 */
function getUrlArchivosPlanQuery($con, $id_plan, $id_tipo_registro)
{
    $sql = "SELECT id_registro, url, id_tipo_registro
    FROM registros_x_planeacion
    WHERE id_planeacion = $id_plan";

    if (!empty($id_tipo_registro)) {
        $sql .= " AND id_tipo_registro = $id_tipo_registro";
    }

    return executeQuery($con, $sql);
}

/* INSERTS */

function insertIndicadoresXFocalizacionQuery($con, $id_focalizacion, $id_indicador)
{
    $sql = "INSERT INTO public.indicadores_chec_x_focalizacion(id_indicador, id_focalizacion)
    VALUES ($id_indicador, $id_focalizacion);";

    return insertQuery($con, $sql);
}

function insertFocalizacionQuery($con, $id_mun, $id_tipoGestion, $fecha)
{
    $sql = "INSERT INTO public.focalizacion(id_municipio, id_tipo_gestion, fecha)
    VALUES ($id_mun, $id_tipoGestion, '$fecha');";

    return insertQuery($con, $sql);

}

function insertPlaneacionQuery($con, $jornada, $lugar_encuentro, $id_barrio, $id_vereda, $id_entidad, $fecha_plan, $fecha_registro, $id_foc, $solicitud, $estado)
{
    $sql = "INSERT INTO public.planeacion(
	jornada, lugar_encuentro, id_barrio, id_vereda, id_entidad, fecha_plan, fecha_registro, id_focalizacion, solicitud_interventora, estado)
    VALUES ('$jornada', '$lugar_encuentro', $id_barrio, $id_vereda, $id_entidad, '$fecha_plan', '$fecha_registro', $id_foc, '$solicitud', '$estado');";

    return insertQuery($con, $sql);
}

function insertEntidadQuery($con, $nombre, $direccion, $telefono, $tipoEntidad, $municipio)
{
    $sql = "INSERT INTO public.entidades(
    nombre_entidad, direccion, telefono, id_tipo_entidad, id_municipio)
    VALUES ('$nombre', '$direccion', '$telefono', $tipoEntidad, $municipio);";

    return insertQuery($con, $sql);
}

function insertContactoQuery($con, $cedula, $nombres, $apellidos, $correo, $telefono, $celular, $cargo, $id_entidad)
{
    $sql = "INSERT INTO public.contacto(
    id_contacto, cedula, nombres, apellidos, correo, telefono, celular, cargo, id_entidad)
    VALUES (nextval('seq_contacto'), '$cedula', '$nombres', '$apellidos', '$correo', '$telefono', '$celular', '$cargo', $id_entidad);";

    return insertQuery($con, $sql);
}

function getMunicipioIdPlanQuery($con, $id_plan)
{
    $sql = "SELECT foc.id_municipio
    FROM focalizacion foc
    JOIN planeacion plan ON plan.id_focalizacion = foc.id_focalizacion
    WHERE id_planeacion = $id_plan";

    return executeQuery($con, $sql);
}

function getNodosQuery($con, $id_plan)
{
    $sql = "SELECT nod.*
    FROM nodo nod
    WHERE nod.id_municipio IN (SELECT foc.id_municipio
        FROM focalizacion foc
        JOIN planeacion plan ON plan.id_focalizacion = foc.id_focalizacion
        WHERE id_planeacion = $id_plan)";

    return executeQuery($con, $sql);
}

function addNodosQuery($con, $nodo, $latitud, $longitud, $id_municipio)
{
    $sql = "INSERT INTO public.nodo(
    id_nodo, nodo, latitud, longitud, id_municipio)
    VALUES ((nextval('seq_nodo'), '$nodo', '$latitud', '$longitud', $id_municipio);";

    return insertQuery($con, $sql);
}

function insertContactosXEntidadQuery($con, $cedula, $entidad)
{
    $sql = "INSERT INTO public.contactos_x_entidad(cedula, id_entidad)
    VALUES ('$cedula', $entidad);";

    return insertQuery($con, $sql);
}

function insertEjecucionQuery($con, $fecha, $hora_inicio, $hora_fin, $id_resultado, $descripcion, $id_planeacion, $total_asist, $tipo_ejecucion, $nodo)
{
    $sql = "INSERT INTO public.ejecucion(
    id_ejecucion, fecha, hora_inicio, hora_fin, id_resultado_ejecucion, descripcion_resultado, id_planeacion, total_asistentes, tipo_ejecucion, id_nodo)
    VALUES (nextval('seq_ejecucion'), '$fecha', '$hora_inicio', '$hora_fin', $id_resultado, '$descripcion', $id_planeacion, $total_asist, '$tipo_ejecucion', '$nodo');";

    return insertQuery($con, $sql);
}

function insertXPlaneacionQuery($con, $id_param, $id_plan, $name)
{

    if ($name == 'subtema') {
        $sql = "INSERT INTO public.subtemas_x_planeacion(
        id_subtema, id_planeacion)
        VALUES ($id_param, $id_plan);";

        return insertQuery($con, $sql);
    }

    if ($name == 'contacto') {
        $sql = "INSERT INTO public.contactos_x_planeacion(
        id_contacto, id_planeacion)
        VALUES ($id_param, $id_plan);";

        return insertQuery($con, $sql);
    }

    if ($name == 'tactico[]') {

        $sql = "INSERT INTO public.tacticos_x_planeacion(
            id_planeacion, id_tactico)
            VALUES ($id_plan, $id_param);";

        return insertQuery($con, $sql);

    }

}

function insertTrabajoAdministrativoQuery($con, $hora_inicio, $hora_fin, $id_municipio, $fecha, $descripcion)
{
    $sql = "INSERT INTO public.trabajo_administrativo(
    hora_inicio, hora_fin, id_municipio, fecha, descripcion)
    VALUES ('$hora_inicio', '$hora_fin', $id_municipio, '$fecha', '$descripcion');";

    return insertQuery($con, $sql);
}

function insertLaborXTrabajoAdministrativoQuery($con, $id_labor, $id_ta)
{
    $sql = "INSERT INTO public.labores_x_trabajo_administrativo(
    id_tipo_labor, id_trabajo_administrativo)
    VALUES ($id_labor, $id_ta);";

    return insertQuery($con, $sql);
}

function insertNovedadNoEjecucionQuery($con, $id_planeacion, $descripcion, $fecha_aplazamiento, $fecha_plan)
{
    if(empty($fecha_aplazamiento)){
        $sql = "INSERT INTO public.novedad_no_ejecucion(
            id_planeacion, descripcion, estado_novedad, fecha_no_ejecutada)
            VALUES ($id_planeacion, '$descripcion', 'No ejecutado', '$fecha_plan');";
    }else {
        $sql = "INSERT INTO public.novedad_no_ejecucion(
            id_planeacion, descripcion, fecha_aplazamiento, estado_novedad, fecha_no_ejecutada)
            VALUES ($id_planeacion, '$descripcion', '$fecha_aplazamiento', 'No ejecutado', '$fecha_plan');";
    }

    return insertQuery($con, $sql);
}

/* UPDATES */
function aplazarPlaneacionQuery($con, $id_plan, $fecha_plan)
{
    $sql = "UPDATE public.planeacion
	SET fecha_plan='$fecha_plan'
    WHERE id_planeacion = $id_plan;";

    return insertQuery($con, $sql);
}

function insertRegistrosQuery($con, $tipo_registro, $id_plan, $url)
{
    $sql = "INSERT INTO public.registros_x_planeacion(
    id_registro, id_tipo_registro, id_planeacion, url)
    VALUES (nextval('seq_tipo_registro'), $tipo_registro, $id_plan, '$url');";

    return insertQuery($con, $sql);
}

function updateEstadoPlaneacionQuery($con, $estado, $id_plan)
{
    $sql = "UPDATE planeacion
    SET estado = '$estado'
    WHERE id_planeacion = $id_plan";

    return insertQuery($con, $sql);
}

function getEtapaPlaneacionQuery($con, $id_plan)
{
    $sql = "SELECT etapa_planeacion
    FROM registro_ubicacion
    WHERE id_planeacion = $id_plan";

    return executeQuery($con, $sql);
}

function getConsultaExcelQuery($con, $zona, $municipio, $estrategia, $tema, $tipo_gestion, $estado_planeacion)
{

    $sql = "SELECT DISTINCT fecha_plan, municipio, zonas, lugar_encuentro, nombre_entidad, tipo_gestion, comportamientos, competencia, temas, nombre_estrategia, nombre_tactico, nombres || ' ' || apellidos as nombre, pl.estado, ejec.total_asistentes
	FROM planeacion pl
    JOIN focalizacion foc ON pl.id_focalizacion = foc.id_focalizacion
    LEFT JOIN subtemas_x_planeacion sxp ON sxp.id_planeacion = pl.id_planeacion
	LEFT JOIN subtemas sutem ON sutem.id_subtema = sxp.id_subtema
    LEFT JOIN temas tem ON tem.id_temas = sutem.id_temas
    LEFT JOIN tacticos_x_planeacion txp ON txp.id_planeacion = pl.id_planeacion
    LEFT JOIN tactico tact ON txp.id_tactico = tact.id_tactico
    LEFT JOIN estrategias estrat ON estrat.id_estrategia = tact.id_estrategia
    LEFT JOIN tipo_gestion tg ON tg.id_tipo_gestion = foc.id_tipo_gestion
	LEFT JOIN municipios mun ON mun.id_municipio = foc.id_municipio
	LEFT JOIN zonas zon ON mun.id_zona = zon.id_zona
	LEFT JOIN asignar_zonas az ON az.id_zona = zon.id_zona
	LEFT JOIN personas per ON per.cedula = az.cedula_asignado
	LEFT JOIN entidades ent ON pl.id_entidad = ent.id_entidad
	LEFT JOIN indicadores_chec_x_focalizacion ixf ON ixf.id_focalizacion = foc.id_focalizacion
	LEFT JOIN indicadores_chec ic ON ic.id_indicador = ixf.id_indicador
	LEFT JOIN comportamientos compor ON ic.id_comportamiento = compor.id_comportamientos OR tem.id_comportamiento = compor.id_comportamientos
    LEFT JOIN competencias compe ON compor.id_competencia = compe.id_competencia
    LEFT JOIN ejecucion ejec ON ejec.id_planeacion = pl.id_planeacion ";

    $where = FALSE;

    if (!empty($zona)) {
        $sql .= " WHERE mun.id_zona = $zona ";
        $where = TRUE;
    }

    if (!empty($municipio)) {
        if ($where) {
            $sql .= " AND foc.id_municipio = $municipio ";
        }else {
            $sql .= " WHERE foc.id_municipio = $municipio ";
            $where = TRUE;
        }
    }

    if (!empty($estrategia)) {
        if ($where) {
            $sql .= " AND tact.id_estrategia = $estrategia ";
        }else {
            $sql .= " WHERE tact.id_estrategia = $estrategia ";
            $where = TRUE;
        }
    }

    if (!empty($tema)) {
        if ($where) {
            $sql .= "AND sutem.id_temas = $tema ";
        }else {
            $sql .= " WHERE sutem.id_temas = $tema ";
            $where = TRUE;
        }
    }

    if (!empty($tipo_gestion)) {
        if ($where) {
            $sql .= " AND foc.id_tipo_gestion = $tipo_gestion ";
        }else {
            $sql .= " WHERE foc.id_tipo_gestion = $tipo_gestion ";
            $where = TRUE;
        }
    }

    if (!empty($estado_planeacion)) {
        if ($where) {
            $sql .= " AND pl.estado = '$estado_planeacion' ";
        }else {
            $sql .= " WHERE pl.estado = '$estado_planeacion' ";
            $where = TRUE;
        }
    }

    $sql .= " ORDER BY tipo_gestion ASC";

    return executeQuery($con, $sql);
}

function insertGeoLocationQuery($con, $lat, $long, $fecha, $hora, $id_plan, $etapa_plan)
{
    $sql = "INSERT INTO registro_ubicacion(
    id_registro, latitud, longitud, fecha, hora, id_planeacion, etapa_planeacion)
    VALUES (nextval('seq_registro_ubicacion'), $lat, $long, '$fecha', '$hora', $id_plan, '$etapa_plan');";

    return insertQuery($con, $sql);
}

function insertPlaneacionInstitucionalQuery($con, $id_plan, $compor)
{
    $sql = "INSERT INTO public.planeacion_institucional(
    id_planeacion, id_comportamiento)
    VALUES ($id_plan, $compor)";

    return insertQuery($con, $sql);
}

function insertTipoPoblacionXEjecucionQuery($con, $id_tipo, $id_ejec, $total)
{
    $sql = "INSERT INTO public.tipo_poblacion_x_ejecucion(
    id_tipo, id_ejecucion, total)
    VALUES ($id_tipo, $id_ejec, $total)";

    return insertQuery($con, $sql);
}

function insertCaractPoblacionXEjecucionQuery($con, $id_caract, $id_ejec, $total)
{
    $sql = "INSERT INTO public.caracteristicas_poblacion_x_ejecucion(
    id_caracteristica, id_ejecucion, total)
    VALUES ($id_caract, $id_ejec, $total)";

    return insertQuery($con, $sql);
}

/* UPDATES ------------------------------*/

function editarEjecucionQuery($con, $id_ejec, $column_name, $arg)
{
    $sql = "UPDATE ejecucion SET $column_name = '$arg'
    WHERE id_planeacion = $id_ejec";

    return insertQuery($con, $sql);
}

function editarPlaneacionQuery($con, $id_ejec, $column_name, $arg)
{
    $sql = "UPDATE ejecucion SET $column_name = '$arg'
    WHERE id_planeacion = $id_ejec";

    return insertQuery($con, $sql);
}