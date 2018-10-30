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
        if ($row = $result->fetch(PDO::FETCH_ASSOC)) {

            $pswdCheck = password_verify($pass, $row['pass']);
            if ($pswdCheck == false) {
                header("Location: ../iniciarSesion.html?error=wrgpswd");
                exit();
                echo false;

            } elseif ($pswdCheck == true) {
                session_start();

                $_SESSION['user'] = array('uid' => $row['numeroidentificacion'],
                    'pass' => $row['pass'], 'nombre' => $row['nombres']);

                header("Location: ../homeGestor.html?id_zona=" . $row['zonas_id_zona']);
                exit();
            }
        } else {
            header("Location: ../iniciarSesion.html?error=nouser");
            exit();
        }
    }
}

function getMunicipioQuery($con)
{
    $sql = "SELECT id_municipio, municipio FROM municipios";

    return executeQuery($con, $sql);
}

function getMunicipiosXZonaQuery($con, $zona)
{
    $sql = "SELECT id_municipio, municipio, zna.zonas
    FROM municipios mn
    JOIN zonas zna ON mn.id_zona = zna.id_zona
    WHERE mn.id_zona = $zona";

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
    $sql = "SELECT comportamientos_id_comportamientos, comportamientos, competencia
    FROM competencias_por_comportamiento cpc
    JOIN comportamientos cpt ON cpc.comportamientos_id_comportamientos = cpt.id_comportamientos
    JOIN competencias comp ON cpc.competencias_id_competencia = comp.id_competencia
    WHERE comportamientos_id_comportamientos <> 5";

    return executeQuery($con, $sql);
}

function getFocalizacionesXZonaQuery($con, $mun)
{
    $sql = "SELECT inter.id_intervenciones, mun.municipio, compor.comportamientos, tipoint.tipo_intervencion, inter.fecha, compe.competencia
    FROM intervenciones inter
    JOIN personas_por_zona pxz ON pxz.id_personas_por_zonacol = inter.personas_por_zona_id_personas_por_zonacol
    JOIN indicadores_chec_por_intervenciones indxinter ON indxinter.intervenciones_id_intervenciones = inter.id_intervenciones
    JOIN indicadores_chec ind ON ind.id_indicadores_chec = indxinter.indicadores_chec_id_indicadores_chec
    JOIN comportamientos compor ON compor.id_comportamientos = ind.comportamientos_id_comportamientos
    JOIN competencias_por_comportamiento cpc ON cpc.comportamientos_id_comportamientos = compor.id_comportamientos
    JOIN competencias compe ON compe.id_competencia = cpc.competencias_id_competencia
    JOIN tipo_intervencion tipoint ON tipoint.id_tipo_intervencion = inter.tipo_intervencion_id_tipo_intervencion
    LEFT OUTER JOIN barrios bar ON bar.id_barrio = inter.id_barrio
    LEFT OUTER JOIN comunas com ON com.id_comuna = bar.id_comuna
    LEFT OUTER JOIN veredas ver ON ver.id_veredas = inter.id_vereda
    JOIN municipios mun ON mun.id_municipio = com.id_municipio OR mun.id_municipio = ver.id_municipio
    WHERE mun.id_municipio = $mun
    GROUP BY id_intervenciones, municipio, comportamientos, tipoint.tipo_intervencion, inter.fecha, compe.competencia
    ORDER BY inter.fecha DESC";

    return executeQuery($con, $sql);
}

function getPlaneacionesXFocalizacionQuery($con, $foc)
{
    $sql = "SELECT pl.id_planeacion, ep.etapaproceso, est.nombreestrategia, tact.nombretactico, tem.temas, pl.fecha
	FROM planeacion pl
	JOIN etapaproceso ep ON pl.etapaproceso_id_etapaproceso = ep.id_etapaproceso
	JOIN tactico_por_planeacion tactxpl ON pl.id_planeacion = tactxpl.planeacion_id_planeacion
	JOIN tactico tact ON tact.id_tactico = tactxpl.tactico_id_tactico
	JOIN estrategias est ON tact.id_estrategia = est.id_estrategia
	LEFT OUTER JOIN subtemas_por_planeacion subxpl ON subxpl.planeacion_id_planeacion = pl.id_planeacion
	LEFT OUTER JOIN subtemas sub ON subxpl.subtemas_id_subtema = sub.id_subtema
	LEFT OUTER JOIN temas tem ON sub.id_temas = tem.id_temas
	JOIN planeaciones_por_intervencion plxint ON plxint.planeacion_id_planeacion = pl.id_planeacion
	LEFT OUTER JOIN ejecuciones_por_planeacion epp ON epp.id_planeaciones_por_intervencion = plxint.id_planeaciones_por_intervencion
	WHERE plxint.intervenciones_id_intervenciones = " . $foc . "
	GROUP BY pl.id_planeacion, etapaproceso, nombreestrategia, nombretactico, temas, fecha
    ORDER BY pl.id_planeacion";

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
    $sql = "SELECT per.numeroidentificacion, per.correoelectronico, per.usuario, per.pass, per.foto_url, ppz.zonas_id_zona, per.nombres
    FROM personas per
	LEFT JOIN personas_por_zona ppz ON ppz.personas_numeroidentificacion = per.numeroidentificacion
    WHERE correoelectronico = '" . $uid . "' OR usuario = '" . $uid . "' ";

    return logIn($con, $sql, $psswd);
}
