<?php
session_start();
if(isset($_POST["accion"]) && $_POST["accion"]=="traerNombre")
{
	traerNombre();
}
function traerNombre()
{
		$nombre="";
		$nombre = $_SESSION["nombreSesion"];
	
	echo json_encode($nombre);
}
?>