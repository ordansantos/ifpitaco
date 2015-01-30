<?php
	
	include ('redirect.php');
	
	session_start();
	
	if ($_SESSION['nm_usuario'] == '') {
		echo '0';
		return;
	}
	
	$_POST["usuario_id"] = trim($_SESSION['id_usuario']);

	echo redirectPost('http://localhost/WebService/postLaike');
	
?>