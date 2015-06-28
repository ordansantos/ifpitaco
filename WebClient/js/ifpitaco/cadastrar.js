/**
 * Chamado por:
 * 	cadastrar.php
 */


//Form dinâmico
function option1Click(){
	$('#professor').hide();
	$('#aluno').show();
}

function option2Click(){
	$('#aluno').hide();
	$('#professor').show();
}

function option3Click(){
	$('#professor').hide();
	$('#aluno').hide();
}

( function(){
		$(document).ready(function(){
			$('#pop').focusin(function (){
				$('#pop').popover('show');
			});
			
			$('#pop').focusout(function(){
				$('#pop').popover('hide');
			});
			
			$('#professor').hide();
			$('[data-toggle="popover"]').popover();
			$('#professor').hide();
		});
})();

// Enviando os dados ao servidor
(function(){
	
	$(document).ready(function(){
		$("#form").submit (function(event){
			event.preventDefault();
		
			$('#submit').attr ("disabled", "disabled");
			var formData = new FormData($(this)[0]);
			$.ajax({
				type: "POST",
				url: "../WebService/postUsuario",
		        contentType: false,
		        processData: false,
				data: formData,
				success: function(data){
					
					if ($.trim(data) != '1'){
						$('#error').hide();
						document.getElementById("error").innerHTML = data;
						$('#error').fadeIn("fast", function (){
							$('#submit').attr ("disabled", false);
						});
						window.scrollBy(0,200);
					} else
						window.location.assign("index.php");
				}, error: function(data){
		
					bootbox.alert("Faça o upload corretamente! Escolha uma imagem válida ou uma imagem menor que 5000 x 5000.", function() {
						window.location.assign('cadastrar.php');
					});
				
				}
			});
		});
	});
})();