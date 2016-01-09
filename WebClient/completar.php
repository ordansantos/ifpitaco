
<?php

session_start();

if ($_SESSION['token'] == '') {
    header("location: index.php");
}

?>
<!DOCTYPE html>

<html>

    <head>
        <link rel="shortcut icon" href="images/favicon.png">
        <title>IFPitaco</title>
        <meta charset="utf-8">

        <!-- JQuery -->
        <script src="jquery/jquery-1.11.3.min.js"></script>

        <!-- Bootstrap -->
        <link href="bootstrap-3.3.5/css/bootstrap.min.css" rel="stylesheet">
        <script src="bootstrap-3.3.5/js/bootstrap.min.js"></script>

        <!-- Bootbox -->
        <script src="js/bootbox/bootbox.min.js"></script>

        <!-- Cropper Plugin-->
        <link  href="js/cropper-master/dist/cropper.css" rel="stylesheet">
        <script src="js/cropper-master/dist/cropper.js"></script>

        <!-- Image Cropper -->
        <script src="js/ifpitaco/tools/image_cropper.js"></script>

        <!-- cadastrar Controller -->
        <script src="js/ifpitaco/cadastrarForm.js"></script>

        <!-- cadastrar Ajax -->
        <script src="js/ifpitaco/services/completarPOST.js"></script>

        <!-- CSS -->
        <link rel="stylesheet" type="text/css"  href="css/login_border.css">
        <link rel="stylesheet" type="text/css" href="css/completar.css">

    </head>


    <body>

        <div id="bar" >
            <div class="container-fluid">
                <a class="navbar-header" href="index.php">
                    <img alt="Brand" src="images/logo2.png" id="logo">
                </a>
            </div>
        </div> 

        <div class="container">	

            <div class="col-md-5 col-md-offset-4" style="margin-top: 5%">
                
                <h1>Complete o cadastro para continuar</h1>
                
                <form class="form-signin" id="form">

                    
                    <label><span class="glyphicon glyphicon-education" aria-hidden="true"></span>Instituição de ensino</label>
                    <select class="form-control" name="grupo" >
                        
                        <?php
                            require_once './services/redirect.php';
                            require_once './services/getRoot.php';
                            $url = getRoot();
                            $grupos = redirectGet($url . 'WebService/getGrupos');
                            $grupos = json_decode($grupos);
                    
                            foreach ($grupos->grupos as $g){
                             
                                echo '<option value="' . $g->id_grupo . '">'.$g->nm_grupo.'</option>';
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
                        <p class="help-block">Escolha uma imagem para usar no seu perfil</p>

                    </div>

                    <br/>

                    <button id="submit" class="btn btn-lg btn-primary btn-block" type="submit">Continuar</button>


                    <!-- Coordenadas da foto | Cropper  -->
                    <input type="hidden" id="crop_x" name="x" value="1"/>
                    <input type="hidden" id="crop_y" name="y" value="1"/>
                    <input type="hidden" id="crop_w" name="w" value="1"/>
                    <input type="hidden" id="crop_h" name="h" value="1"/>
                    

                    
                </form> 
                <br/>
                <div class="alert alert-danger" role="alert" id="error" style="display: none"></div>
            </div>


        </div>

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

    </body>

</html>

