/**
 * TODO: Verificar se o post é do usuário atual, na criação de um post
 * 		 Posts não serão excluídos de postArray
 */
	

POST = (function(){
	
	var postObject = {};
	
	var this_js_script = $('script[src*=somefile]'); 
	
	postObject.usuario_id = this_js_script.attr('data-usuario_id');
	
	//Vetor de índice de posts
	postObject.postArray = [];
	
	//Flag para verificar se ainda pode carregar posts antigos
	postObject.is_there_more_post = true;
	
	//Id do último post carregado
	postObject.last_id_post;

	//Id do primeiro post
	postObject.first_id_post = 0;
	
	//Carregando posts mais antigos em morePost
	//Ajax não para o código, precisa verificar se está em uma chamada de ajax para carregar mais
	postObject.isInAjax_MorePost = false;

	$(document).ready(function() {
		
		postObject.postLoad(); 
		
		setInterval(function () { postObject.postReload() }, 5000);
		setInterval(function () { postObject.newPost() }, 10000);
		
	});

	postObject.postsReload = function (){
		for (p in postObject.postArray)
			postObject.postReload(p);
	}

	postObject.postReload = function (post){
		if (document.getElementById(post) == null || !$('#'+post).visible(true) ) 
			return;
		COMENTARIO.load(post);
		LAIKES.load(post);
	}


	postObject.postLoad = function (){
	
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
	 						postObject.first_id_post = post.post_id;
					
	 					postObject.postArray.push (post.post_id);
					
	 					postObject.createPost( post );	
	     			
	 					postObject.last_id_post = post.post_id;
	     			
	 					postObject.postReload(post.post_id);
	 				}
	      	 
	 				$('#feed').fadeIn(400);
	 			}
	  });
	}

	postObject.morePost = function (){
	
		if (postObject.isInAjax_MorePost) return;
		
		postObject.isInAjax_MorePost = true;
	
		$.ajax({ 
			type: 'GET', 
			url: '../WebService/getNPostsLessThanMid/3/'+postObject.last_id_post, 
			data: { get_param: 'value' }, 
				dataType:'json',
				cache: false,
		    success: function (data) { 
					 if (data.posts.length == 0)
						 postObject.is_there_more_post = false;
		    	 for (i = 0; i < data.posts.length; i++){
		
		     	 		//Json
		        		post = data.posts[i];
		        		
		        		postArray.push(post.post_id);
						
		        		postObject.createPost( post);	
		
		        		postObject.last_id_post = post.post_id;
		        		
		        		postObject.postReload(post.post_id);
		
				 }
		
		    	 postObject.isInAjax_MorePost = false;
		    }
		});
	}

	//Carregando posts mais novos
	postObject.newPost = function (){
		
		$.ajax({ 
			type: 'GET', 
			url: '../WebService/getAllPostsGreaterThanNid/'+ postObject.first_id_post, 
			data: { get_param: 'value' }, 
				dataType:'json',
				cache: false,
		    success: function (data) { 
		
		    	 for (i = 0; i < data.posts.length; i++){
		        		post = data.posts[i];
		        		
		        		postObject.postArray.push(post.post_id);
	
		   			form = postObject.createPost( post );	
		
		   			document.getElementById('feed').insertBefore(form, document.getElementById('feed').firstChild);
		   			$('#'+post.post_id).hide();
		   			$('#'+post.post_id).fadeIn('slow');
						
		   			postObject.first_id_post = post.post_id;
		
		   			postObject.postReload(post.post_id);
					}
		   
		    }
		});
	
	}


	postObject.data_post = [];
	


	function atualizaTempos (){
	
		for (i in postObject.data_post){
				if (!$('#ptime_'+i).length > 0){
					delete data_post[i];
					continue;
				}
				document.getElementById('ptime_'+i).innerHTML = tempo_passado(data_post[i]);
		}
	
		
		if ($('#etime').length > 0){
			document.getElementById('etime').innerHTML = tempo_passado(data_enquete);
		}
	
		
	}
	
	//Cria um post (fiscalização, proposta) novo
	postObject.createPost = function (post){
	
		var form = document.createElement ('form');
		postObject.data_post[post.post_id] = post.data_hora;
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
						'<i onClick="postDELETE(this)" class="glyphicon glyphicon-remove"></i>' +
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
							'<div id="nl'+post.post_id+'" onClick="laikePOST(this.id)" type="button"  data-toggle="tooltip" data-placement="top">' + 
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
	
	return postObject;

})();
