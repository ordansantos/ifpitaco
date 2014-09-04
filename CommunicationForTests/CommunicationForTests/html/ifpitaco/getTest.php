<!DOCTYPE html>


<html>

	<head>
		
		<meta charset="utf-8"/>
	</head>

	<body>
		<h1>Hello World</h1>
		

		<?php
	
		$cURL = curl_init('http://localhost/WebServer/getComentario');
		
		curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);

		$resultado = curl_exec($cURL);

		curl_close($cURL);
		
		//echo $resultado;
		
		$obj = json_decode($resultado);
		
		$comentarios = $obj->comentarios;
	
		foreach($comentarios as $campo){

			echo $campo->nome;
			echo "<br/>";
			echo $campo->comentario;
			echo "<br/>";
			
		}
		
		?>
		
		
	</body>

</html>
