<?php

include "lib.php";

$api = new geBanco();

$errors = array("archivo" => "", "recurso" => "");
$data = array('success' => "", 'errors' => "");

// Validate the variables

if (empty($_POST['archivo'])) {
    $errors['archivo'] = 'No has seleccionado el archivo';
} else {
    $archivo = $_POST['archivo'];
}

if (empty($_POST['recurso'])) {
    $errors['recurso'] = 'No has seleccionado el recurso a la cual deseas subir';
} else {
    $recurso = $_POST['recurso'];
}

// Return response

if (empty($errors['archivo'] == "" || $errors['recurso'] == "")) {

    $data['success'] = false;
    $data['errors'] = $errors;

} else {

    /* if (is_array($archivo)) {
        $archivoLength = sizeof($archivo);
        echo $archivoLength;
        for ($i = 0; $i < $archivoLength; $i++) {
            $json = $api->subirArchivo($archivo[$i], $recurso);
        }
    } */

    echo '<pre>', print_r($_FILES), '</pre>';

/*     $target_dir = "recursos/";
    $target_file = $target_dir . basename($_FILES['archivo']["name"]);
    echo $target_file;
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
// Check if image file is a actual image or fake image
    if (isset($_POST['archivo'])) {
        $check = getimagesize($_FILES["archivo"]["tmp_name"]);
        if ($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    } */

}

echo json_encode($data);
