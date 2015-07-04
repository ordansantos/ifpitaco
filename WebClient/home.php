
<!-- TODO: Remover o id do usuário do javascript e deixar apenas no php -->

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


<!DOCTYPE html>

<html>

<head>

	<meta charset="utf-8" />
	<link rel="shortcut icon" href="images/favicon.png">
	<title>IFPitaco</title>
	
	<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
	<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
	<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" rel="stylesheet">
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
	
	
	<script src="js/ifpitaco/toPlainText.js"></script>
	
	<!-- Verifica se elemento está visível -->
	<script src="js/jquery_visible/jquery-visible.min.js"></script>
	
	<!--http://www.chartjs.org/docs/ | Gráfico pizza-->
	<script src="js/Chart/Chart.js"></script>
	
	<!-- Ícone de carregamento de enquete -->
	<script src="js/spin/spin.js"></script>
	
	<!-- Bootbox -->
	<script src="js/bootbox/bootbox.min.js"></script>
	
	<!-- CSS -->
	<link rel="stylesheet" type="text/css" href="css/tooltip_chart_customized.css">
	<link rel="stylesheet" type="text/css" href="css/search.css">
	<link rel="stylesheet" type="text/css" href="css/foto_size.css">
	<link rel="stylesheet" type="text/css" href="css/enquete_form.css">
	<link rel="stylesheet" type="text/css" href="css/home.css">
	<link rel="stylesheet" type="text/css" href="css/comentario.css">

	<script src="js/ifpitaco/sessionController.js"></script>
	
	<script>SESSION = Session(<?php echo $id?>);</script>
	
	<script src="js/ifpitaco/postController.js"></script>
	<script src="js/ifpitaco/services/comentarioDELETE.js"></script>
	<script src="js/ifpitaco/services/comentarioPOST.js"></script>
	<script src="js/ifpitaco/comentarioController.js"></script>
	<script src="js/ifpitaco/services/fiscalizacaoPOST.js"></script>
	<script src="js/ifpitaco/services/propostaPOST.js"></script>
	<script src="js/ifpitaco/services/postDELETE.js"></script>
	<script src="js/ifpitaco/likesController.js"></script>
	<script src="js/ifpitaco/services/laikePOST.js"></script>
	
	<script src="js/ifpitaco/postForm.js"></script>
	<script src="js/ifpitaco/tempoController.js"></script>
	<script src="js/ifpitaco/enqueteForm.js"></script>
	<script src="js/ifpitaco/services/enquetePOST.js"></script>
	
	<script src="js/ifpitaco/enqueteController.js"></script>

	<script src="js/ifpitaco/tools/chart.js"></script>
	
	<script src="js/ifpitaco/services/votoPOST.js"></script>
</head>



<script type="text/javascript">


	$(document).ready(function() {
		
		setInterval(function () {updateLastAccess()}, 1000 * 20);
		 
	});



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



function tempo_passado (t){

	var d = new Date()
	var n = d.getTimezoneOffset();
	timezone = n * 60 * 1000;
	
	var dt = new Date(t);
	var now = new Date();
	
	mes = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 
       'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
	
	var month = dt.getMonth();
	var day = dt.getDate();
	var year = dt.getFullYear();
	var hour = dt.getHours();
	var minute = dt.getMinutes();
	
	time = now.getTime() - dt.getTime() + timezone;
	
	var dias = parseInt(time / 86400000);
	
	time = time % 86400000;
	
	var horas = parseInt(time / 3600000);
	
	time = time % 3600000;
	
	var minutos = parseInt(time / 60000);
	
	time = time % 60000;
	
	var segundos = parseInt(time / 1000);
		
	if (dias > 28)
			return day + ' de ' + mes[month] + ' de ' + year + ' às ' + hour + ':' + minute;
	
	if (dias)
		return dias + ' dia' + (dias > 1? 's' : '');
	if (horas)
		return horas + ' hora' + (horas > 1? 's' : '');
	if (minutos)
		return minutos + ' minuto' + (minutos > 1? 's' : '');
	if (segundos)
		return segundos + ' segundo' + (segundos > 1? 's' : '');
	return 'agora mesmo';
}

function curiar_curtida(id){
	id = id.replace ('cc', '');

	
	   $.ajax({ 
		  	type: 'GET', 
		  	url: '../WebService/curiarPost/'+id, 
		  	data: { get_param: 'value' }, 
		 	dataType:'json',
			cache: false,
		    success: function (data) { 

		    	createListPeopleModal(data, 'Curiando');
		    	
		    },
		    error: function (){
		   
		    }
		});
}

function curiarEnquete(){

   $.ajax({ 
	  	type: 'GET', 
	  	url: '../WebService/curiarEnquete/'+to_update_enquete, 
	  	data: { get_param: 'value' }, 
	 	dataType:'json',
		cache: false,
	    success: function (data) { 

	    	createEnqueteModal (data, 'Curiando enquete');
	    	
	    },
	    error: function (){
	   
	    }
	});
}

function createEnqueteModal(data, titulo){
	
	colors = ['#F7464A', '#46BFBD', '#FDB45C', '#949FB1', '#4D5360'];
	
	$('#p_list_laike').remove();
	document.getElementById('p_list_people_laike_titulo').innerHTML = titulo;
	var ul = document.createElement ('div');
	
	ul.className = "list-unstyled";
	ul.id = 'p_list_laike';
	
	document.getElementById('list_people_laike_div').appendChild(ul);

	for (i = 1; i <= data.qtd_opt; i++){
		
		ul = document.createElement('ul');
		ul.className = "list-unstyled modal_enquete_curiar";
		ul.id = 'optv_'+i;
		ul.style.borderColor = colors[i-1];
		document.getElementById('p_list_laike').appendChild(ul);

		
		title = document.createElement ('li');
		title.innerHTML = '<div><span style="color:'+colors[i-1]+'" class="glyphicon glyphicon-asterisk" aria-hidden="true"></span> '+data['opt_'+ i]+'</div>';
		title.className = "modal_enquete_curiar_title";
		
		
		document.getElementById('optv_'+i).appendChild(title);
	}
	
	peoples = data.usuarios;
	
	for (i in peoples){
	
		p = peoples[i];
		
		var li = document.createElement ('li');
		li.className= 'user_searched';
		//Tamanho de cada lista
		li.style.width = '360px';
		li.innerHTML = '\
			<div style="margin-left:10px">\
			<a href="userProfile.php?id='+p.id_usuario+'">\
			<img class="f45x45 pull-left" src="'+p.perfil+'"></img>\
			<h3>'+p.nm_usuario+'</h3>\
			<h5>'+p.usuario_tipo+'</h5>\
			<\a>';

		document.getElementById('optv_'+p.voto).appendChild(li);
	}

	//Se colocar o Jquery fora, não atualiza!
	$('.user_searched').hover(function(){
		$(this).css('background-color', 'rgba(100, 108, 164, 0.9)');
	});
	
	$('.user_searched').mouseleave(function(){
		$(this).css('background-color', 'white');
	});

}

function createListPeopleModal(peoples, titulo){


	$('#p_list_laike').remove();
	document.getElementById('p_list_people_laike_titulo').innerHTML = titulo;
	var ul = document.createElement ('ul');
	ul.className = "list-unstyled";
	ul.id = 'p_list_laike';
	
	document.getElementById('list_people_laike_div').appendChild(ul);
	
	for (i in peoples){
		p = peoples[i];
		
		var li = document.createElement ('li');
		li.className= 'user_searched ';
		li.style.width = '350px';
		li.innerHTML = '\
			<div>\
			<a href="userProfile.php?id='+p.id_usuario+'">\
			<img class="f45x45 pull-left" src="'+p.perfil+'"></img>\
			<h3>'+p.nm_usuario+'</h3>\
			<h5>'+p.usuario_tipo+'</h5>\
			<\a>';

		document.getElementById('p_list_laike').appendChild(li);
	}

	//Se colocar o Jquery fora, não atualiza!
	$('.user_searched').hover(function(){
		$(this).css('background-color', 'rgba(100, 108, 164, 0.9)');
	});
	
	$('.user_searched').mouseleave(function(){
		$(this).css('background-color', 'white');
	});

}

function updateLastAccess(){
   $.ajax({url:'services/updateLastAccess.php'});
	  return false;
}

</script>



<body>

	<nav id="bar" class="navbar navbar-default">
		<div class="container-fluid">
			<div class="navbar-header">
				<a class="navbar-brand" href="home.php"> <img alt="Brand"
					src="images/logo2.png" id="logo">
				</a>
			</div>

			<div>
				<button onClick="parent.location='services/logout.php'"
					type="submit" class="btn btn-default navbar-right"
					style="margin: 8px">Sair</button>
			</div>

			<!-- Inline block -->
			<div>
				<form role="search" onsubmit="return doSearchSubmitted()"
					id="search_form">
					<div class="inner-addon right-addon">
						<i class="glyphicon glyphicon-search"></i> <input type="text"
							id='search_input' class="navbar-left form-control"
							placeholder="Procurar Usuário" onkeyup="doSearch(this.value)">
					</div>
				</form>
			</div>

		</div>
	</nav>


	<div class="container-fluid" style="margin-top: 50px">

		<div class="row">

			<!-- Profile -->
			<div class="col-md-2 text-center" id="profile">
				<div class="img-thumbnail">
					<a href="userProfile.php?id=<?php echo $id?>"><img
						src="<?php echo $foto?>" alt="..." class="f120x120"></a>
				</div>
				<a href="userProfile.php?id=<?php echo $id?>">
					<h3>
						<script>document.write(toPlainText('<?php echo $user?>'));</script>
					</h3>
				</a>
				<div class='left_options'>
					<ul class="list-unstyled">
						<li><a href="myProfile.php"><span
								class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
								Editar Perfil</a></li>
					</ul>

				</div>
			</div>

			<!-- Meio -->
			<div class="col-md-6" id="mid">
				<div class="opcoes_btns">
					<!-- Button trigger modal -->
					<button id="propor_btn" type="button"
						class="btn btn-success btn-lg" data-toggle="modal"
						data-target="#proporModal">
						<span class="glyphicon glyphicon-send" aria-hidden="true"></span>
						Propor
					</button>

					<!-- Button trigger modal -->
					<button type="button" class="btn btn btn-danger btn-lg"
						data-toggle="modal" data-target="#fiscalizarModal">
						<span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span>
						Fiscalizar
					</button>

					<!-- Button trigger modal -->
					<button type="button" class="btn btn-warning btn-lg pull-right"
						data-toggle="modal" data-target="#newEnqueteModal">
						<span class="glyphicon glyphicon-bullhorn" aria-hidden="true"></span>
						Criar Enquete
					</button>
				</div>
				<div id="feed"></div>

			</div>

			<div class="col-md-3" id="left">
				<span title="próxima" data-toggle="tooltip" data-placement="bottom"
					class="btn-lg btn pull-right" id="proxima_enquete"
					onClick="ENQUETECONTROLLER.proximaEnquete()"> <span id="next"
					class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span></span>
				<div id="spin"></div>

				<div id="enquete_left"></div>


			</div>

		</div>

	</div>

	<!-- MODAL PROPOSTA -->

	<!-- Modal -->
	<div class="modal fade" id="proporModal" tabindex="-1" role="dialog"
		aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"
						aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title" id="myModalLabel">Proposta</h4>
				</div>

				<div class="modal-body">

					<form id="form_proposta" class="form-horizontal">
						<ul class="list-unstyled">
							<li><h4>Ramo:</h4></li>
							<li><select class="form-control" id="form_ramos_proposta"
								name="ramo_id"></select></li>
							<li><h4>Comentário:</h4></li>
							<li><textarea class="form-control" id="comentariop"
									name="comentario" rows="7"></textarea></li>


						</ul>
					</form>

				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-primary"
						onClick="propostaPOST()" data-dismiss="modal">Enviar</button>
					<button type="button" class="btn btn-default"
						onClick="resetProposta()" data-dismiss="modal">Fechar</button>
				</div>

			</div>
		</div>
	</div>

	<!-- FIM MODAL PROPOSTA -->


	<!-- MODAL FISCALIZAÇÃO -->

	<!-- Modal -->
	<div class="modal fade" id="fiscalizarModal" tabindex="-1"
		role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"
						aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title" id="myModalLabel">Fiscalização</h4>
				</div>

				<div class="modal-body">

					<form id="form_fiscalizacao" class="form-horizontal">
						<ul class="list-unstyled">
							<li><h4>Ramo:</h4></li>
							<li><select class="form-control" id="form_ramos_fiscalizacao"
								name="ramo_id"></select></li>
							<li><h4>Comentário:</h4></li>
							<li><textarea class="form-control" id="comentariof"
									name="comentario" rows="7"></textarea></li>
							<li><h4>Gostaria de enviar uma imagem?</h4></li>
							<li><input type="file" name="imagem" id="img_input_fiscalizacao"></li>
							<li><img id="img_fiscalizar" src="" /></li>

						</ul>
						<input type="hidden" name="usuario_id" value="<?php echo $id?>" />
					</form>

				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-primary"
						onClick="fiscalizacaoPOST()" data-dismiss="modal">Enviar</button>
					<button type="button" class="btn btn-default"
						onClick="resetFiscalizacao()" data-dismiss="modal">Fechar</button>
				</div>

			</div>
		</div>
	</div>

	<!-- FIM MODAL PROPOSTA -->


	<!-- MODAL CRIAR ENQUETE -->

	<!-- Modal-->
	<div class="modal fade" id="newEnqueteModal" tabindex="-1"
		role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"
						aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title" id="myModalLabel">Nova Enquete</h4>
				</div>

				<div class="modal-body">

					<form id="form_new_enquete" class="form-horizontal fnenquete">

						<ul class="list-unstyled" id="lista">
							<li><h4>Título:</h4></li>
							<li><input type="text" class="form-control" name="titulo"
								id="titulo" placeholder="Título"></li>
							<li><h4>Sua enquete precisa de imagem?</h4></li>
							<li><input type="file" name="imagem" id="img_input_new_enquete"></li>
							<li><img src="" id="img_new_enquete" /></li>
							<li><h4>Adicione opções de voto:</h4></li>
							<li><input type="text" class="form-control" name="opt_1"
								id="opt_1" placeholder="1ª Opção"></li>
							<li><input type="text" class="form-control" name="opt_2"
								id="opt_2" placeholder="2ª Opção"></li>

						</ul>
						<input type="hidden" name="usuario_id" value="<?php echo $id?>" />
						<input type="hidden" id="qtd_opt" name="qtd_opt" value="2" />
					</form>



					<button type="button" class="btn btn-primary " id="more"
						onClick="ENQUETEFORM.more()">
						Add <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
					</button>
					<button type="button" class="btn btn-danger " id="less"
						onClick="ENQUETEFORM.less()">
						<span class="glyphicon glyphicon-minus" aria-hidden="true"></span>
					</button>


				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-primary"
						onClick="newEnqueteClick(); " data-dismiss="modal">Enviar</button>
					<button type="button" class="btn btn-default"
						onClick="ENQUETEFORM.resetNewEnquete()" data-dismiss="modal">Fechar</button>
				</div>

			</div>
		</div>
	</div>
	<!-- FIM MODAL CRIAR ENQUETE -->


	<!-- MODAL List People Laike-->


	<!-- Modal-->
	<div class="modal fade" id="list_people_laike" tabindex="-1"
		role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

		<div class="modal-dialog list_people_laike">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"
						aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title" id="myModalLabel">
						<span style="cursor: initial;" id='p_list_people_laike_titulo'>Curiar</span>
					</h4>
				</div>

				<div class="modal-body">

					<div id="list_people_laike_div"></div>

				</div>

				<div class="modal-footer">

					<button type="button" class="btn btn-default"
						onClick="ENQUETEFORM.resetNewEnquete()" data-dismiss="modal">Fechar</button>
				</div>

			</div>
		</div>
	</div>

</body>

</html>



