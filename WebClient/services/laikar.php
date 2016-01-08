<?php

include_once("getRoot.php");
include_once('redirect.php');
include_once 'prepareAuth.php';
	
if (!prepare()) {
    echo '{"status":"unauthorized"}';
}else{
    echo redirectPost(getRoot().'WebService/postLaike');
}
