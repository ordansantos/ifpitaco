<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

include("redirect.php");
include("getRoot.php");

$url = getRoot();

$usuario = json_decode(redirectPost($url . "WebService/postFbLogin"));

if ($usuario->status === "success") {
    session_start();

    $_SESSION['id_usuario'] = $usuario->id_usuario;
    $_SESSION['token'] = $usuario->token;

    echo '1';
} else {
    echo $usuario->msg;
}