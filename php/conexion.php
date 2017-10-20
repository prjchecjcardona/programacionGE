<?php
    //echo "\n";
    // $serverName = "tcp:gestion-educativa.database.windows.net";
	// $database = "gestion_educativa";
	// $uid = "usr_gestion_educativa";
	// $pwd = "YXj0q9JctrQoatODR4lr";
	
	// $serverName = "tcp:gestion-educativa.database.windows.net";
	$database = "GestionEducativa1";
	$uid = "postgres";
	$pwd = "gestion";
	
	 //Establishes the connection
	 $con = new PDO( "pgsql:host=localhost; port=5432;Database = $database", $uid, $pwd);
	 if( $con ) {
	 	
	}else{
     echo "Conexión no se pudo establecer.<br />";
     // die( print_r( sqlsrv_errors(), true));
}


?>