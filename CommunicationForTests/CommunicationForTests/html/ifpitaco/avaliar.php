
	
	

	
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
	<li ><a href="opinar.php">Opine</a></li>
	<li ><a href="fiscalizar.php">Fiscalize</a></li>
	<li class="active"><a href="avaliar.php">Avalie</a></li>
	<li ><a href="index.php">Início</a></li>
  </ul>
  <a href="index.php"><img src="index/images/logo.jpg" /></a>
<br/><br/><br/><br/><br/><br/>
<div id="topPanel">
			
			<h2 class="avaliar" style="text-align:left; text-indent:20px">Avaliar</h2>

		<form method="POST" action="avaliar_post.php">
			<table style="margin:0px auto">
			<tr>
			
			<td style="padding: 10px"> 
			<input type="radio" name="avaliacao_nome" id="radio1" value="banheiro" class="css-checkbox" checked/>  
			<label for="radio1" class="css-label radGroup1">Banheiro</label> 
			</td>
			
			<td style="padding: 10px">
			<input type="radio" name="avaliacao_nome" id="radio2" value="professores" class="css-checkbox"/> 
			<label for="radio2" class="css-label radGroup1">Professores</label> 
			</td>
			
			<td style="padding: 10px">
			<input type="radio" name="avaliacao_nome" id="radio3" value="cantina" class="css-checkbox"/> 
			<label for="radio3" class="css-label radGroup1">Cantina</label> 
			<td>
			
			</tr>
			<tr>
			
			<td style="padding: 10px">
			<input  type="radio" name="avaliacao" id="radio4" value="otimo" class="css-checkbox" checked/>
			<label for="radio4" class="css-label radGroup1">Ótimo</label> 
			</td>
			
			<td style="padding: 10px"> 
			<input type="radio" name="avaliacao" value="regular" id=radio5 class="css-checkbox"/> 
			<label for="radio5" class="css-label radGroup1">Regular</label> </td>
			</td>
			
			
			<td style="padding: 10px">
			<input type="radio" name="avaliacao" value="pessimo" id=radio6 class="css-checkbox"/>
			<label for="radio6" class="css-label radGroup1">Péssimo</label> 
			</td>
			
			</tr>
			</table>
			<br/>
			<p style="text-align:center"><input  type="submit" value="Avaliar"/></p>
			<br/>
		</form>
		
		<?php
			
			error_reporting(E_ALL ^ E_DEPRECATED);
			include ("conexao/conexao.php");
			$result = mysql_query ("SELECT * FROM avaliacao WHERE entidade='banheiro' AND nota='otimo'");
			$a = mysql_num_rows ($result);
			$result = mysql_query ("SELECT * FROM avaliacao WHERE entidade='banheiro' AND nota='regular'");
			$b = mysql_num_rows ($result);
			$result = mysql_query ("SELECT * FROM avaliacao WHERE entidade='banheiro' AND nota='pessimo'");
			$c = mysql_num_rows ($result);
			
			$result = mysql_query ("SELECT * FROM avaliacao WHERE entidade='professores' AND nota='otimo'");
			$d = mysql_num_rows ($result);
			$result = mysql_query ("SELECT * FROM avaliacao WHERE entidade='professores' AND nota='regular'");
			$e = mysql_num_rows ($result);
			$result = mysql_query ("SELECT * FROM avaliacao WHERE entidade='professores' AND nota='pessimo'");
			$f = mysql_num_rows ($result);

			$result = mysql_query ("SELECT * FROM avaliacao WHERE entidade='cantina' AND nota='otimo'");
			$g = mysql_num_rows ($result);
			$result = mysql_query ("SELECT * FROM avaliacao WHERE entidade='cantina' AND nota='regular'");
			$h = mysql_num_rows ($result);
			$result = mysql_query ("SELECT * FROM avaliacao WHERE entidade='cantina' AND nota='pessimo'");
			$i = mysql_num_rows ($result);
			echo"
			
			<table class='notas'>
			
			
			<th>
				
				<td class='nota' style='color:blue'>Ótimo</td>
				<td class='nota' style='color:blue'>Regular</td>
				<td class='nota' style='color:blue'>Péssimo</td>
			</th>
			
			<tr >
				<td class='nota' style='color:black'>Banheiro</td>
				<td class='nota' style='color:red'>$a</td>
				<td class='nota' style='color:red'>$b</td>
				<td class='nota' style='color:red'>$c</td>
			</tr>
			
			<tr>
				<td class='nota' style='color:black'>Professores</td>
				<td class='nota' style='color:red'>$d</td>
				<td class='nota' style='color:red'>$e</td>
				<td class='nota' style='color:red'>$f</td>
			</tr>
			
			<tr>
				<td class='nota' style='color:black'>Banheiro</td>
				<td class='nota' style='color:red'>$g</td>
				<td class='nota' style='color:red'>$h</td>
				<td class='nota' style='color:red'>$i</td>
			</tr>
			
			</table>
			
			";
		?>
</div>
</div>
</body>
</html>

		