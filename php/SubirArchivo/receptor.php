<?php
// require("../controlador/session.php");
// require("../controlador/config.php");
// require("../serviciosTecnicos/utilidades/UtilConexion.php");
// $conexion = new UtilConexion();
// $pdo = $conexion->getPDO();
// PARA EJECUTAR LA CONSULTA $sth = $pdo->prepare( $sql );

$ruta="../../archivos/";
@$nombre_archivo=$_FILES['file']['name'];

// $ext = pathinfo($nombre_archivo, PATHINFO_EXTENSION);
// $cedula1=explode("_",$nombre_archivo);
// $cedula=$cedula1[0];
// @$nombre_archivo=$cedula."_".$accion."_".getStamp().".".$ext;

// echo $ruta.$nombre_archivo;
// die;

	// $ruta.=$carpeta."/";
	// @mkdir($ruta, 0755);
	move_uploaded_file($_FILES['file']['tmp_name'],$ruta.$nombre_archivo);
	
	// $sql = "INSERT INTO subtemas_por_planeacion (id_subtemas_por_planeacion, subtemas_id_subtema, planeacion_id_planeacion)
							// VALUES (nextval('sec_subtemas_por_planeacion'),'".$filas[$i]['id_subtema']."', '".$idPlaneacion."'); 
							  // ";
							  
		// if ($rs = $con->query($sql)) {
			
			 
		// }
		// else
		// {
			// print_r($con->errorInfo());
			// $data['mensaje']="No se realizo el insert subtemas_por_planeacion";
			// $data['error']=1;
		// }

	// move_uploaded_file($_FILES['file']['tmp_name'],$ruta);
		
		// $ruta=$ruta."".$nombre_archivo;
		// $cedula1=explode("_",$nombre_archivo);
		// $cedula=$cedula1[0];
		// $tipoNovedad=$_GET['tipoSoporte'];
		// $rs = null;
		
		// if ($tipoNovedad == 295 || $tipoNovedad == 294 ){
		// //llamar al procedimiento que guarda en la tabla TSOPOTE
		// $sql = "CALL SPAGREGARSOPORTE($cedula,$tipoNovedad,'$ruta');";
	
		
				// if ($rs = $conexion->getPDO()->query($sql)) 
				// {
					// if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC))
					// {               
						// $filas = $filas[0]['pIdNovedad'];
						// if ($filas != "" and $tipoNovedad ==295 )
						// {
						   // $sql = "CALL SPAGREGARSOPORTECASOESPECIAL($filas, '$fechaI', '$fechaF');";
						   // $rs = null;
							// if ($rs = $conexion->getPDO()->query($sql)) 
							// {
								// if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC))
								// {               
									// // $filas = $filas[0]['pIdNovedad'];
										   
								// }
							// }
							// else 
							// {
								// $array = 0;
								// print_r($conexion->getPDO()->errorInfo()); die();
								
							// }
						// }		   
					// }
				// }
				// else 
				// {
					// $array = 0;
					// print_r($conexion->getPDO()->errorInfo()); die();
					
				// }
		// else if($tipoNovedad == 299){
			// $sql = "CALL SPAGREGARSOPORTECONVOCATORIA($convocatoria,$tipoNovedad,'$ruta');";
						   // $rs = null;
							// if ($rs = $conexion->getPDO()->query($sql)) 
							// {
								// if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC))
								// {               
									// // $filas = $filas[0]['pIdNovedad'];
										   
								// }
							// }
							// else 
							// {
								// $array = 0;
								// print_r($conexion->getPDO()->errorInfo()); die();
								
							// }
		// }
		
	   
	// echo "bien";


function getStamp(){
  $now = (string)microtime();
  $now = explode(' ', $now);
  $mm = explode('.', $now[0]);
  $mm = $mm[1];
  $now = $now[1];
  $segundos = $now % 60;
  $segundos = $segundos < 10 ? "$segundos" : $segundos;
  return strval(date("YmdHi",mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))) . "$segundos$mm");
}

?>