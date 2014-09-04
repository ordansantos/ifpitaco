
		
	<?php

		error_reporting(E_ALL ^ E_DEPRECATED);
		include ("conexao/conexao.php");
		
		$result = mysql_query ("SELECT * from comentarios");
		
		
		while ($row = mysql_fetch_array($result)){
			$com = $row['comentario'];
			$nom = $row['nome'];
			
			echo $nom;
			echo ";";
			echo $com;
			echo ";";
		}
		mysql_close($conexao);
	?>

