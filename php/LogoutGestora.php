<?php
session_start();
session_unset();
session_destroy();
ob_start();
header("location:https://sites.google.com/gestioneducativachec-um.com/gestora/paginagestora");
ob_end_flush(); 
//include 'home.php';
//include 'home.php';
exit();
?>