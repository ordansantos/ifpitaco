/**
 * 
 */

/*Sistema para excluir um comentário*/
function excluirComentario(id){

	id = id.replace ('c', '');
	
	if (COMENTARIO.comentario_user[id] != usuario_id)
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