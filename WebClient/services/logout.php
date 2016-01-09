<?php
        include_once("getRoot.php");
        include_once('redirect.php');
	
        session_start();
	
        if ($_SESSION['token'] != '') {
            $_POST['token'] = $_SESSION['token'];
            redirectPost (getRoot().'WebService/logout');
        }
        
	$_SESSION = array();
	
	if (isset($_COOKIE[session_name()])) {
		setcookie(session_name(), '', time()-42000, '/');
	}
	
	session_destroy();
	
	header("location:../index.php");
?>
	
