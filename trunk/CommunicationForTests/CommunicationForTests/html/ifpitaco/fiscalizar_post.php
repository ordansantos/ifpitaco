
<?php
	error_reporting(E_ALL ^ E_DEPRECATED);
	include ("conexao/conexao.php");
	
	$nome = $_POST["nome"];
	$entidade = $_POST["entidade"];
	$comentario = $_POST["comentario"];
	$sql = mysql_query ("INSERT INTO fiscalizacao (nome, entidade, comentario) VALUES ('$nome', '$entidade', '$comentario')");
	mysql_close ($conexao);
	header ('Location: fiscalizar.php');
	
	
?>	