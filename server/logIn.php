<?php

include 'lib.php';

$api = new gestionEducativa();

if (isset($_POST['loginSubmit'])) {

    $addPaswd = 'GIkkU2Eeyw1@!8';
    $mailuid = $_POST['mailuid'];
    $pass = $_POST['passwd'].$addPaswd;

    if (empty($mailuid) || empty($pass)) {
        header("Location: ../iniciarSesion.html?error=emptyFields");
        exit;
    } else {
        $json = $api->logIn($mailuid, $pass);
    }

} else {

    header("Location: ../iniciarSesion.html");
    exit;

}

echo $json;
