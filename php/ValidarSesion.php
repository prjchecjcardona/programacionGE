<?php
//session_start();

if (session_status() == PHP_SESSION_NONE) {
	echo "habilitadas pero no existe niguna";
	session_start();
}
  
	if(session_status() == PHP_SESSION_DISABLED) {
	echo "sesion deshabilitada";
	}
	
		if(session_status() == PHP_SESSION_ACTIVE) {
		echo "sesion habilitada y existe una";
		echo count($_SESSION);
		if(isset($_SESSION["Coordinadora"]))
		{
			echo $_SESSION["Coordinadora"];
			echo $_SESSION["NombreCoordinadora"];
		}
		if(isset($_SESSION["Gestora"]))
		{
			echo $_SESSION["Gestora"];
			echo $_SESSION["NombreGestora"];
		}
		/*if (!count($_SESSION)>0) {*/
    		
		}
	
    
/*
if(isset($_SESSION["Coordinadora"]))
{
	//echo $_SESSION["Coordinadora"];
	//header("Location: ../home_Coordinadora.html");
}
else
{
	//echo "hay sesion";
	echo "no hay sesion";
	 //print_r( $_SESSION );  
}*/
?>