/**
 * 
 */


//Envio de 'Laike'
function laikePOST(id){
	
	id = id.replace ('nl', '');
	$("#nl"+id).tooltip('hide');

	$.post ("services/laikar.php", {post_id: id}, function(data){
		if (data.trim() == '0')
			bootbox.alert("Faça login para laikar!", function() {
				window.location.assign("index.php");
			});
		else
			LAIKES.load(id);
	});
	
}
