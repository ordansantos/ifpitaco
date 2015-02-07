<?php

	include("getRoot.php");
	
	$url = getRoot();
	
	include ('redirect.php');
	
	session_start();
	
	if ($_SESSION['id_usuario'] == trim(redirectGet($url.'WebService/getUsuarioByComentarioPostId/'.$_POST['comentario_post_id'])))
		redirectPost($url.'WebService/postDeleteComentario');

?>
