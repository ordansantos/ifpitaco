
<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

	include ('services/redirect.php');
	
	$json = redirectGet('http://localhost/WebService/getUsuarioById/'.$_GET['id']);

	$user = json_decode($json);
	
	if (sizeof($user) == 0)
		header ("location: index.php");
		
	$user = $user[0];
	
	$nome = $user->nm_usuario;
	$tipo = $user->usuario_tipo;
	$curso = $user->curso;
	$ano_periodo = $user->ano_periodo;
	$grau_academico = $user->grau_academico;
	$image_perfil = '../'.$user->perfil_120;
	
?>



<?php
	session_start();
	if ($_SESSION['nm_usuario'] == '') {
    	header ("location: index.php");
	}
	
	$user = $_SESSION['nm_usuario'];
	$foto = $_SESSION['foto'];
?>


<!DOCTYPE html>

<html>

	<head>
		
  		<meta charset="utf-8"/>
  		<link rel="shortcut icon" href="images/favicon.png">
  		<title>IFPitaco</title>
		<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
	
		<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
		<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" rel="stylesheet">
		<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
		
		
		<script src="js/toPlainText.js"></script>
		
		<link rel="stylesheet" type="text/css"  href="css/user_profile.css">
	</head>



<body>
  
  
	<nav id="bar" class="navbar navbar-default">
	  <div class="container-fluid">
	    <div class="navbar-header">
	      <a class="navbar-brand" href="home.php">
	        <img alt="Brand" src="images/logo2.png" id="logo">
	      </a>
	      
	      
	    </div>
	    <div>
	    	<button onClick="parent.location='services/logout.php'" type="submit" class="btn btn-default navbar-right" style="margin: 8px">Sair</button>
	    </div>
	  </div>
	</nav>
	
	
	<div class="container-fluid" style="margin-top: 50px">

		<div class="row">
		 
		  		<!-- Profile -->
		  		<div class="col-md-2 text-center " id="profile">.
		  
				  	<img  src="<?php echo $foto ?>"  alt="..." class="img-thumbnail">
				  	<h2><script>document.write(toPlainText('<?php echo $user?>'));</script></h2>
				  	
				</div>
				  
				 <!-- Meio -->
				<div class="col-md-4 col-md-offset-2" id="mid" >
	 
					<div id="feed" class="text-center">
					
						<img  src="<?php echo $image_perfil ?>"  alt="..." class="img-thumbnail">
						
						<div class="dados">
							<h2><script>document.write(toPlainText('<?php echo $nome?>'));</script></h2>
							
							<script>
								var tipo = '<?php echo $tipo?>';
								if (tipo == 'Aluno'){
									document.write('<h4><?php echo $curso?></h4>');
									document.write('<h4><?php echo $ano_periodo?>º Ano/Período</h4>');
								}
								if (tipo == 'Professor')
									document.write('<h4><?php echo $grau_academico?></h4>');
								document.write('<h5><?php echo $tipo?></h5>');
							</script>

						</div>
						
					</div>
		
	 			</div>

	 	</div>

	</div>

   
 </body>
  
</html>