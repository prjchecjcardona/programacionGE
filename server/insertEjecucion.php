<?php

include 'lib.php';

$api = new gestionEducativa();

if (isset($_POST['fecha']) && isset($_POST['horaInicio']) &&
    isset($_POST['horaFin']) && isset($_POST['ninios']) && isset($_POST['jovenes']) && isset($_POST['adultos'])
    && isset($_POST['id_plan'])) {

    $desc = 'null';
    $id_resultado = 'null';

    if (isset($_POST['resultadoEjecucion']) && isset($_POST['descResultado'])) {
        if (!is_null($_POST['descResultado'])) {
            $desc = $_POST['descResultado'];
        }

        if (!is_null($_POST['resultadoEjecucion'])) {
            $id_resultado = $_POST['resultadoEjecucion'];
        }
    }

    $tipo = array(1 => $_POST['ninios'], 2 => $_POST['jovenes'], 3 => $_POST['adultos']);
    $caract = array(1 => $_POST['discapacidad'], 2 => $_POST['desplazamiento'], 3 => $_POST['indigena'],
        4 => $_POST['afro'], 5 => $_POST['ninguno']);

    $fecha = $_POST['fecha'];
    $hora_inicio = $_POST['horaInicio'];
    $hora_fin = $_POST['horaFin'];
    $total_asist = $_POST['ninios'] + $_POST['jovenes'] + $_POST['adultos'];
    $id_planeacion = $_POST['id_plan'];
    $tipo_ejecucion = $_POST['tipoEjecucion'];

    $json = $api->insertEjecucion($fecha, $hora_inicio, $hora_fin, $id_resultado, $desc, $id_planeacion, $total_asist, $tipo_ejecucion);

    if ($json['error'] != 1) {
        $max = $api->getMaxIdEjec();

        if (count($json) > 0) {
            for ($i = 1; $i <= count($tipo); $i++) {
                $json = $api->insertTipoPoblacionXEjecucion($i, $max[0]['max'], $tipo[$i]);
            }
            for ($i = 1; $i <= count($caract); $i++) {
                $json = $api->insertCaractPoblacionXEjecucion($i, $max[0]['max'], $caract[$i]);
            }
        }
    }
} else {
    $json = "No se recibieron adecuadamente los datos";
}

echo json_encode($json);
