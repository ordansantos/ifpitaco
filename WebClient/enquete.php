
<!-- TODO: Remover o id do usuário do javascript e deixar apenas no php -->

<?php
session_start();

if ($_SESSION['id_usuario'] == '') {
    header("location: index.php");
}

?>


<!DOCTYPE html>

<html>

    <head>

        <meta charset="utf-8" />
        <link rel="shortcut icon" href="images/favicon.png">
        <title>IFPitaco</title>

        <!-- JQuery -->
        <script src="jquery/jquery-1.11.3.min.js"></script>

        <!-- Bootstrap -->
        <link href="bootstrap-3.3.5/css/bootstrap.min.css" rel="stylesheet">
        <script src="bootstrap-3.3.5/js/bootstrap.min.js"></script>


        <script src="js/ifpitaco/tools/htmlentities.js"></script>

        <!--http://www.chartjs.org/docs/ | Gráfico pizza-->
        <script src="js/Chart/Chart.js"></script>

        <!-- CSS -->
        <link rel="stylesheet" type="text/css" href="css/tooltip_chart_customized.css">
        <link rel="stylesheet" type="text/css" href="css/search.css">
        <link rel="stylesheet" type="text/css" href="css/foto_size.css">
        <link rel="stylesheet" type="text/css" href="css/enquete_form.css">
        <link rel="stylesheet" type="text/css" href="css/home.css">
        <link rel="stylesheet" type="text/css" href="css/comentario.css">


        <script src="js/ifpitaco/tools/tempoPassado.js"></script>

        <script src="js/ifpitaco/tools/chart.js"></script>
  
        <script src="js/ifpitaco/services/enquetePOST.js"></script>
        <script src="js/ifpitaco/searchController.js"></script>
        <script src="js/ifpitaco/curiarController.js"></script>
    </head>
    <script>

    function votoPost(id) {

        event.preventDefault();
        var data = $('#form_enquete').serializeArray();
        data.push({name: 'enquete_id', value: id});

        $.post("services/postVoto.php", data, function (data) {

            if (data.trim() == '0')
                bootbox.alert("Faça login para votar!", function () {
                    window.location.assign("index.php");
                });
            else{
                voted();
            }

        });
    }
    
    enquete = "<?php echo $_GET['enquete_id'] ?>";

    number_votes_enquete = 0;
    data_enquete = 0;
    isVoting = false;

    opts_spin = {
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

    $(document).ready(function () {
        getEnquete();
        setInterval(function () {
            update();
        }, 5000);
 
    });



    createEnqueteForm = function (data) {

        var opts = [data.opt_1, data.opt_2, data.opt_3, data.opt_4, data.opt_5];

        if ($('#enquete').length)
            $('#enquete').remove();


        var enquete = document.createElement('div');
        enquete.className = "enquete well";
        enquete.id = "enquete";

        var imagem = '';
        if (data.e_imagem != '')
            imagem = "<img src='" + data.e_imagem + "'>";

        data_enquete = data.data_hora;

        enquete.innerHTML = "\
			 <div class='e_top'>\
				<img class='f45x45 pull-left img-circle' src='" + data.perfil + "'>\
					<a href='userProfile.php?id=" + data.usuario_id + "'><div class='nome_user'>" + htmlentitiesJS(data.nm_usuario) + "</div></a>\
					<div class='data'><span id='etime'>" + tempoPassado(data.data_hora) + "</span></div>\
				 </div>\
				 <div class='titulo'>" + htmlentitiesJS(data.titulo) + "</div>\
				 " + imagem + "\
				 <form id='form_enquete' class='form-horizontal'>\
				 </form>\
				 <div class='bot'><button onClick='votoPost(" + data.id_enquete + ")' class='btn btn-danger'>Opinar!</button></div>\
				 <div title='Enquete' class='glyphicon glyphicon-bullhorn enquete_icon_no_vote pull-right'></div>\
			</div>\
	   ";

        document.getElementById("enquete_left").appendChild(enquete);


        for (var i = 0; i < data.qtd_opt; i++) {

            o = document.createElement('div');
            o.className = "radio";
            o.innerHTML = "\
				<label>\
					<input type='radio' name='voto' value='" + (i + 1) + "' " + (i == 0 ? 'checked' : '') + ">\
					" + htmlentitiesJS(opts[i]) + "\
				</label>\
			";

            document.getElementById("form_enquete").appendChild(o);

        }
    };

    getEnquete = function () {
        $.ajax({
            type: 'GET',
            url: '../WebService/getEnqueteById/' + enquete + '/' + <?php echo $_SESSION['id_usuario']; ?>,
            data: {get_param: 'value'},
            dataType: 'json',
            cache: false,
            success: function (data) {
     
                if (data == '')
                    window.location.assign("index.php");
                if (data.to_vote == 1) {
                    createEnqueteForm(data);
                    voting();
                } else {
                    createEnqueteVisualizacao(data);
                }
            },
            error: function (data){
                console.log ('data');
            }
        });
    };

    voted = function () {
        isVoting = false;
        update();
    };

    voting = function () {
        isVoting = true;
        number_votes_enquete = 0;
    };

    reset = function () {
        lastEnquete = 0;
    };

    update = function () {
        if (isVoting === true || enquete == 0)
            return;

        $.ajax({
            type: 'GET',
            url: '../WebService/getEnqueteById/' + enquete + '/' + <?php echo $_SESSION['id_usuario']; ?>,
            data: {get_param: 'value'},
            dataType: 'json',
            cache: false,
            success: function (data) {

                if (data.length == 0)
                    return;

                var sum = parseInt(data.qtd_opt_1) + parseInt(data.qtd_opt_2) + parseInt(data.qtd_opt_3) + parseInt(data.qtd_opt_4) + parseInt(data.qtd_opt_5);

                if (number_votes_enquete < sum)
                    createEnqueteVisualizacao(data);

            }
        });
        atualizaTempo();
    };

    createEnqueteVisualizacao = function (data) {
        number_votes_enquete = parseInt(data.qtd_opt_1) + parseInt(data.qtd_opt_2) + parseInt(data.qtd_opt_3) + parseInt(data.qtd_opt_4) + parseInt(data.qtd_opt_5);
        var opts = [data.opt_1, data.opt_2, data.opt_3, data.opt_4, data.opt_5];
        var qtd_opts = [data.qtd_opt_1, data.qtd_opt_2, data.qtd_opt_3, data.qtd_opt_4, data.qtd_opt_5];

        if ($('#enquete').length)
            $('#enquete').remove();


        var enquete = document.createElement('div');
        enquete.className = "enquete well";
        enquete.id = "enquete";
        enquete.display = "none";
        var imagem = '';
        if (data.e_imagem != '')
            imagem = "<img src='" + data.e_imagem + "'>";

        data_enquete = data.data_hora;

        enquete.innerHTML = "\
   			 <div class='e_top'>\
   				<img class='f45x45 pull-left img-circle' src='" + data.perfil + "'>\
   				<a href='userProfile.php?id=" + data.usuario_id + "'><div class='nome_user'>" + htmlentitiesJS(data.nm_usuario) + "</div></a>\
   					<div class='data'><span id='etime'>" + tempoPassado(data.data_hora) + "</span></div>\
   				 </div>\
   				 <div class='titulo'>" + htmlentitiesJS(data.titulo) + "</div>\
   				 " + imagem + "\
   				 <h4>Resultados: </h4>\
   				 <canvas id='myChart' width=250 ></canvas>\
   				 <div id='chartjs-tooltip'></div>\
   				 <div>\
   				 <span title='Enquete' class='glyphicon glyphicon-bullhorn enquete_icon pull-right'></span>\
   				<span class='glyphicon glyphicon-th-list curiar_enquete_icon' onClick='CURIAR.curiarEnquete(" + data.id_enquete + ")' rel='tooltip' data-placement='top' data-original-title='Curiar' data-toggle='modal' data-target='#list_people_laike'></span>\
   				</div>\
   			</div>\
   			\
   	   ";

        document.getElementById("enquete_left").appendChild(enquete);

        //Não possui nenhuma opção no tooltip, então esconda-o
        $('#chartjs-tooltip').hide();

        CHART.drawChart(opts, qtd_opts, data.qtd_opt);

        $("[rel='tooltip']").tooltip();
    };

    atualizaTempo = function () {

        if ($('#etime').length > 0) {
            document.getElementById('etime').innerHTML = tempoPassado(data_enquete);
        }
    };
        
    </script>
    <body>

        <nav id="bar" class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="home.php"> <img alt="Brand"
                                                                  src="images/logo2.png" id="logo">
                    </a>
                </div>

                <div>
                    <button onClick="parent.location = 'services/logout.php'"
                            type="submit" class="glyphicon glyphicon-log-out btn btn-default navbar-right"
                            style="margin: 8px"> Sair</button>
                            
                    <?php
                       if ($_SESSION['is_admin']) 
                            echo '<button  onClick="parent.location = \'admin/publicacao.php\'"
                            type="submit" class=" glyphicon glyphicon-wrench btn btn-default navbar-right"
                            style="margin: 8px"> Admin</button>';
                    ?>
                </div>

                <!-- Inline block -->
                <div>
                    <form role="search" onsubmit="return SEARCH.doSearchSubmitted()"
                          id="search_form">
                        <div class="inner-addon right-addon">
                            <i class="glyphicon glyphicon-search"></i> <input type="text"
                                                                              id='search_input' class="navbar-left form-control"
                                                                              placeholder="Procurar Usuário" onkeyup="SEARCH.doSearch(this.value)"
                                                                              autocomplete="off">
                        </div>
                    </form>
                </div>

            </div>
        </nav>


        <div class="container-fluid" style="margin-top: 50px">

            <div class="row">

                <!-- Profile -->
                <div class="col-md-2 text-center" id="profile">
                    <div class="img-thumbnail">
                        <a href="userProfile.php?id=<?php echo $_SESSION['id_usuario'] ?>"><img
                                src="<?php echo $_SESSION['foto'] ?>" alt="..." class="f120x120"></a>
                    </div>
                    <a href="userProfile.php?id=<?php echo $_SESSION['id_usuario'] ?>">
                        <h3>
                        <?php echo htmlentities($_SESSION['name']) ?>
                        </h3>
                    </a>
                    <div class='left_options'>
                        <ul class="list-unstyled">
                            <li><a href="myProfile.php"><span
                                        class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                                    Editar Perfil</a></li>
                        </ul>

                    </div>
                </div>



                <div class="col-md-3 col-md-offset-3" id="left">
 

                    <div id="enquete_left"></div>


                </div>

            </div>

        </div>
        <!-- MODAL List People Laike-->


        <!-- Modal-->
        <div class="modal fade" id="list_people_laike" tabindex="-1"
             role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

            <div class="modal-dialog list_people_laike">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel">
                            <span style="cursor: initial;" id='p_list_people_laike_titulo'>Curiar</span>
                        </h4>
                    </div>

                    <div class="modal-body">

                        <div id="list_people_laike_div"></div>

                    </div>

                    <div class="modal-footer">

                        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                    </div>

                </div>
            </div>
        </div>
    </body>

</html>



