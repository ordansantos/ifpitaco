
<?php
	include("getRoot.php");
	
	$url = getRoot();
	
	include ('redirect.php');
	
	session_start();
	
	if ($_SESSION['id_usuario'] == trim(redirectGet($url.'WebService/getUsuarioByPostId/'.$_POST['post_id'])))
		redirectPost($url.'WebService/postDeletePost');
	
?>



