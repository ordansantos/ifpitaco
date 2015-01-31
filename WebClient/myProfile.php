

<?php
	session_start();
	if ($_SESSION['nm_usuario'] == '') {
    	header ("location: index.php");
	}
	
	$user = $_SESSION['nm_usuario'];
	$foto = $_SESSION['foto'];
?>


<!DOCTYPE html>

<html>

	<head>
		<link rel="shortcut icon" href="images/favicon.png">
  		<title>IFPitaco</title>
  		
  		<meta charset="utf-8"/>
		<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
	
		<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
		<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" rel="stylesheet">
		<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>

		<link rel="stylesheet" type="text/css" href="css/comentario.css">
		<script src="js/bootbox.min.js"></script>
		<link rel="stylesheet" type="text/css" href="css/home.css">
		<script src="js/jquery-visible.min.js"></script>
		<script src="js/toPlainText.js"></script>
		<link rel="stylesheet" type="text/css"  href="css/enquete_form.css">
		
		<!--http://www.chartjs.org/docs/-->
		<script src="Chart/Chart.js"></script>
	</head>

	
	
<script type="text/javascript">



//Matriz de posts, cada linha possui o id dos comentários
var post_array = [];
//Id do usuario que realizou o comentário
var comentario_user = [];
//Id do usuario que criou o post
var post_user = [];
//Flag para verificar se ainda pode carregar posts antigos
var is_there_more_post = true;


/*Definindo os intervalos de Updates e carregamento de comentários mais velhos*/
$(document).ready(function() {
	 getEnquete();
	 $('[data-toggle="tooltip"]').tooltip();
});


/*SISTEMA DE ENVIO DE VOTO E CARREGAMENTO DE ENQUETES*/


   function createEnqueteForm(perfil_45, e_imagem, nm_usuario, data_hora, titulo, id_enquete, e_qtd_opt, opts){
	
	   if ($('#enquete').length)
		   $('#enquete').remove();
	   

	   var enquete = document.createElement ('div');
	   enquete.className = "enquete";
	   enquete.id = "enquete";
	   
	   imagem = '';
	   if (e_imagem != '')
		   imagem = "<img src='../"+e_imagem+"'>"
	   
	   
	   enquete.innerHTML = "\
			 <div class='e_top'>\
				<img class='pull-left img-circle' src='../"+perfil_45+"'>\
					<div class='nome_user'>"+toPlainText(nm_usuario)+"</div>\
					<div class='data'>"+data_hora+"</div>\
				 </div>\
				 <div class='titulo'>"+toPlainText(titulo)+"</div>\
				 "+imagem+"\
				 <form id='form_enquete' class='form-horizontal'>\
				 </form>\
				 <div class='bot'><button onClick='votoSend("+id_enquete+")' class='btn btn-danger'>Opinar!</button></div>\
			</div>\
	   "

	   document.getElementById("enquete_left").appendChild(enquete);
			
			
		for (i = 0; i < e_qtd_opt; i++){
			
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
   
   function novaEnqueteForm(id_enquete){

	   $.ajax({ 
		  	type: 'GET', 
		  	url: '../WebService/getEnquete/'+id_enquete, 
		  	data: { get_param: 'value' }, 
		 	dataType:'json',
			cache: false,
		    success: function (data) { 
		    	data = data[0];
		    	createEnqueteForm (data.perfil_45, data.e_imagem, data.nm_usuario, 
   					data.data_hora, data.titulo, data.id_enquete, data.qtd_opt,
   					[data.opt_1, data.opt_2, data.opt_3, data.opt_4, data.opt_5]	
		    		);	
		    }
		});
   }
   
   function votoSend(id){
	   
		console.log('entrou: votoSend');
		event.preventDefault();
		var data = $('#form_enquete').serializeArray();
		data.push ({name:'enquete_id', value: id});
			$.post ("postVoto.php", data, function(data){
				console.log(data);
				if (data.trim() == '0')
					bootbox.alert("Faça login para votar!", function() {
						window.location.assign("index.php");
					});
				else{
					novaEnqueteVisualizacao(id);
					if (last_array_enquete = Number.POSITIVE_INFINITY)
						last_array_enquete = id;
				}
			});
   }
   
   var last_array_enquete = Number.POSITIVE_INFINITY;
   var last_array_enquete_to_vote = Number.POSITIVE_INFINITY;
	
   
   
function getEnquete(){

	$.get ("getIdUsuario.php", function(data){ 
	
		$.ajax({ 
			type: 'GET', 
			url: '../WebService/getEnqueteIdsWhereUserDidNotVote/'+data, 
			data: { get_param: 'value' }, 
			dataType:'json',
			cache: false,
			success: function (data) {
				console.log(data);
				if (data.ids.length != 0){ 
					
					created = false;
					
					for (i = data.ids.length - 1; i >= 0; i--){
						if (last_array_enquete_to_vote > data.ids[i].id_enquete){
							created = true;
							last_array_enquete_to_vote = data.ids[i].id_enquete;
							break;
						}
					}
							
					if (!created)
						last_array_enquete_to_vote = data.ids[data.ids.length - 1].id_enquete;
					
					console.log(last_array_enquete_to_vote);
					novaEnqueteForm (last_array_enquete_to_vote);

					if (data.ids.length == 1)
						$("#proxima_enquete").hide();
					else
						$("#proxima_enquete").show();
						
				}else{
						
					$.ajax({ 
						type: 'GET', 
						url: '../WebService/getEnqueteIds/', 
						data: { get_param: 'value' }, 
						dataType:'json',
						cache: false,
						success: function (data) {
							console.log('entrou');
							if (data.ids.length != 0){
								created = false;
							
								for (i = data.ids.length - 1; i >= 0; i--){
									if (last_array_enquete > data.ids[i].id_enquete){
										created = true;
										last_array_enquete = data.ids[i].id_enquete;
										break;
									}
								}
								
								if (!created)
									last_array_enquete = data.ids[data.ids.length - 1].id_enquete;
								console.log(last_array_enquete);
								novaEnqueteVisualizacao(last_array_enquete);

								if (data.ids.length == 1)
									$("#proxima_enquete").hide();
								else
									$("#proxima_enquete").show();
							}
							
						}
					});
				}
			}
		});
	});
}
   

   function novaEnqueteVisualizacao(id_enquete){
	   $.ajax({ 
		  	type: 'GET', 
		  	url: '../WebService/getEnquete/'+id_enquete, 
		  	data: { get_param: 'value' }, 
		 	dataType:'json',
			cache: false,
		    success: function (data) { 
		    	console.log(data);
		    	data = data[0];

		    	createEnqueteVisualizacao (data.perfil_45, data.e_imagem, data.nm_usuario, 
		    					data.data_hora, data.titulo, data.qtd_opt,
		    		[data.opt_1, data.opt_2, data.opt_3, data.opt_4, data.opt_5],
		    		[data.qtd_opt_1, data.qtd_opt_2, data.qtd_opt_3, data.qtd_opt_4, data.qtd_opt_5]
		    		);	
		    	
		    }
		});
   }
   
   function createEnqueteVisualizacao(perfil_45, e_imagem, nm_usuario, data_hora, titulo, qtd_opt, opts, qtd_opts){
	
	   if ($('#enquete').length)
		   $('#enquete').remove();
	   
	   
	   var enquete = document.createElement ('div');
	   enquete.className = "enquete";
	   enquete.id = "enquete";
	   enquete.display = "none";
	   imagem = '';
	   if (e_imagem != '')
		   imagem = "<img src='../"+e_imagem+"'>"
	   
	   
	   enquete.innerHTML = "\
			 <div class='e_top'>\
				<img class='pull-left img-circle' src='../"+perfil_45+"'>\
					<div class='nome_user'>"+toPlainText(nm_usuario)+"</div>\
					<div class='data'>"+data_hora+"</div>\
				 </div>\
				 <div class='titulo'>"+toPlainText(titulo)+"</div>\
				 "+imagem+"\
				 <h4>Resultados: </h4>\
				 <canvas id='myChart'></canvas>\
			</div>\
			\
	   ";
			
		document.getElementById("enquete_left").appendChild(enquete);
		
		drawChart (opts, qtd_opts, qtd_opt);
   }

function proximaEnquete(){
	getEnquete();
}

function drawChart(opts, qtd_opts, qtd_opt){
	
	colors = ['#F7464A', '#46BFBD', '#FDB45C', '#949FB1', '#4D5360'];
	highlights = ['#FF5A5E', '#5AD3D1', '#FFC870', '#A8B3C5', '#616774'];
	data = [];
	for (i = 0; i < qtd_opt; i++){
		
		data.push({
			value: parseInt(qtd_opts[i]),
			color: colors[i],
			highlight: highlights[i],
			label: opts[i]
		});
	}

	var ctx = $("#myChart").get(0).getContext("2d");
	var myPieChart = new Chart(ctx).Pie(data, {
			animationSteps : 50
	});
}


</script>


<body>
  
  
	<nav id="bar" class="navbar navbar-default">
	  <div class="container-fluid">
	    <div class="navbar-header">
	      <a class="navbar-brand" href="home.php">
	        <img alt="Brand" src="images/logo2.png" class="img-responsive">
	      </a>
	      
	      
	    </div>
	    <div>
	    	<button onClick="parent.location='logout.php'" type="submit" class="btn btn-default navbar-right" style="margin: 8px">Sair</button>
	    </div>
	  </div>
	</nav>
	
	
	<div class="container-fluid" style="margin-top: 50px">

		<div class="row">
		 
		  		<!-- Profile -->
		  		<div class="col-md-2" id="profile">.
		  
				  	<a class="thumbnail" ><img  src="<?php echo $foto?>"  alt="..." class="img-thumbnail"></a>
				  	<h2 class=" text-center"><script>document.write(toPlainText('<?php echo $user?>'));</script></h2>
				</div>
				  
				 <!-- Meio -->
				<div class="col-md-6" id="mid">
			
					<div id="profile"></div>
					
	 			</div>
	 			
	 			<div class="col-md-3" id="left">
	 				<span title="próxima" data-toggle="tooltip" data-placement="bottom" class="btn-lg btn pull-right" id="proxima_enquete" onClick="proximaEnquete()">
	 				<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span></span>
	 				<div id="enquete_left">
	 				</div>
	 			

	 			</div>

	 	</div>

	</div>
			

  
 </body>
  
</html>