
	
<!DOCTYPE html>

<head>
<title>IFPitaco</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="index/style.css" rel="stylesheet" type="text/css" />
<link href="stylesheet.css" rel="stylesheet" type="text/css" />
<style>
#comentarios{
height: 270px;
overflow: scroll;
}
</style>

<script language="JavaScript">


function reloaded() {
    var elem = document.getElementById('comentarios');
    elem.scrollTop = 99999999;
    return false;
}

</script>
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.js"></script>
     <script type="text/javascript">
		 
     $(document).ready(function() {
			
            $("#submit").click(function(){
			
			var arr = { nome: $("#nome").val(), comentario: $("#comentario").val() };
			
            $.ajax({
            url: "http://179.180.149.74/WebServer/postComentario",
            type: "POST",
            data : JSON.stringify(arr),
            dataType: "json",
            async: false
          //  success: function () {
         //       window.alert ("Hello! I am an alert box!!");
         //   },
          //  error: function()
          //  {
           //       window.alert ("erro");
          //  }
        });

    }); 

    });
    </script>



</head>
<body onload="reloaded()">

<div id="topPanel">
  <ul>
    
	<li class="active"><a href="opinar.php">Opine</a></li>
	<li ><a href="fiscalizar.php">Fiscalize</a></li>
	<li><a href="avaliar.php">Avalie</a></li>
	<li><a href="index.php">Início</a></li>
  </ul>
  <a href="index.php"><img src="index/images/logo.jpg" /></a>
<br/><br/><br/><br/><br/><br/>

<div id="topPanel">
	<h2 class="opinar" style="text-align:left; text-indent:20px">Opinar</h2>
	<div>
	<form method="POST" action="" >
		
		<br/><br/>
		<p class="pergunta">Qual sua opinião acerca da opção selecionada? </p>
		<br/>
		Nome: <input type="text" name="nome" id="nome"/> </br>
		<textarea rows=4 cols= 50 name="comentario" id="comentario">Digite aqui sua opiniao</textarea></br>
		<input type="submit" id="submit"/>
	</form>
	</div>
	<div id="comentarios">

		<?php
	
		$cURL = curl_init('http://localhost/WebServer/getComentario');
		
		curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);

		$resultado = curl_exec($cURL);

		curl_close($cURL);
		
		//echo $resultado;
		
		$obj = json_decode($resultado);
		
		$comentarios = $obj->comentarios;
	
		foreach($comentarios as $campo){


			echo 
			"
			<br/>
			<table class='tabela'>
			<tr><td><p class='nome_id'>usuário: $campo->nome</p><p class='opiniao'>$campo->comentario</p></table></td><tr>
			</table>
			
			";	
		}
		
		?>

	
	</div>
</div>
</div>
</body>
</html>

		


