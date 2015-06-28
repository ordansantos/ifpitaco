


<?php
	include("getRoot.php");
	
	$url = getRoot();
	
	include ('redirect.php');
	
	session_start();

	$_POST["usuario_id"] = trim($_SESSION['id_usuario']); 
	
	echo redirectPost($url.'WebService/postProposta');
	
?>
