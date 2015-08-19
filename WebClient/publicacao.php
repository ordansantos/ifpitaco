
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

        <!-- Verifica se elemento está visível -->
        <script src="js/jquery_visible/jquery-visible.min.js"></script>

        <!--http://www.chartjs.org/docs/ | Gráfico pizza-->
        <script src="js/Chart/Chart.js"></script>

        <!-- Ícone de carregamento de enquete -->
        <script src="js/spin/spin.js"></script>

        <!-- Bootbox -->
        <script src="js/bootbox/bootbox.min.js"></script>

        <!-- CSS -->
        <link rel="stylesheet" type="text/css" href="css/tooltip_chart_customized.css">
        <link rel="stylesheet" type="text/css" href="css/search.css">
        <link rel="stylesheet" type="text/css" href="css/foto_size.css">
        <link rel="stylesheet" type="text/css" href="css/enquete_form.css">
        <link rel="stylesheet" type="text/css" href="css/home.css">
        <link rel="stylesheet" type="text/css" href="css/comentario.css">

        <script src="js/ifpitaco/sessionController.js"></script>

        <script>SESSION = Session(<?php echo $_SESSION['id_usuario'] ?>, <?php echo $_SESSION['grupo'] ?>);</script>

        <script src="js/ifpitaco/services/comentarioDELETE.js"></script>
        <script src="js/ifpitaco/services/comentarioPOST.js"></script>
        <script src="js/ifpitaco/comentarioController.js"></script>
        <script src="js/ifpitaco/services/postDELETE.js"></script>
        <script src="js/ifpitaco/likesController.js"></script>
        <script src="js/ifpitaco/services/laikePOST.js"></script>
        <script src="js/ifpitaco/tools/tempoPassado.js"></script>
        <script src="js/ifpitaco/searchController.js"></script>
        <script src="js/ifpitaco/curiarController.js"></script>
        
        <script>

            getPost = "<?php echo $_GET['id']; ?>";
            
            console.log (getPost);
            
            data = 0;
            
            $(document).ready(function () {
                $.ajax({
                    type: 'GET',
                    url: '../WebService/getPostById/' + getPost,
                    data: {get_param: 'value'},
                    dataType: 'json',
                    cache: false,
                    success: function (data) {
                        
                        if (data == "")
                            window.location.assign("index.php");
                        
                        $('#feed').fadeOut(0);

                        createPost(data);
                        postReload();
                        $('#feed').fadeIn(400);
                    }
                });
                
                setInterval(function () {
                    postReload();
                }, 5000);
                
            });

            function postReload(){
                
                COMENTARIO.load(getPost);
                LAIKES.load(getPost);
                
                document.getElementById('ptime_' + getPost).innerHTML = tempoPassado(data);
                
            }
            function createPost (post) {

                var form = document.createElement('form');
                data = post.data_hora;
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
                        '<h6><span id="ptime_' + post.post_id + '">' + tempoPassado(post.data_hora) + '</span>&nbsp' + post.nm_ramo + '</h6>' +
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
            
        </script>
            
        
    </head>

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

                <!-- Meio -->
                <div class="col-md-6" id="mid">
 
                    <div id="feed"></div>

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



