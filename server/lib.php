<?php
include "consultas.php";

class gestionEducativa
{
    private $con;

    public function __construct()
    {
        $this->connectDB();
    }

    public function connectDB()
    {
        //DEV
        /*         $database = "d4asqdqb9dlt9p";
        $uid = "ntafkvnrqqlbig";
        $pwd = "300113b0978731b5003f9916b2684ec44d7eafdeb2f3a36dca99bfcd115f33f1";
        $host = "ec2-54-197-233-123.compute-1.amazonaws.com"; */

        $database = "GE3";
        $uid = "postgres";
        $pwd = "1234";
        $host = "localhost";

        /*         //PRODUCCION
        $database = "gestjjlg_gestion_educativa";
        $uid = "gestjjlg_usr_gestion";
        $pwd = "r!Hh7XNv22E(";
        $host = "127.0.0.1";  */

        //establecer la conexión
        $this->con = new PDO("pgsql:host=$host;port=5432;dbname=$database;user=$uid;password=$pwd");
        if (!$this->con) {
            die('error de conexión');
        }
    }

    public function getComportamientos()
    {
        return getComportamientosQuery($this->con);
    }

    public function getBarrios($id_mun)
    {
        return getBarriosQuery($this->con, $id_mun);
    }

    public function getVeredas($id_mun)
    {
        return getVeredasQuery($this->con, $id_mun);
    }

    public function getEntidades($id_mun)
    {
        return getEntidadesQuery($this->con, $id_mun);
    }

    public function getEstrategias()
    {
        return getEstrategiasQuery($this->con);
    }

    public function getFicheros()
    {
        return getFicherosQuery($this->con);
    }

    public function getContactos($id_mun)
    {
        return getContactosQuery($this->con);
    }

    public function getTacticos($estrat)
    {
        return getTacticosQuery($this->con, $estrat);
    }

    public function getTemas($compor)
    {
        return getTemasQuery($this->con, $compor);
    }

    public function logIn($uid, $pass)
    {
        return loginQuery($this->con, $uid, $pass);
    }

    public function getMunicipiosXZona($zona)
    {
        return getMunicipiosXZonaQuery($this->con, $zona);
    }

    public function getFocalizacionesXZona($zona)
    {
        return getFocalizacionesXZonaQuery($this->con, $zona);
    }

    public function getPlaneacionesXFocalizacion($foc)
    {
        return getPlaneacionesXFocalizacionQuery($this->con, $foc);
    }

    public function getPlaneacionesCalendar()
    {
        return getPlaneacionesCalendarQuery($this->con);
    }

    public function insertFocalizacion($id_mun, $id_tipoGestion, $tipo_focalizacion, $fecha)
    {
        return insertFocalizacionQuery($this->con, $id_mun, $id_tipoGestion, $tipo_focalizacion, $fecha);
    }

    public function insertIndicadoresXFocalizacion($id_foc, $id_indicador)
    {
        return insertIndicadoresXFocalizacionQuery($this->con, $id_foc, $id_indicador);
    }

    public function getMaxIdFoc()
    {
        return getMaxIdFocQuery($this->con);
    }

    public function getIndicadoresChec($comp)
    {
        return getIndicadoresChecQuery($this->con, $comp);
    }

    public function insertContacto($cedula, $nombres, $apellidos, $correo, $telefono, $celular, $cargo)
    {
        return insertContactoQuery($this->con, $cedula, $nombres, $apellidos, $correo, $telefono, $celular, $cargo);
    }

    public function insertContactosXEntidad($cedula, $entidad)
    {
        return insertContactosXEntidadQuery($this->con, $cedula, $entidad);
    }
}
