<?php

function prepare(){
    
    session_start();
    
    if ($_SESSION['token'] == '') {
        return false;
    }
    
    $_POST['token'] = $_SESSION['token'];
    
    return true;
}