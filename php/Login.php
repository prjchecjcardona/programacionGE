<?php

include('conexion.php');
if(isset($_GET["Id_Coordinadora"]))
{
	$Id_Coordinadora=$_GET["Id_Coordinadora"];

	if( $con )
 	{
    	//$sql = "SELECT * FROM personas where Id_Cargo = '2' AND NumeroIdentificacion = '".$Id_Coordinadora."'";
  		//$result = $con->query($sql);
 		//$sql = $con->prepare("SELECT * FROM personas where Id_Cargo = '2'");//NumeroIdentificacion = '30239536'");
 		//$sql->execute();
 		$sql = "SELECT * FROM personas where id_cargo = 2 AND numeroidentificacion = '".$Id_Coordinadora."'";
  		$result = $con->query($sql);
	  // Parse returned data, and displays them
	  	while($row = $result->fetch(PDO::FETCH_ASSOC)) {

	  // Parse returned data, and displays them
	  	//while($row = $result->fetch(PDO::FETCH_ASSOC)) {

	       $_SESSION["Coordinadora"] = $row["NumeroIdentificacion"];
	       echo "true";
	      // header("Refresh:0; url=Home_Coordinadora.html");
	  	}
	}
}


if(isset($_GET["Id_Gestora"]))
{


	$Id_Gestora=$_GET["Id_Gestora"];
	if( $con )
 	{
    	$sql = "SELECT * FROM personas where id_cargo = 4 AND numeroidentificacion = '".$Id_Gestora."'";
  		$result = $con->query($sql);
	  // Parse returned data, and displays them
	  	while($row = $result->fetch(PDO::FETCH_ASSOC)) {
	       $_SESSION["Gestora"] = $row["NumeroIdentificacion"];
			echo "true";
	       //header("Refresh:0; url=Home_Gestora.html");
	  	}
	}
}
?>
