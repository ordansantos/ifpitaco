
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
		<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
	
		<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
		<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" rel="stylesheet">
		<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>

		<link rel="stylesheet" type="text/css" href="css/comentario.css">
		<script src="js/bootbox.min.js"></script>
		<link rel="stylesheet" type="text/css" href="css/home.css">
		<script src="js/jquery-visible.min.js"></script>
		<script src="js/toPlainText.js"></script>
		<link rel="stylesheet" type="text/css"  href="css/enquete_form.css">
		
		<!--http://www.chartjs.org/docs/-->
		<script src="Chart/Chart.js"></script>
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


/*Definindo os intervalos de Updates e carregamento de comentários mais velhos*/
$(document).ready(function() {
	
	postLoad(); 
	setInterval(function () {comentarioLoad()}, 5000);
	setInterval(function () {newPosts()}, 5000);
	setInterval(function () {likesLoad()}, 5000);
	
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

	$.post ("laikar.php", {post_id: id}, function(data){
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
	console.log ('likesload');
  for (post_idx in post_array){
	  
	  if(!$('#'+post_idx).visible(true)){
		  console.log ('nao like'+post_idx);
		  continue;
	  }
	  
      (function (post_idx){
			console.log ('like'+post_idx);
			$.ajax({
				type: 'POST',
				url: 'getLaikes.php',
				data: { post_id: post_idx },
				dataType: 'json',
				cache: false,
				success: function (data) {
					console.log(data);
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
						console.log('erro');
					}
			});
      })(post_idx);
     
  }
}

/*Sistema de Envio e carregamento de Posts*/


//Id do último post carregado
var last_id_post;

//Id do primeiro post
var first_id_post = 0;

//Criando novo post
function novoPost (nome, ramo, id, data, comentario, foto, tipo){

	var form = document.createElement ('form');
	
	form.id = id;
	$img='';
	
	//É uma fiscalização com imagem?
	if (tipo == 2)
		$img = "<img class='fiscalizacao_img' src='../WebService/uploaded_images/fiscalizacao_foto/"+id+".jpg' />"
	form.innerHTML = '\
	<div class="well well-sm" style="margin-bottom: 1px">\
	 	<div class="post">\
		 <div class="top">\
		 <i onClick="excluirPost(this)" class="glyphicon glyphicon-remove"></i>\
			<img  class="pull-left" src="../'+foto+'" >\
			<div>\
				<h4>'+toPlainText(nome)+'</h4>\
				<h6>'+data+'-'+ramo+'</h6>\
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
		</div>\
	 </div>\
	</div>\
	<div id="pc'+id+'"></div>\
	<div class="input-group add-on">\
	<input type="text" class="form-control" placeholder="Comentar..." name="comentario" autocomplete="off" id="comentario_input">\
	  <div class="input-group-btn">\
	  <button class="btn btn-default" type="submit" onClick="comentarioSend(this.form.id)"><i class="glyphicon glyphicon-share-alt"></i></button>\
	  </div>\
	</div>'
	document.getElementById('feed').appendChild(form);
	
	return form;
}

//Carregamento N posts mais novos ao carregar a página
function postLoad(){
	console.log('entrou: postLoad');
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
				
     			novoPost( p.nm_usuario, p.nm_ramo, p.post_id, p.data_hora, p.comentario, p.perfil_45, p.tipo);	

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
	console.log('entrou: morePost');
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
				
     			novoPost( p.nm_usuario, p.nm_ramo, p.post_id, p.data_hora, p.comentario, p.perfil_45, p.tipo);	

     			last_id_post = p.post_id;
			}
     
      }
  });
  likesLoad();
  comentarioLoad();
	
}
//Carregando posts mais novos
function newPosts(){
	console.log('entrou: newsPost');
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
     			form = novoPost( p.nm_usuario, p.nm_ramo, p.post_id, p.data_hora, p.comentario, p.perfil_45, p.tipo);	

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


	console.log('entrou: comentarioSend');
	event.preventDefault();
	var data = $('#' + id).serializeArray();
	data.push ({name:'post_id', value: id});
		$.post ("comentar.php", data, function(data){
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
function novoComentario (nome, comentario, data,  id_usuario, post_id, comentario_post_id, foto){

	var com = document.createElement("div");
	com.className = "comentario";
	com.id = 'c'+comentario_post_id;
	com.innerHTML = "<img src='../"+foto+"'/><div class='content'><strong>"+toPlainText(nome)+"</strong> "+toPlainText(comentario)+"<h6>"+data+"</h6></div><i onClick='excluirComentario(this.parentNode.id)'class='glyphicon glyphicon-remove'></i>";
	document.getElementById("pc"+post_id).appendChild(com);
	
}

//Carregando novos comentários
function comentarioLoad(){
	console.log('entrou: comentarioLoad');
	
  for (post_idx in post_array){
	  
	  //Verifica se o post é visível e assim atualiza seus comentários
	  //Se o elemento não estiver na tela é considerado como visível

	  
	  if(!$('#'+post_idx).visible(true)){
		  console.log (post_idx+'nao entrou');
		  continue;
	  }
	  
      (function (post_idx){
          
    	   	console.log (post_idx+'entrou');
    	   	
			$.ajax({
				type: 'GET',
				url: '../WebService/getComentariosById/'+post_idx,
				data: { get_param: 'value' },	
				dataType: 'json',
				cache: false,
				success: function (data) {
					console.log(data);
					 //Verificando se o comentário foi apagado
					 if (data.flag == '0'){
						 	post_array.splice (post_idx, 1);
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
						
					 		console.log(data);
					 	novoComentario( c.nm_usuario, c.comentario, c.data_hora,  c.id_usuario, post_idx, c.comentario_post_id, c.perfil_32);	
					 	comentario_user[c.comentario_post_id] = c.id_usuario;
						
						comment_idx_new++;
					 
					 }

					 post_array[post_idx] = [];
					 for (c in data.comentarios)
						 post_array[post_idx].push(data.comentarios[c].comentario_post_id);
					 
					 

				},
				error: function(data){
						console.log('erro');
					}
			});
      })(post_idx);
     
  }
  
}





/*Sistema para excluir um comentário*/
function excluirComentario(id){
	
	console.log('entrou: excluir');
	id = id.replace ('c', '');
	
	$.get ("getIdUsuario.php", function(data){
		console.log(data + ' ' + comentario_user[id] + ' ' + id);
		if (comentario_user[id] != data)
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
								$.post ("deletarComentario.php", {comentario_post_id: id});
								
							}
					}
					
				}
			}); 
	});
}

/*Sistema para excluir uma publicação*/

function excluirPost(sender){
	console.log('entrou: excluir-post');
	e = sender.parentNode.parentNode.parentNode.parentNode;
	id = e.getAttribute('id');

	$.get ("getIdUsuario.php", function(data){
		if (post_user[id] != data)
			bootbox.dialog({message: "Essa pubicação está lhe incomodando?", 
				buttons:{
					"Não": {className: "btn-default btn-sm"},
					"Sim": {
							className: "btn-primary btn-sm",
							
							callback: function(){ $('#'+id).remove();}
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
								
								$.param({post_id: id});
								a = $.post ("deletarPost.php", {post_id: id});
								console.log(a);
							}
					}
					
				}
			}); 
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
		url: "propor.php",
		type: "post",
		data: values,
		async: false,
		success: function(data){
			if ($.trim(data)!='1')
				alert ("Faça login antes de propor!");
			else
				document.getElementById("comentariop").value = "";

			
		},
		error:function(){
			alert("error");
		}
	});
	resetProposta();
	newPosts();
}

function fiscalizacaoClick(){

	var formData = new FormData($("#form_fiscalizacao")[0]);
	$.ajax({
		type: "POST",
		url: "fiscalizar.php",
        contentType: false,
        processData: false,
		data: formData,
		success: function(data){
			console.log(data);
		}, error: function(data){
			console.log(data);
		}
	});
	
	resetFiscalizacao();

	newPosts();
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
		url: "postNewEnquete.php",
        contentType: false,
        processData: false,
		data: formData,
		success: function(data){
			console.log(data);
			if (data.trim() != '0'){
				novaEnqueteForm(data);
			}
				
		}, error: function(data){
			console.log('erro'+data);
		}
	});
	
	resetNewEnquete();
}

/*SISTEMA DE ENVIO DE VOTO E CARREGAMENTO DE ENQUETES*/


   function createEnqueteForm(perfil_45, e_imagem, nm_usuario, data_hora, titulo, id_enquete, e_qtd_opt, opts){
	
	   if ($('#enquete').length)
		   $('#enquete').remove();
	   

	   var enquete = document.createElement ('div');
	   enquete.className = "enquete";
	   enquete.id = "enquete";
	   
	   imagem = '';
	   if (e_imagem != '')
		   imagem = "<img src='../"+e_imagem+"'>"
	   
	   
	   enquete.innerHTML = "\
			 <div class='e_top'>\
				<img class='pull-left img-circle' src='../"+perfil_45+"'>\
					<div class='nome_user'>"+toPlainText(nm_usuario)+"</div>\
					<div class='data'>"+data_hora+"</div>\
				 </div>\
				 <div class='titulo'>"+toPlainText(titulo)+"</div>\
				 "+imagem+"\
				 <form id='form_enquete' class='form-horizontal'>\
				 </form>\
				 <div class='bot'><button onClick='votoSend("+id_enquete+")' class='btn btn-danger'>Opinar!</button></div>\
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

	   $.ajax({ 
		  	type: 'GET', 
		  	url: '../WebService/getEnquete/'+id_enquete, 
		  	data: { get_param: 'value' }, 
		 	dataType:'json',
			cache: false,
		    success: function (data) { 
		    	data = data[0];
		    	createEnqueteForm (data.perfil_45, data.e_imagem, data.nm_usuario, 
   					data.data_hora, data.titulo, data.id_enquete, data.qtd_opt,
   					[data.opt_1, data.opt_2, data.opt_3, data.opt_4, data.opt_5]	
		    		);	
		    }
		});
   }
   
   function votoSend(id){
	   
		console.log('entrou: votoSend');
		event.preventDefault();
		var data = $('#form_enquete').serializeArray();
		data.push ({name:'enquete_id', value: id});
			$.post ("postVoto.php", data, function(data){
				console.log(data);
				if (data.trim() == '0')
					bootbox.alert("Faça login para votar!", function() {
						window.location.assign("index.php");
					});
				else{
					novaEnqueteVisualizacao(id);
					if (last_array_enquete = Number.POSITIVE_INFINITY)
						last_array_enquete = id;
				}
			});
   }
   
   var last_array_enquete = Number.POSITIVE_INFINITY;
   var last_array_enquete_to_vote = Number.POSITIVE_INFINITY;
	
   
   
function getEnquete(){

	$.get ("getIdUsuario.php", function(data){ 
	
		$.ajax({ 
			type: 'GET', 
			url: '../WebService/getEnqueteIdsWhereUserDidNotVote/'+data, 
			data: { get_param: 'value' }, 
			dataType:'json',
			cache: false,
			success: function (data) {
				console.log(data);
				if (data.ids.length != 0){ 
					
					created = false;
					
					for (i = data.ids.length - 1; i >= 0; i--){
						if (last_array_enquete_to_vote > data.ids[i].id_enquete){
							created = true;
							last_array_enquete_to_vote = data.ids[i].id_enquete;
							break;
						}
					}
							
					if (!created)
						last_array_enquete_to_vote = data.ids[data.ids.length - 1].id_enquete;
					
					console.log(last_array_enquete_to_vote);
					novaEnqueteForm (last_array_enquete_to_vote);

					if (data.ids.length == 1)
						$("#proxima_enquete").hide();
					else
						$("#proxima_enquete").show();
						
				}else{
						
					$.ajax({ 
						type: 'GET', 
						url: '../WebService/getEnqueteIds/', 
						data: { get_param: 'value' }, 
						dataType:'json',
						cache: false,
						success: function (data) {
							console.log('entrou');
							if (data.ids.length != 0){
								created = false;
							
								for (i = data.ids.length - 1; i >= 0; i--){
									if (last_array_enquete > data.ids[i].id_enquete){
										created = true;
										last_array_enquete = data.ids[i].id_enquete;
										break;
									}
								}
								
								if (!created)
									last_array_enquete = data.ids[data.ids.length - 1].id_enquete;
								console.log(last_array_enquete);
								novaEnqueteVisualizacao(last_array_enquete);

								if (data.ids.length == 1)
									$("#proxima_enquete").hide();
								else
									$("#proxima_enquete").show();
							}
							
						}
					});
				}
			}
		});
	});
}
   

   function novaEnqueteVisualizacao(id_enquete){
	   $.ajax({ 
		  	type: 'GET', 
		  	url: '../WebService/getEnquete/'+id_enquete, 
		  	data: { get_param: 'value' }, 
		 	dataType:'json',
			cache: false,
		    success: function (data) { 
		    	console.log(data);
		    	data = data[0];

		    	createEnqueteVisualizacao (data.perfil_45, data.e_imagem, data.nm_usuario, 
		    					data.data_hora, data.titulo, data.qtd_opt,
		    		[data.opt_1, data.opt_2, data.opt_3, data.opt_4, data.opt_5],
		    		[data.qtd_opt_1, data.qtd_opt_2, data.qtd_opt_3, data.qtd_opt_4, data.qtd_opt_5]
		    		);	
		    	
		    }
		});
   }
   
   function createEnqueteVisualizacao(perfil_45, e_imagem, nm_usuario, data_hora, titulo, qtd_opt, opts, qtd_opts){
	
	   if ($('#enquete').length)
		   $('#enquete').remove();
	   
	   
	   var enquete = document.createElement ('div');
	   enquete.className = "enquete";
	   enquete.id = "enquete";
	   enquete.display = "none";
	   imagem = '';
	   if (e_imagem != '')
		   imagem = "<img src='../"+e_imagem+"'>"
	   
	   
	   enquete.innerHTML = "\
			 <div class='e_top'>\
				<img class='pull-left img-circle' src='../"+perfil_45+"'>\
					<div class='nome_user'>"+toPlainText(nm_usuario)+"</div>\
					<div class='data'>"+data_hora+"</div>\
				 </div>\
				 <div class='titulo'>"+toPlainText(titulo)+"</div>\
				 "+imagem+"\
				 <h4>Resultados: </h4>\
				 <canvas id='myChart'></canvas>\
			</div>\
			\
	   ";
			
		document.getElementById("enquete_left").appendChild(enquete);
		
		drawChart (opts, qtd_opts, qtd_opt);
   }

function proximaEnquete(){
	getEnquete();
}

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
	var myPieChart = new Chart(ctx).Pie(data, {
			animationSteps : 50
	});
}


</script>


<body>
  
  
	<nav id="bar" class="navbar navbar-default">
	  <div class="container-fluid">
	    <div class="navbar-header">
	      <a class="navbar-brand" href="home.php">
	        <img alt="Brand" src="images/logo2.png" class="img-responsive">
	      </a>
	      
	      
	    </div>
	    <div>
	    	<button onClick="parent.location='logout.php'" type="submit" class="btn btn-default navbar-right" style="margin: 8px">Sair</button>
	    </div>
	  </div>
	</nav>
	
	
	<div class="container-fluid" style="margin-top: 50px">

		<div class="row">
		 
		  		<!-- Profile -->
		  		<div class="col-md-2 text-center" id="profile">.
		  
				  	<img  src="<?php echo $foto?>"  alt="..." class="img-thumbnail">
				  	<h2><script>document.write(toPlainText('<?php echo $user?>'));</script></h2>
				</div>
				  
				 <!-- Meio -->
				<div class="col-md-6" id="mid">
	                                
					<!-- Button trigger modal -->
					<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#proporModal">
					  Propor
					</button>
					
					<!-- Button trigger modal -->
					<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#fiscalizarModal">
					  Fiscalizar
					</button>
					
					<!-- Button trigger modal -->
					<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#newEnqueteModal">
	  					Criar Enquete
					</button>
			
					<div id="feed"></div>
		
	 			</div>
	 			
	 			<div class="col-md-3" id="left">
	 				<span title="próxima" data-toggle="tooltip" data-placement="bottom" class="btn-lg btn pull-right" id="proxima_enquete" onClick="proximaEnquete()">
	 				<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span></span>
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