<?php

require_once ("getRoot.php");
require_once ('redirect.php');

$url = getRoot();



$response = json_decode(redirectPost($url.'WebService/systemCadastroPost'));

if ($response->status == "error"){
    echo $response->msg;
}else{
    session_start();
    $_SESSION['id_usuario'] = $response->id_usuario;
    $_SESSION['token'] = $response->token;
    echo "1";
}
    