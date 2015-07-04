

SEARCH = (function () {

    var search = {};

    $(document).ready(function () {
        $('#search_form')[0].reset();
        $('#search_form').focusout(function () {
            /*	  Se desfocar em cima do form é por que clicou no filho, logo não deve ser removido
             para realizar a troca de página */

            if (!$('#search_form').is(':hover')) {
                $('#search_list').remove();
                $('#search_form')[0].reset();
                clearTimeout(search.delayTimer);
            }
        });

    });

    search.addUserToSearchList = function (id, nome, foto, tipo) {
        var li = document.createElement('li');
        li.id = 'user_searched_id_' + id;
        li.className = 'user_searched';

        li.innerHTML = '\
                    <a href="userProfile.php?id=' + id + '">\
                    <img class="f45x45 pull-left" src="' + foto + '"></img>\
                    <h3>' + nome + '</h3>\
                    <h5>' + tipo + '</h5>\
                    <\a>';

        document.getElementById('search_list').appendChild(li);
    };

    search.createSearchList = function () {

        $('#search_list').attr('id', 'search_list_old');

        var ul = document.createElement('ul');
        ul.className = "list-unstyled";
        ul.id = 'search_list';
        document.getElementById('search_form').appendChild(ul);

    };


    search.delayTimer;
    search.gettingUsers = false;

    // Quando usuário pesquisa e não aperta Enter, apenas clica
    search.doSearch = function (text) {

        if (search.gettingUsers)
            return;
        search.gettingUsers = true;

        if (text == '') {
            $('#search_list').remove();
            $('#search_form')[0].reset();
            clearTimeout(search.delayTimer);

            search.gettingUsers = false;
            return;
        }


        if (/[^a-zA-Z]/.test(text)) {
            search.gettingUsers = false;
            return;
        }

        clearTimeout(search.delayTimer);

        search.delayTimer = setTimeout(function () {

            search.createSearchList();

            search.getUsersFromBusca(text, function (usuariosFromBusca) {
                for (i = 0; i < usuariosFromBusca.length; i++) {
                    var u = usuariosFromBusca[i];
                    search.addUserToSearchList(
                            u.id_usuario, u.nm_usuario, u.perfil, u.usuario_tipo
                            );
                }

                //Se colocar o Jquery fora, não atualiza!
                $('.user_searched').hover(function () {
                    $(this).css('background-color', 'rgba(100, 108, 164, 0.9)');
                });

                $('.user_searched').mouseleave(function () {
                    $(this).css('background-color', 'white');
                });
                $('#search_list_old').remove();
                gettingUsers = false;
            });


        }, 100); //Tempo após outra digidatação

    };

    // Quando usuário pesquisa e aperta Enter
    search.doSearchSubmitted = function () {

        text = $('#search_input')[0].value;

        if (/[^a-zA-Z]/.test(text))
            return false;

        if (text == '')
            return false;

        getUsersFromBusca(text, function (usuariosFromBusca) {
            if (usuariosFromBusca.length == 0)
                return;

            u = usuariosFromBusca[0].id_usuario;

            window.location.assign("userProfile.php?id=" + u);
        });

        return false;
    };

    search.getUsersFromBusca = function (nome, handleData) {

        $.ajax({
            type: 'GET',
            url: '../WebService/getBuscaUsuario/' + nome,
            data: {get_param: 'value'},
            dataType: 'json',
            cache: false,
            success: function (data) {
                handleData(data.usuarios);
            },
            error: function () {
                handleData([]);
            }
        });
    };

    return search;

})();

