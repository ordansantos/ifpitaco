
<?php

	include("getRoot.php");
	$url = getRoot();
	
	include ('redirect.php');
	
	session_start();
	
	if ($_SESSION['nm_usuario'] == '') {
		echo '0';
		exit(0);
	}

	$_POST["usuario_id"] = trim($_SESSION['id_usuario']);

	echo redirectPost($url.'WebService/postComentario');

?>