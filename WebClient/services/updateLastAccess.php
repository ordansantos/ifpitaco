<?php

include_once("getRoot.php");
include_once('redirect.php');
include_once 'prepareAuth.php';

if (!prepare()) {
    return;
} else{
    redirectPost(getRoot().'WebService/postUpdateLastAccess');
}

?>