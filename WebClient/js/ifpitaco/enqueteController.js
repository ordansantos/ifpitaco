/**
 * TODO: Melhorar essa implementação horrível
 */



ENQUETE = (function(){

	var enqueteObject = {};

	enqueteObject.usuario_id = SESSION.getUsuarioId();

	$(document).ready(function() {
		
		setInterval(function () {enqueteObject.updateEnqueteVisualizacao()}, 5000);
		$("#proxima_enquete").hide();
		enqueteObject.getEnquete();
	});
	
	enqueteObject.last_array_enquete = Number.POSITIVE_INFINITY;
	enqueteObject.last_array_enquete_to_vote = Number.POSITIVE_INFINITY;
	enqueteObject.to_update_enquete = 0;
	enqueteObject.number_votes_enquete = 0;
	enqueteObject.first_call = true;
	
	enqueteObject.createEnqueteForm = function (data){
	   
	   opts = [data.opt_1, data.opt_2, data.opt_3, data.opt_4, data.opt_5];
	   
	   if ($('#enquete').length)
		   $('#enquete').remove();
	   

	   var enquete = document.createElement ('div');
	   enquete.className = "enquete well";
	   enquete.id = "enquete";
	   
	   var imagem = '';
	   if (data.e_imagem != '')
		   imagem = "<img src='"+data.e_imagem+"'>"
	   
	   enqueteObject.data_enquete = data.data_hora;
		   
	   enquete.innerHTML = "\
			 <div class='e_top'>\
				<img class='f45x45 pull-left img-circle' src='"+data.perfil+"'>\
					<a href='userProfile.php?id="+data.usuario_id+"'><div class='nome_user'>"+toPlainText(data.nm_usuario)+"</div></a>\
					<div class='data'><span id='etime'>"+TEMPO.tempoPassado(data.data_hora)+"</span></div>\
				 </div>\
				 <div class='titulo'>"+toPlainText(data.titulo)+"</div>\
				 "+imagem+"\
				 <form id='form_enquete' class='form-horizontal'>\
				 </form>\
				 <div class='bot'><button onClick='votoPost("+data.id_enquete+")' class='btn btn-danger'>Opinar!</button></div>\
				 <div title='Enquete' class='glyphicon glyphicon-bullhorn enquete_icon_no_vote pull-right'></div>\
			</div>\
	   "

	   document.getElementById("enquete_left").appendChild(enquete);
			
			
		for (i = 0; i < data.qtd_opt; i++){
			
			o = document.createElement ('div');
			o.className = "radio";
			o.innerHTML = "\
				<label>\
					<input type='radio' name='voto' value='"+(i+1)+"' "+(i==0?'checked':'')+">\
					"+toPlainText(opts[i])+"\
				</label>\
			";
		
			document.getElementById("form_enquete").appendChild(o);

		}
   }
   
   enqueteObject.novaEnqueteForm = function (id_enquete){
	   $("#proxima_enquete").hide();
	   $.ajax({ 
		  	type: 'GET', 
		  	url: '../WebService/getEnquete/'+id_enquete, 
		  	data: { get_param: 'value' }, 
		 	dataType:'json',
			cache: false,
		    success: function (data) { 
		    	data = data[0];
		    	enqueteObject.createEnqueteForm (data);	
		    }
		});
   }

   
   enqueteObject.getEnquete = function (){
		
	   var opts = {
		  lines: 9, // The number of lines to draw
		  length: 6, // The length of each line
		  width: 4, // The line thickness
		  radius: 8, // The radius of the inner circle
		  corners: 1, // Corner roundness (0..1)
		  rotate: 0, // The rotation offset
		  direction: 1, // 1: clockwise, -1: counterclockwise
		  color: '#000', // #rgb or #rrggbb or array of colors
		  speed: 1.6, // Rounds per second
		  trail: 39, // Afterglow percentage
		  shadow: false, // Whether to render a shadow
		  hwaccel: false, // Whether to use hardware acceleration
		  className: 'spinner', // The CSS class to assign to the spinner
		  zIndex: 2e9, // The z-index (defaults to 2000000000)
		  top: '50%', // Top position relative to parent
		  left: '50%' // Left position relative to parent
		};
		
		var target = document.getElementById('spin');
		var spinner = new Spinner(opts).spin();
		if (!enqueteObject.first_call)
			target.appendChild(spinner.el);
		enqueteObject.first_call=false;
		$.ajax({ 
			type: 'GET', 
			url: '../WebService/getEnqueteIdsWhereUserDidNotVote/'+enqueteObject.usuario_id, 
			data: { get_param: 'value' }, 
			dataType:'json',
			cache: false,
			success: function (data) {
	
				if (data.ids.length != 0){ 
					
					created = false;
					
					for (i = data.ids.length - 1; i >= 0; i--){
						if (enqueteObject.last_array_enquete_to_vote > parseInt(data.ids[i].id_enquete)){
							created = true;
							enqueteObject.last_array_enquete_to_vote = parseInt(data.ids[i].id_enquete);
							break;
						}
					}
							
					if (!created)
						enqueteObject.last_array_enquete_to_vote = parseInt(data.ids[data.ids.length - 1].id_enquete);
					
		
					enqueteObject.novaEnqueteForm (enqueteObject.last_array_enquete_to_vote);
					enqueteObject.to_update_enquete = 0;
					enqueteObject.number_votes_enquete = 0;
				}else{
						
					$.ajax({ 
						type: 'GET', 
						url: '../WebService/getEnqueteIds/', 
						data: { get_param: 'value' }, 
						dataType:'json',
						cache: false,
						success: function (data) {
					
							if (data.ids.length != 0){
								created = false;
								for (i = data.ids.length - 1; i >= 0; i--){
				
									if (enqueteObject.last_array_enquete > parseInt(data.ids[i].id_enquete)){
										created = true;
										enqueteObject.last_array_enquete = parseInt(data.ids[i].id_enquete);
										break;
										
									}
								}
								
								if (!created)
									enqueteObject.last_array_enquete = parseInt(data.ids[data.ids.length - 1].id_enquete);
								
								
								enqueteObject.novaEnqueteVisualizacao(enqueteObject.last_array_enquete);
								enqueteObject.to_update_enquete = enqueteObject.last_array_enquete;
								enqueteObject.number_votes_enquete = 0;
							}
							
						}
					});
				}
				spinner.stop();
			}
		});
   }
   
   
   enqueteObject.afterPostVoto = function(id){
	   
	   enqueteObject.number_votes_enquete = 0;
	   enqueteObject.to_update_enquete = id;
	   enqueteObject.novaEnqueteVisualizacao(id);
	   
		if (enqueteObject.last_array_enquete == Number.POSITIVE_INFINITY)
			enqueteObject.last_array_enquete = id;
		
   }
   
   enqueteObject.updateEnqueteVisualizacao = function(){

	   if (enqueteObject.to_update_enquete != 0){
		   enqueteObject.novaEnqueteVisualizacao(enqueteObject.to_update_enquete);

	   }
   }

   enqueteObject.novaEnqueteVisualizacao = function (id_enquete){

	   $("#proxima_enquete").show();
	   $.ajax({ 
		   type: 'GET', 
		   url: '../WebService/getEnquete/'+id_enquete, 
		   data: { get_param: 'value' }, 
		   dataType:'json',
		   cache: false,
		   success: function (data) { 
			   if (data.length == 0) return;


			   var data = data[0];
			   sum = parseInt(data.qtd_opt_1) + parseInt(data.qtd_opt_2) + parseInt(data.qtd_opt_3) + parseInt(data.qtd_opt_4) + parseInt(data.qtd_opt_5);
			   if (enqueteObject.number_votes_enquete < sum ){

				   enqueteObject.number_votes_enquete = sum;
				   enqueteObject.createEnqueteVisualizacao (data, id_enquete);	

			   }

		   }
	   });
   }
      
   enqueteObject.createEnqueteVisualizacao = function (data, id_enquete){
	   
	   var opts = [data.opt_1, data.opt_2, data.opt_3, data.opt_4, data.opt_5];
	   var qtd_opts =  [data.qtd_opt_1, data.qtd_opt_2, data.qtd_opt_3, data.qtd_opt_4, data.qtd_opt_5];
	   
	   if ($('#enquete').length)
		   $('#enquete').remove();


	   var enquete = document.createElement ('div');
	   enquete.className = "enquete well";
	   enquete.id = "enquete";
	   enquete.display = "none";
	   imagem = '';
	   if (data.e_imagem != '')
		   imagem = "<img src='"+data.e_imagem+"'>";

	   enqueteObject.data_enquete = data.data_hora;

   	   enquete.innerHTML = "\
   			 <div class='e_top'>\
   				<img class='f45x45 pull-left img-circle' src='"+data.perfil+"'>\
   				<a href='userProfile.php?id="+data.usuario_id+"'><div class='nome_user'>"+toPlainText(data.nm_usuario)+"</div></a>\
   					<div class='data'><span id='etime'>"+TEMPO.tempoPassado(data.data_hora)+"</span></div>\
   				 </div>\
   				 <div class='titulo'>"+toPlainText(data.titulo)+"</div>\
   				 "+imagem+"\
   				 <h4>Resultados: </h4>\
   				 <canvas id='myChart' width=250 ></canvas>\
   				 <div id='chartjs-tooltip'></div>\
   				 <div>\
   				 <span title='Enquete' class='glyphicon glyphicon-bullhorn enquete_icon pull-right'></span>\
   				<span class='glyphicon glyphicon-th-list curiar_enquete_icon' onClick='CURIAR.curiarEnquete("+ id_enquete +")' rel='tooltip' data-placement='top' data-original-title='Curiar' data-toggle='modal' data-target='#list_people_laike'></span>\
   				</div>\
   			</div>\
   			\
   	   ";

   	   document.getElementById("enquete_left").appendChild(enquete);

   	   //Não possui nenhuma opção no tooltip, então esconda-o
   	   $('#chartjs-tooltip').hide();
   	   
   	   CHART.drawChart (opts, qtd_opts, data.qtd_opt);

   	   $("[rel='tooltip']").tooltip();
   }

   enqueteObject.proximaEnquete = function(){
	   enqueteObject.getEnquete();
   }

   enqueteObject.atualizaTempo = function(){
        if ($('#etime').length > 0){
                document.getElementById('etime').innerHTML = TEMPO.tempoPassado(enqueteObject.data_enquete);
        }
   }
   
   return enqueteObject;
	

})();