/**
 * 
 */
	

//Vetor de índice de posts
var postArray = [];

(function(){

	var this_js_script = $('script[src*=somefile]'); 
	usuario_id = this_js_script.attr('data-usuario_id')

	$(document).ready(function() {
		
		postLoad(); 
		
		setInterval(function () {postReload()}, 5000);
		setInterval(function () {newPosts()}, 5000);
		setInterval(function () {likesLoad()}, 5000);
		tintervals = setInterval(function () {atualizaTempos()}, 1000 * 20);
		
		$(window).scroll(function() {
			
			  if( $(document).height() - ($(window).scrollTop() + $(window).height())  < 50 && is_there_more_post) {
				  morePost();
			  }
			 });
		
		$("#img_input_fiscalizacao").change(function(){
			readUrlFromFiscalizacao(this);
		});
		
		resetFiscalizacao();
		
	});



//Id do usuario que criou o post
var post_user = [];
//Flag para verificar se ainda pode carregar posts antigos
var is_there_more_post = true;

//Cria um post (fiscalização, proposta) novo
function novoPost (post){

	var form = document.createElement ('form');
	data_post[post.post_id] = post.data_hora;
	form.id = post.post_id;
	var img='';
	
	var tipo_icon = '<div title="Fiscalização" class="fiscalizar_icon glyphicon glyphicon-warning-sign pull-right">';
	if (post.tipo == 0)
		tipo_icon = '<div title="Proposta" class="propor_icon glyphicon glyphicon-send pull-right">';
	
	//É uma fiscalização com imagem?
	if (post.tipo == 2)
		img = "<img class='fiscalizacao_img' src='"+post.imagem+"' />"

	form.innerHTML = 
		'<div class="well well-sm">' + 
			'<div class="post">' + 
				'<div class="top">' + 
					'<i onClick="excluirPost(this)" class="glyphicon glyphicon-remove"></i>' +
					'<img class="pull-left f45x45" src="'+post.perfil+'" >' + 
					'<div>' + 
						'<h4><a  href="userProfile.php?id='+post.usuario_id+'">'+toPlainText(post.nm_usuario)+'</a></h4>' + 
						'<h6><span id="ptime_'+post.post_id+'">'+tempo_passado(post.data)+'</span>&nbsp'+post.nm_ramo+'</h6>' +
					'</div>' +
				'</div>' + 
				'<div class="content">'+toPlainText(post.comentario)+'</div>' + 
				img+
				'<div class="bot">' + 
					'<div class="laike btn-lg ">' + 
						'<div id="nl'+post.post_id+'" onClick="laikeSend(this.id)" type="button"  data-toggle="tooltip" data-placement="top">' + 
							'<i class="glyphicon glyphicon-thumbs-up" aria-hidden="true" ></i>' +
						'</div>' + 
						'<span id="cc'+post.post_id+'" onClick="curiar_curtida(this.id)" rel="tooltip" data-placement="top" data-original-title="Curiar" data-toggle="modal" data-target="#list_people_laike"> 0</span>' + 
					'</div>' + 
					'<div class="btn" style="cursor:default">' +
						'<span class="glyphicon glyphicon-comment" aria-hidden="true" ></i><span id="nc'+post.post_id+'"> 0</span>' + 
					'</div>' + 
				tipo_icon +
				'</div>' + 
			'</div>' +
		'</div>' + 
	'</div>' + 

	'<div id="pc'+post.post_id+'"></div>' + 
	'<div class="input-group add-on">' + 
		'<input type="text" class="form-control" placeholder="Comentar..." name="comentario" autocomplete="off" id="input'+post.post_id+'">' + 
		'<div class="input-group-btn">' + 
			'<button class="btn btn-default" type="submit" onClick="comentarioPOST(this.form.id)"><i class="glyphicon glyphicon-share-alt"></i></button>' +
		'</div>' + 
	'</div>';
	
	document.getElementById('feed').appendChild(form);
	
	$("[rel='tooltip']").tooltip();
	
	return form;
}

function postReload(){

		for (p in postArray){
			  
			  /**
			   * Verifica se o post é visível e assim atualiza seus comentários
			  */
				if (document.getElementById(postArray[p]) == null)
					console.log(postArray[p]);
			  if(!$('#'+postArray[p]).visible(true)){
				  continue;
			  }
			 
		    COMENTARIO.load(postArray[p]);
		   
		}

}

//Carregando novos 'laikes'
function likesLoad(){
	
  for (p in postArray){
	  post_idx = postArray[p];
	  
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
					
					$("#nl"+post_idx).siblings('span').html(" "+data.cnt) ;
					
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
	
/*Sistema de Envio e carregamento de Posts*/

//Carrega N posts mais novos ao carregar a página
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

          	post = data.posts[i];
          	
          	if (i == 0)
          		first_id_post = post.post_id;
				
				postArray.push (post.post_id);
				post_user[post.post_id] = post.usuario_id;
				
     			novoPost( post );	
     			
     			last_id_post = post.post_id;
     			
     			COMENTARIO.load(post.post_id);
			}
      	 
      	$('#feed').fadeIn(400);
      	likesLoad();
      }
  });

}


//Id do último post carregado
var last_id_post;

//Id do primeiro post
var first_id_post = 0;



isInAjax = false;
//Carregando posts mais antigos
//Ajax não para o código, precisa verificar se está em uma chamada de ajax para carregar mais
function morePost(){

	if (isInAjax) return;
	isInAjax = true;

	
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
	
	     	 		//Json
	        		post = data.posts[i];
	        		postArray.push(post.post_id);
					post_user[post.post_id] = post.usuario_id;
					
	   			novoPost( post);	
	
	   			last_id_post = post.post_id;
	   			COMENTARIO.load(post.post_id);
	
			 }
	
	    	  likesLoad();
	    	isInAjax = false;
	    }
	});
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
	        		post = data.posts[i];
	        		
	        		postArray.push(post.post_id);
					post_user[post.post_id] = post.usuario_id;
	   			form = novoPost( post );	
	
	   			document.getElementById('feed').insertBefore(form, document.getElementById('feed').firstChild);
	   			$('#'+post.post_id).hide();
	   			$('#'+post.post_id).fadeIn('slow');
					
	   			first_id_post = post.post_id;
	
	   			COMENTARIO.load(post.post_id);
				}
				if (data.posts.length != 0){
					likesLoad();
				}
	   
	    }
	});

}


/*Sistema para excluir uma publicação*/

function excluirPost(sender){

	e = sender.parentNode.parentNode.parentNode.parentNode;
	id = e.getAttribute('id');

	if (post_user[id] != usuario_id)
		bootbox.dialog({message: "Essa pubicação está lhe incomodando?", 
			buttons:{
				"Não": {className: "btn-default btn-sm"},
				"Sim": {
						className: "btn-primary btn-sm",
						
						callback: function(){ 
							$('#'+id).remove();
							postArray.splice(postArray.indexOf(id), 1);
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
							postArray.splice(postArray.indexOf(id), 1);
							delete data_post[id];
							$.param({post_id: id});
							a = $.post ("services/deletarPost.php", {post_id: id});
	
						}
				}
				
			}
		}); 

}



var data_post = [];
var data_enquete;


function atualizaTempos (){

	for (i in data_post){
			document.getElementById('ptime_'+i).innerHTML = tempo_passado(data_post[i]);
	}

	
	if ($('#etime').length > 0){
		document.getElementById('etime').innerHTML = tempo_passado(data_enquete);
	}

	COMENTARIO.atualizaTempo();
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




})();