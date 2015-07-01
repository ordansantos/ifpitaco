/**
 * 
 */

//Enviando um novo comentário
function comentarioPOST (id){

	event.preventDefault();

	if ($("#input"+id)[0].value == ''){
		return;
	}
	
	var data = $('#' + id).serializeArray();
	data.push ({name:'post_id', value: id});
		$.post ("services/comentar.php", data, function(data){
			if (data.trim() == '0')
				bootbox.alert("Faça login para comentar!", function() {
					window.location.assign("index.php");
				});
			else{
				COMENTARIO.load(id);
			}
		});
		document.getElementById(id).reset();
}