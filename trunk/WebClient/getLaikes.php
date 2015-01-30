<?php
	
	include ('redirect.php');
	
	session_start();
	
	if ($_SESSION['nm_usuario'] == '') {
		echo '0';
		return;
	}

	echo redirectGet('http://localhost/WebService/getCntLaikesAndUserFlagByPostIdAndUserId/'.$_POST['post_id'].'/'.trim($_SESSION['id_usuario']));
	

?>