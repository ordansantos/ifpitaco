<?php

require_once ("getRoot.php");
require_once ('redirect.php');

$url = getRoot();
session_start();

if ($_SESSION['id_usuario'] == '') {
        echo '0';
        exit(0);
}

$_POST["usuario_id"] = trim($_SESSION['id_usuario']);

$base64 = "";

if(!empty($_FILES) && $_FILES['imagem']['name']) {
    $path_image = $_FILES['imagem']['tmp_name'];
    $type_image = pathinfo($path_image, PATHINFO_EXTENSION);
    $data_image = file_get_contents($path_image);
    $base64 = 'data:image/' . $type_image . ';base64,' . base64_encode($data_image);
}

$_POST["imagem"] = $base64;

echo redirectPost($url.'WebService/postFiscalizacao');

