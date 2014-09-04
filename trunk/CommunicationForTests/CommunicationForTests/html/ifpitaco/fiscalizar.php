<!DOCTYPE html>

<head>
<title>IFPitaco</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="index/style.css" rel="stylesheet" type="text/css" />
<link href="stylesheet.css" rel="stylesheet" type="text/css" />
</head>
<body>

<div id="topPanel">
  <ul>
    
	<li><a href="opinar.php">Opine</a></li>
	<li class="active"><a href="fiscalizar.php">Fiscalize</a></li>
	<li><a href="avaliar.php">Avalie</a></li>
	<li><a href="index.php">Início</a></li>
  </ul>
  <a href="index.php"><img src="index/images/logo.jpg" /></a>
<br/><br/><br/><br/><br/><br/>

<div id="topPanel">
	<h2 class="fiscalizar" style="text-align:left; text-indent:20px">Fiscalizar</h2>
	<form method="POST" action="fiscalizar_post.php">
		
		<br/><br/>
		Nome: <input type="text" name="nome"/> </br>
		O que está sendo fiscalizado:  <input type="text" name="entidade"/> </br>
		
		<textarea rows=4 cols= 50 name="comentario">Seu comentário: </textarea></br>
		<input type="submit"/>
	</form>
	
	<?php
		error_reporting(E_ALL ^ E_DEPRECATED);
		include ("conexao/conexao.php");
		
		$result = mysql_query ("SELECT * from fiscalizacao");
		
		
		while ($row = mysql_fetch_array($result)){
			$com = $row['comentario'];
			$nom = $row['nome'];
			$ent = $row['entidade'];
			echo 
			"
			<br/>
			<table class='tabela'>
			<tr><td><p class='nome_id'>usuário: $nom</p><p style='color:red'>Sendo fiscalizado: $ent</p><p class='opiniao'>$com</p></table></td><tr>
			</table>
			
			";
		}
		mysql_close($conexao);
	?>
	

</div>
</div>
</body>
</html>
