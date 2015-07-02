/**
 * 
 */


POSTFORM = (function(){
	
	var postform = {};
	
	$(document).ready(function() {
		
		$("#img_input_fiscalizacao").change(function(){
			loadImageFiscalizacaoForm(this);
		});
		
		$(window).scroll(function() {
			  if( $(document).height() - ($(window).scrollTop() + $(window).height())  < 50 && is_there_more_post) {
				  POST.morePost();
			  }
		});
		
		postform.resetFiscalizacao();
		
	});
	
	//Mostra o preview da imagem da fiscalização
	function loadImageFiscalizacaoForm(input) {

	    if (input.files && input.files[0]) {
	        var reader = new FileReader();

	        reader.onload = function (e) {
	     
	            $('#img_fiscalizar').attr('src', e.target.result);
	            $("#img_fiscalizar").fadeIn("slow");
	        }
	        reader.readAsDataURL(input.files[0]);
	    }
	}
	
	//Adicionando os ramos aos formulários
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
	
	postform.resetFiscalizacao = function(){
		$("#img_fiscalizar").hide();
		$("#form_fiscalizacao")[0].reset();
	}
	
	postform.resetProposta = function(){
		$("#form_proposta")[0].reset();
	}
	
	return postform;
	
})();