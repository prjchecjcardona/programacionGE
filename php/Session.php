<?php 
session_start();
define("INCLUDE_CHECK",true);

include('conexion.php'); 
// Starting the session


if(!isset($_SESSION["coordinadora"]) || !isset($_SESSION["gestora"]))
{		
	$_SESSION = array();
	
	if(isset($_POST["cargo"]) and $_POST["cargo"] == 2)
	{
				       
		$_SESSION["numeroIdentificacion"] = $_POST["numeroIdentificacion"];
		$_SESSION["nombreSesion"] = $_POST["nombreSesion"];
		$_SESSION["cargo"] = $_POST["cargo"];
		$_SESSION["coordinadora"]=1;
		
		
		       
	}
	elseif(isset($_POST["cargo"]) and $_POST["cargo"] == 4)
	{		

		$_SESSION["numeroIdentificacion"] = $_POST["numeroIdentificacion"];
		$_SESSION["nombreSesion"] = $_POST["nombreSesion"];
		$_SESSION["cargo"] = $_POST["cargo"];
		$_SESSION["id_zona"] = $_POST["id_zona"];
		$_SESSION["gestora"]=1;
		       
	}
	echo json_encode($_SESSION);
}
?>