<?php
include 'conexion.php';

if (isset($_POST["accion"])) {

  if($_POST["accion"] == "getCoberturaMunicipio") {
      getCoberturaMunicipio($_POST['filtro']);
  }
}

function getCoberturaMunicipio($filtro){
    include 'conexion.php';
    $data = array();

    if ($con) {
      if($filtro == 'municipio'){
        $sql = "SELECT muncipio as name, sum(cantidad_participantes) as y
        FROM informe_t as inft
        JOIN informes_municipio as mun ON id_municipio = inft.fk_municipio
        GROUP BY muncipio";
      }else if($filtro == 'entidad'){
        $sql = "SELECT entidad as name, sum(cantidad_participantes) as y
        FROM informe_t as inft
        JOIN informes_entidad as inent ON inent.id_entidad = inft.fk_entidad
        GROUP BY name
        ORDER BY name";
      }else if($filtro == 'zonas'){
        $sql = "SELECT zonas as name, sum(cantidad_participantes) as y
        FROM informe_t as inft
        JOIN zonas as zona ON zona.id_zona = inft.fk_zona
        GROUP BY name
        ORDER BY name";
      }else if($filtro == 'estrategia'){
        $sql = "SELECT estrategia as name, sum(cantidad_participantes) as y
        FROM informe_t as inft
        JOIN informes_estrategia inest ON inest.id_estrategia = inft.fk_estrategia
        GROUP BY name
        ORDER BY name";
      }else if($filtro == 'tipo_poblacion'){
        $sql = "SELECT tipopoblacion as name, sum(cantidad_participantes) as y
        FROM informe_t as inft
        JOIN tipopoblacion as tipo ON tipo.id_tipopoblacion = inft.fk_tipo_poblacion
        GROUP BY name
        ORDER BY name";
      }else if($filtro == 'competencia_comportamiento'){
        $sql = "SELECT competencia_comportamiento as name, sum(cantidad_participantes) as y
        FROM informe_t as inft
        JOIN informes_competencia_comportamiento as incom ON incom.id_competencia_comportamiento = inft.fk_competencia_comportamiento
        GROUP BY name
        ORDER BY name";
      }else{
        $sql = "SELECT (nombres || ' ' || apellidos) as name, sum(cantidad_participantes) as y
        FROM informe_t as inft
        JOIN personas as per ON per.numeroidentificacion = inft.fk_gestor
        GROUP BY name
        ORDER BY name";
      }
        if ($rs = $con->query($sql)) {
            $filas = $rs->fetchAll(PDO::FETCH_ASSOC);
            if ($filas) {
              $data = $filas;  
            }
        }
    }
    echo json_encode($data);
}

function getSql($municipio, $zona, $sector, $entidad, $estrategia, $tactico, $tema, $caracteristica, $tipopoblacion, $cantidad_participantes){
  include('conexion.php');

  if(isnull($municipio) && isnull($zona) && isnull($sector) && isnull($entidad) && isnull($estrategia) && isnull($tactico) && 
  isnull($tema) && isnull($caracteristica) && isnull($tipopoblacion) && isnull($cantidad_participantes)){

  }
}
