<?php

if( $_FILES['foto']['name'] ){

	list($width, $height) = getimagesize($_FILES['foto']['tmp_name']);
		
	echo $width.' '. $height;
	
	echo 'teste';
}
?>
