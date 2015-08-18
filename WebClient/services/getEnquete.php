<?php

include("getRoot.php");
include ('redirect.php');

$url = getRoot();

session_start();

if ($_SESSION['id_usuario'] == '') {
        echo '0';
        return;
}

echo redirectGet(
                $url.'WebService/getEnquete/'.
                $_SESSION['id_usuario'].'/'.
                $_GET['last_enquete_id'] .'/'. 
                $_SESSION['grupo']
                );

