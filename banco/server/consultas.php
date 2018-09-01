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

function executeQueryInsert($con, $sql)
{
    $data = array('success' => "", 'message' => "");
    $result = $con->query($sql);
    if ($result) {
        $data['success'] = true;
        $data['message'] = 'Insertado exitosamente';
    } else {
        $data['success'] = false;
        $data['message'] = 'Error!: ' . $con->errorInfo()[2];
    }
    return $data;
}

function getRecursosQuery($con)
{
    $sql = "SELECT id_archivo, rec.recurso_url || '/' || nombre_archivo AS recurso_url, nombre_archivo, rec.icon, rec.id_recurso
    FROM archivos_recursos ar
    JOIN recursos rec ON rec.id_recurso = ar.id_recurso";

    return executeQuery($con, $sql);
}

function getFicherosQuery($con, $competencia, $tema, $zona, $indicador)
{
    $sql = "SELECT rec.recurso_url || '/' || zna.zonas || '/' || nombre_fichero AS fichero_url, fro.nombre_fichero, rec.icon, fro.codigo, tma.temas
    FROM ficheros as fro
    JOIN competencias as cpte ON fro.id_competencia = cpte.id_competencia
    JOIN temas as tma ON tma.id_temas = fro.id_tema
    JOIN zonas as zna ON zna.id_zona = fro.id_zona
    JOIN recursos rec ON rec.id_recurso = fro.id_recurso";
    /* Validar que la consulta termine en un WHERE y no en un AND */
    $is_where = true;

    if (!is_null($competencia)) {
        if ($is_where) {
            $sql .= " WHERE fro.id_competencia = $competencia";
            $is_where = false;
        } else {
            $sql .= " AND fro.id_competencia = $competencia";
        }
    }
    if (!is_null($tema)) {
        if ($is_where) {
            $sql .= " WHERE fro.id_tema = $tema";
            $is_where = false;
        } else {
            $sql .= " AND fro.id_tema = $tema";
        }
    }
    if (!is_null($zona)) {
        if ($is_where) {
            $sql .= " WHERE fro.id_zona = $zona";
            $is_where = false;
        } else {
            $sql .= " AND fro.id_zona = $zona";
        }
    }
    if (!is_null($indicador)) {
        if ($is_where) {
            $sql .= " WHERE id_indicador = $indicador";
            $is_where = false;
        } else {
            $sql .= " AND id_indicador = $indicador";
        }
    }

    return executeQuery($con, $sql);
}

function getCompetenciasQuery($con)
{
    $sql = "SELECT id_competencia, competencia
    FROM competencias";

    return executeQuery($con, $sql);
}

function getTemasQuery($con, $competencia)
{
    $sql = "SELECT id_temas, temas
    FROM temas
    WHERE compe_por_compo_compe_id_compe = $competencia";

    return executeQuery($con, $sql);
}

function getZonasQuery($con)
{
    $sql = "SELECT id_zona, zonas
    FROM zonas";

    return executeQuery($con, $sql);
}

function getIndicadoresQuery($con)
{
    $sql = "SELECT id_indicador, indicador FROM indicador_fichero";

    return executeQuery($con, $sql);
}

function getListaRecursosQuery($con)
{
    $sql = "SELECT id_recurso, recurso, recurso_url FROM recursos";

    return executeQuery($con, $sql);
}

function subirArchivoQuery($con, $archivo, $recurso)
{
    $sql = "INSERT INTO archivos_recursos (nombre_archivo, id_recurso) VALUES ($archivo, $recurso)";
    var_dump($sql);

    return executeQueryInsert($con, $sql);
}
