<?php

function checkLogin() {
    
    session_start();

    if ($_SESSION['token'] == '') {
        header("location: index.php");
    }
    
    require_once ("getRoot.php");
    require_once ('redirect.php');

    $url = getRoot();
    
    $_POST['token'] = $_SESSION['token'];
    
    $response = json_decode(redirectPost($url . 'WebService/getStatus/'));
    
    if ($response->status == "unauthorized") {
        require_once 'logout.php';
        header("location: index.php");
    }
    
    if ($response->status == "uncomplete") {
        header("location: completar.php");
    }
    
    if ($response->status == "fb_uncomplete") {
        header("location: completar.php?from=fb");
    }
    
    $info = $response->data;
    $_SESSION['foto'] = $info->perfil;
    $_SESSION['name'] = $info->nm_usuario;
    $_SESSION['grupo'] = $info->grupo;
    $_SESSION['is_admin'] = $info->is_admin;
}
