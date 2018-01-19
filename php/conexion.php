<?php
    
/* --LOCAL-- 
$database = "GestionEducativa1";
$uid = "postgres";
$pwd = "gestion";
$host = "localhost"; */

$database = "d4asqdqb9dlt9p";
$uid = "ntafkvnrqqlbig";
$pwd = "300113b0978731b5003f9916b2684ec44d7eafdeb2f3a36dca99bfcd115f33f1";
$host = "ec2-54-197-233-123.compute-1.amazonaws.com";

//establecer la conexión
$con = new PDO( "pgsql:host=$host; port=5432;dbname = $database", $uid, $pwd);
if( $con ) {
	
}else{
	echo "Conexión no se pudo establecer.<br />";
	// die( print_r( sqlsrv_errors(), true));
}


?>