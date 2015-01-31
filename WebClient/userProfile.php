
<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

	include ('services/redirect.php');
	
	$json = redirectGet('http://localhost/WebService/getUsuarioById/'.$_GET['id']);

	$user = json_decode($json);
	
	if (sizeof($user) == 0)
		header ("location: index.php");
		
	$user = $user[0];
	
	$nome = $user->nm_usuario;
	$tipo = $user->usuario_tipo;
	$curso = $user->curso;
	$ano_periodo = $user->ano_periodo;
	$grau_academico = $user->grau_academico;
	$image_perfil = '../'.$user->perfil_120;
	
?>



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
		
  		<meta charset="utf-8"/>
  		<link rel="shortcut icon" href="images/favicon.png">
  		<title>IFPitaco</title>
		<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
	
		<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
		<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" rel="stylesheet">
		<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
		
		
		<script src="js/toPlainText.js"></script>
		
		<!--http://www.chartjs.org/docs/-->
		<script src="Chart/Chart.js"></script>
		<link rel="stylesheet" type="text/css"  href="css/tooltip_chart_customized.css">
		<link rel="stylesheet" type="text/css"  href="css/enquete_form.css">
		<link rel="stylesheet" type="text/css"  href="css/user_profile.css">
	</head>

	
	
<script type="text/javascript">

/*Definindo os intervalos de Updates e carregamento de comentários mais velhos*/
$(document).ready(function() {
	

	setInterval(function () {updateEnqueteVisualizacao()}, 5000);
	
	$("#proxima_enquete").hide();
	getEnquete();


});


/*SISTEMA DE ENVIO DE VOTO E CARREGAMENTO DE ENQUETES*/


   function createEnqueteForm(perfil_45, e_imagem, nm_usuario, data_hora, titulo, id_enquete, e_qtd_opt, opts){
	
	   if ($('#enquete').length)
		   $('#enquete').remove();
	   

	   var enquete = document.createElement ('div');
	   enquete.className = "enquete well";
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
	   $("#proxima_enquete").hide();
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
			$.post ("services/postVoto.php", data, function(data){
				console.log(data);
				if (data.trim() == '0')
					bootbox.alert("Faça login para votar!", function() {
						window.location.assign("index.php");
					});
				else{
					number_votes_enquete = 0;
					to_update_enquete = id;
					novaEnqueteVisualizacao(id);
					if (last_array_enquete = Number.POSITIVE_INFINITY)
						last_array_enquete = id;
				}
			});
   }
   
   var last_array_enquete = Number.POSITIVE_INFINITY;
   var last_array_enquete_to_vote = Number.POSITIVE_INFINITY;
   var to_update_enquete = 0;
   var number_votes_enquete = 0;

   
function getEnquete(){

	$.get ("services/getIdUsuario.php", function(data){ 
	
		$.ajax({ 
			type: 'GET', 
			url: '../WebService/getEnqueteIdsWhereUserDidNotVote/'+data, 
			data: { get_param: 'value' }, 
			dataType:'json',
			cache: false,
			success: function (data) {
	
				if (data.ids.length != 0){ 
	
					created = false;
					
					for (i = data.ids.length - 1; i >= 0; i--){
						if (last_array_enquete_to_vote > parseInt(data.ids[i].id_enquete)){
							created = true;
							last_array_enquete_to_vote = parseInt(data.ids[i].id_enquete);
							break;
						}
					}
							
					if (!created)
						last_array_enquete_to_vote = parseInt(data.ids[data.ids.length - 1].id_enquete);
					
					console.log(last_array_enquete_to_vote);
					novaEnqueteForm (last_array_enquete_to_vote);
					to_update_enquete = 0;
					number_votes_enquete = 0;
				}else{
						
					$.ajax({ 
						type: 'GET', 
						url: '../WebService/getEnqueteIds/', 
						data: { get_param: 'value' }, 
						dataType:'json',
						cache: false,
						success: function (data) {
							console.log(data);
							if (data.ids.length != 0){
								created = false;
								for (i = data.ids.length - 1; i >= 0; i--){
				
									if (last_array_enquete > parseInt(data.ids[i].id_enquete)){
										created = true;
										last_array_enquete = parseInt(data.ids[i].id_enquete);
										break;
										
									}
								}
								
								if (!created)
									last_array_enquete = parseInt(data.ids[data.ids.length - 1].id_enquete);
								
								console.log('last = ' + last_array_enquete);
								
								novaEnqueteVisualizacao(last_array_enquete);
								to_update_enquete = last_array_enquete;
								number_votes_enquete = 0;
							}
							
						}
					});
				}
			}
		});
	});
}


function updateEnqueteVisualizacao(){
	console.log ('entrou na enquete update' + to_update_enquete);
	if (to_update_enquete != 0){
		novaEnqueteVisualizacao(to_update_enquete);
		console.log("atualizou");
	}
}

   function novaEnqueteVisualizacao(id_enquete){

	   $("#proxima_enquete").show();
	   $.ajax({ 
		  	type: 'GET', 
		  	url: '../WebService/getEnquete/'+id_enquete, 
		  	data: { get_param: 'value' }, 
		 	dataType:'json',
			cache: false,
		    success: function (data) { 
			    if (data.length == 0) return;
			    
		    	console.log(data);
		    	data = data[0];
				sum = parseInt(data.qtd_opt_1) + parseInt(data.qtd_opt_2) + parseInt(data.qtd_opt_3) + parseInt(data.qtd_opt_4) + parseInt(data.qtd_opt_5);
				if (number_votes_enquete < sum ){
				
					number_votes_enquete = sum;
			    	createEnqueteVisualizacao (data.perfil_45, data.e_imagem, data.nm_usuario, 
			    					data.data_hora, data.titulo, data.qtd_opt,
			    		[data.opt_1, data.opt_2, data.opt_3, data.opt_4, data.opt_5],
			    		[data.qtd_opt_1, data.qtd_opt_2, data.qtd_opt_3, data.qtd_opt_4, data.qtd_opt_5]
			    		);	

		    		console.log ('atualizou gráfico' + ' ' + number_votes_enquete);
				}
		    	
		    }
		});
   }
   
   function createEnqueteVisualizacao(perfil_45, e_imagem, nm_usuario, data_hora, titulo, qtd_opt, opts, qtd_opts){
	
	   if ($('#enquete').length)
		   $('#enquete').remove();
	   
	   
	   var enquete = document.createElement ('div');
	   enquete.className = "enquete well";
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
				 <canvas id='myChart' width=250 ></canvas>\
				 <div id='chartjs-tooltip'></div>\
			</div>\
			\
	   ";
		
		document.getElementById("enquete_left").appendChild(enquete);

		//Não possui nenhuma opção no tooltip, então esconda-o
		$('#chartjs-tooltip').hide();
		
		drawChart (opts, qtd_opts, qtd_opt);
   }

function proximaEnquete(){
	getEnquete();
}

//Customizando o tooltip padrão para não limitar a quantidade de caracteres
Chart.defaults.global.customTooltips = function(tooltip) {

	// Tooltip Element
    var tooltipEl = $('#chartjs-tooltip');
    $('#chartjs-tooltip').show();
    
    // Hide if no tooltip
    if (!tooltip) {
        tooltipEl.css({
            opacity: 0
        });
        return;
    }

    // Set caret Position
    tooltipEl.removeClass('above below');
    tooltipEl.addClass(tooltip.yAlign);

    // Set Text
    tooltipEl.html(tooltip.text);

    // Find Y Location on page
    var top;
    if (tooltip.yAlign == 'above') {
        top = tooltip.y - tooltip.caretHeight - tooltip.caretPadding;
    } else {
        top = tooltip.y + tooltip.caretHeight + tooltip.caretPadding;
    }

    // Display, position, and set styles for font
    tooltipEl.css({
        opacity: 1,
        left: tooltip.chart.canvas.offsetLeft + tooltip.x + 'px',
        top: tooltip.chart.canvas.offsetTop + top + 'px',
        fontFamily: tooltip.fontFamily,
        fontSize: tooltip.fontSize,
        fontStyle: tooltip.fontStyle,
    });
};

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
	var myPieChart = new Chart(ctx).Pie(data, {animationSteps : 50});
}



</script>


<body>
  
  
	<nav id="bar" class="navbar navbar-default">
	  <div class="container-fluid">
	    <div class="navbar-header">
	      <a class="navbar-brand" href="home.php">
	        <img alt="Brand" src="images/logo2.png" id="logo">
	      </a>
	      
	      
	    </div>
	    <div>
	    	<button onClick="parent.location='services/logout.php'" type="submit" class="btn btn-default navbar-right" style="margin: 8px">Sair</button>
	    </div>
	  </div>
	</nav>
	
	
	<div class="container-fluid" style="margin-top: 50px">

		<div class="row">
		 
		  		<!-- Profile -->
		  		<div class="col-md-2 text-center " id="profile">.
		  
				  	<img  src="<?php echo $foto ?>"  alt="..." class="img-thumbnail">
				  	<h2><script>document.write(toPlainText('<?php echo $user?>'));</script></h2>
				  	
				</div>
				  
				 <!-- Meio -->
				<div class="col-md-4 col-md-offset-1" id="mid" >
	 
					<div id="feed" class="text-center">
					
						<img  src="<?php echo $image_perfil ?>"  alt="..." class="img-thumbnail">
						
						<div class="dados">
							<h2><script>document.write(toPlainText('<?php echo $nome?>'));</script></h2>
							
							<script>
								var tipo = '<?php echo $tipo?>';
								if (tipo == 'Aluno'){
									document.write('<h4><?php echo $curso?></h4>');
									document.write('<h4><?php echo $ano_periodo?>º Ano/Período</h4>');
								}
								if (tipo == 'Professor')
									document.write('<h4><?php echo $grau_academico?></h4>');
								document.write('<h5><?php echo $tipo?></h5>');
							</script>

						</div>
						
					</div>
		
	 			</div>
	 			
	 			<div class="col-md-3 col-md-offset-1" id="left">
	 				<span title="próxima" data-toggle="tooltip" data-placement="bottom" class="btn-lg btn pull-right" id="proxima_enquete" onClick="proximaEnquete()">
	 				<span id="next" class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span></span>
	 				
	 				<div id="enquete_left">
	 				</div>
	 			

	 			</div>

	 	</div>

	</div>

   
 </body>
  
</html>