<!DOCTYPE html> 
<html lang="en"> 
<head> 
		<meta charset="utf-8"> 

		<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.js"></script>
		<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" rel="stylesheet">
		<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
<script>

//Adicionando os ramos às propostas
$.get("../WebService/getRamos", function(data, status){
	
	var ramos = JSON.parse(data).ramos;
	var form = document.getElementById("form_ramos");
	
	for (i = 0; i < ramos.length; i++){
		
		var t = ramos[i].nm_ramo.split(":");
		
		if (i == 0 || (i > 0 && t[0] != ramos[i-1].nm_ramo.split(":")[0])){
			var optgroup = document.createElement("optgroup");
			optgroup.label = t[0];
			form.add(optgroup);
		}
		
		
		var option = document.createElement("option");
		option.value = ramos[i].id_ramo;
		option.text = ramos[i].nm_ramo.split(":")[1];
		form.add(option);
	}
});

//Enviando a proposta por meio do post
function propostaClick() {
	alert ("entrou");	
	var values = $('#form_proposta').serialize();
	
	$.ajax({
		url: "propor.php",
		type: "post",
		data: values,
		async: false,
		success: function(data){
			if ($.trim(data)!='1')
				alert ("Faça login antes de comentar!");
			else
				document.getElementById("comentario").value = "";
		},
		error:function(){
			alert("error");
		}
	});
}

function clearContents(element) {
	element.value = '';
}

</script>

</head>
<body>


	<!-- Button trigger modal -->
	<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">
	  Launch demo modal
	</button>
	
	<!-- Modal -->
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
	    				<li><select class="form-control" id="form_ramos" name="ramo_id"></select></li>
	    				<li><h4 >Comentário:</h4></li>
						<li><textarea class="form-control" id="comentario" name="comentario" rows="7"onfocus="clearContents(this);">Escreva aqui o que você tem a dizer sobre o ramo selecionado...</textarea></li>
				

				</ul>
				</form>
				
	      </div>
	      
	      <div class="modal-footer">
	      	<button type="button" class="btn btn-primary" onClick="propostaClick()" data-dismiss="modal">Enviar</button>
	        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
	      </div>
	      
	    </div>
	  </div>
	</div>


</body>
</html>