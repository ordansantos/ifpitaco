<?php

	include("getRoot.php");
	
	$url = getRoot();
	include ('redirect.php');
	
	session_start();
	
	if ($_SESSION['id_usuario'] == '') {
		echo '0';
		return;
	}
	
	$_POST["usuario_id"] = trim($_SESSION['id_usuario']);
	
	echo redirectPost($url.'WebService/updateLastAccess');
	
?>