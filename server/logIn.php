<?php

include 'lib.php';

$api = new gestionEducativa();

if (isset($_POST['loginSubmit'])) {

    $addPaswd = 'GIkkU2Eeyw1@!8';
    $mailuid = $_POST['mailuid'];
    $passwd = $_POST['passwd'].$addPaswd;

    if (empty($mailuid) || empty($passwd)) {
        header("Location: ../iniciarSesion.html?error=emptyFields");
        exit;
    } else {
        $json = $api->logIn($mailuid, $passwd);
    }

} else {

    header("Location: ../iniciarSesion.html");
    exit;

}

echo $passwd;
echo $json;
