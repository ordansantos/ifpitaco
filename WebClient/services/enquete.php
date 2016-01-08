<?php

include_once("getRoot.php");
include_once('redirect.php');
include_once 'prepareAuth.php';

if (!prepare()) {
    echo '{"status":"unauthorized"}';
}else{

    $base64 = "";

    if(!empty($_FILES) && $_FILES['imagem']['name']) {
        $path_image = $_FILES['imagem']['tmp_name'];
        $type_image = pathinfo($path_image, PATHINFO_EXTENSION);
        $data_image = file_get_contents($path_image);
        $base64 = 'data:image/' . $type_image . ';base64,' . base64_encode($data_image);
    }

    $_POST["imagem"] = $base64;

    echo redirectPost(getRoot().'WebService/postEnquete');
}

