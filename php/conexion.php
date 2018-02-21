<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

//DEV
/* $database = "d4asqdqb9dlt9p";
$uid = "ntafkvnrqqlbig";
$pwd = "300113b0978731b5003f9916b2684ec44d7eafdeb2f3a36dca99bfcd115f33f1";
$host = "ec2-54-197-233-123.compute-1.amazonaws.com";
 */
//PRODUCCION
$database = "d7jmsqb0pb9n11";
$uid = "ymuglgckigeyxm";
$pwd = "8a86f637e663ed9f778e1ec74e3da85d6f6aec7ce57dbbd2cf3c5c82afa3380a";
$host = "ec2-184-73-201-79.compute-1.amazonaws.com";

//establecer la conexión
$con = new PDO( "pgsql:host=$host; port=5432;dbname = $database", $uid, $pwd);
if( $con ) {
	
}else{
	echo "Conexión no se pudo establecer.<br />";
	// die( print_r( sqlsrv_errors(), true));
}


?>