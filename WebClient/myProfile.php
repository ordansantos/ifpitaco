

<?php

require_once 'services/checkLogin.php';

checkLogin();
?>



<!DOCTYPE html>

<html>

    <head>
        <!-- Bootbox -->
        <script src="js/bootbox/bootbox.min.js"></script>

        <link rel="stylesheet" type="text/css"  href="css/login_border.css">
        <link rel="stylesheet" type="text/css"  href="css/my_profile.css">
        <meta charset="utf-8"/>
        <link rel="shortcut icon" href="images/favicon.png">
        <title>IFPitaco</title>

        <!-- JQuery -->
        <script src="jquery/jquery-1.11.3.min.js"></script>

        <!-- Bootstrap -->
        <link href="bootstrap-3.3.5/css/bootstrap.min.css" rel="stylesheet">
        <script src="bootstrap-3.3.5/js/bootstrap.min.js"></script>

        <link rel="stylesheet" type="text/css"  href="css/user_profile.css">
        <link rel="stylesheet" type="text/css"  href="css/search.css">
        <link rel="stylesheet" type="text/css" href="css/foto_size.css">

        <!-- LOAD ANIMATION -->
        <link href="js/pace/themes/green/pace-theme-flash.css" rel="stylesheet" />
        <script data-pace-options='{ "ajax": true }' src='js/pace/pace.js'></script>

        <script src="js/ifpitaco/searchController.js"></script>
        <script src="js/ifpitaco/sessionController.js"></script>

         <!-- Cropper Plugin-->
        <link  href="js/cropper-master/dist/cropper.css" rel="stylesheet">
        <script src="js/cropper-master/dist/cropper.js"></script>

        <!-- Image Cropper -->
        <script src="js/ifpitaco/tools/image_cropper.js"></script>

        

        <script src="js/ifpitaco/cadastrarForm.js"></script>
        
        <!-- cadastrar Ajax -->
        <script src="js/ifpitaco/services/completarPOST.js"></script>

    </head>


    <body>

        <nav id="bar" class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="home.php">
                        <img alt="Brand" src="images/logo2.png" id="logo">
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
                    <form  role="search" onsubmit="return SEARCH.doSearchSubmitted()" id="search_form">
                        <div class="inner-addon right-addon">
                            <i class="glyphicon glyphicon-search"></i>	
                            <input type="text" id='search_input' class="navbar-left form-control" placeholder="Procurar Usuário"  onkeyup="SEARCH.doSearch(this.value)"
                                   autocomplete="off">
                        </div>
                    </form>
                </div>

            </div>
        </nav>


        <div class="container-fluid" style="margin-top: 50px">

            <div class="row">

                <!-- Profile -->
                <div class="col-md-2 text-center " id="profile">
                    <div class="img-thumbnail">
                        <a href="userProfile.php?id=<?php echo $_SESSION['id_usuario'] ?>"><img src="<?php echo $_SESSION['foto'] ?>"  alt="..." class="f120x120"></a>
                    </div>
                    <a href="userProfile.php?id=<?php echo $_SESSION['id_usuario'] ?>"><h3><?php echo htmlentities($_SESSION['name']) ?></h3></a>

                </div>

                <!-- Meio -->
                <div class="col-md-4 col-md-offset-2" id="mid" >


                    <h2><span class="glyphicon glyphicon-pencil"></span> Editar</h2>	


                    <form class="form-signin" id="form">










                        <form class="form-signin" id="form">


                            <label><span class="glyphicon glyphicon-education" aria-hidden="true"></span>Instituição de ensino</label>
                            <select class="form-control" name="grupo" >

                                <?php
                                require_once './services/redirect.php';
                                require_once './services/getRoot.php';
                                $url = getRoot();
                                $grupos = redirectGet($url . 'WebService/getGrupos');
                                $grupos = json_decode($grupos);

                                foreach ($grupos->grupos as $g) {

                                    echo '<option value="' . $g->id_grupo . '">' . $g->nm_grupo . '</option>';
                                }
                                ?>

                            </select>
                            <br/>
                            <div class="radios text-center">
                                <label class="radio-inline">
                                    <input type="radio" name="usuario_tipo" onClick="option1Click()" id="option1" value="Aluno" checked> Aluno
                                </label>

                                <label class="radio-inline">
                                    <input type="radio" name="usuario_tipo" onClick="option2Click()" id="option1" value="Professor"> Professor
                                </label>

                                <label class="radio-inline">
                                    <input type="radio" name="usuario_tipo" onClick="option3Click()" id="option1" value="Servidor"> Servidor
                                </label>
                            </div>

                            <div id="aluno">
                                <label style="display: block"><span  class="glyphicon glyphicon-book" aria-hidden="true"></span>Curso</label>
                                <span>
                                    <select style="display: inline" class="curso form-control" name="curso">

                                        <optgroup label="Integrado">
                                            <option value="Informática Integrado">Informática</option>
                                            <option value="Manutenção e Suporte em Informática Integrado">Manutenção e Suporte em Informática</option>
                                            <option value="Mineração Integrado">Mineração</option>
                                            <option value="Petróleo e Gás Integrado">Petróleo e Gás</option>

                                        </optgroup>

                                        <optgroup label="Subsequente">
                                            <option value="Manutenção e Suporte em Informática Subsequente">Manutenção e Suporte em Informática</option>
                                            <option value="Mineração Subsequente">Mineração</option>
                                        </optgroup>

                                        <optgroup label="Superior">
                                            <option value="Construção de Edifícios Superior">Construção de Edifícios</option>
                                            <option value="Física Superior">Física</option>
                                            <option value="Letras em Língua Portuguesa Superior">Letras em Língua Portuguesa</option>
                                            <option value="Matemática Superior">Matemática</option>
                                        </optgroup>

                                        <optgroup label="Proeja">
                                            <option value="Operação de Microcomputadores Proeja">Operação de Microcomputadores</option>
                                        </optgroup>
                                        <option value="Outro">Outro</option>	
                                    </select>

                                    <select style="display: inline; padding : 5px;" tabindex="0" data-trigger="focus" class="ano_periodo form-control" id="pop" name="ano_periodo" class="btn btn-default" data-container="body" data-toggle="popover" data-placement="right" data-content="Ano/Período" >
                                        <option value="1">1º</option>
                                        <option value="2">2º</option>
                                        <option value="3">3º</option>
                                        <option value="4">4º</option>
                                        <option value="5">5º</option>
                                        <option value="6">6º</option>
                                        <option value="7">7º</option>
                                        <option value="8">8º</option>
                                    </select>
                                </span>
                            </div>		

                            <div id="professor">
                                <label><span class="glyphicon glyphicon-book" aria-hidden="true"></span>Grau Acadêmico</label>
                                <select class="form-control" name="grau_academico">
                                    <option value="Especialização">Especialização</option>
                                    <option value="Doutorado">Doutorado</option>
                                    <option value="Mestrado">Mestrado</option>
                                    <option value="Outro">Outro</option>
                                </select>
                            </div>

                            <div class="form-group">

                                <label for="foto"><span class="glyphicon glyphicon glyphicon-camera" aria-hidden="true"></span>Imagem de Perfil</label>
                                <input accept="image/x-png, image/gif, image/jpeg"type="file" id="image_input" name="foto" class="button">
                                <p class="help-block">Trocar imagem</p>

                            </div>

                            <br/>

                            <button id="submit" class="btn btn-lg btn-primary btn-block" type="submit">Modificar</button>


                            <!-- Coordenadas da foto | Cropper  -->
                            <input type="hidden" id="crop_x" name="x" value="1"/>
                            <input type="hidden" id="crop_y" name="y" value="1"/>
                            <input type="hidden" id="crop_w" name="w" value="1"/>
                            <input type="hidden" id="crop_h" name="h" value="1"/>
                            <input type="hidden" name="modify" value="yes"/>


                        </form> 


                        <!-- MODAL CROPPER -->
                        <div class="modal fade" id="cropper-modal">
                            <div class="modal-dialog">
                                <div class="modal-content">

                                    <div class="modal-body">
                                        <div>
                                            <img id="img_to_crop">
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary" data-dismiss="modal">Cortar</button>
                                    </div>

                                </div>
                            </div>
                        </div>








                    </form>

                </div>

            </div>

        </div>


    </body>

</html>