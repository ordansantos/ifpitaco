

<?php
	
	include("redirect.php");
	
	$id = redirectPost("http://localhost/WebService/postLogin");

	if ($id != '0'){
		session_start();
		$_SESSION['id_usuario'] = $id;
		$_SESSION['nm_usuario'] = redirectGet('http://localhost/WebService/getNomeById/' . $id);
		$_SESSION['foto'] = '../'.redirectGet('http://localhost/WebService/getFotoPerfilById/'.$id);
	} 
	
	echo $id;
?>
