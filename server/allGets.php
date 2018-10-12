<?php
include 'lib.php';

$api = new gestionEducativa();

if (isset($_POST['get'])) {

/* ------------------------ GET FICHEROS ------------------------ */
    if ($_POST['get'] == "getFicheros") {
        if (isset($_POST)) {
            $json = $api->getFicheros();
        } else {
            $json = "No se recibieronlos datos de manera adecuada";
        }

        echo json_encode($json);
    }

/* ------------------------ GET COMPORTAMIENTOS ------------------------ */
    if ($_POST['get'] == "getComportamientos") {
        if (isset($_POST)) {
            $json = $api->getComportamientos();
        } else {
            $json = "No se recibieron los datos de manera adecuada";
        }

        echo json_encode($json);
    }

/* ------------------------ GET ENTIDADES ------------------------ */
    if ($_POST['get'] == "getEntidades") {
        if (isset($_POST)) {
            $json = $api->getEntidades();
        } else {
            $json = "No se recibieron los datos de manera adecuada";
        }

        echo json_encode($json);
    }

/* ------------------------ GET COMUNAS ------------------------ */
    if ($_POST['get'] == "getComunas") {
        if (isset($_POST)) {
            $json = $api->getComunas();
        } else {
            $json = "No se recibieron los datos de manera adecuada";
        }

        echo json_encode($json);
    }

/* ------------------------ GET CONSULTAS ------------------------ */
    if ($_POST['get'] == "getBarrios") {
        if (isset($_POST)) {
            $json = $api->getBarrios();
        } else {
            $json = "No se recibieron los datos de manera adecuada";
        }

        echo json_encode($json);
    }

/* ------------------------ GET ESTRATEGIAS ------------------------ */
    if ($_POST['get'] == "getEstrategias") {
        if (isset($_POST)) {
            $json = $api->getEstrategias();
        } else {
            $json = "No se recibieron los datos de manera adecuada";
        }

        echo json_encode($json);
    }

/* ------------------------ GET VEREDAS ------------------------ */
    if ($_POST['get'] == "getVeredas") {
        if (isset($_POST)) {
            $json = $api->getVeredas();
        } else {
            $json = "No se recibieron los datos de manera adecuada";
        }

        echo json_encode($json);
    }

/* ------------------------ GET MUNICIPIOS ------------------------ */
    if ($_POST['get'] == "getMunicipio") {
        if (isset($_POST)) {
            $json = $api->getMunicipio();
        } else {
            $json = "No se recibieron los datos de manera adecuada";
        }

        echo json_encode($json);
    }

/* ------------------------ GET CONTACTOS ------------------------ */
    if ($_POST['get'] == "getContactos") {
        if (isset($_POST)) {
            $json = $api->getContactos();
        } else {
            $json = "No se recibieron los datos de manera adecuada";
        }

        echo json_encode($json);
    }
}
