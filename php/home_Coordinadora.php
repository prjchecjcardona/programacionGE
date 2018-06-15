
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
                    $data['html'] .= '<h4 class="card-title">' . $datos['zonas'] . '</h4>';
                    $data['html'] .= '<h6 class="card-subtitle mb-2 text-muted">' . $datos['nombres'] . '</h6>';
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
            print_r($conexion->getPDO()->errorInfo());
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
    $sql = "SELECT DISTINCT ON (per.nombres) reg.latitud, reg.longitud, per.nombres, per.apellidos, zon.zonas, reg.fecha, reg.hora, reg.tipo_registro
	FROM registro_ubicacion reg
	JOIN planeacion pl ON reg.id_planeacion = pl.id_planeacion
	JOIN planeaciones_por_intervencion ppi ON ppi.planeacion_id_planeacion = pl.id_planeacion
	JOIN intervenciones inter ON inter.id_intervenciones = ppi.intervenciones_id_intervenciones
	JOIN personas_por_zona ppz ON ppz.id_personas_por_zonacol = inter.personas_por_zona_id_personas_por_zonacol
	JOIN personas per ON per.numeroidentificacion = ppz.personas_numeroidentificacion
	JOIN zonas zon ON zon.id_zona = ppz.zonas_id_zona
	ORDER BY per.nombres, reg.fecha DESC, reg.hora DESC";

    if ($rs = $con->query($sql)) {
        if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
            foreach ($filas as $key => $value) {
                $data['html'][$key] = array(
                    'latitud' => $value['latitud'],
                    'longitud' => $value['longitud'],
                    'nombres' => $value['nombres'],
                    'apellidos' => $value['apellidos'],
                    'zonas' => $value['zonas'],
                    'fecha' => $value['fecha'],
                    'hora' => $value['hora'],
                    'tipo_registro' => $value['tipo_registro']);
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
