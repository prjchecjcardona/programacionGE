<?php
include 'conexion.php';

if (isset($_POST["accion"])) {

    if ($_POST["accion"] == "cargarJornadas") {
        cargarJornadas();
    }
    if ($_POST["accion"] == "cargarPoblacion") {
        cargarPoblacion();
    }
    if ($_POST["accion"] == "cargarEstrategias") {
        cargarEstrategias();
    }
    if ($_POST["accion"] == "cargarTacticos") {
        cargarTacticos();
    }
    if ($_POST["accion"] == "guardarPlaneacion") {
        guardarPlaneacion($_POST['nombreContacto'], $_POST['cargoContacto'],
            $_POST['telefonoContacto'], $_POST['correoContacto'], $_POST['fecha'],
            $_POST['lugar'], $_POST['jornada'], $_POST['comunidad'], $_POST['poblacion'],
            $_POST['observaciones'], $_POST['idIntervencion'], $_POST['idEtapa'],
            $_POST['idEntidad'], $_POST['contacto']);
    }
    if ($_POST["accion"] == "cargarEtapas") {
        cargarEtapas();
    }
    if ($_POST["accion"] == "guardarGestionRedes") {
        guardarGestionRedes($_POST['idPlaneacion'], $_POST['indicadores']);
    }
    if ($_POST["accion"] == "guardarGestionEducativa") {
        guardarGestionEducativa($_POST['idPlaneacion'], $_POST['idTema'], $_POST['indicadores'], $_POST['tactico']);
    }
    if ($_POST["accion"] == "consultarTemas") {
        consultarTemas($_POST['idComportamientos'], $_POST['idCompetencia']);
    }
    if ($_POST["accion"] == "consultarIndicadoresGE") {
        consultarIndicadoresGE();
    }
    if ($_POST["accion"] == "cargarEntidades") {
        cargarEntidades($_POST["idIntervencion"]);
    }
    if ($_POST["accion"] == "cargarTipoEntidad") {
        cargarTipoEntidad();
    }
    if ($_POST["accion"] == "cargarEntidadesTotales") {
        cargarEntidadesTotales();
    }
    if ($_POST["accion"] == "guardarNuevaEntidad") {
        guardarNuevaEntidad($_POST["idIntervencion"], $_POST["nombreEntidad"], $_POST["direccion"], $_POST["telefono"], $_POST["tipo_entidad"], $_POST["nodo"]);
    }
    if ($_POST["accion"] == "cargarPlaneacionFormulario") {
        cargarPlaneacionFormulario($_POST["id_planeacion"]);
    }
    if ($_POST["accion"] == "actualizarPlaneacion") {
        actualizarPlaneacion($_POST["datos"], $_POST["id_planeacion"]);
    }
    if ($_POST["accion"] == "cargarGestionEducativa") {
        cargarGestionEducativa($_POST["id_planeacion"]);
    }
    if ($_POST["accion"] == "actualizarGestionEducativa") {
        actualizarGestionEducativa($_POST['id_planeacion'], $_POST['idTema'], $_POST['indicadores'], $_POST['tactico']);
    }

}

function cargarJornadas()
{
    include "conexion.php";
    $data = array('error' => 0, 'mensaje' => '', 'html' => '');
    $sql = "SELECT id_jornada, jornada FROM jornada";

    $array = array();
    if ($rs = $con->query($sql)) {
        if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
            $data['html'] = '<option value="0">Selecciona una opción</option>';
            for ($i = 0; $i < count($filas); $i++) {

                $data['html'] .= '<option value="' . $filas[$i]['id_jornada'] . '">' . $filas[$i]['jornada'] . '</option>';
            }
        }
    } else {
        print_r($conexion->errorInfo());
        $data['mensaje'] = "No se realizo la consulta";
        $data['error'] = 1;
    }

    echo json_encode($data);
}

function cargarPoblacion()
{
    include "conexion.php";
    $data = array('error' => 0, 'mensaje' => '', 'html' => '');
    $sql = "SELECT id_tipoPoblacion, tipoPoblacion FROM tipopoblacion";

    $array = array();
    if ($rs = $con->query($sql)) {
        if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
            $data['html'] = '<option value="0">Selecciona una opción</option>';
            for ($i = 0; $i < count($filas); $i++) {
                // $array[] = $fila;

                $data['html'] .= '<option value="' . $filas[$i]['id_tipopoblacion'] . '">' . $filas[$i]['tipopoblacion'] . '</option>';
            }
        }
    } else {
        print_r($conexion->errorInfo());
        $data['mensaje'] = "No se realizo la consulta";
        $data['error'] = 1;
    }
    // $arr = array();
    echo json_encode($data);
}

function cargarEstrategias()
{
    include "conexion.php";
    $data = array('error' => 0, 'mensaje' => '', 'html' => '');
    $sql = "SELECT id_estrategia, nombreestrategia FROM estrategias";

    $array = array();
    if ($rs = $con->query($sql)) {
        if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
            $data['html'] = '<option value="0">Selecciona una opción</option>';
            for ($i = 0; $i < count($filas); $i++) {
                // $array[] = $fila;

                $data['html'] .= '<option value="' . $filas[$i]['id_estrategia'] . '">' . $filas[$i]['nombreestrategia'] . '</option>';
            }
        }
    } else {
        print_r($conexion->errorInfo());
        $data['mensaje'] = "No se realizo la consulta";
        $data['error'] = 1;
    }
    // $arr = array();
    echo json_encode($data);
}

function cargarTacticos()
{
    include "conexion.php";
    $data = array('error' => 0, 'mensaje' => '', 'html' => '');
    $sql = "SELECT id_tactico, nombretactico
            FROM tactico
            WHERE id_estrategia = " . $_POST["idEstrategia"] . "
            AND id_tactico IN(36, 1, 7, 6, 37, 29, 14, 22, 35, 34, 4, 19)";

    $array = array();
    if ($rs = $con->query($sql)) {
        if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
            $data['html'] = '<option value="0">Selecciona una opción</option>';
            for ($i = 0; $i < count($filas); $i++) {
                // $array[] = $fila;

                $data['html'] .= '<option value="' . $filas[$i]['id_tactico'] . '">' . $filas[$i]['nombretactico'] . '</option>';
            }
        }
    } else {
        print_r($conexion->errorInfo());
        $data['mensaje'] = "No se realizo la consulta";
        $data['error'] = 1;
    }
    // $arr = array();
    echo json_encode($data);
}

function cargarEtapas()
{
    include "conexion.php";
    $data = array('error' => 0, 'mensaje' => '', 'html' => '');
    $sql = "SELECT id_etapaproceso, etapaproceso
            FROM etapaproceso";

    $array = array();
    if ($rs = $con->query($sql)) {
        if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {

            for ($i = 0; $i < count($filas); $i++) {
                // $array[] = $fila;

                // $data['html'].= '<option value="'.$filas[$i]['id_tactico'].'">'.$filas[$i]['nombretactico'].'</option>';
                $data['html'] .= '<button id="btnGestion_' . $filas[$i]['id_etapaproceso'] . '" name="button1id" class="btn btn-success" onclick="seleccionarEtapa(' . $filas[$i]['id_etapaproceso'] . ');">' . $filas[$i]['etapaproceso'] . '</button>';
            }
        }
    } else {
        print_r($conexion->errorInfo());
        $data['mensaje'] = "No se realizo la consulta";
        $data['error'] = 1;
    }

    echo json_encode($data);
}

function cargarEntidades($idIntervencion)
{
    include 'conexion.php';
    $data = array('error' => 0, 'mensaje' => '', 'html' => '');
    if ($con) {
        $sql = "SELECT id_barrio, id_vereda FROM intervenciones WHERE id_intervenciones = $idIntervencion";
        if ($rs = $con->query($sql)) {
            if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
                $barrio = $filas[0]['id_barrio'];
                $vereda = $filas[0]['id_vereda'];
                if (is_null($barrio)) {
                    $data = cargarEntidadPorVereda($vereda);
                } else {
                    $data = cargarEntidadesPorBarrio($barrio);
                }
            }
        } else {
            print_r($con->errorInfo());
            $data['mensaje'] = "No se realizo el insert de contacto";
            $data['error'] = 1;
        }
    }
    echo json_encode($data);
}

function cargarEntidadPorVereda($idVereda)
{

    include 'conexion.php';
    $data = array('error' => 0, 'mensaje' => '', 'html' => '', 'tipo' => '');

    if ($con) {
        //Datos de la zona que se selecciono
        $sql = "SELECT e.id_entidad,e.nombreentidad,t.id_tipoentidad,t.tipoentidad
                FROM entidades as e,tipoentidad as t
                    WHERE veredas_id_veredas= '" . $idVereda . "'
                AND e.id_tipoentidad = t.id_tipoentidad
              ";

        if ($rs = $con->query($sql)) {
            if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {

                $data['html'] = '<option value="0">Selecciona tu opción</option>';

                for ($i = 0; $i < count($filas); $i++) {
                    $data['html'] .= '<option value="' . $filas[$i]['id_entidad'] . '">' . $filas[$i]['nombreentidad'] . '</option>';
                }

                $data['tipo'] = '<option value="0" class="disabled">Selecciona tu opción</option>';

                for ($i = 0; $i < count($filas); $i++) {
                    $data['tipo'] .= '<option value="' . $filas[$i]['id_tipoentidad'] . '">' . $filas[$i]['tipoentidad'] . '</option>';
                }

            }
        } else {
            print_r($con->errorInfo());
            $data['mensaje'] = "No se realizo la consulta de entidades por vereda";
            $data['error'] = 1;
        }

        return $data;
    }
}

function cargarTipoEntidad()
{
    include 'conexion.php';
    $data = array('error' => 0, 'mensaje' => '', 'html' => '', 'tipo' => '');

    if ($con) {
        //Datos de la zona que se selecciono
        $sql = "SELECT * FROM public.tipoentidad";

        if ($rs = $con->query($sql)) {
            if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
                $data['html'] = '<option value="0">Selecciona tu opción</option>';

                for ($i = 0; $i < count($filas); $i++) {
                    $data['html'] .= '<option value="' . $filas[$i]['id_tipoentidad'] . '">' . $filas[$i]['tipoentidad'] . '</option>';
                }
            }
        } else {
            print_r($con->errorInfo());
            $data['mensaje'] = "No se realizo la consulta de tipo entidades";
            $data['error'] = 1;
        }

        echo json_encode($data);
    }
}

function cargarEntidadesPorBarrio($idBarrio)
{

    include 'conexion.php';
    $data = array('error' => 0, 'mensaje' => '', 'html' => '', 'tipo' => '');

    if ($con) {
        //Datos de la zona que se selecciono
        $sql = "SELECT e.id_entidad,e.nombreentidad,t.id_tipoentidad,t.tipoentidad
                FROM entidades as e,tipoentidad as t
                WHERE id_barrio= '" . $idBarrio . "'
                AND e.id_tipoentidad = t.id_tipoentidad
              ";

        if ($rs = $con->query($sql)) {
            if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {

                $data['html'] = '<option value="0">Selecciona tu opción</option>';

                for ($i = 0; $i < count($filas); $i++) {
                    $data['html'] .= '<option value="' . $filas[$i]['id_entidad'] . '">' . $filas[$i]['nombreentidad'] . '</option>';
                }

            }
        } else {
            print_r($con->errorInfo());
            $data['mensaje'] = "No se realizo la consulta de entidades";
            $data['error'] = 1;
        }

        return $data;
    }
}

function cargarEntidadesTotales()
{
    include 'conexion.php';
    $data = array('error' => 0, 'mensaje' => '', 'html' => '');

    if ($con) {
        $sql = "SELECT id_entidad, nombreentidad
        FROM entidades";

        if ($rs = $con->query($sql)) {
            if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
                $data['html'] = '<option value="0">Selecciona tu opción</option>';

                for ($i = 0; $i < count($filas); $i++) {
                    $data['html'] .= '<option value="' . $filas[$i]['id_entidad'] . '">' . $filas[$i]['nombreentidad'] . '</option>';
                }
            }
        } else {
            print_r($con->errorInfo());
            $data['mensaje'] = "No se realizo la consulta de entidades";
            $data['error'] = 1;
        }
        echo json_encode($data);
    }
}

function guardarPlaneacion($nombreContacto, $cargoContacto, $telefonoContacto, $correoContacto, $fecha, $lugar, $jornada, $comunidad, $poblacion, $observaciones, $idIntervencion, $idEtapa, $idEntidad, $contacto)
{

    include 'conexion.php';
    $data = array('error' => 0, 'mensaje' => '', 'html' => '');

    if ($con) {
        //Guardar contacto
        if ($contacto == 1) {
            //Verificar si identidad es nulo (Gestion de redes)
            if ($idEntidad == '') {
                //Cuando entidad es nulo, se añade el contacto a un registro general del municipio correspondiente.
                $sql = "INSERT INTO contactos (id_contacto, nombrecontacto, cargo,telefono,correo,confirmado,entidades_id_entidad)
                VALUES (nextval('sec_contactos'),'" . $nombreContacto . "', '" . $cargoContacto . "', '" . $telefonoContacto . "', '" . $correoContacto . "', '0',(
                    SELECT mun.id_municipio
                    FROM intervenciones inte
                    LEFT OUTER JOIN barrios bar ON bar.id_barrio = inte.id_barrio
                    LEFT OUTER JOIN veredas ver ON ver.id_veredas = inte.id_vereda
                    LEFT OUTER JOIN comunas com ON com.id_comuna = bar.id_comuna
                    JOIN municipios mun ON mun.id_municipio = com.id_municipio OR mun.id_municipio = ver.id_municipio
                    WHERE inte.id_intervenciones = $idIntervencion
                ));
                    ";
            } else {
                $sql = "INSERT INTO contactos (id_contacto, nombrecontacto, cargo,telefono,correo,confirmado,entidades_id_entidad)
                VALUES (nextval('sec_contactos'),'" . $nombreContacto . "', '" . $cargoContacto . "', '" . $telefonoContacto . "', '" . $correoContacto . "', '0','" . $idEntidad . "');
                    ";
            }

            if ($rs = $con->query($sql)) {
            } else {
                print_r($con->errorInfo());
                $data['mensaje'] = "No se realizo el insert de contacto";
                $data['error'] = 1;
            }
        }

        if ($idEntidad == "") {
            //Insertar la planeacion
            $sql = "INSERT INTO planeacion (id_planeacion, etapaproceso_id_etapaproceso, fecha, lugarencuentro, id_jornada, comunidadespecial,id_tipopoblacion,observaciones)
                VALUES (nextval('sec_planeacion'),'" . $idEtapa . "', '" . $fecha . "', '" . $lugar . "', '" . $jornada . "', '" . $comunidad . "', '" . $poblacion . "', '" . $observaciones . "');
                  ";
        } else {
            $sql = "INSERT INTO planeacion (id_planeacion, etapaproceso_id_etapaproceso, fecha, lugarencuentro, id_jornada, comunidadespecial,id_tipopoblacion,observaciones, id_entidad)
                VALUES (nextval('sec_planeacion'),'" . $idEtapa . "', '" . $fecha . "', '" . $lugar . "', '" . $jornada . "', '" . $comunidad . "', '" . $poblacion . "', '" . $observaciones . "', '" . $idEntidad . "');
                  ";

        }

        if ($rs = $con->query($sql)) {

            //obtener el ultimo id insertado
            $sql = "SELECT MAX(id_planeacion) as id_planeacion FROM planeacion";

            if ($rs = $con->query($sql)) {
                if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
                    $idPlaneacion = $filas[0]['id_planeacion'];

                    if ($idPlaneacion != "") {

                        // SE INSERTA EN planeaciones_por_intervencion

                        $sql = "INSERT INTO planeaciones_por_intervencion (id_planeaciones_por_intervencion, planeacion_id_planeacion,
                                    intervenciones_id_intervenciones)
                                    VALUES (nextval('sec_planeaciones_por_intervencion'),'" . $idPlaneacion . "', '" . $idIntervencion . "'); ";

                        if ($rs = $con->query($sql)) {
                            $data['mensaje'] = "Guardado Exitosamente";
                            $data['idIntervencion'] = $idIntervencion;
                            $data['idPlaneacion'] = $idPlaneacion;

                        } //
                        else {
                            print_r($con->errorInfo());
                            $data['mensaje'] = "No se inserto la planeaciones_por_intervencion";
                            $data['error'] = 1;
                        }

                    }
                } else {
                    print_r($con->errorInfo());
                    $data['mensaje'] = "No se realizo la consulta de id intervencion";
                    $data['error'] = 1;
                }

            }
        } else {
            print_r($con->errorInfo());
            $data['mensaje'] = "No se inserto la intervencion";
            $data['error'] = 1;
        }

        echo json_encode($data);
    }
}

function guardarGestionRedes($idPlaneacion, $indicadores)
{

    include 'conexion.php';
    $data = array('error' => 0, 'mensaje' => '', 'html' => '');

    if ($con) {
        $array = array();

        //se recorren los indicadores
        foreach ($indicadores as $idIndicador) {
            //Insertar la indicadores_por_planeacion
            $sql = "INSERT INTO indicadores_por_planeacion (indicadores_id_indicador, planeacion_id_planeacion)
            VALUES ('" . $idIndicador . "', '" . $idPlaneacion . "');
                ";

            if ($rs = $con->query($sql)) {

            } else {
                print_r($con->errorInfo());
                $data['mensaje'] = "No se realizo el insert indicadores_por_planeacion";
                $data['error'] = 1;
            }
        }

        //Insertar la tactico_por_planeacion
        $sql = "INSERT INTO tactico_por_planeacion (tactico_id_tactico, planeacion_id_planeacion)
            VALUES (25, '" . $idPlaneacion . "');
              ";

        if ($rs = $con->query($sql)) {

        } else {
            print_r($con->errorInfo());
            $data['mensaje'] = "No se realizo el insert tactico_por_planeacion";
            $data['error'] = 1;
        }

        echo json_encode($data);
    }
}

function guardarGestionEducativa($idPlaneacion, $idTema, $indicadores, $tactico)
{

    include 'conexion.php';
    $data = array('error' => 0, 'mensaje' => '', 'html' => '');

    if ($con) {
        //consultar subtemas por temas
        $sql = "SELECT temas, id_subtema, subtemas
                FROM temas as tem
                LEFT JOIN subtemas as sub ON sub.id_temas = tem.id_temas
                WHERE tem.id_temas = '" . $idTema . "'";
        if ($rs = $con->query($sql)) {
            if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
                //Verificar si existe un subtema relacionado con tema, sino insertar subtema con el nombre del tema
                if ($filas[0]['id_subtema'] == null) {
                    $sql = "INSERT INTO subtemas(id_subtema, subtemas, id_temas)
                                        VALUES (nextval('sec_subtemas'), '" . $filas[0]['temas'] . "', " . $idTema . ");";
                    if ($rs = $con->query($sql)) {

                    } else {
                        print_r($con->errorInfo());
                        $data['error'] = 1;
                        $data['mensaje'] = "No se pudo realizar el insert de subtemas";
                    }
                }
                //Si existe subtema, lo inserta en subtemas
                $sql = "SELECT id_subtema FROM subtemas WHERE id_temas = " . $idTema . "; ";
                if ($rs = $con->query($sql)) {
                    if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
                        for ($i = 0; $i < count($filas); $i++) {
                            //Insertar la subtemas_por_planeacion
                            $sql = "INSERT INTO subtemas_por_planeacion (id_subtemas_por_planeacion, subtemas_id_subtema, planeacion_id_planeacion)
                                                    VALUES (nextval('sec_subtemas_por_planeacion'),'" . $filas[$i]['id_subtema'] . "', '" . $idPlaneacion . "');";

                            if ($rs = $con->query($sql)) {

                            } else {
                                print_r($con->errorInfo());
                                $data['mensaje'] = "No se realizo el insert subtemas_por_planeacion";
                                $data['error'] = 1;
                            }
                        }
                    }
                }
            }
        } else {
            print_r($conexion->errorInfo());
            $data['mensaje'] = "No se realizo la consulta";
            $data['error'] = 1;
        }

        //se recorren los indicadores
        foreach ($indicadores as $idIndicador) {
            //Insertar la indicadores_por_planeacion
            $sql = "INSERT INTO indicadores_por_planeacion (indicadores_id_indicador, planeacion_id_planeacion)
                            VALUES ('" . $idIndicador . "', '" . $idPlaneacion . "');";

            if ($rs = $con->query($sql)) {

            } else {
                print_r($con->errorInfo());
                $data['mensaje'] = "No se realizo el insert indicadores_por_planeacion";
                $data['error'] = 1;
            }

        }

        //Insertar la tactico_por_planeacion
        $sql = "INSERT INTO tactico_por_planeacion (tactico_id_tactico, planeacion_id_planeacion)
            VALUES ('" . $tactico . "', '" . $idPlaneacion . "');";

        if ($rs = $con->query($sql)) {

        } else {
            print_r($con->errorInfo());
            $data['mensaje'] = "No se realizo el insert tactico_por_planeacion";
            $data['error'] = 1;
        }
        echo json_encode($data);
    }
}

function consultarTemas($idComportamientos, $idCompetencia)
{

    include 'conexion.php';
    $data = array('error' => 0, 'mensaje' => '', 'html' => '');

    if ($con) {
        //Datos de la indicador
        $sql = "SELECT id_temas,temas
                from temas
                WHERE compe_por_compo_compe_id_compe = '" . $idCompetencia . "'
                AND compe_por_compo_compo_id_compo = '" . $idComportamientos . "'
                AND id_temas IN (10, 19, 51, 8, 53, 11, 21, 48, 2)";

        if ($rs = $con->query($sql)) {
            if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {

                $data['html'] = '<option value="0">Selecciona tu opción</option>';

                for ($i = 0; $i < count($filas); $i++) {
                    $data['html'] .= '<option value="' . $filas[$i]['id_temas'] . '">' . $filas[$i]['temas'] . '</option>';
                }

            }
        } else {
            print_r($con->errorInfo());
            $data['mensaje'] = "No se realizo la consulta de temas";
            $data['error'] = 1;
        }

        echo json_encode($data);
    }

}

function consultarIndicadoresGE()
{

    include 'conexion.php';
    $data = array('error' => 0, 'mensaje' => '', 'html' => '');

    if ($con) {
        //Datos de la indicador
        $sql = "SELECT id_indicador,nombreindicador
                from indicadores_ge WHERE tipo=1
              ";

        if ($rs = $con->query($sql)) {
            if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {

                // $data['html']= '<option value="0">Selecciona tu opción</option>';
                $data['html'] = "";
                for ($i = 0; $i < count($filas); $i++) {
                    // $data['html'].= '<option value="'.$filas[$i]['id_temas'].'">'.$filas[$i]['temas'].'</option>';
                    $data['html'] .= '<div class="checkbox">
                    <label class="grisTexto"><input id="indicador' . $filas[$i]['id_indicador'] . '" type="checkbox" value="' . $filas[$i]['id_indicador'] . '"> ' . $filas[$i]['nombreindicador'] . '</label>
                  </div>';
                }

            }
        } else {
            print_r($con->errorInfo());
            $data['mensaje'] = "No se realizo la consulta de indicadores";
            $data['error'] = 1;
        }

        $sql = "SELECT id_indicador,nombreindicador
                from indicadores_ge WHERE tipo=0
              ";
        if ($rs = $con->query($sql)) {
            if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {

                $data['indGr'] = "";
                for ($i = 0; $i < count($filas); $i++) {
                    $data['indGr'] .= '<div class="checkbox">
                    <label class="grisTexto"><input type="checkbox" value="' . $filas[$i]['id_indicador'] . '"> ' . $filas[$i]['nombreindicador'] . '</label>
                  </div>';
                }

            }
        } else {
            print_r($con->errorInfo());
            $data['mensaje'] = "No se realizo la consulta de indicadores";
            $data['error'] = 1;
        }

        echo json_encode($data);
    }

}

function guardarNuevaEntidad($idIntervencion, $nombreEntidad, $direccion, $telefono, $tipo_entidad, $nodo)
{
    include 'conexion.php';

    $data = array('error' => 0, 'mensaje' => '', 'html' => '');
    if ($con) {
        $sql = "SELECT id_barrio, id_vereda FROM intervenciones WHERE id_intervenciones = $idIntervencion";
        if ($rs = $con->query($sql)) {
            if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
                $barrio = $filas[0]['id_barrio'];
                $vereda = $filas[0]['id_vereda'];
                if (is_null($barrio)) {
                    $sql = "INSERT INTO public.entidades(
                        id_entidad, nombreentidad, id_barrio, veredas_id_veredas, direccion, telefono, id_tipoentidad, nodo)
                        VALUES (nextval('sec_entidades'),
                                '" . $nombreEntidad . "',
                                null,
                                " . $vereda . ",
                                '" . $direccion . "',
                                '" . $telefono . "',
                                " . $tipo_entidad . ",
                                '" . $nodo . "');";
                } else {
                    $sql = "INSERT INTO public.entidades(
                        id_entidad, nombreentidad, id_barrio, veredas_id_veredas, direccion, telefono, id_tipoentidad, nodo)
                        VALUES (nextval('sec_entidades'),
                                '" . $nombreEntidad . "',
                                " . $barrio . ",
                                null,
                                '" . $direccion . "',
                                '" . $telefono . "',
                                " . $tipo_entidad . ",
                                '" . $nodo . "');";
                }
            }
        } else {
            print_r($con->errorInfo());
            $data['mensaje'] = "No se realizo el insert de contacto";
            $data['error'] = 1;
        }
    }

    if ($rs = $con->query($sql)) {
        $data['mensaje'] = "Guardado Exitosamente";
    } else {
        print_r($con->errorInfo());
        $data['mensaje'] = "No se pudo insertar la entidad";
        $data['error'] = 1;
    }
    echo json_encode($data);
}

function cargarPlaneacionFormulario($id_planeacion)
{
    include "conexion.php";
    $data = array('error' => 0, 'mensaje' => '', 'html' => '');
    $sql = "SELECT * FROM planeacion WHERE id_planeacion = $id_planeacion";

    $array = array();
    if ($rs = $con->query($sql)) {
        if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
            $data['html'] = $filas;
        }
    } else {
        print_r($con->errorInfo());
        $data['mensaje'] = "No se realizo la consulta";
        $data['error'] = 1;
    }
    echo json_encode($data);
}

function cargarGestionEducativa($id_planeacion)
{
    include 'conexion.php';
    $data = array('error' => 0, 'mensaje' => '', 'html' => '');
    if ($con) {
        $sql = "SELECT DISTINCT id_entidad, tact.id_tactico, estr.id_estrategia, tem.id_temas, ipp.indicadores_id_indicador
        FROM planeacion as pl
        JOIN tactico_por_planeacion as tpp ON pl.id_planeacion = tpp.planeacion_id_planeacion
        JOIN tactico as tact ON tpp.tactico_id_tactico = tact.id_tactico
        JOIN estrategias estr ON tact.id_estrategia = estr.id_estrategia
        LEFT JOIN subtemas_por_planeacion as spp ON spp.planeacion_id_planeacion = pl.id_planeacion
        LEFT JOIN subtemas as sub ON sub.id_subtema = spp.subtemas_id_subtema
        LEFT JOIN temas as tem ON tem.id_temas = sub.id_temas
        JOIN indicadores_por_planeacion as ipp ON ipp.planeacion_id_planeacion = pl.id_planeacion
        WHERE pl.id_planeacion = $id_planeacion;";

        if ($rs = $con->query($sql)) {
            if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
                $cantfilas = count($filas);
                for ($i = 0; $i < $cantfilas; $i++) {
                    $data['html'] = $filas;
                    $data['html'][0]['indicadores_id_indicador'] = array($data['html'][0]['indicadores_id_indicador']);
                    array_push($data['html'][0]['indicadores_id_indicador'], $filas[$i]['indicadores_id_indicador']);
                }
            } else {
                print_r($con->errorInfo());
                $data['mensaje'] = "No se realizo la consulta";
                $data['error'] = 1;
            }
        }
    }
    echo json_encode($data);
}

function actualizarPlaneacion($datos, $id_planeacion)
{
    include "conexion.php";
    $data = array('error' => 0, 'mensaje' => '', 'html' => '');

    $sql = "UPDATE planeacion
        SET fecha='" . $datos['fecha'] . "', lugarencuentro='" . $datos['lugar_encuentro'] . "', id_jornada=" . $datos['jornada'] . ",
        comunidadespecial=" . $datos['comunidad'] . ", id_tipopoblacion=" . $datos['poblacion'] . ",
        observaciones='" . $datos['observaciones'] . "'
        WHERE id_planeacion = $id_planeacion; ";

    if ($rs = $con->query($sql)) {
        $data['mensaje'] = "Actualizado exitosamente";
    } else {
        print_r($con->errorInfo());
        $data['mensaje'] = "Error al actualizar";
        $data['error'] = 1;
    }

    echo json_encode($data);

}

function actualizarGestionEducativa($id_planeacion, $idTema, $indicadores, $tactico)
{

    include 'conexion.php';
    $data = array('error' => 0, 'mensaje' => '', 'html' => '');

    if ($con) {
        //consultar subtemas por temas
        $sql = "SELECT temas, id_subtema, subtemas
        FROM temas as tem
        LEFT JOIN subtemas as sub ON sub.id_temas = tem.id_temas
        WHERE tem.id_temas = '" . $idTema . "'";

        $array = array();
        if ($rs = $con->query($sql)) {
            if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
                $cantfilas = count($filas);
                if ($filas[0]['id_subtema'] == null) {
                    $sql = "INSERT INTO subtemas(id_subtema, subtemas, id_temas)
                            VALUES (nextval('sec_subtemas'), '" . $filas[0]['temas'] . "', " . $idTema . ");";
                    if ($rs = $con->query($sql)) {
                        $sql = "SELECT * FROM subtemas WHERE id_temas = '" . $idTema . "'";
                        if ($rs = $con->query($sql)) {
                            if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
                            }
                        }
                    } else {
                        $data['error'] = 1;
                        $data['mensaje'] = "No se pudo realizar el insert de subtemas";
                    }
                }
                $sql = "SELECT *
                        FROM subtemas_por_planeacion
                        WHERE planeacion_id_planeacion = '" . $id_planeacion . "'";
                if ($rs = $con->query($sql)) {
                    $filassub = $rs->fetchAll(PDO::FETCH_ASSOC);
                    $cantrowsub = count($filassub);
                    if ($cantrowsub >= 1) {
                        for ($i = 0; $i < $cantfilas; $i++) {
                            $sql = "UPDATE subtemas_por_planeacion
                                    SET subtemas_id_subtema = '" . $filas[$i]['id_subtema'] . "'
                                    WHERE planeacion_id_planeacion = '" . $id_planeacion . "'";
                            if ($rs = $con->query($sql)) {

                            } else {
                                print_r($con->errorInfo());
                                $data['mensaje'] = "No se realizo el update subtemas_por_planeacion";
                                $data['error'] = 1;
                            }
                        }
                    } else {
                        $sql = "SELECT temas, id_subtema, subtemas
                                    FROM temas as tem
                                    LEFT JOIN subtemas as sub ON sub.id_temas = tem.id_temas
                                    WHERE tem.id_temas = '" . $idTema . "'";
                        if ($rs = $con->query($sql)) {
                            $filas = $rs->fetchAll(PDO::FETCH_ASSOC);
                            if ($filas) {
                                $sql = "INSERT INTO subtemas_por_planeacion (id_subtemas_por_planeacion, subtemas_id_subtema, planeacion_id_planeacion)
                                            VALUES (nextval('sec_subtemas_por_planeacion'),'" . $filas[0]['id_subtema'] . "', '" . $id_planeacion . "');";
                                if ($rs = $con->query($sql)) {

                                } else {
                                    print_r($con->errorInfo());
                                    $data['mensaje'] = "No se realizo el insert subtemas_por_planeacion";
                                    $data['error'] = 1;
                                }
                            }
                        }
                    }
                }
            }
        } else {
            print_r($con->errorInfo());
            $data['mensaje'] = "No se realizo la consulta";
            $data['error'] = 1;
        }
        //se recorren los indicadores
        foreach ($indicadores as $idIndicador) {
            //Insertar la indicadores_por_planeacion
            $sql = "SELECT indicadores_id_indicador
                            FROM indicadores_por_planeacion
                            WHERE indicadores_id_indicador = '" . $idIndicador . "'
                            AND planeacion_id_planeacion = '" . $id_planeacion . "'";
            if ($rs = $con->query($sql)) {
                $filas = $rs->fetchAll(PDO::FETCH_ASSOC);
                $cantfilas = count($filas);
                if ($cantfilas >= 1) {
                } else {
                    $sql = "INSERT INTO indicadores_por_planeacion (indicadores_id_indicador, planeacion_id_planeacion)
                                        VALUES ('" . $idIndicador . "', '" . $id_planeacion . "');";
                    if ($rs = $con->query($sql)) {

                    } else {
                        print_r($con->errorInfo());
                        $data['mensaje'] = "No se realizo el insert indicadores_por_planeacion";
                        $data['error'] = 1;
                    }
                }
            } else {
                print_r($con->errorInfo());
                $data['mensaje'] = "No se realizo el update indicadores_por_planeacion";
                $data['error'] = 1;
            }
        }
        //Insertar la tactico_por_planeacion
        $sql = "UPDATE public.tactico_por_planeacion
            SET tactico_id_tactico='" . $tactico . "'
            WHERE planeacion_id_planeacion = '" . $id_planeacion . "';";
        if ($rs = $con->query($sql)) {

        } else {
            print_r($con->errorInfo());
            $data['mensaje'] = "No se realizo el update tactico_por_planeacion";
            $data['error'] = 1;
        }
        echo json_encode($data);
    }
}
