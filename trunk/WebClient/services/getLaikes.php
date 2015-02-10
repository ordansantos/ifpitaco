<?php
	include("getRoot.php");
	
	$url = getRoot();
	
	include ('redirect.php');
	
	session_start();
	
	if ($_SESSION['id_usuario'] == '') {
		echo '0';
		return;
	}

	echo redirectGet($url.'WebService/getCntLaikesAndUserFlagByPostIdAndUserId/'.$_POST['post_id'].'/'.trim($_SESSION['id_usuario']));
	

?>