

<?php
	error_reporting(E_ALL ^ E_DEPRECATED);
	include ("conexao/conexao.php");
	
	$entidade = $_POST["avaliacao_nome"];
	$nota = $_POST["avaliacao"];
	$sql = mysql_query ("INSERT INTO avaliacao (entidade, nota) VALUES ('$entidade', '$nota')");
	mysql_close ($conexao);
	header ('Location: avaliar.php');
	
	
?>	