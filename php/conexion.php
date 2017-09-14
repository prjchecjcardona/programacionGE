<?php
    echo "\n";
    $serverName = "tcp:gestion-educativa.database.windows.net";
	$database = "gestion_educativa";
	$uid = "usr_gestion_educativa";
	$pwd = "YXj0q9JctrQoatODR4lr";
	
	 //Establishes the connection
	 $con = new PDO( "sqlsrv:server=$serverName ; Database = $database", $uid, $pwd);
	 if( $con ) {
     echo "Conexión establecida.<br />";
	}else{
     echo "Conexión no se pudo establecer.<br />";
     die( print_r( sqlsrv_errors(), true));
}

?>