<?php

require_once ("getRoot.php");
require_once ('redirect.php');

$url = getRoot();

$base64 = "";

if(!empty($_FILES) && $_FILES['foto']['name']) {
    $path_image = $_FILES['foto']['tmp_name'];
    $type_image = pathinfo($path_image, PATHINFO_EXTENSION);
    $data_image = file_get_contents($path_image);
    $base64 = 'data:image/' . $type_image . ';base64,' . base64_encode($data_image);
}

$_POST["imagem"] = $base64;

echo redirectPost($url.'WebService/postUsuario');
