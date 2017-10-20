<?php
    
/* --LOCAL-- 
$database = "GestionEducativa1";
$uid = "postgres";
$pwd = "gestion";
$host = "localhost"; */

$database = "d5sdlsrh69tg7v";
$uid = "yockkrzyjzfmtt";
$pwd = "e0c3afe7326d5f8f377730b3e166e2456363333d972a44a2b7ad424c5be019ac";
$host = "ec2-54-235-119-0.compute-1.amazonaws.com";

//establecer la conexión
$con = new PDO( "pgsql:host=$host; port=5432;dbname = $database", $uid, $pwd);
if( $con ) {
	
}else{
	echo "Conexión no se pudo establecer.<br />";
	// die( print_r( sqlsrv_errors(), true));
}


?>