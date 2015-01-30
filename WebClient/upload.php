<?php

	//echo redirectPost('http://localhost/WebService/postImagemPerfil');
	
	if (@$_FILES['image']['name']){
		require ('/var/www/html/wideimage/WideImage.php');
		$novo_nome = date ("dmyhis");
		$caminho = '../uploaded_images/'.$novo_nome.'.'.'jpg';
		$image = WideImage::load ($_FILES['image']['tmp_name'])->resize(50, 50)->saveToFile($caminho);
		
	}

?>

