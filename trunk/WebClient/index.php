

<?php
	session_start();

	if ($_SESSION['nm_usuario'] != '') {
    	header ("location: home.php");
	}
	
	$user = $_SESSION['nm_usuario'];
	$foto = $_SESSION['foto'];
?>

<!DOCTYPE html>

<html>
 
	<head>
		<meta charset="utf-8"/>
		<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
		<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
		<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" rel="stylesheet">
		<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
		

	</head>

	
	<script type="text/javascript">
	
	 $(document).ready(function() {
		 $("#form").submit (function(event){
			 	event.preventDefault();
			 	
				var values = $(this).serialize();
				$.ajax({		
					type: "POST",
					url: "login.php",
					data : values,
					success: function (data){
						if ($.trim(data) != '0')
							window.location.assign("home.php");
						 else{
							$('#error').hide();
							$('#error').fadeIn("slow");
						 }
					}
				});
	 	});
		 
	 });
	</script>


  <body>
  
	<div class="container">	
		<div class="col-md-4 col-md-offset-4" style="margin-top: 5%">
		 
	      <form class="form-signin" id="form">
	        
	        <img class="img-responsive" id="logo" src="images/logo2.png">
	        
	        <label for="inputEmail" class="sr-only">Email address</label>
	        
	        <input name="email" type="email" id="inputEmail" class="form-control" placeholder="Email address" required autofocus>
	        <br/>
	        <label for="inputPassword" class="sr-only">Password</label>
	        <input name="senha" type="password" id="inputPassword" class="form-control" placeholder="Password" required>
			<br/>
			<div class="row">
				<div class="col-xs-6">
	        		<button class="btn btn-lg btn-primary btn-block" type="submit">Log in</button>
	        	</div>
	        	<div class="col-xs-6">
	        		<button onClick="parent.location='cadastrar.html'" class="btn btn-lg btn-success btn-block" type="button">Sign up</button>
		  		</div>
		  	</div>
	    	
	      </form> 
	      <br/>
		 <div class="alert alert-danger" role="alert" id="error" style="display: none">Dados Inválidos</div>
		</div>
	</div>
  </body>
  
</html>