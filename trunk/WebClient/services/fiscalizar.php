
<?php
	
	include ('redirect.php');
	include("getRoot.php");
	$url = getRoot();
	
	session_start();

	$_POST["usuario_id"] = trim($_SESSION['id_usuario']); 
	
	echo redirectPostFileFiscalizacao($url.'WebService/postFiscalizacao');
	
?>
