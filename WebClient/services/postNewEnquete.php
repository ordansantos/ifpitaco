<?php
	
	include ('redirect.php');
	
	session_start();

	$_POST["usuario_id"] = trim($_SESSION['id_usuario']); 
	echo redirectPostFileNewEnquete('http://localhost/WebService/postEnquete');
	
?>