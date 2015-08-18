/**
 * TODO: Verificar se o post é do usuário atual, na criação de um post
 * 		 Posts não serão excluídos de postArray
 */


POST = (function () {

    var postObject = {};

    postObject.usuario_id = SESSION.getUsuarioId();

    //Vetor de índice de posts
    postObject.postArray = [];

    //Flag para verificar se ainda pode carregar posts antigos
    postObject.is_there_more_post = true;

    //Id do último post carregado
    postObject.last_id_post;

    //Id do primeiro post
    postObject.first_id_post = 0;

    //Carregando posts mais antigos em morePost
    //Ajax não para o código, precisa verificar se está em uma chamada de ajax para carregar mais
    postObject.isInAjax_MorePost = false;

    $(document).ready(function () {

        postObject.postLoad();

        setInterval(function () {
            postObject.postsReload();
        }, 5000);
        setInterval(function () {
            postObject.newPost();
        }, 10000);

        $(window).scroll(function () {
            if ($(document).height() - ($(window).scrollTop() + $(window).height()) < 50 && postObject.is_there_more_post) {
                POST.morePost();
            }
        });

    });

    postObject.postsReload = function () {
        for (var p in postObject.postArray)
            postObject.postReload(postObject.postArray[p]);
    };

    postObject.postReload = function (post) {

        if (document.getElementById(post) === null || !$('#' + post).visible(true))
            return;

        COMENTARIO.load(post);
        LAIKES.load(post);
    };


    postObject.postLoad = function () {

        $.ajax({
            type: 'GET',
            url: '../WebService/getNPosts/5/' + SESSION.getGrupo(),
            data: {get_param: 'value'},
            dataType: 'json',
            cache: false,
            success: function (data) {

                $('#feed').fadeOut(0);

                for (var i = 0; i < data.posts.length; i++) {

                    var post = data.posts[i];

                    if (i == 0)
                        postObject.first_id_post = post.post_id;

                    postObject.postArray.push(post.post_id);

                    postObject.createPost(post);

                    postObject.last_id_post = post.post_id;

                    postObject.postReload(post.post_id);
                }

                $('#feed').fadeIn(400);
            }
        });
    };

    postObject.morePost = function () {

        if (postObject.isInAjax_MorePost)
            return;

        postObject.isInAjax_MorePost = true;

        $.ajax({
            type: 'GET',
            url: '../WebService/getNPostsLessThanMid/3/' + postObject.last_id_post + '/' + SESSION.getGrupo(),
            data: {get_param: 'value'},
            dataType: 'json',
            cache: false,
            success: function (data) {
                if (data.posts.length == 0)
                    postObject.is_there_more_post = false;
                for (var i = 0; i < data.posts.length; i++) {

                    //Json
                    var post = data.posts[i];

                    postObject.postArray.push(post.post_id);

                    postObject.createPost(post);

                    postObject.last_id_post = post.post_id;

                    postObject.postReload(post.post_id);

                }

                postObject.isInAjax_MorePost = false;
            }
        });
    };

    //Carregando posts mais novos
    postObject.newPost = function () {

        $.ajax({
            type: 'GET',
            url: '../WebService/getAllPostsGreaterThanNid/' + postObject.first_id_post + '/' + SESSION.getGrupo(),
            data: {get_param: 'value'},
            dataType: 'json',
            cache: false,
            success: function (data) {

                for (var i = 0; i < data.posts.length; i++) {
                    var post = data.posts[i];

                    postObject.postArray.push(post.post_id);

                    var form = postObject.createPost(post);

                    document.getElementById('feed').insertBefore(form, document.getElementById('feed').firstChild);
                    $('#' + post.post_id).hide();
                    $('#' + post.post_id).fadeIn('slow');

                    postObject.first_id_post = post.post_id;

                    postObject.postReload(post.post_id);
                }

            }
        });

    };


    postObject.data_post = [];

    postObject.atualizaTempo = function () {

        for (i in postObject.data_post) {
            if (!$('#ptime_' + i).length > 0) {
                delete postObject.data_post[i];
                continue;
            }

            document.getElementById('ptime_' + i).innerHTML = TEMPO.tempoPassado(postObject.data_post[i]);
        }

    };

    //Cria um post (fiscalização, proposta) novo
    postObject.createPost = function (post) {

        var form = document.createElement('form');
        postObject.data_post[post.post_id] = post.data_hora;
        form.id = post.post_id;
        var img = '';

        var tipo_icon = '<div title="Fiscalização" class="fiscalizar_icon glyphicon glyphicon-warning-sign pull-right">';
        if (post.tipo == 0)
            tipo_icon = '<div title="Proposta" class="propor_icon glyphicon glyphicon-send pull-right">';

        //É uma fiscalização com imagem?
        if (post.tipo == 2)
            img = "<img class='fiscalizacao_img' src='" + post.imagem + "' />"

        form.innerHTML =
                '<div class="well well-sm">' +
                '<div class="post">' +
                '<div class="top">' +
                '<i onClick="postDELETE(this)" class="glyphicon glyphicon-remove"></i>' +
                '<img class="pull-left f45x45" src="' + post.perfil + '" >' +
                '<div>' +
                '<h4><a  href="userProfile.php?id=' + post.usuario_id + '">' + htmlentitiesJS(post.nm_usuario) + '</a></h4>' +
                '<a href="publicacao.php?id='+form.id+'"><h6><span id="ptime_' + post.post_id + '">' + TEMPO.tempoPassado(post.data_hora) + '</span>&nbsp' + post.nm_ramo + '</h6></a>' +
                '</div>' +
                '</div>' +
                '<div class="content">' + htmlentitiesJS(post.comentario) + '</div>' +
                img +
                '<div class="bot">' +
                '<div class="laike btn-lg ">' +
                '<div id="nl' + post.post_id + '" onClick="laikePOST(this.id)" type="button"  data-toggle="tooltip" data-placement="top">' +
                '<i class="glyphicon glyphicon-thumbs-up" aria-hidden="true" ></i>' +
                '</div>' +
                '<span id="cc' + post.post_id + '" onClick="CURIAR.curiarCurtida(this.id)" rel="tooltip" data-placement="top" data-original-title="Curiar" data-toggle="modal" data-target="#list_people_laike"> 0</span>' +
                '</div>' +
                '<div class="btn" style="cursor:default">' +
                '<span class="glyphicon glyphicon-comment" aria-hidden="true" ></i><span id="nc' + post.post_id + '"> 0</span>' +
                '</div>' +
                tipo_icon +
                '</div>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '<div id="pc' + post.post_id + '"></div>' +
                '<div class="input-group add-on">' +
                '<input type="text" class="form-control" placeholder="Comentar..." name="comentario" autocomplete="off" id="input' + post.post_id + '">' +
                '<div class="input-group-btn">' +
                '<button class="btn btn-default" type="submit" onClick="comentarioPOST(this.form.id)"><i class="glyphicon glyphicon-share-alt"></i></button>' +
                '</div>' +
                '</div>';

        document.getElementById('feed').appendChild(form);

        $("[rel='tooltip']").tooltip();

        return form;
    };

    return postObject;

})();
