<?php
class Dashboard 
{
    private $con;
    
    function __construct(){
        include "conexion.php";
        $this->con = $con;
        $json = "";
    }

    function getGeneralGestionRedes(){
        $array = array();
        $m = array();
        $sql = "SELECT anio, mes, SUM(numero_actores)
        FROM public.informes_actoressociales
        GROUP BY anio, mes";
        if($rs = $this->con->query($sql)){
            if($filas = $filas = $rs->fetchAll(PDO::FETCH_ASSOC)){
                $json['response'] = $filas;
                foreach ($filas as $key => $value) {
                    $array[$key] = $filas[$key];
                    switch ($value['mes']) {
                        case 'Enero':
                            $array[$key]['m']=1;
                            break;
                        case 'Febrero':
                            $array[$key]['m']=2;
                            break;
                        case 'Marzo':
                            $array[$key]['m']=3;
                            break;
                        case 'Abril':
                            $array[$key]['m']=4;
                            break;
                        case 'Mayo':
                            $array[$key]['m']=5;
                            break;
                        case 'Junio':
                            $array[$key]['m']=6;
                            break;
                        case 'Julio':
                            $array[$key]['m']=7;
                            break;
                        case 'Agosto':
                            $array[$key]['m']=8;
                            break;
                        case 'Septiembre':
                            $array[$key]['m']=9;
                            break;
                        case 'Octubre':
                            $array[$key]['m']=10;
                            break;
                        case 'Noviembre':
                            $array[$key]['m']=11;
                            break;
                        case 'Diciembre':
                            $array[$key]['m']=12;
                            break;
                        
                        default:
                            # code...
                            break;
                    }
                    
                }
                foreach ($array as $clave => $fila) {
                    $m[$clave] = $fila['m'];
                }
                // Ordenar los datos con volumen descendiente, edición ascendiente
                // Agregar $datos como el último parámetro, para ordenar por la clave común
                array_multisort($m, SORT_ASC, $array);
                $json['response'] = $array;
            }else $json['err'] = 1;
        }else $json['err'] = 1;
        return $json;
    }

    function getMesGestionRedes($mes, $anio){
        $array = array();
        $sql = "select municipio, sum(numero_actores), tipo_entidad from informes_actoressociales where mes = '$mes' AND anio = '$anio' group by municipio, tipo_entidad";
        if($rs = $this->con->query($sql)){
            if($filas = $rs->fetchAll(PDO::FETCH_ASSOC)){
                $json['response'] = $filas;
            }else $json['err'] = 1;
        }else $json['err'] = 1;
        return $json;
    }

    function getDataCombos(){
        $response = array();
        $sql = "SELECT id_zona, zonas FROM public.zonas;";
        if($rs = $this->con->query($sql)){
            if($filas = $filas = $rs->fetchAll(PDO::FETCH_ASSOC)){
                $response['zonas'] = $filas;
            }
        }else {
            $response['err'] = 1;
        }

        $sql = "SELECT id_municipio, municipio, id_zona FROM public.municipios;";
        if($rs = $this->con->query($sql)){
            if($filas = $filas = $rs->fetchAll(PDO::FETCH_ASSOC)){
                $response['municipios'] = $filas;
            }
        }else {
            $response['err'] = 1;
        }

        $sql = "SELECT id_entidad, nombreentidad FROM public.entidades;";
        if($rs = $this->con->query($sql)){
            if($filas = $filas = $rs->fetchAll(PDO::FETCH_ASSOC)){
                $response['entidades'] = $filas;
            }
        }else {
            $response['err'] = 1;
        }

        $sql = "SELECT id_comportamientos, comportamientos FROM public.comportamientos;";
        if($rs = $this->con->query($sql)){
            if($filas = $filas = $rs->fetchAll(PDO::FETCH_ASSOC)){
                $response['comportamientos'] = $filas;
            }
        }else {
            $response['err'] = 1;
        }
        
        $sql = "SELECT id_estrategia, nombreestrategia FROM public.estrategias;";
        if($rs = $this->con->query($sql)){
            if($filas = $filas = $rs->fetchAll(PDO::FETCH_ASSOC)){
                $response['estrategias'] = $filas;
            }
        }else {
            $response['err'] = 1;
        }

        $sql = "SELECT id_tactico, nombretactico, id_estrategia FROM public.tactico;";
        if($rs = $this->con->query($sql)){
            if($filas = $filas = $rs->fetchAll(PDO::FETCH_ASSOC)){
                $response['tacticos'] = $filas;
            }
        }else {
            $response['err'] = 1;
        }

        return $response;
    }
    
}
