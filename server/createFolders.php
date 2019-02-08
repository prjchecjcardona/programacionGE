<?php

//DEV
$database = "d4asqdqb9dlt9p";
$uid = "ntafkvnrqqlbig";
$pwd = "300113b0978731b5003f9916b2684ec44d7eafdeb2f3a36dca99bfcd115f33f1";
$host = "ec2-54-197-233-123.compute-1.amazonaws.com";

/*         //PRODUCCION
$database = "gestjjlg_gestion_educativa";
$uid = "gestjjlg_usr_gestion";
$pwd = "r!Hh7XNv22E(";
$host = "127.0.0.1";  */

//establecer la conexión
$con = new PDO("pgsql:host=$host;port=5432;dbname=$database;user=$uid;password=$pwd");
if (!$con) {
    die('error de conexión');
}

$sql = "SELECT zonas, municipio, nombre_entidad
FROM entidades ent
JOIN municipios mun ON ent.id_municipio = mun.id_municipio
JOIN zonas zon ON zon.id_zona = mun.id_zona";

if ($con) {
    $result = $con->query($sql);
    if ($result) {
        $data = array();
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            array_push($data, $row);
        }

        foreach ($data as $key => $value) {
          if(mkdir('../registros/' . $value['zonas'] . '/' . $value['municipio'] . '/' . $value['nombre_entidad'], 0700, true)){
            echo 'creado';
          }
        }
    } else {
        return $con->errorInfo()[2];
    }
}
