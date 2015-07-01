/**
 * 
 */

var COMENTARIO = (function(){
	
	var comentario = {};	
	
	//Id do usuario que realizou o comentário
	comentario.comentario_user = [];
	
	comentario.data_comentario = [];
	
	//Criando um novo comentário
	comentario.novo = function (comentarioData, post_idx){
		comentario.data_comentario[comentarioData.comentario_post_id] = comentarioData.data_hora;
		var com = document.createElement("div");
		com.className = "comentario";
		com.id = 'c'+comentarioData.comentario_post_id;
		com.innerHTML = "<img class='f32x32' src='"+comentarioData.perfil+"'/><div class='content'><a href='userProfile.php?id="+comentarioData.id_usuario+"'><strong>"+toPlainText(comentarioData.nm_usuario)+"</strong></a> "+toPlainText(comentarioData.comentario)+"<h6><span id='ctime_"+comentarioData.comentario_post_id+"'>"+tempo_passado(comentarioData.data_hora)+"</span></h6></div><i onClick='excluirComentario(this.parentNode.id)'class='glyphicon glyphicon-remove'></i>";
		document.getElementById("pc" + post_idx).appendChild(com);
		
	}
	
	//Carregando novos comentários
	comentario.load = function (post_array){

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
								
							
							 	comentario.novo(c, post_idx);	
							 	comentario.comentario_user[c.comentario_post_id] = c.id_usuario;
								
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
	
	comentario.atualizaTempo = function(){
		
		for (i in comentario.data_comentario){
			if (!$('#ctime_'+i).length > 0){
				delete comentario.data_comentario[i];
				continue;
			}
			document.getElementById('ctime_'+i).innerHTML = tempo_passado(comentario.data_comentario[i]);
		}
		
	}
	
	return comentario;
	
})();