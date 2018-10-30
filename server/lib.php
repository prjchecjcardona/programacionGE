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
        $this->con = new PDO("pgsql:host=$host;port=5432;dbname=$database;user=$uid;password=$pwd");
        if (!$this->con) {
            die('error de conexión');
        }
    }

    public function getMunicipio()
    {
        return getMunicipioQuery($this->con);
    }

    public function getComportamientos()
    {
        return getComportamientosQuery($this->con);
    }

    public function getComunas()
    {
        return getComunasQuery($this->con);
    }

    public function getBarrios()
    {
        return getBarriosQuery($this->con);
    }

    public function getVeredas()
    {
        return getVeredasQuery($this->con);
    }

    public function getEntidades()
    {
        return getEntidadesQuery($this->con);
    }

    public function getEstrategias()
    {
        return getEstrategiasQuery($this->con);
    }

    public function getFicheros()
    {
        return getFicherosQuery($this->con);
    }

    public function getContactos()
    {
        return getContactosQuery($this->con);
    }

    public function getTacticos()
    {
        return getTacticosQuery($this->con);
    }

    public function getTemas()
    {
        return getTemasQuery($this->con);
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
}
