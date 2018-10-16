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

function executeQueryInsert($con, $sql, $file_tmp, $file_dest)
{
    $data = array('success' => 0, 'message' => "");
    $result = $con->query($sql);
    if ($result) {
        if (move_uploaded_file($file_tmp, $file_dest)) {
            $data['message'] = 'Subido con exito';
            return $data;
        }
    } else {
        $data['success'] = 1;
        $data['message'] = $con->errorInfo()[2];
    }
    return $data;
}

function getMunicipioQuery($con)
{
    $sql = "SELECT id_municipio, municipio FROM municipios";

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
