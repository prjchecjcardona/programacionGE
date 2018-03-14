<?php

include 'conexion.php';

if (isset($_POST["accion"])) {

    if ($_POST["accion"] == "getEjecucionId") {
        getEjecucionId($_POST["idPlaneacion"], $_POST["isEjecutada"]);
    }

    if ($_POST["accion"] == "cargarTipoCedula") {
        cargarTipoCedula();
    }

}

function cargarCompetencia()
{
    include "conexion.php";
    $data = array('error' => 0, 'mensaje' => '', 'html' => '');
    $sql = "SELECT id_nivelcumplimiento, nivel_cumplimiento FROM nivelcumplimiento";

    $array = array();
    if ($rs = $con->query($sql)) {
        if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
            $data['html'] = '<option value="0">Selecciona una opción</option>';
            for ($i = 0; $i < count($filas); $i++) {

                $data['html'] .= '<div class="form-check"><label class="form-check-label grisTexto"><input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios1" value="' . $filas[$i]['id_nivelcumplimiento'] . '" > ' . $filas[$i]['nivel_cumplimiento'] . '</label></div>';

                // $data['html'].= '<option value="'.$filas[$i]['id_jornada'].'">'.$filas[$i]['jornada'].'</option>';
            }
        }
    } else {
        print_r($conexion->errorInfo());
        $data['mensaje'] = "No se realizo la consulta";
        $data['error'] = 1;
    }

    echo json_encode($data);
}


function getEjecucionId($idPlaneacion, $isEjecutada){
	include "conexion.php";
	$data = array('error'=>0,'mensaje'=>'','html'=>array());
	$sql = "
    SELECT ep.ejecucion_id_ejecucion
    FROM ejecuciones_planeacion
	";
	
    $array="";
    if ($rs = $con->query($sql)) {
        if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
            $data['html']['fecha']= $filas[0]['fecha'];
            $data['html']['lugar']= $filas[0]['lugarencuentro'];
            $data['html']['municipio']= $filas[0]['municipio'];
            $data['html']['comportamiento']= $filas[0]['comportamientos'];
            $data['html']['competencia']= $filas[0]['competencia'];
            $data['html']['estrategia']= $filas[0]['nombreestrategia'];
            $data['html']['tactico']= $filas[0]['nombretactico'];
            for ($i=0;$i<count($filas);$i++)
            {				
                $array.= '<div class="row"><div class="col-md-12">
                <label class="mr-sm-2" id="lblIndicadorChec1"><li>'.$filas[$i]['nombreindicador'].'</li></label></div></div>';
            }
            $data['html']['indicador']= $array;
        }
    }
    else
    {
        print_r($conexion->errorInfo());
        $data['mensaje']="No se realizo la consulta";
        $data['error']=1;
    }

    if($isEjecutada){
        $data['html']['datosEjec'] = array();
        //Obtiene los datos registrados en la ejecucion
        $sql = "SELECT ejec.fecha, ejec.horafinalizacion, ejec.numeroasistentes, ejec.observaciones, nc.id_nivelcumplimiento, dncxe.id_detalle_nivelcumplimiento, dncxe.nivel_cumplimiento
        FROM ejecucion ejec
        JOIN ejecuciones_por_planeacion ep ON ejec.id_ejecucion = ep.ejecucion_id_ejecucion
        JOIN planeaciones_por_intervencion pi ON ep.id_planeaciones_por_intervencion = pi.id_planeaciones_por_intervencion
        JOIN nivelcumplimiento nc ON nc.id_nivelcumplimiento = ejec.nivelcumplimiento
        JOIN detallenivelcumplimiento_por_ejecucion dncxe ON dncxe.ejecucion_id_ejecucion = ejec.id_ejecucion
        WHERE pi.planeacion_id_planeacion = $idPlaneacion";

        if ($rs = $con->query($sql)) {
            if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
                $data['html']['datosEjec']['fecha'] = $filas[0]['fecha'];
                $data['html']['datosEjec']['horafinalizacion'] = $filas[0]['horafinalizacion'];
                $data['html']['datosEjec']['numeroasistentes'] = $filas[0]['numeroasistentes'];
                $data['html']['datosEjec']['observaciones'] = $filas[0]['observaciones'];
                $data['html']['datosEjec']['nivel_cumplimiento'] = $filas[0]['id_nivelcumplimiento'];
                $data['html']['datosEjec']['detalle_nivel'] = array();
                foreach ($filas as $key => $value) {
                    $data['html']['datosEjec']['detalle_nivel'][$key] = $value['id_detalle_nivelcumplimiento'];
                }
            }
        }

        //Obtener los asistentes de la ejecucion
        $sql="SELECT asi.numerodocumento, asi.nombres, asi.apellidos, asi.celular
        FROM asistentes asi
        JOIN ejecucion_asistentes ea ON ea.id_asistente = asi.id_asistente
        JOIN ejecucion e ON e.id_ejecucion = ea.id_ejecucion
        JOIN ejecuciones_por_planeacion epp ON epp.ejecucion_id_ejecucion = e.id_ejecucion
        JOIN planeaciones_por_intervencion ppi ON ppi.id_planeaciones_por_intervencion = epp.id_planeaciones_por_intervencion
        WHERE ppi.planeacion_id_planeacion = $idPlaneacion";

        $data['html']['datosEjec']['asistentes'] = array();
        if ($rs = $con->query($sql)) {
            if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
                foreach ($filas as $key => $value) {
                    $data['html']['datosEjec']['asistentes'][$key] = array('numero_documento' => $value['numerodocumento'], 'nombres' => $value['nombres'], 'apellidos' => $value['apellidos'], 'movil' => $value['celular']);;
                }
            }
        }
    }
    
    echo json_encode($data);
}

function guardarEjecucion($fecha, $hora, $asistentes, $detalleCumplimiento, $nCumplimiento, $idPlaneacion, $idIntervencion, $arrayAsistentes, $observaciones, $nombreContacto,$cargoContacto,$telefonoContacto,$correoContacto, $contacto)
{
    include 'conexion.php';
    $data = array('error' => 0, 'mensaje' => '', 'html' => '');

    if ($con) {

        //Guardar contacto
 		if ($contacto == 1){
            $sql = "SELECT id_entidad FROM planeacion WHERE id_planeacion = $idPlaneacion";
            if ($rs = $con->query($sql)) {
                if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
                    if(is_null($filas[0]['id_entidad'])){
                         //Cuando entidad es nulo, se añade el contacto a un registro general del municipio correspondiente.
                        $sql = "INSERT INTO contactos (id_contacto, nombrecontacto, cargo,telefono,correo,confirmado,entidades_id_entidad)
                        VALUES (nextval('sec_contactos'),'".$nombreContacto."', '".$cargoContacto."', '".$telefonoContacto."', '".$correoContacto."', '0',(
                            SELECT mun.id_municipio
                            FROM intervenciones inte
                            LEFT OUTER JOIN barrios bar ON bar.id_barrio = inte.id_barrio
                            LEFT OUTER JOIN veredas ver ON ver.id_veredas = inte.id_vereda
                            LEFT OUTER JOIN comunas com ON com.id_comuna = bar.id_comuna
                            JOIN municipios mun ON mun.id_municipio = com.id_municipio OR mun.id_municipio = ver.id_municipio
                            WHERE inte.id_intervenciones = $idIntervencion
                        )); 
                            ";
                    }else{
                        $idEntidad = $filas[0]['id_entidad'];
                        $sql = "INSERT INTO contactos (id_contacto, nombrecontacto, cargo,telefono,correo,confirmado,entidades_id_entidad)
                        VALUES (nextval('sec_contactos'),'".$nombreContacto."', '".$cargoContacto."', '".$telefonoContacto."', '".$correoContacto."', '0','".$idEntidad."'); 
                            ";   
                    }
                }
            }
                   
           if ($rs = $con->query($sql)) {
           }
           else
           {
               print_r($con->errorInfo());
               $data['mensaje']="No se realizo el insert de contacto";
               $data['error']=1;
           }
       }

        //Insertar en ejecucion
        $sql = "INSERT INTO ejecucion (id_ejecucion, nivelcumplimiento,fecha,horafinalizacion,numeroasistentes,observaciones)
			VALUES (nextval('sec_ejecucion'),'" . $nCumplimiento . "', '" . $fecha . "', '" . $hora . "', '" . $asistentes . "','$observaciones');";

        if ($rs = $con->query($sql)) {

            //obtener el ultimo id insertado
            $sql = "SELECT MAX(id_ejecucion) as id_ejecucion FROM ejecucion
						";

            if ($rs = $con->query($sql)) {
                if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {

                    $id_ejecucion = $filas[0]['id_ejecucion'];
                    if ($id_ejecucion != "") {
                        //Validar si hay asistentes en la ejecucion
                        if($arrayAsistentes[0] != "1"){
                            //Añadir asistentes por ejecucion
                            foreach ($arrayAsistentes as $i => $value) {
                                $sql = "INSERT INTO public.asistentes(id_asistente, tipo_documento_id_tipo_documento, numerodocumento, nombres, apellidos, genero, cuentachec, telefonofijo, celular, direccion, correoelectronico, rol, fecha_nacimiento, manejodatos, sesionesformacion) VALUES (nextval('sec_asistentes'), " . $value['tipo_documento'] . ", '" . $value['numero_documento'] . "', '" . $value['nombres'] . "', '" . $value['apellidos'] . "', '" . $value['genero'] . "', '" . $value['cuenta_CHEC'] . "', '" . $value['telefono'] . "', '" . $value['movil'] . "', '" . $value['direccion'] . "', '" . $value['correo_electronico'] . "', '" . $value['rol'] . "', '" . $value['fecha_asistencia'] . "', " . $value['manejo_datos'] . ", " . $value['sesiones'] . ");";
                                if ($rs = $con->query($sql)) {
                                    $sql = "SELECT MAX(id_asistente) as id_asistente FROM asistentes";
                                    if ($rs = $con->query($sql)) {
                                        if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
                                            $id_asistente = $filas[0]['id_asistente'];
                                            $sql = "INSERT INTO public.ejecucion_asistentes(
                                                            id_ejecucion_asistentes, id_ejecucion, id_asistente)
                                                            VALUES (nextval('sec_ejecucion_asistentes'), " . $id_ejecucion . ", " . $id_asistente . ");";
                                            if ($rs = $con->query($sql)) {
                                                $data['mensajeAsist'] = "Guardado Exitosamente";
                                            } else {
                                                print_r($con->errorInfo());
                                                $data['mensaje'] = "No se inserto la ejecuciones por planeacion";
                                                $data['error'] = 1;
                                            }
                                        }
                                    }
                                }
                            }
                        }


                        //Se obtiene el id_planeaciones_por_intervencion
                        $sql = "SELECT id_planeaciones_por_intervencion FROM planeaciones_por_intervencion WHERE planeacion_id_planeacion = $idPlaneacion";
                        if ($rs = $con->query($sql)) {
                            if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
                                $id_planeaciones_por_intervencion = $filas[0]['id_planeaciones_por_intervencion'];
                                // SE INSERTA EN ejecuciones por planeacion

                                $sql = "INSERT INTO ejecuciones_por_planeacion (id_ejecuciones_por_planeacion, ejecucion_id_ejecucion,id_planeaciones_por_intervencion)
												VALUES (nextval('sec_ejecuciones_por_planeacion')," . $id_ejecucion . ", " . $id_planeaciones_por_intervencion . ");
												  ";

                                if ($rs = $con->query($sql)) {
                                    $data['mensaje'] = "Guardado Exitosamente";
                                    $data['idEjecucion'] = $id_ejecucion;
                                    $data['idPlaneacion'] = $idPlaneacion;

                                } //
                                else {
                                    print_r($con->errorInfo());
                                    $data['mensaje'] = "No se inserto la ejecuciones por planeacion";
                                    $data['error'] = 1;
                                }

                                //TODO REVISAR GUARDADO DE DETALLE NIVEL CUMPL EN BD
                                //se inserta en detalle nivel cumplimiento por ejecucion
                                for ($i = 0; $i < count($detalleCumplimiento); $i++) {
                                    if ($detalleCumplimiento[$i] == 1) {$nivelCumplimiento = "Completa";}
                                    if ($detalleCumplimiento[$i] == 2) {$nivelCumplimiento = "Parcial";}
                                    if ($detalleCumplimiento[$i] == 3) {$nivelCumplimiento = "No se cumplio";}

                                    

                                    //Insertar la detallenivelcumplimiento_por_ejecucion  FALTA CREA LA SECENCIA
                                    $sql = "INSERT INTO detallenivelcumplimiento_por_ejecucion (id_detallenivelcumplimiento_por_ejecucioncl, id_detalle_nivelcumplimiento, ejecucion_id_ejecucion,nivel_cumplimiento)
															VALUES (nextval('sec_detallenivelcumplimiento_por_ejecucion'),'" . $detalleCumplimiento[$i] . "', '" . $id_ejecucion . "','" . $nivelCumplimiento . "');
                                                              ";
                                                              
                                   

                                    if ($rs = $con->query($sql)) {
                                        $data['mensaje'] = "exito";
                                        $data['error'] = 0;

                                    } else {
                                        print_r($con->errorInfo());
                                        $data['mensaje'] = "No se realizo el insert detallenivelcumplimiento_por_ejecucion";
                                        $data['error'] = 1;
                                    }

                                }
                            }
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
            $data['mensaje'] = "No se realizo el insert ejecucion";
            $data['error'] = 1;
        }

        echo json_encode($data);
    }
}

function cargarTipoCedula(){
    include 'conexion.php';
    $sql = "SELECT id_tipo_documento, tipo_documento FROM public.tipo_documento;";
    if ($rs = $con->query($sql)) {
        if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
            $data['response'] = $filas;
        }
    } else {
        print_r($con->errorInfo());
        $data['mensaje'] = "No se realizo la consulta de los tipos de documento";
        $data['error'] = 1;
    }

    echo json_encode($data);
}

