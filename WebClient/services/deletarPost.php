
<?php
	include("getRoot.php");
        
	$url = getRoot();
        
	include ('redirect.php');
        
	session_start();
        
        $_POST['id_usuario'] = $_SESSION['id_usuario'];
        
	echo redirectPost($url.'WebService/postDeletePublicacao');
	
?>



