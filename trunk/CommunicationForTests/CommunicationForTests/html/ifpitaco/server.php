<?php
	$f = fopen('POST_DATA.txt', 'a');
	fwrite($f, 'Nome: '.$_POST['nome']."\r\n");
	fwrite($f, 'Sobrenome: '.$_POST['sobrenome']."\r\n");
	fwrite($f, 'Email:'.$_POST['email']."\r\n\r\n");
	
	echo 'Dados enviados com sucesso';
?>
