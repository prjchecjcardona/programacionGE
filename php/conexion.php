<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

//DEV
$database = "GE5";
$uid = "postgres";
$pwd = "1234";
$host = "localhost";

/* //PRODUCCION
$database = "gestjjlg_gestion_educativa";
$uid = "gestjjlg_usr_gestion";
$pwd = "r!Hh7XNv22E(";
$host = "127.0.0.1";  */


//pgsql:host=$host;port=5432;dbname=$database;user=$uid;password=$pwd
//pgsql:host=$host; port=5432;dbname = $database", $uid, $pwd

//establecer la conexión
$con = new PDO( "pgsql:host=$host;port=5432;dbname=$database;user=$uid;password=$pwd");


if( $con) {

}else{
	echo "Conexión no se pudo establecer.<br />";
	// die( print_r( sqlsrv_errors(), true));
}

?>