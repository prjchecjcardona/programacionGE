<?php

if (!isset($_SESSION['user'])) {
    $userError = ["message" => "No has inicado sesión!", "error" => 1];
    echo json_encode($userError);
} else {
    $nombre = $_SESSION['user']['nombre'];
    echo json_encode($_SESSION['user']);
}
