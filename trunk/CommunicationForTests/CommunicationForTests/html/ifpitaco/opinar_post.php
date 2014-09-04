
<?php
	error_reporting(E_ALL ^ E_DEPRECATED);
	include ("conexao/conexao.php");
	
	$comentario = $_POST["opiniao"];
	$nome = $_POST["nome"];
	$sql = mysql_query("INSERT INTO comentarios(comentario, nome) VALUES ('$comentario', '$nome')");
	mysql_close($conexao);
	header ('Location: opinar.php');
?>
