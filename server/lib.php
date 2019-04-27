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
        /* //DEV
        $database = "d4asqdqb9dlt9p";
        $uid = "ntafkvnrqqlbig";
        $pwd = "300113b0978731b5003f9916b2684ec44d7eafdeb2f3a36dca99bfcd115f33f1";
        $host = "ec2-54-197-233-123.compute-1.amazonaws.com"; */

        /* //DEV
        $database = "d9om12i7bb5imh";
        $uid = "dmldxxackjdyjy";
        $pwd = "117bb8bc08addca06bfbd6d7244f77f96387b241397aa15c69b7e6851199da5b";
        $host = "ec2-107-22-238-186.compute-1.amazonaws.com"; */

        /* //DEV
        $database = "GE10";
        $uid = "postgres";
        $pwd = "1234";
        $host = "localhost"; */

        //DEV
        $database = "GE243";
        $uid = "postgres";
        $pwd = "1234";
        $host = "localhost";

        /* //PRODUCCION
        $database = "gestjjlg_gestion_educativa";
        $uid = "gestjjlg_usr_gestion";
        $pwd = "r!Hh7XNv22E(";
        $host = "127.0.0.1"; */

        //establecer la conexión
        $this->con = new PDO("pgsql:host=$host;port=5432;dbname=$database;user=$uid;password=$pwd");
        if (!$this->con) {
            die('error de conexión');
        }

        $this->con2 = pg_connect("host=$host port=5432 dbname=$database user=$uid password=$pwd");
        if (!$this->con2) {
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

    public function eliminarEjecucion($id_plan)
    {
        return eliminarEjecucionQuery($this->con2, $id_plan);
    }

    public function eliminarPlaneacion($id_plan)
    {
        return eliminarPlaneacionQuery($this->con2, $id_plan);
    }

    public function getIndicadoresGEXSubtema($id_subtema)
    {
        return getIndicadoresGEXSubtemaQuery($this->con, $id_subtema);
    }

    public function getTacticos($estrat, $cercania)
    {
        return getTacticosQuery($this->con, $estrat, $cercania);
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

    public function getTotalAsistentes($id_plan)
    {
        return getTotalAsistentesQuery($this->con, $id_plan);
    }

    public function getPlaneacionesCalendar($zona)
    {
        return getPlaneacionesCalendarQuery($this->con, $zona);
    }

    public function getDetallePlaneacionEjecucion($id_plan)
    {
        return getDetallePlaneacionEjecucionQuery($this->con, $id_plan);
    }

    public function getZonas()
    {
        return getZonasQuery($this->con);
    }

    public function getGuiasPlaneacion($subtema)
    {
        return getGuiasPlaneacionQuery($this->con, $subtema);
    }

    public function eliminarContacto($id_contacto)
    {
        return eliminarContactoQuery($this->con, $id_contacto);
    }

    public function getContactoEditar($id_contacto)
    {
        return getContactoEditarQuery($this->con, $id_contacto);
    }

    public function getPoblacionXEjecucion($ejec)
    {
        return getPoblacionXEjecucionQuery($this->con, $ejec);
    }

    public function getCaracteristicasXEjecucion($ejec)
    {
        return getCaracteristicasXEjecucionQuery($this->con, $ejec);
    }

    public function getContactosXPlaneacion($id_plan)
    {
        return getContactosXPlaneacionQuery($this->con, $id_plan);
    }

    public function insertFocalizacion($id_mun, $id_tipoGestion, $fecha)
    {
        return insertFocalizacionQuery($this->con, $id_mun, $id_tipoGestion, $fecha);
    }

    public function insertIndicadoresXFocalizacion($id_foc, $id_indicador)
    {
        return insertIndicadoresXFocalizacionQuery($this->con, $id_foc, $id_indicador);
    }

    public function getMaxIdFoc()
    {
        return getMaxIdFocQuery($this->con);
    }

    public function getTacticosInformes($estrategia)
    {
        return getTacticosInformesQuery($this->con, $estrategia);
    }

    public function getMaxPlanFoc()
    {
        return getMaxIdPlanQuery($this->con);
    }

    public function getMaxIdTAdmin()
    {
        return getMaxIdTAdminQuery($this->con);
    }

    public function getIndicadoresChec($comp)
    {
        return getIndicadoresChecQuery($this->con, $comp);
    }

    public function insertContacto($cedula, $nombres, $apellidos, $correo, $telefono, $celular, $cargo, $id_entidad)
    {
        return insertContactoQuery($this->con, $cedula, $nombres, $apellidos, $correo, $telefono, $celular, $cargo, $id_entidad);
    }

    public function insertEntidad($nombre, $direccion, $telefono, $tipoEntidad, $municipio)
    {
        return insertEntidadQuery($this->con, $nombre, $direccion, $telefono, $tipoEntidad, $municipio);
    }

    public function insertPlaneacion($jornada, $lugar_encuentro, $id_barrio, $id_vereda, $id_entidad, $fecha_plan, $fecha_registro, $id_foc, $solictud, $estado)
    {
        return insertPlaneacionQuery($this->con, $jornada, $lugar_encuentro, $id_barrio, $id_vereda, $id_entidad, $fecha_plan, $fecha_registro, $id_foc, $solictud, $estado);
    }

    public function insertContactosXEntidad($cedula, $entidad)
    {
        return insertContactosXEntidadQuery($this->con, $cedula, $entidad);
    }

    public function insertEjecucion($fecha, $hora_inicio, $hora_fin, $id_resultado, $descripcion, $id_planeacion, $total_asist, $tipo_ejecucion)
    {
        return insertEjecucionQuery($this->con, $fecha, $hora_inicio, $hora_fin, $id_resultado, $descripcion, $id_planeacion, $total_asist, $tipo_ejecucion);
    }

    public function getTipoGestion($id_foc)
    {
        return getTipoGestionQuery($this->con, $id_foc);
    }

    public function insertXPlaneacion($id_param, $id_plan, $name)
    {
        return insertXPlaneacionQuery($this->con, $id_param, $id_plan, $name);
    }

    public function insertTrabajoAdministrativo($hora_inicio, $hora_fin, $id_municipio, $fecha, $descripcion)
    {
        return insertTrabajoAdministrativoQuery($this->con, $hora_inicio, $hora_fin, $id_municipio, $fecha, $descripcion);
    }

    public function insertLaborXTrabajoAdministrativo($id_labor, $id_ta)
    {
        return insertLaborXTrabajoAdministrativoQuery($this->con, $id_labor, $id_ta);
    }

    public function getTrabajosAdministrativosCalendar()
    {
        return getTrabajosAdministrativosCalendarQuery($this->con);
    }

    public function insertNovedadNoEjecucion($id_planeacion, $descripcion, $fecha_aplazamiento, $fecha_plan)
    {
        return insertNovedadNoEjecucionQuery($this->con, $id_planeacion, $descripcion, $fecha_aplazamiento, $fecha_plan);
    }

    public function getPlaneacionesEjecutadosOEnEjecucion($zona)
    {
        return getPlaneacionesEjecutadosOEnEjecucionQuery($this->con, $zona);
    }

    public function getNovedadesNoEjecucion($zona)
    {
        return getNovedadesNoEjecucionQuery($this->con, $zona);
    }

    public function getContactos($mun)
    {
        return getContactosQuery($this->con, $mun);
    }

    public function contactoExiste($cedula)
    {
        return contactoExisteQuery($this->con, $cedula);
    }

    public function aplazarPlaneacion($id_plan, $fecha_plan)
    {
        return aplazarPlaneacionQuery($this->con, $id_plan, $fecha_plan);
    }

    public function getUrlArchivosPlan($id_plan, $id_tipo_registro)
    {
        return getUrlArchivosPlanQuery($this->con, $id_plan, $id_tipo_registro);
    }

    public function getSubtemasXTema($id_tema)
    {
        return getSubtemasXTemaQuery($this->con, $id_tema);
    }

    public function getDetalleEjecucion($id_plan)
    {
        return getDetalleEjecucionQuery($this->con, $id_plan);
    }

    public function getMunicipioInforme()
    {
        return getMunicipioInformeQuery($this->con);
    }

    public function getEjecucion($type)
    {
        return getEjecucionQuery($this->con, $type);
    }

    public function getUserRol()
    {
        return getUserRolQuery($this->con);
    }

    public function getPlaneacionesGeoApp($zona)
    {
        return getPlaneacionesGeoAppQuery($this->con, $zona);
    }

    public function insertRegistros($tipo_registro, $id_plan, $url)
    {
        return insertRegistrosQuery($this->con, $tipo_registro, $id_plan, $url);
    }

    public function getInformes($comportamiento, $temas, $municipio, $estrategia, $tactico, $tipo, $zona, $month)
    {
        return getInformesQuery($this->con, $comportamiento, $temas, $municipio, $estrategia, $tactico, $tipo, $zona, $month);
    }

    public function checkGestion($id_foc)
    {
        return checkGestionQuery($this->con, $id_foc);
    }

    public function getTacticosPorEstrategiaCobertura($estrategia)
    {
        return getTacticosPorEstrategiaCoberturaQuery($this->con, $estrategia);
    }

    public function getTemasPorComportamientoCobertura($competencia)
    {
        return  getTemasPorComportamientoCoberturaQuery($this->con, $competencia);
    }

    public function insertPlaneacionInstitucional($id_plan, $compor)
    {
        return insertPlaneacionInstitucionalQuery($this->con, $id_plan, $compor);
    }

    public function getEtapaPlaneacion($id_plan)
    {
        return getEtapaPlaneacionQuery($this->con, $id_plan);
    }

    public function updateEstadoPlaneacion($estado, $id_plan)
    {
        return updateEstadoPlaneacionQuery($this->con, $estado, $id_plan);
    }

    public function insertGeoLocation($lat, $long, $fecha, $hora, $id_plan, $etapa_plan)
    {
        return insertGeoLocationQuery($this->con, $lat, $long, $fecha, $hora, $id_plan, $etapa_plan);
    }

    public function checkCompetenciasFocalizacion($mun)
    {
        return checkCompetenciasFocalizacionQuery($this->con, $mun);
    }

    public function checkFocalizacion($id_mun, $comp)
    {
        return checkFocalizacionQuery($this->con, $id_mun, $comp);
    }

    public function ejecucion_planeacion($id_plan)
    {
        return ejecucion_planeacionQuery($this->con, $id_plan);
    }

    public function checkRegistros($id_plan, $tipo)
    {
        return checkRegistrosQuery($this->con, $id_plan, $tipo);
    }

    public function getConsultaExcel($zona, $municipio, $estrategia, $tema, $tipo_gestion, $estado_planeacion)
    {
        return getConsultaExcelQuery($this->con, $zona, $municipio, $estrategia, $tema, $tipo_gestion, $estado_planeacion);
    }

    public function getMaxIdEjec()
    {
        return getMaxIdEjecQuery($this->con);
    }

    public function getMunicipioIdPlan($id_plan)
    {
        return getMunicipioIdPlanQuery($this->con, $id_plan);
    }

    public function getNodos($id_plan)
    {
        return getNodosQuery($this->con, $id_plan);
    }

    public function addNodos($nodo, $latitud, $longitud, $id_municipio)
    {
        return addNodosQuery($this->con, $nodo, $latitud, $longitud, $id_municipio);
    }

    public function insertTipoPoblacionXEjecucion($id_tipo, $id_ejec, $total)
    {
        return insertTipoPoblacionXEjecucionQuery($this->con, $id_tipo, $id_ejec, $total);
    }

    public function insertCaractPoblacionXEjecucion($id_caract, $id_ejec, $total)
    {
        return insertCaractPoblacionXEjecucionQuery($this->con, $id_caract, $id_ejec, $total);
    }

    public function editarEjecucion($id_ejec, $column_name, $arg)
    {
        return editarEjecucionQuery($this->con, $id_ejec, $column_name, $arg);
    }
}
