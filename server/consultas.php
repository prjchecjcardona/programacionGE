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
    if ($result = $con->query($sql)) {
        echo 'pass', $pass;
        if ($row = $result->fetch(PDO::FETCH_ASSOC)) {

            $pswdCheck = password_verify($pass, $row['passwd']);
            if ($pswdCheck == false) {
                header("Location: ../iniciarSesion.html?error=wrgpswd");
                exit();
                echo false;

            } elseif ($pswdCheck == true) {
                session_start();
                if ($row['id_rol'] != 3) {
                    $_SESSION['user'] = array('uid' => $row['cedula'],
                        'pass' => $row['passwd'], 'nombre' => $row['nombres'], 'rol' => $row['id_rol'],
                        'zona' => $row['id_zona']);

                    header("Location: ../homeGestor.html?user=" . $row['cargo'] . "");
                    exit();
                } else {
                    $_SESSION['user'] = array('uid' => $row['cedula'],
                        'pass' => $row['passwd'], 'nombre' => $row['nombres']);

                    header("Location: ../homeGestor.html?id_zona=" . $row['zonas_id_zona']);
                    exit();
                }
            }
        } else {
            header("Location: ../iniciarSesion.html?error=nouser");
            exit();
        }
    }
}

function insertQuery($con, $sql)
{
    $result = $con->query($sql);
    if ($result) {
        return $data = "Guardado con exito!";

    } else {
        return $con->errorInfo()[2];
    }
}

function getEventsCalendar($con, $sql)
{
    $result = $con->query($sql);
    if ($result) {
        $data = array();
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            array_push($data, $row);
        }
        foreach ($data as $key => $value) {

            $data[$key] = array(
                'id' => $value['id_planeacion'],
                'title' => $value['municipio'] . ' - ' . $value['comportamientos'],
                'start' => $value['fecha'],
                'editable' => false,
                'color' => 'red',
                'textColor' => "white",
            );
        }
        return $data;
    } else {
        return $con->errorInfo()[2];
    }
}

/* CONSULTAS */

function getMunicipiosXZonaQuery($con, $zona)
{
    $sql = "SELECT id_municipio, municipio, zna.zonas, zna.id_zona
    FROM municipios mn
    JOIN zonas zna ON mn.id_zona = zna.id_zona";

    if (!empty($zona)) {
        $sql .= " WHERE mn.id_zona = $zona";
    }

    $sql .= " ORDER BY municipio;";

    return executeQuery($con, $sql);
}

function getIndicadoresGEQuery($con)
{
    $sql = "SELECT id_indicador, nombre_indicador
    FROM indicadores_ge";

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
    $sql = "SELECT foc.id_focalizacion, mun.id_municipio, id_tipo_gestion, mun.municipio, mun.id_zona, compor.id_comportamientos, compor.comportamientos, foc.tipo_focalizacion, foc.fecha, compe.competencia
    FROM focalizacion foc
    LEFT JOIN indicadores_chec_x_focalizacion icxf ON icxf.id_focalizacion= foc.id_focalizacion
    LEFT JOIN indicadores_chec ind ON ind.id_indicador= icxf.id_indicador
    LEFT JOIN comportamientos compor ON compor.id_comportamientos = ind.id_comportamiento
    LEFT JOIN competencias compe ON compe.id_competencia = compor.id_competencia
    JOIN municipios mun ON mun.id_municipio = foc.id_municipio
    WHERE mun.id_municipio = $mun
    GROUP BY foc.id_focalizacion, mun.id_municipio, mun.municipio, compor.id_comportamientos, compor.comportamientos, foc.tipo_focalizacion, foc.fecha, compe.competencia
    ORDER BY foc.fecha DESC";

    return executeQuery($con, $sql);
}

function getPlaneacionesXFocalizacionQuery($con, $foc)
{
    $sql = "SELECT DISTINCT pl.id_planeacion, tg.tipo_gestion, estrat.nombre_estrategia, tem.temas, pl.fecha_plan, pl.fecha_registro, zon.id_zona, pl.id_focalizacion
	FROM planeacion pl
    JOIN focalizacion foc ON pl.id_focalizacion = foc.id_focalizacion
    JOIN temas tem ON tem.id_temas = pl.id_tema
    JOIN tacticos_x_planeacion txp ON txp.id_planeacion = pl.id_planeacion
    JOIN tactico tact ON txp.id_tactico = tact.id_tactico
    JOIN estrategias estrat ON estrat.id_estrategia = tact.id_estrategia
    JOIN tipo_gestion tg ON tg.id_tipo_gestion = foc.id_tipo_gestion
	JOIN municipios mun ON mun.id_municipio = foc.id_municipio
	JOIN zonas zon ON mun.id_zona = zon.id_zona
    WHERE pl.id_focalizacion = '$foc'";

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
    $sql = "SELECT * FROM estrategias";

    return executeQuery($con, $sql);
}

function getFicherosQuery($con)
{
    $sql = "SELECT codigo FROM ficheros";

    return executeQuery($con, $sql);
}

function getContactosQuery($con, $id_mun)
{
    $sql = "SELECT cedula, nombre, apellidos, cargo, ent.nombre_entidad
    FROM contacto con
    JOIN contactos_x_entidad cxe ON con.cedula = cxe.cedula
    JOIN entidades ent ON ent.id_entidad = cxe.id_entidad
    WHERE ent.id_municipio = $id_mun";

    return executeQuery($con, $sql);
}

function getTacticosQuery($con, $estrat)
{
    $sql = "SELECT id_tactico, nombre_tactico
    FROM tactico
    WHERE id_estrategia = $estrat";

    return executeQuery($con, $sql);
}

function getTemasQuery($con, $compor)
{
    $sql = "SELECT id_temas, temas
    FROM temas
    WHERE id_comportamiento = $compor";

    return executeQuery($con, $sql);
}

function loginQuery($con, $uid, $psswd)
{
    $sql = "SELECT per.cedula, per.id_rol, rol.cargo, per.email, per.usuario, per.passwd, per.foto_url, asz.id_zona, per.nombres
    FROM personas per
	LEFT JOIN asignar_zonas asz ON asz.cedula_asignado = per.cedula
    JOIN roles rol ON rol.id_cargo = per.id_rol
    WHERE email = '" . $uid . "' OR usuario = '" . $uid . "' ";

    return logIn($con, $sql, $psswd);
}

function getIndicadoresChecQuery($con, $comp)
{
    $sql = "SELECT id_indicador, indicador
    FROM indicadores_chec
    WHERE id_comportamiento = $comp";

    return executeQuery($con, $sql);
}

function getDetallePlaneacionEjecucionQuery($con, $id_plan)
{
    $sql = "SELECT DISTINCT fecha_plan, municipio, CONCAT(nombres, ' ', apellidos) as nombre, zonas, nombre_entidad, comportamientos, competencia, nombre_estrategia, temas, nombre_tactico
	FROM planeacion pl
    JOIN focalizacion foc ON pl.id_focalizacion = foc.id_focalizacion
    JOIN temas tem ON tem.id_temas = pl.id_tema
    JOIN tacticos_x_planeacion txp ON txp.id_planeacion = pl.id_planeacion
    JOIN tactico tact ON txp.id_tactico = tact.id_tactico
    JOIN estrategias estrat ON estrat.id_estrategia = tact.id_estrategia
    JOIN tipo_gestion tg ON tg.id_tipo_gestion = foc.id_tipo_gestion
	JOIN municipios mun ON mun.id_municipio = foc.id_municipio
	JOIN zonas zon ON mun.id_zona = zon.id_zona
	JOIN asignar_zonas az ON az.id_zona = zon.id_zona
	JOIN personas per ON per.cedula = az.cedula_asignado
	JOIN entidades ent ON pl.id_entidad = ent.id_entidad
	JOIN indicadores_chec_x_focalizacion ixf ON ixf.id_focalizacion = foc.id_focalizacion
	JOIN indicadores_chec ic ON ic.id_indicador = ixf.id_indicador
	JOIN comportamientos compor ON ic.id_comportamiento = compor.id_comportamientos
	JOIN competencias compe ON compor.id_competencia = compe.id_competencia
    WHERE pl.id_planeacion = $id_plan";

    return executeQuery($con, $sql);
}

function getMaxIdFocQuery($con)
{
    $sql = "SELECT MAX(id_focalizacion) FROM focalizacion";

    return executeQuery($con, $sql);
}

function getMaxIdPlanQuery($con)
{
    $sql = "SELECT MAX(id_planeacion) FROM planeacion";

    return executeQuery($con, $sql);
}

function getPlaneacionesCalendarQuery($con)
{
    $sql = "SELECT DISTINCT id_planeacion, plan.fecha, lugarencuentro, jor.jornada, mun.municipio, inter.id_intervenciones, bar.id_barrio, compor.comportamientos
    FROM planeacion plan
    JOIN jornada jor ON jor.id_jornada = plan.id_jornada
    JOIN planeaciones_por_intervencion ppi ON ppi.planeacion_id_planeacion = plan.id_planeacion
    lEFT JOIN intervenciones inter ON ppi.intervenciones_id_intervenciones = inter.id_intervenciones
    LEFT JOIN barrios bar ON bar.id_barrio = inter.id_barrio
    LEFT JOIN comunas com ON bar.id_comuna = com.id_comuna
    LEFT JOIN veredas ver ON ver.id_veredas = bar.id_comuna
    LEFT JOIN municipios mun ON mun.id_municipio = com.id_municipio
	JOIN indicadores_chec_por_intervenciones icpi ON icpi.intervenciones_id_intervenciones = inter.id_intervenciones
	JOIN indicadores_chec ic ON ic.id_indicadores_chec = icpi.indicadores_chec_id_indicadores_chec
	JOIN comportamientos compor ON compor.id_comportamientos = ic.comportamientos_id_comportamientos";

    return getEventsCalendar($con, $sql);
}

/* INSERTS */

function insertIndicadoresXFocalizacionQuery($con, $id_focalizacion, $id_indicador)
{
    $sql = "INSERT INTO public.indicadores_chec_x_focalizacion(id_indicador, id_focalizacion)
    VALUES ($id_indicador, $id_focalizacion);";

    return insertQuery($con, $sql);
}

function insertFocalizacionQuery($con, $id_mun, $id_tipoGestion, $tipo_focalizacion, $fecha)
{
    $sql = "INSERT INTO public.focalizacion(id_municipio, id_tipo_gestion, fecha, tipo_focalizacion)
    VALUES ($id_mun, $id_tipoGestion, '$fecha', '$tipo_focalizacion');";

    return insertQuery($con, $sql);

}

function insertPlaneacionQuery($con, $jornada, $lugar_encuentro, $id_barrio, $id_vereda, $id_entidad, $id_tema, $fecha_plan, $fecha_registro, $id_foc)
{
    $sql = "INSERT INTO public.planeacion(
	jornada, lugar_encuentro, id_barrio, id_vereda, id_entidad, id_tema, fecha_plan, fecha_registro, id_focalizacion)
    VALUES ('$jornada', '$lugar_encuentro', $id_barrio, $id_vereda, $id_entidad, $id_tema, '$fecha_plan', '$fecha_registro', $id_foc);";

    return insertQuery($con, $sql);
}

function insertEntidadQuery($con, $nombre, $direccion, $telefono, $tipoEntidad, $municipio)
{
    $sql = "INSERT INTO public.entidades(
    nombre_entidad, direccion, telefono, id_tipo_entidad, id_municipio)
    VALUES ('$nombre', '$direccion', '$telefono', $tipoEntidad, $municipio);";

    return insertQuery($con, $sql);
}

function insertContactoQuery($con, $cedula, $nombres, $apellidos, $correo, $telefono, $celular, $cargo)
{
    $sql = "INSERT INTO public.contacto(
    cedula, nombres, apellidos, correo, telefono, celular, cargo)
    VALUES ($cedula, '$nombres', '$apellidos', '$correo', $telefono, $celular, '$cargo');";

    return insertQuery($con, $sql);
}

function insertContactosXEntidadQuery($con, $cedula, $entidad)
{
    $sql = "INSERT INTO public.contactos_x_entidad(cedula, id_entidad)
    VALUES ($cedula, $entidad);";

    return insertQuery($con, $sql);
}

function insertEjecucionQuery($con, $fecha, $hora_inicio, $hora_fin, $id_resultado, $descripcion, $id_planeacion, $total_asist)
{
    $sql = "INSERT INTO public.ejecucion(
    fecha, hora_inicio, hora_fin, id_resultado_ejecucion, descripcion_resultado, id_planeacion, total_asistentes)
    VALUES ('$fecha', '$hora_inicio', '$hora_fin', $id_resultado, '$descripcion', $id_planeacion, $total_asist);";

    return insertQuery($con, $sql);
}

function insertXPlaneacionQuery($con, $id_param, $id_plan, $name)
{
    if ($name == 'tactico[]') {

        $sql = "INSERT INTO public.tacticos_x_planeacion(
            id_planeacion, id_tactico)
            VALUES ($id_plan, $id_param);";

        return insertQuery($con, $sql);

    } else if ($name == 'indicador[]') {

        $sql = "INSERT INTO public.indicadores_x_planeacion(
            id_planeacion, id_indicador)
            VALUES ($id_plan, $id_param);";

        return insertQuery($con, $sql);
    }

}
