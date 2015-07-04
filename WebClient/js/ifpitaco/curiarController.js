

CURIAR = (function () {
    var curiar = {};


    curiar.curiarCurtida = function (id) {
        id = id.replace('cc', '');


        $.ajax({
            type: 'GET',
            url: '../WebService/curiarPost/' + id,
            data: {get_param: 'value'},
            dataType: 'json',
            cache: false,
            success: function (data) {

                curiar.createListPeopleModal(data, 'Curiando');

            },
            error: function () {

            }
        });
    };

    curiar.curiarEnquete = function (id_enquete) {

        $.ajax({
            type: 'GET',
            url: '../WebService/curiarEnquete/' + id_enquete,
            data: {get_param: 'value'},
            dataType: 'json',
            cache: false,
            success: function (data) {

                curiar.createEnqueteModal(data, 'Curiando enquete');

            },
            error: function () {

            }
        });
    };

    curiar.createEnqueteModal = function (data, titulo) {

        var colors = ['#F7464A', '#46BFBD', '#FDB45C', '#949FB1', '#4D5360'];

        $('#p_list_laike').remove();
        document.getElementById('p_list_people_laike_titulo').innerHTML = titulo;
        var ul = document.createElement('div');

        ul.className = "list-unstyled";
        ul.id = 'p_list_laike';

        document.getElementById('list_people_laike_div').appendChild(ul);

        for (var i = 1; i <= data.qtd_opt; i++) {

            ul = document.createElement('ul');
            ul.className = "list-unstyled modal_enquete_curiar";
            ul.id = 'optv_' + i;
            ul.style.borderColor = colors[i - 1];
            document.getElementById('p_list_laike').appendChild(ul);


            title = document.createElement('li');
            title.innerHTML = '<div><span style="color:' + colors[i - 1] + '" class="glyphicon glyphicon-asterisk" aria-hidden="true"></span> ' + data['opt_' + i] + '</div>';
            title.className = "modal_enquete_curiar_title";


            document.getElementById('optv_' + i).appendChild(title);
        }

        var peoples = data.usuarios;

        for (i in peoples) {

            var p = peoples[i];

            var li = document.createElement('li');
            li.className = 'user_searched';
            //Tamanho de cada lista
            li.style.width = '360px';
            li.innerHTML = '\
                            <div style="margin-left:10px">\
                            <a href="userProfile.php?id=' + p.id_usuario + '">\
                            <img class="f45x45 pull-left" src="' + p.perfil + '"></img>\
                            <h3>' + p.nm_usuario + '</h3>\
                            <h5>' + p.usuario_tipo + '</h5>\
                            <\a>';

            document.getElementById('optv_' + p.voto).appendChild(li);
        }

        //Se colocar o Jquery fora, não atualiza!
        $('.user_searched').hover(function () {
            $(this).css('background-color', 'rgba(100, 108, 164, 0.9)');
        });

        $('.user_searched').mouseleave(function () {
            $(this).css('background-color', 'white');
        });

    };

    curiar.createListPeopleModal = function (peoples, titulo) {


        $('#p_list_laike').remove();
        document.getElementById('p_list_people_laike_titulo').innerHTML = titulo;
        var ul = document.createElement('ul');
        ul.className = "list-unstyled";
        ul.id = 'p_list_laike';

        document.getElementById('list_people_laike_div').appendChild(ul);

        for (i in peoples) {
            p = peoples[i];

            var li = document.createElement('li');
            li.className = 'user_searched ';
            li.style.width = '350px';
            li.innerHTML = '\
                            <div>\
                            <a href="userProfile.php?id=' + p.id_usuario + '">\
                            <img class="f45x45 pull-left" src="' + p.perfil + '"></img>\
                            <h3>' + p.nm_usuario + '</h3>\
                            <h5>' + p.usuario_tipo + '</h5>\
                            <\a>';

            document.getElementById('p_list_laike').appendChild(li);
        }

        //Se colocar o Jquery fora, não atualiza!
        $('.user_searched').hover(function () {
            $(this).css('background-color', 'rgba(100, 108, 164, 0.9)');
        });

        $('.user_searched').mouseleave(function () {
            $(this).css('background-color', 'white');
        });

    };

    return curiar;
})();