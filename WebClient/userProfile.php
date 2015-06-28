

<?php

	session_start();
	if ($_SESSION['id_usuario'] == '') {
    	header ("location: index.php");
	}
	
	include("services/redirect.php");
	include("services/getRoot.php");
	$url = getRoot();
	$id = $_SESSION['id_usuario'];
	$foto = redirectGet($url.'WebService/getFotoPerfilById/'.$id);
	$user = redirectGet($url.'WebService/getNomeById/' . $id);
	

?>

<?php

	$json = redirectGet($url.'WebService/getUsuarioById/'.$_GET['id']);

	$usuario = json_decode($json);
	
	if (sizeof($usuario) == 0)
		header ("location: index.php");
		
	$usuario = $usuario[0];
	
	$nome = $usuario->nm_usuario;
	$tipo = $usuario->usuario_tipo;
	$curso = $usuario->curso;
	$ano_periodo = $usuario->ano_periodo;
	$grau_academico = $usuario->grau_academico;
	$image_perfil = $usuario->perfil;
	$time = redirectGet($url.'WebService/getLastAccess/'. $_GET['id']);
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
		
		
		<script src="js/ifpitaco/toPlainText.js"></script>
		
		<link rel="stylesheet" type="text/css"  href="css/user_profile.css">
		<link rel="stylesheet" type="text/css"  href="css/search.css">
		<link rel="stylesheet" type="text/css" href="css/foto_size.css">
	</head>
<script>

oTime = <?php echo $time?>



function addUserToSearchList (id, nome, foto, tipo){
	var li = document.createElement ('li');
	li.id = 'user_searched_id_'+id;
	li.className= 'user_searched';
	
	li.innerHTML = '\
		<a href="userProfile.php?id='+id+'">\
		<img class="f45x45 pull-left" src="'+foto+'"></img>\
		<h3>'+nome+'</h3>\
		<h5>'+tipo+'</h5>\
		<\a>';
	
	document.getElementById('search_list').appendChild(li);
}

function createSearchList(){
	
	$('#search_list').attr('id', 'search_list_old');
	
	var ul = document.createElement ('ul');
	ul.className = "list-unstyled";
	ul.id = 'search_list';
	document.getElementById('search_form').appendChild(ul);
	
}

function getUsersFromBusca(nome, handleData){

   $.ajax({ 
	  	type: 'GET', 
	  	url: '../WebService/getBuscaUsuario/'+nome, 
	  	data: { get_param: 'value' }, 
	 	dataType:'json',
		cache: false,
	    success: function (data) { 
	    	handleData (data.usuarios);
	    },
	    error: function (){
	    	handleData ([]);
	    }
	});
}

var delayTimer;
var gettingUsers = false;

function doSearch(text) {

	if (gettingUsers) return;
	gettingUsers = true;
	
	if (text == ''){
		$('#search_list').remove();
		$('#search_form')[0].reset();
		clearTimeout(delayTimer);

		gettingUsers = false;
		return;
	}
	
	
    if( /[^a-zA-Z]/.test( text ) ){
    	gettingUsers = false;
		return;
    }
	
    clearTimeout(delayTimer);
    
    delayTimer = setTimeout(function() {

		createSearchList();
	
		getUsersFromBusca(text, function(usuariosFromBusca){
			for (i = 0; i < usuariosFromBusca.length; i++){
				u = usuariosFromBusca[i];
				addUserToSearchList(
					u.id_usuario, u.nm_usuario, u.perfil, u.usuario_tipo		
				);
			}
			
			//Se colocar o Jquery fora, não atualiza!
			$('.user_searched').hover(function(){
				$(this).css('background-color', 'rgba(100, 108, 164, 0.9)');
			});
			
			$('.user_searched').mouseleave(function(){
				$(this).css('background-color', 'white');
			});
			$('#search_list_old').remove();
			gettingUsers = false;
		});
		
		
    }, 100); //Tempo após outra digidatação
    
}


function doSearchSubmitted(){
	
	text = $('#search_input')[0].value;
	
    if( /[^a-zA-Z]/.test( text ) )
       return false;
	    
	if (text == '') return false;
	
	getUsersFromBusca(text, function(usuariosFromBusca){
		if (usuariosFromBusca.length == 0) return;
		
		u = usuariosFromBusca[0].id_usuario;
		
		window.location.assign("userProfile.php?id="+u);
	});
	
	return false;
}

$(document).ready(function(){

	setInterval(function () {updateLastAccess()}, 1000 * 20);
	
	tempo_passado(oTime.time);
	$('#search_form')[0].reset();
	$('#search_form').focusout(function(){
	/*	  Se desfocar em cima do form é por que clicou no filho, logo não deve ser removido
			para realizar a troca de página */
			
		if (!$('#search_form').is(':hover')){
			$('#search_list').remove();
			$('#search_form')[0].reset();
			clearTimeout(delayTimer);
		}
	}); 
	
});

function updateLastAccess(){
	   $.ajax({url:'services/updateLastAccess.php'});
		  return false;
}

function tempo_passado (t){

	var d = new Date()
	var n = d.getTimezoneOffset();
	//timezone = n * 60 * 1000;
	timezone = 0;
	var maximo_tempo = 1000 * 30;
	
	var dt = new Date(t);
	var now = new Date();
	
	time = now.getTime() - dt.getTime() + timezone;

	
	
	if (time <= maximo_tempo){
		$('#status').html ('online');
		$('#status').css ('color', 'green');
		$('#status').css ('font-size', '18px');
	} else{
		$('#status').html ('offline');
		$('#status').css ('color', '#d9534f');
		$('#status').css ('font-size', '16px');
	}
		
}


</script>
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
	    
	    <!-- Inline block -->
	    <div>
	    	<form  role="search" onsubmit="return doSearchSubmitted()" id="search_form">
	          	<div class="inner-addon right-addon">
    	   			<i class="glyphicon glyphicon-search"></i>	
	    			<input type="text" id='search_input' class="navbar-left form-control" placeholder="Procurar Usuário"  onkeyup="doSearch(this.value)">
	   			 </div>
	    	</form>
	    </div>
	    
	  </div>
	</nav>
	
	
	<div class="container-fluid" style="margin-top: 50px">

		<div class="row">
		 
		  		<!-- Profile -->
		  		<div class="col-md-2 text-center " id="profile">
		  			<div class="img-thumbnail">
				  		<a href="userProfile.php?id=<?php echo $id?>"><img src="<?php echo $foto?>"  alt="..." class="f120x120"></a>
				  	</div>
				  		<a href="userProfile.php?id=<?php echo $id?>"><h3><script>document.write(toPlainText('<?php echo $user?>'));</script></h3></a>
		
				</div>
				  
				 <!-- Meio -->
				<div class="col-md-4 col-md-offset-2" id="mid" >
	 
					<div id="feed" class="text-center">
					
						<img  src="<?php echo $image_perfil ?>"  alt="..." class="f120x120 img-thumbnail">
						
						<div class="dados">
							<h2><script>document.write(toPlainText('<?php echo $nome?>'));</script></h2>
							<h5 id="status"></h5>
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