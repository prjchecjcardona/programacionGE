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
$database = "gestjjlg_gestion_educativa";
$uid = "gestjjlg_usr_gestion";
$pwd = "r!Hh7XNv22E(";
$host = "localhost";


//pgsql:host=$host;port=5432;dbname=$database;user=$uid;password=$pwd
//pgsql:host=$host; port=5432;dbname = $database", $uid, $pwd

//establecer la conexión
$con = new PDO( "pgsql:host=$host;port=5432;dbname=$database;user=$uid;password=$pwd");
if( $con ) {
	
}else{
	echo "Conexión no se pudo establecer.<br />";
	// die( print_r( sqlsrv_errors(), true));
}


?>