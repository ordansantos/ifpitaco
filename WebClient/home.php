
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

        <script src="js/ifpitaco/postController.js"></script>
        <script src="js/ifpitaco/services/comentarioDELETE.js"></script>
        <script src="js/ifpitaco/services/comentarioPOST.js"></script>
        <script src="js/ifpitaco/comentarioController.js"></script>
        <script src="js/ifpitaco/services/fiscalizacaoPOST.js"></script>
        <script src="js/ifpitaco/services/propostaPOST.js"></script>
        <script src="js/ifpitaco/services/postDELETE.js"></script>
        <script src="js/ifpitaco/likesController.js"></script>
        <script src="js/ifpitaco/services/laikePOST.js"></script>

        <script src="js/ifpitaco/postForm.js"></script>
        <script src="js/ifpitaco/tempoController.js"></script>
        <script src="js/ifpitaco/enqueteForm.js"></script>
        <script src="js/ifpitaco/services/enquetePOST.js"></script>

        <script src="js/ifpitaco/enqueteController.js"></script>

        <script src="js/ifpitaco/tools/chart.js"></script>

        <script src="js/ifpitaco/services/votoPOST.js"></script>

        <script src="js/ifpitaco/searchController.js"></script>
        <script src="js/ifpitaco/curiarController.js"></script>
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
                            type="submit" class="btn btn-default navbar-right"
                            style="margin: 8px">Sair</button>
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
                    <div class="opcoes_btns">
                        <!-- Button trigger modal -->
                        <button id="propor_btn" type="button"
                                class="btn btn-success btn-lg" data-toggle="modal"
                                data-target="#proporModal">
                            <span class="glyphicon glyphicon-send" aria-hidden="true"></span>
                            Propor
                        </button>

                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn btn-danger btn-lg"
                                data-toggle="modal" data-target="#fiscalizarModal">
                            <span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span>
                            Fiscalizar
                        </button>

                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-warning btn-lg pull-right"
                                data-toggle="modal" data-target="#newEnqueteModal">
                            <span class="glyphicon glyphicon-bullhorn" aria-hidden="true"></span>
                            Criar Enquete
                        </button>
                    </div>
                    <div id="feed"></div>

                </div>

                <div class="col-md-3" id="left">
                    <span title="próxima" data-toggle="tooltip" data-placement="bottom"
                          class="btn-lg btn pull-right" id="proxima_enquete"
                          onClick="ENQUETE.getEnquete()"> <span id="next"
                                                              class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span></span>
                    <div id="spin"></div>

                    <div id="enquete_left"></div>


                </div>

            </div>

        </div>

        <!-- MODAL PROPOSTA -->

        <!-- Modal -->
        <div class="modal fade" id="proporModal" tabindex="-1" role="dialog"
             aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel">Proposta</h4>
                    </div>

                    <div class="modal-body">

                        <form id="form_proposta" class="form-horizontal">
                            <ul class="list-unstyled">
                                <li><h4>Ramo:</h4></li>
                                <li><select class="form-control" id="form_ramos_proposta"
                                            name="ramo_id"></select></li>
                                <li><h4>Comentário:</h4></li>
                                <li><textarea class="form-control" id="comentariop"
                                              name="comentario" rows="7"></textarea></li>


                            </ul>
                        </form>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary"
                                onClick="propostaPOST()" data-dismiss="modal">Enviar</button>
                        <button type="button" class="btn btn-default"
                                onClick="POSTFORM.resetProposta()" data-dismiss="modal">Fechar</button>
                    </div>

                </div>
            </div>
        </div>

        <!-- FIM MODAL PROPOSTA -->


        <!-- MODAL FISCALIZAÇÃO -->

        <!-- Modal -->
        <div class="modal fade" id="fiscalizarModal" tabindex="-1"
             role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel">Fiscalização</h4>
                    </div>

                    <div class="modal-body">

                        <form id="form_fiscalizacao" class="form-horizontal">
                            <ul class="list-unstyled">
                                <li><h4>Ramo:</h4></li>
                                <li><select class="form-control" id="form_ramos_fiscalizacao"
                                            name="ramo_id"></select></li>
                                <li><h4>Comentário:</h4></li>
                                <li><textarea class="form-control" id="comentariof"
                                              name="comentario" rows="7"></textarea></li>
                                <li><h4>Gostaria de enviar uma imagem?</h4></li>
                                <li><input type="file" name="imagem" id="img_input_fiscalizacao"></li>
                                <li><img id="img_fiscalizar" src="" /></li>

                            </ul>
                        </form>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary"
                                onClick="fiscalizacaoPOST()" data-dismiss="modal">Enviar</button>
                        <button type="button" class="btn btn-default"
                                onClick="POSTFORM.resetFiscalizacao()" data-dismiss="modal">Fechar</button>
                    </div>

                </div>
            </div>
        </div>

        <!-- FIM MODAL PROPOSTA -->


        <!-- MODAL CRIAR ENQUETE -->

        <!-- Modal-->
        <div class="modal fade" id="newEnqueteModal" tabindex="-1"
             role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel">Nova Enquete</h4>
                    </div>

                    <div class="modal-body">

                        <form id="form_new_enquete" class="form-horizontal fnenquete">

                            <ul class="list-unstyled" id="lista">
                                <li><h4>Título:</h4></li>
                                <li><input type="text" class="form-control" name="titulo"
                                           id="titulo" placeholder="Título"></li>
                                <li><h4>Sua enquete precisa de imagem?</h4></li>
                                <li><input type="file" name="imagem" id="img_input_new_enquete"></li>
                                <li><img src="" id="img_new_enquete" /></li>
                                <li><h4>Adicione opções de voto:</h4></li>
                                <li><input type="text" class="form-control" name="opt_1"
                                           id="opt_1" placeholder="1ª Opção"></li>
                                <li><input type="text" class="form-control" name="opt_2"
                                           id="opt_2" placeholder="2ª Opção"></li>

                            </ul>
                            <input type="hidden" id="qtd_opt" name="qtd_opt" value="2" />
                        </form>



                        <button type="button" class="btn btn-primary " id="more"
                                onClick="ENQUETEFORM.more()">
                            Add <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                        </button>
                        <button type="button" class="btn btn-danger " id="less"
                                onClick="ENQUETEFORM.less()">
                            <span class="glyphicon glyphicon-minus" aria-hidden="true"></span>
                        </button>


                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary"
                                onClick="enquetePost();
                                                        " data-dismiss="modal">Enviar</button>
                        <button type="button" class="btn btn-default"
                                onClick="ENQUETEFORM.resetNewEnquete()" data-dismiss="modal">Fechar</button>
                    </div>

                </div>
            </div>
        </div>
        <!-- FIM MODAL CRIAR ENQUETE -->


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

                        <button type="button" class="btn btn-default"
                                onClick="ENQUETEFORM.resetNewEnquete()" data-dismiss="modal">Fechar</button>
                    </div>

                </div>
            </div>
        </div>

    </body>

</html>



