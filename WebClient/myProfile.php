

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
	
	
	$json = redirectGet($url.'WebService/getUsuarioById/'.$id);
	
	$usuario = json_decode($json);
	
	if (sizeof($usuario) == 0)
		header ("location: index.php");
	
	$usuario = $usuario[0];
	
	$nome = $usuario->nm_usuario;
	$tipo = $usuario->usuario_tipo;
	$curso = $usuario->curso;
	$ano_periodo = $usuario->ano_periodo;
	$grau_academico = $usuario->grau_academico;

?>



<!DOCTYPE html>

<html>

	<head>
		<!-- Bootbox -->
		<script src="js/bootbox/bootbox.min.js"></script>
		
		<link rel="stylesheet" type="text/css"  href="css/login_border.css">
		<link rel="stylesheet" type="text/css"  href="css/my_profile.css">
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
		
		<link href="pace/themes/blue/pace-theme-barber-shop.css" rel="stylesheet" />
		<script data-pace-options='{ "ajax": true }' src='pace/pace.js'></script>
		<link href="jcrop/css/jquery.Jcrop.css" rel="stylesheet" type="text/css" />
		<script src="jcrop/js/jquery.Jcrop.js"></script>
		
	</head>
<script>


//Não funciona quando não tem src



function toCrop(){

	$(function(){
	
	    $('#img').Jcrop({
	    	setSelect: [0,0,120,120],
	        aspectRatio: 1,
	        onSelect: updateCoords,
	        onChange: updateCoords
	    });
	
	});
}

//Coordenadas da imagem
function updateCoords(c)
{
	
	var img = $(".jcrop-holder img");
	
	var width = img.width();
	var height = img.height();
	
    $('#x').val(c.x/width);
    $('#y').val(c.y/height);
    $('#w').val(c.w/width);
    $('#h').val(c.h/height);

};

isThereJcrop = false;

//Mostra o preview
function readURL(input) {
	
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
        	
        	if (!isThereJcrop){
        		$("#img").show();
        		$('#img').attr('src', e.target.result);
	            toCrop();
	            isThereJcrop = true;
        	} else{
   
        		$('.jcrop-holder img').attr('src', e.target.result);
        		
        	}

        }
        reader.readAsDataURL(input.files[0]);
    }
}



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

	
	$('#pop').focusin(function (){
		$('#pop').popover('show');
	});
	
	$('#pop').focusout(function(){
		$('#pop').popover('hide');
	});
	
	$('#professor').hide();
	$("#img").hide();

	//Ao trocar o input
	$("#foto").change(function(){
	    readURL(this);
	});



	$("#form").submit (function(event){
		event.preventDefault();

		$('#submit').attr ("disabled", "disabled");
		var formData = new FormData($(this)[0]);
		$.ajax({
			type: "POST",
			url: "../WebService/alterarDados",
	        contentType: false,
	        processData: false,
			data: formData,
			success: function(data){
				
				if ($.trim(data) != '1'){
					
				} else
					window.location.assign("home.php");
				
			}, error: function(data){

				bootbox.alert("Faça o upload corretamente! Escolha uma imagem válida ou uma imagem menor que 5000 x 5000.", function() {
					window.location.assign('myProfile.php');
				});
			
			}
		});
	});


	var tipo = '<?php echo $tipo?>';
	var curso = '<?php echo $curso?>';
	var ano_periodo = <?php echo $ano_periodo?>;
	var grau_academico = '<?php echo $grau_academico?>';

	ids = {};

	ids['Informática Integrado'] = 'c1';
	ids['Manutenção e Suporte em Informática Integrado'] = 'c2';
	ids['Mineração Integrado'] = 'c3';
	ids['Petróleo e Gás Integrado'] = 'c4';
	ids['Manutenção e Suporte em Informática Subsequente'] = 'c5';
	ids['Mineração Subsequente'] = 'c6';
	ids['Construção de Edifícios Superior'] = 'c7';	
	ids['Física Superior'] = 'c8';
	ids['Letras em Língua Portuguesa Superior'] = 'c9';
	ids['Matemática Superior'] = 'c10';
	ids['Operação de Microcomputadores Proeja'] = 'c11';
	ids['Outro'] = 'c12';
	gs = {};

	gs['Especialização'] = 'g1';
	gs['Doutorado'] = 'g2';
	gs['Mestrado'] = 'g3';
	gs['Outro'] = 'g4';
	
	if (tipo == 'Aluno'){
		document.getElementById("option1").checked = true;
		document.getElementById(ids[curso]).selected = true;
		document.getElementById('a'+ano_periodo).selected = true;
		option1Click();
	}
	if (tipo == 'Professor'){
		document.getElementById("option2").checked = true;
		document.getElementById(gs[grau_academico]).selected = true;
		option2Click();
	}
	if (tipo == 'Servidor'){
		document.getElementById("option3").checked = true;
		option3Click();
	}
	
});

function updateLastAccess(){
	   $.ajax({url:'services/updateLastAccess.php'});
		  return false;
}

function option1Click(){
	$('#professor').hide();
	$('#aluno').show();
}

function option2Click(){
	$('#aluno').hide();
	$('#professor').show();
}

function option3Click(){
	$('#professor').hide();
	$('#aluno').hide();
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
	 
	
				<h2><span class="glyphicon glyphicon-pencil"></span> Editar</h2>	
	  <form class="form-signin" id="form">
				
  			<div class="form-group">
    			<label for="nm_usuario"><span style="margin-right:10px" class="glyphicon glyphicon glyphicon-user" aria-hidden="true"></span>Nome de Usuário</label>
    			<input required="required"  type="text" 
    			class="form-control" id="nm_usuario" name="nm_usuario" 
    			placeholder="Digite seu nome" value='<?php echo $nome?>'>
  			</div>

  			
		 
  		<div class="radios text-center">
		  	<label class="radio-inline">
			  <input type="radio" name="usuario_tipo" onClick="option1Click()" id="option1" value="Aluno" checked> Aluno
			</label>
			
			<label class="radio-inline">
			  <input type="radio" name="usuario_tipo" onClick="option2Click()" id="option2" value="Professor"> Professor
			</label>
			
			<label class="radio-inline">
			  <input type="radio" name="usuario_tipo" onClick="option3Click()" id="option3" value="Servidor"> Servidor
			</label>
  		</div>
	  		
	  	<div id="aluno">
			<label style="display: block"><span style="margin-right: 10px" class="glyphicon glyphicon-book" aria-hidden="true"></span>Curso</label>
			<span >
		  		<select  style="display: inline" class="curso form-control" name="curso">
		  		
			  		<optgroup label="Integrado">
					  <option value="Informática Integrado" id="c1">Informática</option>
					  <option value="Manutenção e Suporte em Informática Integrado" id="c2">Manutenção e Suporte em Informática</option>
					  <option value="Mineração Integrado" id="c3">Mineração</option>
					  <option value="Petróleo e Gás Integrado" id="c4">Petróleo e Gás</option>
					  
					</optgroup>
					
					<optgroup label="Subsequente">
						<option value="Manutenção e Suporte em Informática Subsequente" id="c5">Manutenção e Suporte em Informática</option>
						<option value="Mineração Subsequente" id="c6">Mineração</option>
					</optgroup>
					
					<optgroup label="Superior">
						<option value="Construção de Edifícios Superior" id="c7">Construção de Edifícios</option>
						<option value="Física Superior" id="c8">Física</option>
						<option value="Letras em Língua Portuguesa Superior" id="c9">Letras em Língua Portuguesa</option>
						<option value="Matemática Superior" id="c10">Matemática</option>
					</optgroup>
					
					<optgroup label="Proeja">
						<option value="Operação de Microcomputadores Proeja" id="c11">Operação de Microcomputadores</option>
					</optgroup>
					<option value="Outro" id="c12">Outro</option>	
				</select>
		
				<select style="display: inline; padding : 5px;" tabindex="0" data-trigger="focus" class="ano_periodo form-control" id="pop" name="ano_periodo" class="btn btn-default" data-container="body" data-toggle="popover" data-placement="right" data-content="Ano/Período" >
					<option value="1" id='a1'>1º</option>
					<option value="2" id='a2'>2º</option>
					<option value="3" id='a3'>3º</option>
					<option value="4" id='a4'>4º</option>
					<option value="5" id='a5'>5º</option>
					<option value="6" id='a6'>6º</option>
					<option value="7" id='a7'>7º</option>
					<option value="8" id='a8'>8º</option>
				</select>
			</span>
		</div>		
		
		<div id="professor">
			<label><span style="margin-right: 10px" class="glyphicon glyphicon-book" aria-hidden="true"></span>Grau Acadêmico</label>
			<select class="form-control" name="grau_academico">
				<option value="Especialização" id="g1">Especialização</option>
				<option value="Doutorado" id="g2">Doutorado</option>
				<option value="Mestrado" id="g3">Mestrado</option>
				<option value="Outro" id="g4">Outro</option>
			</select>
		</div>
	
		  <div class="form-group">
		 
		    <label for="foto"><span style="margin-right: 10px" class="glyphicon glyphicon glyphicon-camera" aria-hidden="true"></span>Imagem de Perfil</label>
			<input accept="image/x-png, image/gif, image/jpeg"type="file" id="foto" name="foto" class="button">
		    <p class="help-block">Escolha uma imagem para usar no seu perfil</p>
		    
		  </div>
		 
		 		 <img  class="preview" id="img" src="" />
		  	 <br/>
		  
  		  <button id="submit" class="btn btn-lg btn-primary btn-block" type="submit">Alterar Dados</button>
  		  	
  		  	
  		  	<!-- Coordenadas da foto  -->
  		    <input type="hidden" id="x" name="x" value="-1"/>
            <input type="hidden" id="y" name="y" value="-1"/>
            <input type="hidden" id="w" name="w" value="-1"/>
            <input type="hidden" id="h" name="h" value="-1"/>
  		  	<input type="hidden" name="usuario_id" value="<?php echo $id?>"/>
		  </form> 
					
		
	 	</div>

	 	</div>

	</div>

   
 </body>
  
</html>