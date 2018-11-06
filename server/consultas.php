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

function getEntidadesQuery($con)
{
    $sql = "SELECT id_entidad, nombreentidad FROM entidades ORDER BY nombreentidad ASC";

    return executeQuery($con, $sql);
}

function getTipoIntervencionQuery($con)
{
    $sql = "SELECT * FROM tipo_intervencion";

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
    $sql = "SELECT foc.id_focalizacion, mun.id_municipio, mun.municipio, compor.comportamientos, foc.tipo_focalizacion, foc.fecha, compe.competencia
    FROM focalizacion foc
    JOIN indicadores_chec_x_focalizacion icxf ON icxf.id_focalizacion= foc.id_focalizacion
    JOIN indicadores_chec ind ON ind.id_indicador= icxf.id_indicador
    JOIN comportamientos compor ON compor.id_comportamientos = ind.id_comportamiento
    JOIN competencias compe ON compe.id_competencia = compor.id_competencia
    JOIN municipios mun ON mun.id_municipio = foc.id_municipio
    WHERE mun.id_municipio = $mun
    GROUP BY foc.id_focalizacion, mun.municipio, compor.comportamientos, foc.tipo_focalizacion, foc.fecha, compe.competencia
    ORDER BY foc.fecha DESC";

    return executeQuery($con, $sql);
}

function getPlaneacionesXFocalizacionQuery($con, $foc)
{
    $sql = "SELECT pl.id_planeacion, foc.id_tipo_gestion, est.estrategia, tem.tema, pl.fecha
	FROM planeacion pl
    JOIN focalizacion foc ON pl.id_focalizacion = foc.id_focalizacion
    JOIN tema tem ON tem.id_tema = pl.id_tema
    JOIN estrategias_x_planeacion esxp ON esxp.id_planeacion = pl.id_planeacion
    JOIN estrategias est ON est.id_estrategia = esxp.id_estrategia
    WHERE pl.id_focalizacion = '.$foc.'";

    return executeQuery($con, $sql);
}

function getComunasQuery($con)
{
    $sql = "SELECT id_comuna, comuna FROM comunas";

    return executeQuery($con, $sql);
}

function getBarriosQuery($con)
{
    $sql = "SELECT id_barrio, barrio FROM barrios";

    return executeQuery($con, $sql);
}

function getVeredasQuery($con)
{
    $sql = "SELECT id_veredas, vereda FROM veredas";

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

function getContactosQuery($con)
{
    $sql = "SELECT nombrecontacto, id_contacto, telefono, cargo, entidades_id_entidades
    FROM contactos";

    return executeQuery($con, $sql);
}

function getTacticosQuery($con)
{
    $sql = "SELECT id_tactico, nombretactico FROM tactico";

    return executeQuery($con, $sql);
}

function getTemasQuery($con)
{
    $sql = "SELECT id_temas, temas FROM temas";

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

function insertFocalizacionQuery($con, $id_mun, $id_tipoGestion, $tipo_focalizacion, $fecha)
{
    $sql = "INSERT INTO public.focalizacion(id_municipio, id_tipo_gestion, fecha, tipo_focalizacion)
    VALUES ($id_mun, $id_tipoGestion, '$fecha', '$tipo_focalizacion');";

    return insertQuery($con, $sql);

}

function getMaxIdFocQuery($con)
{
    $sql = "SELECT MAX(id_focalizacion) FROM focalizacion";

    return executeQuery($con, $sql);
}

function insertIndicadoresXFocalizacionQuery($con, $id_focalizacion, $id_indicador)
{
    $sql = "INSERT INTO public.indicadores_chec_x_focalizacion(id_indicador, id_focalizacion)
    VALUES ($id_indicador, $id_focalizacion);";

    return insertQuery($con, $sql);
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
