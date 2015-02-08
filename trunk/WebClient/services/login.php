

<?php
	
	include("redirect.php");
	include("getRoot.php");

	$url = getRoot();

	$id = redirectPost($url."WebService/postLogin");

	if ($id != '0'){
		session_start();
		$_SESSION['id_usuario'] = $id;
		$_SESSION['nm_usuario'] = redirectGet($url.'WebService/getNomeById/' . $id);
		$_SESSION['foto'] = redirectGet($url.'WebService/getFotoPerfilById/'.$id);
	} 
	
	echo $id;
?>
