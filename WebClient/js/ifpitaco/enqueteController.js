
ENQUETE = (function () {

    var enqueteObject = {};

    enqueteObject.lastEnquete = 0;
    enqueteObject.number_votes_enquete = 0;
    enqueteObject.data_enquete = 0;
    enqueteObject.isVoting = false;

    enqueteObject.opts_spin = {
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
        $("#proxima_enquete").show();
        setInterval(function () {
            enqueteObject.update()
        }, 5000);
        enqueteObject.getEnquete();
    });



    enqueteObject.createEnqueteForm = function (data) {

        var opts = [data.opt_1, data.opt_2, data.opt_3, data.opt_4, data.opt_5];

        if ($('#enquete').length)
            $('#enquete').remove();


        var enquete = document.createElement('div');
        enquete.className = "enquete well";
        enquete.id = "enquete";

        var imagem = '';
        if (data.e_imagem != '')
            imagem = "<img src='" + data.e_imagem + "'>";

        enqueteObject.data_enquete = data.data_hora;

        enquete.innerHTML = "\
			 <div class='e_top'>\
				<img class='f45x45 pull-left img-circle' src='" + data.perfil + "'>\
					<a href='userProfile.php?id=" + data.usuario_id + "'><div class='nome_user'>" + htmlentitiesJS(data.nm_usuario) + "</div></a>\
					<div class='data'><span id='etime'>" + TEMPO.tempoPassado(data.data_hora) + "</span></div>\
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

    enqueteObject.getEnquete = function () {


        var target = document.getElementById('spin');
        var spinner = new Spinner(enqueteObject.opts_spin).spin();
        if (enqueteObject.lastEnquete !== 0) // on Page load    
            target.appendChild(spinner.el);
        $.ajax({
            type: 'GET',
            url: 'services/getEnquete.php?last_enquete_id=' + enqueteObject.lastEnquete,
            data: {get_param: 'value'},
            dataType: 'json',
            cache: false,
            success: function (data) {
                if (data.is_there == 0)
                    return;
                enqueteObject.lastEnquete = data.data.id_enquete;
                if (data.to_vote == 1) {
                    enqueteObject.createEnqueteForm(data.data);
                    enqueteObject.voting();
                } else {
                    enqueteObject.createEnqueteVisualizacao(data.data);
                }
                spinner.stop();
            }
        });
    };

    enqueteObject.voted = function () {
        enqueteObject.isVoting = false;
        enqueteObject.update();
    };

    enqueteObject.voting = function () {
        enqueteObject.isVoting = true;
        enqueteObject.number_votes_enquete = 0;
    };

    enqueteObject.reset = function () {
        enqueteObject.lastEnquete = 0;
    };

    enqueteObject.update = function () {
        if (enqueteObject.isVoting === true || enqueteObject.lastEnquete == 0)
            return;

        $.ajax({
            type: 'GET',
            url: '../WebService/getEnqueteById/' + enqueteObject.lastEnquete,
            data: {get_param: 'value'},
            dataType: 'json',
            cache: false,
            success: function (data) {

                if (data.length == 0)
                    return;

                var sum = parseInt(data.qtd_opt_1) + parseInt(data.qtd_opt_2) + parseInt(data.qtd_opt_3) + parseInt(data.qtd_opt_4) + parseInt(data.qtd_opt_5);

                if (enqueteObject.number_votes_enquete < sum)
                    enqueteObject.createEnqueteVisualizacao(data);

            }
        });
    };

    enqueteObject.createEnqueteVisualizacao = function (data) {
        enqueteObject.number_votes_enquete = parseInt(data.qtd_opt_1) + parseInt(data.qtd_opt_2) + parseInt(data.qtd_opt_3) + parseInt(data.qtd_opt_4) + parseInt(data.qtd_opt_5);
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

        enqueteObject.data_enquete = data.data_hora;

        enquete.innerHTML = "\
   			 <div class='e_top'>\
   				<img class='f45x45 pull-left img-circle' src='" + data.perfil + "'>\
   				<a href='userProfile.php?id=" + data.usuario_id + "'><div class='nome_user'>" + htmlentitiesJS(data.nm_usuario) + "</div></a>\
   					<div class='data'><span id='etime'>" + TEMPO.tempoPassado(data.data_hora) + "</span></div>\
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

    enqueteObject.atualizaTempo = function () {

        if ($('#etime').length > 0) {
            document.getElementById('etime').innerHTML = tempoPassado(enqueteObject.data_enquete);
        }
    };

    return enqueteObject;


})();