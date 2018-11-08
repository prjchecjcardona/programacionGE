<?php

include 'lib.php';

$api = new gestionEducativa();

//if(isset($_POST['foc'])){
$json = $api->getPlaneacionesXFocalizacion(10);

$newArray = array();
$n = 0;
foreach ($json as $key => $value) {

    echo $value['id_planeacion'];

    $newArray[$value['id_planeacion']] = [
        "id_planeacion" => $value['id_planeacion'],
        "tipo_gestion" => $value['tipo_gestion'],
        "nombre_estrategia" => [],
        "temas" => $value['temas'],
        "fecha_plan" => $value['fecha_plan'],
    ];



    foreach ($newArray[$value['id_planeacion']]['nombre_estrategia'] as $k => $val) {

        print_r($val);

        array_push($newArray[$value['id_planeacion']]['nombre_estrategia'], $value['nombre_estrategia']);


        // echo $val['nombre_estrategia'], ' -- ', $value['nombre_estrategia'], '</br>';

/*         if ($val != $value['nombre_estrategia']) {
array_push($arrayNombreEstrategia, $value['nombre_estrategia']);
} */
    }

/*     if($newArray[$value['id_planeacion']]['nombre_estrategia']){
array_push($newArray[$value['id_planeacion']]['nombre_estrategia'], $value['nombre_estrategia']);
} */
}

echo '</br>', json_encode($newArray);
//}else{
//$json = 'No se recibieron los datos de manera adecuada';
//}

//echo json_encode($json);
