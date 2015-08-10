<?php

	include("getRoot.php");
	include ('redirect.php');
        
	$url = getRoot();
	
	session_start();
        
        $_POST['id_usuario'] = $_SESSION['id_usuario'];
	
	echo redirectPost($url.'WebService/postDeleteComentario');

?>
