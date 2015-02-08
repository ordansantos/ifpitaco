
<?php
	session_start();
	if ($_SESSION['nm_usuario'] == '') {
    	header ("location: index.php");
	}
	
	$user = $_SESSION['nm_usuario'];
	$foto = $_SESSION['foto'];
	$id = $_SESSION['id_usuario'];
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
		<link rel="stylesheet" type="text/css" href="css/home.css">
		<link rel="stylesheet" type="text/css" href="css/comentario.css">
		<script src="js/bootbox.min.js"></script>
		
		<script src="js/jquery-visible.min.js"></script>
		<script src="js/toPlainText.js"></script>
		<link rel="stylesheet" type="text/css"  href="css/enquete_form.css">
		
		<!--http://www.chartjs.org/docs/-->
		<script src="Chart/Chart.js"></script>
		<link rel="stylesheet" type="text/css"  href="css/tooltip_chart_customized.css">
		
		<link rel="stylesheet" type="text/css"  href="css/search.css">
		
		<link rel="stylesheet" type="text/css" href="css/foto_size.css">
	</head>

	
	
<script type="text/javascript">



//Matriz de posts, cada linha possui o id dos comentários
var post_array = [];
//Id do usuario que realizou o comentário
var comentario_user = [];
//Id do usuario que criou o post
var post_user = [];
//Flag para verificar se ainda pode carregar posts antigos
var is_there_more_post = true;

var post_comentario_more = [];


/*Definindo os intervalos de Updates e carregamento de comentários mais velhos*/
$(document).ready(function() {

	
	postLoad(); 
	setInterval(function () {comentarioLoad()}, 5000);
	setInterval(function () {newPosts()}, 5000);
	setInterval(function () {likesLoad()}, 5000);
	setInterval(function () {updateEnqueteVisualizacao()}, 5000);
	tintervals = setInterval(function () {atualizaTempos()}, 10000);
	
	$(window).scroll(function() {
	
	  if( $(document).height() - ($(window).scrollTop() + $(window).height())  < 50 && is_there_more_post) {
		  morePost();
	  }
	 });

	$("#img_input_fiscalizacao").change(function(){
		readUrlFromFiscalizacao(this);
	});
	resetFiscalizacao();

	$("#proxima_enquete").hide();
	getEnquete();

	 $('[data-toggle="tooltip"]').tooltip();


	 
});

//Mostra o preview da imagem da fiscalização
function readUrlFromFiscalizacao(input) {

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
     
            $('#img_fiscalizar').attr('src', e.target.result);
            $("#img_fiscalizar").fadeIn("slow");
        }
        reader.readAsDataURL(input.files[0]);
    }
}

/*Sistema de envio e carregamento de 'laikes'*/
//Envio de 'Laike'
function laikeSend(id){
	
	id = id.replace ('nl', '');
	$("#nl"+id).tooltip('hide');

	$.post ("services/laikar.php", {post_id: id}, function(data){
		if (data.trim() == '0')
			bootbox.alert("Faça login para laikar!", function() {
				window.location.assign("index.php");
			});
		else
			likesLoad();
	});
	
}

//Carregando novos 'laikes'
function likesLoad(){
	
  for (post_idx in post_array){
	  
	  if(!$('#'+post_idx).visible(true)){
	
		  continue;
	  }
	  
      (function (post_idx){
		
			$.ajax({
				type: 'POST',
				url: 'services/getLaikes.php',
				data: { post_id: post_idx },
				dataType: 'json',
				cache: false,
				success: function (data) {
			
					title = "Laikar"
					$("#nl"+post_idx).attr('class', '');
					
					if (data.flag == '1'){
						title = "disLaikar";
						$("#nl"+post_idx).attr('class', 'laikou');
					}
					
					document.getElementById("nl"+post_idx).innerHTML = "<i class='glyphicon glyphicon-thumbs-up' aria-hidden='true'></i><span>"+data.cnt+"</span>" ;

					$("#nl"+post_idx)
			          .attr('data-original-title', title)
			          .tooltip('fixTitle');
					
				},
				error: function(data){
			
					}
			});
      })(post_idx);
     
  }
}

var data_post = [];
var data_enquete;
var data_comentario = [];

function atualizaTempos (){

	for (i in data_post){
			document.getElementById('ptime_'+i).innerHTML = tempo_passado(data_post[i]);
	}

	
	if ($('#etime').length > 0){
		document.getElementById('etime').innerHTML = tempo_passado(data_enquete);
	}

	for (i in data_comentario){
		if (!$('#ctime_'+i).length > 0){
			delete data_comentario[i];
			continue;
		}
		document.getElementById('ctime_'+i).innerHTML = tempo_passado(data_comentario[i]);
	}
}

/*Sistema de Envio e carregamento de Posts*/


//Id do último post carregado
var last_id_post;

//Id do primeiro post
var first_id_post = 0;

//Criando novo post
function novoPost (nome, ramo, id, data, comentario, foto, tipo, usuario_id, imagem){

	var form = document.createElement ('form');
	data_post[id] = data;
	form.id = id;
	$img='';
	
	var tipo_icon = '<div title="Fiscalização" class="fiscalizar_icon glyphicon glyphicon-warning-sign pull-right">';
	if (tipo == 0)
		tipo_icon = '<div title="Proposta" class="propor_icon glyphicon glyphicon-send pull-right">';
	
	//É uma fiscalização com imagem?
	if (tipo == 2)
		$img = "<img class='fiscalizacao_img' src='"+imagem+"' />"

	form.innerHTML = '\
	<div class="well well-sm">\
	 	<div class="post">\
		 <div class="top">\
		 <i onClick="excluirPost(this)" class="glyphicon glyphicon-remove"></i>\
			<img class="pull-left f45x45" src="'+foto+'" >\
			<div>\
				<h4><a  href="userProfile.php?id='+usuario_id+'">'+toPlainText(nome)+'</a></h4>\
				<h6><span id="ptime_'+id+'">'+tempo_passado(data)+'</span>&nbsp'+ramo+'</h6>\
			</div>\
		 </div>\
		<div class="content">'+toPlainText(comentario)+'</div>\
		'+$img+'\
		<div class="bot">\
			<div class="laike btn btn-lg "><div id="nl'+id+'" onClick="laikeSend(this.id)" type="button"  data-toggle="tooltip" data-placement="top">\
					<i class="glyphicon glyphicon-thumbs-up" aria-hidden="true" ></i><span> 0</span>\
			</div></div> \
			<div class="btn" style="cursor:default">\
					<span class="glyphicon glyphicon-comment" aria-hidden="true" ></i><span id="nc'+id+'"> 2</span>\
			</div> \
			'+tipo_icon+'\
			</div>\
		</div>\
	 </div>\
	</div>\
	<div id="cg'+id+'">\
		<div id="pc'+id+'"></div>\
		<div class="input-group add-on">\
		<input type="text" class="form-control" placeholder="Comentar..." name="comentario" autocomplete="off" id="comentario_input">\
		  <div class="input-group-btn">\
		  <button class="btn btn-default" type="submit" onClick="comentarioSend(this.form.id)"><i class="glyphicon glyphicon-share-alt"></i></button>\
		  </div>\
		</div>\
	</div>'
	document.getElementById('feed').appendChild(form);
	
	
	return form;
}

//Carregamento N posts mais novos ao carregar a página
function postLoad(){

  $.ajax({ 
  	type: 'GET', 
  	url: '../WebService/getNPosts/5', 
  	data: { get_param: 'value' }, 
 		dataType:'json',
		cache: false,
      success: function (data) { 
          
      	 $('#feed').fadeOut(0);
      	 
      	 for (i = 0; i < data.posts.length; i++){

          	p = data.posts[i];
          	
          	if (i == 0)
          		first_id_post = p.post_id;
				
				post_array[p.post_id] = [];
				post_user[p.post_id] = p.usuario_id;
				
     			novoPost( p.nm_usuario, p.nm_ramo, p.post_id, p.data_hora, p.comentario, p.perfil, p.tipo, p.usuario_id, p.imagem);	
     			
     			last_id_post = p.post_id;
			}
      	 
      	 $('#feed').fadeIn(400);
     	comentarioLoad();
      	likesLoad();
      }
  });

}

//Carregando posts mais antigos
function morePost(){

  $.ajax({ 
  	type: 'GET', 
  	url: '../WebService/getNPostsLessThanMid/3/'+last_id_post, 
  	data: { get_param: 'value' }, 
 		dataType:'json',
		cache: false,
      success: function (data) { 
			 if (data.posts.length == 0)
				 is_there_more_post = false;
      	 for (i = 0; i < data.posts.length; i++){
          	p = data.posts[i];
          		
				post_array[p.post_id] = [];
				post_user[p.post_id] = p.usuario_id;
				
     			novoPost( p.nm_usuario, p.nm_ramo, p.post_id, p.data_hora, p.comentario, p.perfil, p.tipo, p.usuario_id, p.imagem);	

     			last_id_post = p.post_id;
			}
     
      }
  });
  likesLoad();
  comentarioLoad();
	
}
//Carregando posts mais novos
function newPosts(){
	
  $.ajax({ 
  	type: 'GET', 
  	url: '../WebService/getAllPostsGreaterThanNid/'+first_id_post, 
  	data: { get_param: 'value' }, 
 		dataType:'json',
		cache: false,
      success: function (data) { 

      	 for (i = 0; i < data.posts.length; i++){
          	p = data.posts[i];
          		
				post_array[p.post_id] = [];
				post_user[p.post_id] = p.usuario_id;
     			form = novoPost( p.nm_usuario, p.nm_ramo, p.post_id, p.data_hora, p.comentario, p.perfil, p.tipo, p.usuario_id, p.imagem);	

     			document.getElementById('feed').insertBefore(form, document.getElementById('feed').firstChild);
     			$('#'+p.post_id).hide();
     			$('#'+p.post_id).fadeIn('slow');
				
     			first_id_post = p.post_id;

     			
			}
			if (data.posts.length != 0){
				likesLoad();
			  	comentarioLoad();
			}
     
      }
  });

}


/*Sistema de Envio e carregamento de Comentários*/

//Enviando um novo comentário
function comentarioSend (id){

	event.preventDefault();

	if ($("#"+id+" #comentario_input")[0].value == '')
		return;
	

	
	var data = $('#' + id).serializeArray();
	data.push ({name:'post_id', value: id});
		$.post ("services/comentar.php", data, function(data){
			if (data.trim() == '0')
				bootbox.alert("Faça login para comentar!", function() {
					window.location.assign("index.php");
				});
			else{
				comentarioLoad();
			}
		});
		document.getElementById(id).reset();
}

//Criando um novo comentário
function novoComentario (nome, comentario, data,  id_usuario, post_id, comentario_post_id, foto, id_usuario){
	data_comentario[comentario_post_id] = data;
	var com = document.createElement("div");
	com.className = "comentario";
	com.id = 'c'+comentario_post_id;
	com.innerHTML = "<img class='f32x32' src='"+foto+"'/><div class='content'><a href='userProfile.php?id="+id_usuario+"'><strong>"+toPlainText(nome)+"</strong></a> "+toPlainText(comentario)+"<h6><span id='ctime_"+comentario_post_id+"'>"+tempo_passado(data)+"</span></h6></div><i onClick='excluirComentario(this.parentNode.id)'class='glyphicon glyphicon-remove'></i>";
	document.getElementById("pc"+post_id).appendChild(com);
	
}


//Carregando novos comentários
function comentarioLoad(){

	
  for (post_idx in post_array){
	  
	  //Verifica se o post é visível e assim atualiza seus comentários
	  //Se o elemento não estiver na tela é considerado como visível

	  
	  if(!$('#'+post_idx).visible(true)){

		  continue;
	  }
	
      (function (post_idx){
          
    	   	
			$.ajax({
				type: 'GET',
				url: '../WebService/getComentariosById/'+post_idx,
				data: { get_param: 'value' },	
				dataType: 'json',
				cache: false,
				success: function (data) {
				
					 //Verificando se o comentário foi apagado
	
					 if (data.flag == '0'){

						 	delete post_array[post_idx];
						 	delete data_post[post_idx];
						 	$('#'+post_idx).fadeOut(1000);
						 	return;
					 }

					 var comment_idx_new = 0;
					 var comment_idx_old = 0;
					 
					 document.getElementById("nc"+post_idx).innerHTML = " "+data.comentarios.length;
					 
					 while (comment_idx_new < data.comentarios.length){
						 
						c = data.comentarios[comment_idx_new];
						
						while (comment_idx_old < post_array[post_idx].length && post_array[post_idx][comment_idx_old] < c.comentario_post_id){
							comment_idx_old++;	
						}

						if (comment_idx_old < post_array[post_idx].length && post_array[post_idx][comment_idx_old] == c.comentario_post_id){
							 comment_idx_old++;
							 comment_idx_new++;
							 continue;
						}
						
					
					 	novoComentario( c.nm_usuario, c.comentario, c.data_hora,  c.id_usuario, post_idx, c.comentario_post_id, c.perfil, c.id_usuario);	
					 	comentario_user[c.comentario_post_id] = c.id_usuario;
						
						comment_idx_new++;
					 
					 }

					 post_array[post_idx] = [];
					 for (c in data.comentarios)
						 post_array[post_idx].push(data.comentarios[c].comentario_post_id);
					 
					 

				},
				error: function(data){
						
					}
			});
      })(post_idx);
     
  }
  
}





/*Sistema para excluir um comentário*/
function excluirComentario(id){
	

	id = id.replace ('c', '');
	
	if (comentario_user[id] != <?php echo $id?>)
		bootbox.dialog({message: "Esse comentário está lhe incomodando?", 
			buttons:{
				"Não": {className: "btn-default btn-sm"},
				"Sim": {
						className: "btn-primary btn-sm",
						
						callback: function(){ $('#c'+id).remove();}
				}
				
			}
		}); 
	else
		bootbox.dialog({message: "Deseja excluir seu comentário?", 
			buttons:{
				"Não": {className: "btn-default btn-sm"},
				"Sim": {
						className: "btn-primary btn-sm",
						
						callback: function(){
							
							$('#c'+id).remove();
							$.param({comentario_post_id: id});
							$.post ("services/deletarComentario.php", {comentario_post_id: id});
							
						}
				}
				
			}
		}); 

}

/*Sistema para excluir uma publicação*/

function excluirPost(sender){

	e = sender.parentNode.parentNode.parentNode.parentNode;
	id = e.getAttribute('id');

	if (post_user[id] != <?php echo $id?>)
		bootbox.dialog({message: "Essa pubicação está lhe incomodando?", 
			buttons:{
				"Não": {className: "btn-default btn-sm"},
				"Sim": {
						className: "btn-primary btn-sm",
						
						callback: function(){ 
							$('#'+id).remove();
			
							delete post_array[id];
							delete data_post[id];
				
						}
				}
				
			}
		}); 
	else
		bootbox.dialog({message: "Deseja excluir sua publicação?", 
			buttons:{
				"Não": {className: "btn-default btn-sm"},
				"Sim": {
						className: "btn-primary btn-sm",
						
						callback: function(){
							
							$('#'+id).remove();
							delete post_array[id];
							delete data_post[id];
							$.param({post_id: id});
							a = $.post ("services/deletarPost.php", {post_id: id});
	
						}
				}
				
			}
		}); 

}


//Adicionando os ramos às propostas
$.get("../WebService/getRamos", function(data, status){
	
	var ramos = JSON.parse(data).ramos;
	var form1 = document.getElementById("form_ramos_proposta");
	var form2 = document.getElementById("form_ramos_fiscalizacao");
	
	for (i = 0; i < ramos.length; i++){
		
		var t = ramos[i].nm_ramo.split(":");
		
		if (i == 0 || (i > 0 && t[0] != ramos[i-1].nm_ramo.split(":")[0])){
			var optgroup = document.createElement("optgroup");
			optgroup.label = t[0];
			form1.add(optgroup);
			optgroup = document.createElement("optgroup");
			optgroup.label = t[0];
			form2.add(optgroup);
		}
		
		
		var option = document.createElement("option");
		option.value = ramos[i].id_ramo;
		option.text = ramos[i].nm_ramo.split(":")[1];
		form1.add(option);
		
		option = document.createElement("option");
		option.value = ramos[i].id_ramo;
		option.text = ramos[i].nm_ramo.split(":")[1];
		form2.add(option);
	}
});


//Enviando a proposta por meio do post
function propostaClick() {
		
var values = $('#form_proposta').serialize();
	
	$.ajax({
		url: "services/propor.php",
		type: "post",
		data: values,
		async: false,
		success: function(data){
			if ($.trim(data)!='1')
				alert ("Faça login antes de propor!");
			else
				document.getElementById("comentariop").value = "";
			newPosts();
			
		},
		error:function(){
			alert("error");
		}
	});
	resetProposta();
	
}

function fiscalizacaoClick(){

	var formData = new FormData($("#form_fiscalizacao")[0]);
	$.ajax({
		type: "POST",
		url: "../WebService/postFiscalizacao",
        contentType: false,
        processData: false,
		data: formData,
		success: function(data){
			console.log(data);
			newPosts();
		}, error: function(data){
		
		}
	});
	
	resetFiscalizacao();

	
}

function resetFiscalizacao(){
	$("#img_fiscalizar").hide();
	$("#form_fiscalizacao")[0].reset();
}

function resetProposta(){
	$("#form_proposta")[0].reset();
}


/*SISTEMA DE ENVIO DE UMA NOVA ENQUETE*/
$(document).ready(function() {
	

	$("#img_input_new_enquete").change(function(){
	    readUrlFromEnquete(this);
	});
	
	resetNewEnquete();
	$("#img_new_enquete").hide();
});


function readUrlFromEnquete(input) {

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
     
            $('#img_new_enquete').attr('src', e.target.result);
            $("#img_new_enquete").fadeIn("slow");
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function resetNewEnquete(){
	
	$("#form_new_enquete")[0].reset();
	for (i = qtd_opt; i > 2; i--){
		$('#opt_'+i).remove();
		$('#l_'+i).remove();
	}
	qtd_opt = 2;
	$("#img_new_enquete").hide();
	$('#less').hide();
	$('#more').show();
}

var qtd_opt = 2;

function more(){
	if (qtd_opt == 2) $('#less').show();
	qtd_opt++;
	
	var form = "<input type='text' class='form-control' name='opt_"+qtd_opt+"' id='opt_"+qtd_opt+"' placeholder='"+qtd_opt+"ª Opção'>"
	
	var li = document.createElement ('li');
	li.id = "l_"+qtd_opt;
	li.innerHTML = form;
	document.getElementById('lista').appendChild(li);
	
	$('#qtd_opt').val(qtd_opt);
	
	if (qtd_opt == 5) $('#more').hide();
	
}

function less(){
	
	if (qtd_opt == 5)  $('#more').show();
	
	$('#opt_'+qtd_opt).remove();
	$('#l_'+qtd_opt).remove();
	qtd_opt--;
	
	$('#qtd_opt').val(qtd_opt);
	
	if (qtd_opt == 2) $('#less').hide();
}

function newEnqueteClick(){

	if (document.getElementById('titulo').value == ''){
		bootbox.alert("<h4><strong>Dê um título a sua enquete!</strong></h4>");
		return;
	}
	for ( i = 1; i <= qtd_opt; i++){
		if (document.getElementById('opt_'+i).value == ''){
			bootbox.alert("<h4><strong>Preencha todas as opções de voto!</strong></h4>");
			return;
		}
	}

	
	var formData = new FormData($("#form_new_enquete")[0]);
	$.ajax({
		type: "POST",
		url: "../WebService/postEnquete",
        contentType: false,
        processData: false,
		data: formData,
		success: function(data){
	
			if (data.trim() != '0'){
				novaEnqueteForm(data);
			}
				
		}, error: function(data){

		}
	});
	
	resetNewEnquete();
}

/*SISTEMA DE ENVIO DE VOTO E CARREGAMENTO DE ENQUETES*/


   function createEnqueteForm(usuario_id, perfil, e_imagem, nm_usuario, data_hora, titulo, id_enquete, e_qtd_opt, opts){
	
	   if ($('#enquete').length)
		   $('#enquete').remove();
	   

	   var enquete = document.createElement ('div');
	   enquete.className = "enquete well";
	   enquete.id = "enquete";
	   
	   imagem = '';
	   if (e_imagem != '')
		   imagem = "<img src='"+e_imagem+"'>"
	   
	   data_enquete = data_hora;
		   
	   enquete.innerHTML = "\
			 <div class='e_top'>\
				<img class='f45x45 pull-left img-circle' src='"+perfil+"'>\
					<a href='userProfile.php?id="+usuario_id+"'><div class='nome_user'>"+toPlainText(nm_usuario)+"</div></a>\
					<div class='data'><span id='etime'>"+tempo_passado(data_hora)+"</span></div>\
				 </div>\
				 <div class='titulo'>"+toPlainText(titulo)+"</div>\
				 "+imagem+"\
				 <form id='form_enquete' class='form-horizontal'>\
				 </form>\
				 <div class='bot'><button onClick='votoSend("+id_enquete+")' class='btn btn-danger'>Opinar!</button></div>\
				 <div title='Enquete' class='glyphicon glyphicon-bullhorn enquete_icon_no_vote pull-right'></div>\
			</div>\
	   "

	   document.getElementById("enquete_left").appendChild(enquete);
			
			
		for (i = 0; i < e_qtd_opt; i++){
			
			o = document.createElement ('div');
			o.className = "radio";
			o.innerHTML = "\
				<label>\
					<input type='radio' name='voto' value='"+(i+1)+"' "+(i==0?'checked':'')+">\
					"+toPlainText(opts[i])+"\
				</label>\
			";
		
			document.getElementById("form_enquete").appendChild(o);

		}
   }
   
   function novaEnqueteForm(id_enquete){
	   $("#proxima_enquete").hide();
	   $.ajax({ 
		  	type: 'GET', 
		  	url: '../WebService/getEnquete/'+id_enquete, 
		  	data: { get_param: 'value' }, 
		 	dataType:'json',
			cache: false,
		    success: function (data) { 
		    	data = data[0];
		    	createEnqueteForm (data.usuario_id, data.perfil, data.e_imagem, data.nm_usuario, 
   					data.data_hora, data.titulo, data.id_enquete, data.qtd_opt,
   					[data.opt_1, data.opt_2, data.opt_3, data.opt_4, data.opt_5]	
		    		);	
		    }
		});
   }
   
   function votoSend(id){
	   
		event.preventDefault();
		var data = $('#form_enquete').serializeArray();
		data.push ({name:'enquete_id', value: id});
			$.post ("services/postVoto.php", data, function(data){
			
				if (data.trim() == '0')
					bootbox.alert("Faça login para votar!", function() {
						window.location.assign("index.php");
					});
				else{
					number_votes_enquete = 0;
					to_update_enquete = id;
					novaEnqueteVisualizacao(id);
					if (last_array_enquete = Number.POSITIVE_INFINITY)
						last_array_enquete = id;
				}
			});
   }
   
   var last_array_enquete = Number.POSITIVE_INFINITY;
   var last_array_enquete_to_vote = Number.POSITIVE_INFINITY;
   var to_update_enquete = 0;
   var number_votes_enquete = 0;

   
function getEnquete(){


	$.ajax({ 
		type: 'GET', 
		url: '../WebService/getEnqueteIdsWhereUserDidNotVote/'+<?php echo $id?>, 
		data: { get_param: 'value' }, 
		dataType:'json',
		cache: false,
		success: function (data) {

			if (data.ids.length != 0){ 

				created = false;
				
				for (i = data.ids.length - 1; i >= 0; i--){
					if (last_array_enquete_to_vote > parseInt(data.ids[i].id_enquete)){
						created = true;
						last_array_enquete_to_vote = parseInt(data.ids[i].id_enquete);
						break;
					}
				}
						
				if (!created)
					last_array_enquete_to_vote = parseInt(data.ids[data.ids.length - 1].id_enquete);
				
	
				novaEnqueteForm (last_array_enquete_to_vote);
				to_update_enquete = 0;
				number_votes_enquete = 0;
			}else{
					
				$.ajax({ 
					type: 'GET', 
					url: '../WebService/getEnqueteIds/', 
					data: { get_param: 'value' }, 
					dataType:'json',
					cache: false,
					success: function (data) {
				
						if (data.ids.length != 0){
							created = false;
							for (i = data.ids.length - 1; i >= 0; i--){
			
								if (last_array_enquete > parseInt(data.ids[i].id_enquete)){
									created = true;
									last_array_enquete = parseInt(data.ids[i].id_enquete);
									break;
									
								}
							}
							
							if (!created)
								last_array_enquete = parseInt(data.ids[data.ids.length - 1].id_enquete);
							
							
							novaEnqueteVisualizacao(last_array_enquete);
							to_update_enquete = last_array_enquete;
							number_votes_enquete = 0;
						}
						
					}
				});
			}
		}
	});
}


function updateEnqueteVisualizacao(){

	if (to_update_enquete != 0){
		novaEnqueteVisualizacao(to_update_enquete);

	}
}

   function novaEnqueteVisualizacao(id_enquete){

	   $("#proxima_enquete").show();
	   $.ajax({ 
		  	type: 'GET', 
		  	url: '../WebService/getEnquete/'+id_enquete, 
		  	data: { get_param: 'value' }, 
		 	dataType:'json',
			cache: false,
		    success: function (data) { 
			    if (data.length == 0) return;
			    

		    	data = data[0];
				sum = parseInt(data.qtd_opt_1) + parseInt(data.qtd_opt_2) + parseInt(data.qtd_opt_3) + parseInt(data.qtd_opt_4) + parseInt(data.qtd_opt_5);
				if (number_votes_enquete < sum ){
				
					number_votes_enquete = sum;
			    	createEnqueteVisualizacao (data.usuario_id, data.perfil, data.e_imagem, data.nm_usuario, 
			    					data.data_hora, data.titulo, data.qtd_opt,
			    		[data.opt_1, data.opt_2, data.opt_3, data.opt_4, data.opt_5],
			    		[data.qtd_opt_1, data.qtd_opt_2, data.qtd_opt_3, data.qtd_opt_4, data.qtd_opt_5]
			    		);	

				}
		    	
		    }
		});
   }
   
   function createEnqueteVisualizacao(usuario_id, perfil, e_imagem, nm_usuario, data_hora, titulo, qtd_opt, opts, qtd_opts){
	
	   if ($('#enquete').length)
		   $('#enquete').remove();
	   
	   
	   var enquete = document.createElement ('div');
	   enquete.className = "enquete well";
	   enquete.id = "enquete";
	   enquete.display = "none";
	   imagem = '';
	   if (e_imagem != '')
		   imagem = "<img src='"+e_imagem+"'>"
	   
	   data_enquete = data_hora;
		   
	   enquete.innerHTML = "\
			 <div class='e_top'>\
				<img class='f45x45 pull-left img-circle' src='"+perfil+"'>\
				<a href='userProfile.php?id="+usuario_id+"'><div class='nome_user'>"+toPlainText(nm_usuario)+"</div></a>\
					<div class='data'><span id='etime'>"+tempo_passado(data_hora)+"</span></div>\
				 </div>\
				 <div class='titulo'>"+toPlainText(titulo)+"</div>\
				 "+imagem+"\
				 <h4>Resultados: </h4>\
				 <canvas id='myChart' width=250 ></canvas>\
				 <div id='chartjs-tooltip'></div>\
				 <div title='Enquete' class='glyphicon glyphicon-bullhorn enquete_icon'></div>\
			</div>\
			\
	   ";
		
		document.getElementById("enquete_left").appendChild(enquete);

		//Não possui nenhuma opção no tooltip, então esconda-o
		$('#chartjs-tooltip').hide();
		
		drawChart (opts, qtd_opts, qtd_opt);
   }

function proximaEnquete(){
	getEnquete();
}

//Customizando o tooltip padrão para não limitar a quantidade de caracteres
Chart.defaults.global.customTooltips = function(tooltip) {

	// Tooltip Element
    var tooltipEl = $('#chartjs-tooltip');
    $('#chartjs-tooltip').show();
    
    // Hide if no tooltip
    if (!tooltip) {
        tooltipEl.css({
            opacity: 0
        });
        return;
    }

    // Set caret Position
    tooltipEl.removeClass('above below');
    tooltipEl.addClass(tooltip.yAlign);

    // Set Text
    tooltipEl.html(tooltip.text);

    // Find Y Location on page
    var top;
    if (tooltip.yAlign == 'above') {
        top = tooltip.y - tooltip.caretHeight - tooltip.caretPadding;
    } else {
        top = tooltip.y + tooltip.caretHeight + tooltip.caretPadding;
    }

    // Display, position, and set styles for font
    tooltipEl.css({
        opacity: 1,
        left: tooltip.chart.canvas.offsetLeft + tooltip.x + 'px',
        top: tooltip.chart.canvas.offsetTop + top + 'px',
        fontFamily: tooltip.fontFamily,
        fontSize: tooltip.fontSize,
        fontStyle: tooltip.fontStyle,
    });
};

function drawChart(opts, qtd_opts, qtd_opt){
	
	colors = ['#F7464A', '#46BFBD', '#FDB45C', '#949FB1', '#4D5360'];
	highlights = ['#FF5A5E', '#5AD3D1', '#FFC870', '#A8B3C5', '#616774'];
	data = [];
	for (i = 0; i < qtd_opt; i++){

		data.push({
			value: parseInt(qtd_opts[i]),
			color: colors[i],
			highlight: highlights[i],
			label: opts[i]
		});
	}

	var ctx = $("#myChart").get(0).getContext("2d");
	var myPieChart = new Chart(ctx).Pie(data, {animationSteps : 50});
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

function doSearch(text) {

	if (text == ''){
		$('#search_list').remove();
		$('#search_form')[0].reset();
		clearTimeout(delayTimer);
	}
	
	
    if( /[^a-zA-Z]/.test( text ) ) 
		return;
	
    
	if (text == '') return;
	
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

		});
		
		
    }, 200); //Tempo após outra digidatação
    
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

function teste(){
	console.log ('entrou');
	$("#cg"+55).hide();
}

</script>


	
<body>
<!-- <button onClick="teste()"/> -->	

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
		  		<div class="col-md-2 text-center" id="profile">
		  			<div class="img-thumbnail">
				  		<img src="<?php echo $foto?>"  alt="..." class="f120x120">
				  	</div>
				  	<a href="userProfile.php?id=<?php echo $id?>"><h2><script>document.write(toPlainText('<?php echo $user?>'));</script></h2></a>
				</div>
				  
				 <!-- Meio -->
				<div class="col-md-6" id="mid">
	                <div class="opcoes_btns">             
						<!-- Button trigger modal -->
						<button id="propor_btn" type="button" class="btn btn-success btn-lg" data-toggle="modal" data-target="#proporModal">
						  <span class="glyphicon glyphicon-send" aria-hidden="true"></span> Propor
						</button>
						
						<!-- Button trigger modal -->
						<button type="button" class="btn btn btn-danger btn-lg" data-toggle="modal" data-target="#fiscalizarModal">
						  <span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span> Fiscalizar
						</button>
						
						<!-- Button trigger modal -->
						<button type="button" class="btn btn-warning btn-lg pull-right" data-toggle="modal" data-target="#newEnqueteModal">
		  					<span class="glyphicon glyphicon-bullhorn" aria-hidden="true"></span> Criar Enquete
						</button>
					</div>
					<div id="feed"></div>
		
	 			</div>
	 			
	 			<div class="col-md-3" id="left">
	 				<span title="próxima" data-toggle="tooltip" data-placement="bottom" class="btn-lg btn pull-right" id="proxima_enquete" onClick="proximaEnquete()">
	 				<span id="next" class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span></span>
	 				
	 				<div id="enquete_left">
	 				</div>
	 			

	 			</div>

	 	</div>

	</div>
			
  	<!-- MODAL PROPOSTA -->
  	
	<!-- Modal -->
	<div class="modal fade" id="proporModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel">Proposta</h4>
	      </div>
	      
	      <div class="modal-body">
	      
	        	<form id="form_proposta" class="form-horizontal">
	        	<ul class="list-unstyled">
	    				<li><h4 >Ramo:</h4></li>
	    				<li><select class="form-control" id="form_ramos_proposta" name="ramo_id"></select></li>
	    				<li><h4 >Comentário:</h4></li>
						<li><textarea class="form-control" id="comentariop" name="comentario" rows="7"></textarea></li>
				
					
				</ul>
				</form>
				
	      </div>
	      
	      <div class="modal-footer">
	      	<button type="button" class="btn btn-primary" onClick="propostaClick()" data-dismiss="modal">Enviar</button>
	        <button type="button" class="btn btn-default" onClick="resetProposta()" data-dismiss="modal">Fechar</button>
	      </div>
	      
	  </div>
   </div>
  </div>
   
   <!-- FIM MODAL PROPOSTA -->
   
   
    <!-- MODAL FISCALIZAÇÃO -->
  	
	<!-- Modal -->
	<div class="modal fade" id="fiscalizarModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel">Fiscalização</h4>
	      </div>
	      
	      <div class="modal-body">
	      
	        	<form id="form_fiscalizacao" class="form-horizontal">
	        	<ul class="list-unstyled">
	    				<li><h4 >Ramo:</h4></li>
	    				<li><select class="form-control" id="form_ramos_fiscalizacao" name="ramo_id"></select></li>
	    				<li><h4 >Comentário:</h4></li>
						<li><textarea class="form-control" id="comentariof" name="comentario" rows="7"></textarea></li>
						<li><h4 >Gostaria de enviar uma imagem?</h4></li>
						<li><input type="file" name="imagem" id="img_input_fiscalizacao"></li>
						<li><img id="img_fiscalizar"  src=""/></li>
						
				</ul>
				<input type="hidden" name="usuario_id" value="<?php echo $id?>"/>
				</form>
				
	      </div>
	      
	      <div class="modal-footer">
	      	<button type="button" class="btn btn-primary" onClick="fiscalizacaoClick()" data-dismiss="modal">Enviar</button>
	        <button type="button" class="btn btn-default" onClick="resetFiscalizacao()" data-dismiss="modal">Fechar</button>
	      </div>
	      
	  </div>
   </div>
  </div>
   
   <!-- FIM MODAL PROPOSTA -->

  
    <!-- MODAL CRIAR ENQUETE -->
    
	<!-- Modal-->
	<div class="modal fade" id="newEnqueteModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel">Nova Enquete</h4>
	      </div>
	      
	      <div class="modal-body">
	      	
	      	 <form id="form_new_enquete" class="form-horizontal fnenquete">
 
				<ul class="list-unstyled" id="lista">
					<li><h4>Título: </h4></li>
					<li><input type="text" class="form-control" name="titulo" id="titulo" placeholder="Título"></li>
					<li><h4 >Sua enquete precisa de imagem?</h4></li>
					<li><input type="file" name="imagem" id="img_input_new_enquete"></li>
					<li><img src="" id="img_new_enquete"/></li>
					<li><h4>Adicione opções de voto: </h4></li>
					<li><input type="text" class="form-control" name="opt_1" id="opt_1" placeholder="1ª Opção"></li>
					<li><input type="text" class="form-control" name="opt_2" id="opt_2" placeholder="2ª Opção"></li>
	 			
	 			</ul>
				<input type="hidden" name="usuario_id" value="<?php echo $id?>"/>
	 			<input type="hidden" id="qtd_opt" name="qtd_opt" value="2"/>
 			</form>



			<button type="button" class = "btn btn-primary " id="more" onClick="more()">Add <span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button> 
			<button type="button" class = "btn btn-danger " id="less" onClick="less()"><span class="glyphicon glyphicon-minus" aria-hidden="true"></span> </button>

				
	      </div>
	      
	      <div class="modal-footer">
	      	<button type="button" class="btn btn-primary" onClick="newEnqueteClick(); " data-dismiss="modal">Enviar</button>
	        <button type="button" class="btn btn-default" onClick="resetNewEnquete()" data-dismiss="modal">Fechar</button>
	      </div>
	      
	  </div>
   </div>
  </div>
	<!-- FIM MODAL CRIAR ENQUETE -->
  
 </body>
  
</html>