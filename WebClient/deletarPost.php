
<?php

	include ('redirect.php');
	
	session_start();
	
	if ($_SESSION['id_usuario'] == trim(redirectGet('http://localhost/WebService/getUsuarioByPostId/'.$_POST['post_id'])))
		redirectPost('http://localhost/WebService/postDeletePost');
	
?>



