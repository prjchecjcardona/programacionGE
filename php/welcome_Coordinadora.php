<?php
 include('conexion.php');

if(isset($_POST["accion"]))
{

	if($_POST["accion"]=="traerNombreCoordinadora")
	{
		traerNombreCoordinadora();
	}

}

function traerNombreCoordinadora(){
	include "conexion.php";
	$data = array('error'=>0,'mensaje'=>'','html'=>'');
	$sql = "SELECT numeroidentificacion, nombres, apellidos FROM personas where id_cargo= '2'";
	  		// $result = $con->query($sql);
			$array=array();
			if ($rs = $con->query($sql)) {
				if ($filas = $rs->fetchAll(PDO::FETCH_ASSOC)) {
					// foreach ($filas as $fila) {
						// $array[] = $fila;
					// }

					$data['html']= '<option value="0">Selecciona tu opción</option>';
					foreach ($filas as $key => $value) {
						$data['html'].= '<option value="'.$value['numeroidentificacion'].'_2">'.$value['nombres'].' '.$value['apellidos'].'</option>';
					}
				}
			}
			else
			{
				print_r($con->errorInfo());
				$data['mensaje']="No se realizo la consulta";
				$data['error']=1;
			}
			// $arr = array();
		  echo json_encode($data);
}
?>