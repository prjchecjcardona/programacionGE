<?php

include 'lib.php';

$api = new gestionEducativa();

if (isset($_POST)) {
    if (isset($_POST['nombres'])) {
        $nombres = $_POST['nombres'];
    } else {
        $nombres = "null";
    }

    if (isset($_POST['apellidos'])) {
        $apellidos = $_POST['apellidos'];
    } else {
        $apellidos = "null";
    }

    if (isset($_POST['cedula'])) {
        $cedula = $_POST['cedula'];
    } else {
        $cedula = "null";
    }

    if (isset($_POST['celular'])) {
        $celular = $_POST['celular'];
    } else {
        $celular = "null";
    }

    if (isset($_POST['telefono'])) {
        $telefono = $_POST['telefono'];
    } else {
        $telefono = "null";
    }

    if (isset($_POST['email'])) {
        $email = $_POST['email'];
    } else {
        $email = "null";
    }

    if (isset($_POST['entidad'])) {
        $entidad = $_POST['entidad'];
    } else {
        $entidad = "null";
    }

    if (isset($_POST['cargo'])) {
        $cargo = $_POST['cargo'];
    } else {
        $cargo = "null";
    }

    $json = $api->insertContacto($cedula, $nombres, $apellidos, $email, $telefono, $celular, $cargo);

    $query = $api->insertContactosXEntidad($cedula, $entidad);
} else {
    $json = "No se recibieron los datos de manera adecuada.";
}

echo json_encode($json);
