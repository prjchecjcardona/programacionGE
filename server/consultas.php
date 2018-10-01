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

    executeQuery($con, $sql);
}

function getEntidadQuery($con)
{
    $sql = "SELECT id_entidad, entidad FROM entidades";

    executeQuery($con, $sql);
}

function getTipoIntervencionQuery($con)
{
    $sql = "SELECT * FROM tipo_intervencion";

    executeQuery($con, $sql);
}

function getCompetenciasQuery($con)
{
    $sql = "SELECT competencias_id_competencia, comportamientos_id_comportamientos, comportamientos
    FROM competencias_por_comportamiento cpc
    JOIN comportamientos cpt ON cpc.comportamientos_id_comportamientos = cpt.id_comportamientos";

    executeQuery($con, $sql);
}


