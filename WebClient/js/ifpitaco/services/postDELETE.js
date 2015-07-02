/**
 * 
 */

/*Sistema para excluir uma publicação*/

function postDELETE(sender){

	e = sender.parentNode.parentNode.parentNode.parentNode;
	id = e.getAttribute('id');

	bootbox.dialog({message: "Deseja excluir sua publicação?", 
		buttons:{
			"Não": {className: "btn-default btn-sm"},
			"Sim": {
					className: "btn-primary btn-sm",
					
					callback: function(){
						
						$('#'+id).remove();
						$.param({post_id: id});
						a = $.post ("services/deletarPost.php", {post_id: id});

					}
			}
			
		}
	}); 

}