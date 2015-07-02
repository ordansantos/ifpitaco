/**
 * Controlador dos comentários
 * TODO: verificar se o comentário carregado é do usuário atual
 */

var COMENTARIO = (function(){
	
	var comentario = {};	
	
	comentario.data_comentario = [];
	
	//Criando um novo comentário
	comentario.novo = function (comentarioData, post_idx){
		comentario.data_comentario[comentarioData.comentario_post_id] = comentarioData.data_hora;
		var com = document.createElement("div");
		com.className = "comentario";
		com.id = 'c'+comentarioData.comentario_post_id;
		com.innerHTML = "<img class='f32x32' src='"+comentarioData.perfil+"'/><div class='content'><a href='userProfile.php?id="+comentarioData.id_usuario+"'><strong>"+toPlainText(comentarioData.nm_usuario)+"</strong></a> "+toPlainText(comentarioData.comentario)+"<h6><span id='ctime_"+comentarioData.comentario_post_id+"'>"+tempo_passado(comentarioData.data_hora)+"</span></h6></div><i onClick='comentarioDELETE(this.parentNode.id)'class='glyphicon glyphicon-remove'></i>";
		document.getElementById("pc" + post_idx).appendChild(com);
		
	}
	
	//Carregando novos comentários
	comentario.load = function (post_idx){
			$.ajax({
				type: 'GET',
				url: '../WebService/getComentariosById/'+post_idx,
				data: { get_param: 'value' },	
				dataType: 'json',
				cache: false,
				success: function (data) {
					
					 //Verificando se o comentário foi apagado
					 if (data.flag == '0')
						 return;
					 
					 document.getElementById("nc"+post_idx).innerHTML = " "+data.comentarios.length;
					 
					 for (i in data.comentarios){
						 c = data.comentarios[i];
						 if (document.getElementById("c"+c.comentario_post_id) == null)
							 comentario.novo (c, post_idx);
					 }
					 
				},
				error: function(data){}
			});

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