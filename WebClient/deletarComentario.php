<?php

	include ('redirect.php');
	
	session_start();
	
	if ($_SESSION['id_usuario'] == trim(redirectGet('http://localhost/WebService/getUsuarioByComentarioPostId/'.$_POST['comentario_post_id'])))
		redirectPost('http://localhost/WebService/postDeleteComentario');

?>
