<?php

if (!isset($_SESSION['user'])) {
    $data = ["message" => "No has inicado sesión!", "error" => 1];
} else {
    $id_zona = $_POST['zona'];
    $zona_user = $_SESSION['user']['zona'];
    $rol_user = $_SESSION['user']['rol'];

    if ($rol_user == 3) {
        if ($id_zona == $zona_user) {
            $data = $_SESSION['user']['nombre'];
        } else {
            $data = ["message" => "Esta no es tu zona!", "error" => 2];
        }
    }else{
        $data = $_SESSION['user']['nombre'];
    }
}

echo json_encode($data);
