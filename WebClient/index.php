
<?php
session_start();

if (isset($_SESSION['token']) &&  $_SESSION['token'] != '') {
    header("location: home.php");
}

?>

<!DOCTYPE html>

<html>

    <head>

        <link rel="shortcut icon" href="images/favicon.png">
        <title>IFPitaco</title>
        <meta charset="utf-8"/>

        <!-- JQuery -->
        <script src="jquery/jquery-1.11.3.min.js"></script>

        <!-- Bootstrap -->
        <link href="bootstrap-3.3.5/css/bootstrap.min.css" rel="stylesheet">
        <script src="bootstrap-3.3.5/js/bootstrap.min.js"></script>

        <!-- Services -->
        <script type="text/javascript" src="js/ifpitaco/services/loginPOST.js"></script>
        <script type="text/javascript" src="js/ifpitaco/services/systemCadastroPOST.js"></script>

        <!-- CSS -->
        <link rel="stylesheet" type="text/css"  href="css/index.css">
        <link rel="stylesheet" type="text/css"  href="css/login_border.css">

        <link rel="stylesheet" href="css/bootstrap-social.css"/>

        <link rel="stylesheet" href="css/font-awesome-4.5.0/css/font-awesome.css"/>
        <!-- facebook login -->
        <script type="text/javascript" src="js/ifpitaco/services/loginFb.js"></script>
        
        <!-- LOAD ANIMATION -->
        <link href="js/pace/themes/green/pace-theme-flash.css" rel="stylesheet" />
        <script data-pace-options='{ "ajax": true }' src='js/pace/pace.js'></script>
    </head>


    <body>

        <div id="bar">
            <div class="container-fluid">
                <a class="navbar-header" href="index.php">
                    <img alt="Brand" src="images/logo2.png" id="logo">
                </a>
            </div>
        </div>

        <div class="container">	

            <div class="box-info col-md-6 " >
                <p>Proponha melhorias ao IFPB, fiscalize problemas do dia a dia e opine! Ajude no crescimento de sua instituição.</p>
            </div>

            <div class="col-md-4 col-sm-push-2" >

                <form class="form-signin form" id="form">

                    <label for="inputEmail" class="sr-only">Email</label>

                    <div class="inner-addon left-addon">
                        <i class="glyphicon glyphicon-user"></i>
                        <input name="email" type="email" id="inputEmail" class="form-control" placeholder="Email" required autofocus >
                    </div>

                    <br/>
                    <label for="inputPassword" class="sr-only">Senha</label>

                    <div class="inner-addon left-addon">
                        <i class="glyphicon glyphicon-lock"></i>
                        <input name="senha" type="password" id="inputPassword" class="form-control" placeholder="Senha" required >
                    </div>

                    <br/>
                    <div class="row">
                        <div class="col-xs-6">
                            <button class="btn  btn-primary btn-block" id="login" type="submit">Entrar</button>
                        </div>
                        <div class="col-xs-6">
                            <button data-toggle="modal"
                                    data-target="#cadastrarModal"  id="signup" class="btn  btn-success btn-block" type="button">Cadastrar</button>
                        </div>
                        <!-- onClick="parent.location = 'cadastrar.php'" -->
                    </div>

                    <a onclick="fb_login()" style="margin-top: 2px" class="btn btn-block  btn-facebook">
                        <span class="fa fa-facebook"></span> Entrar com Facebook
                    </a>
                    
                </form> 
                <br/>
                <div class="alert alert-danger" role="alert" id="error" style="display: none">Dados Inválidos</div>
            </div>
        </div>





        <!-- Modal -->
        <div class="modal fade " id="cadastrarModal" tabindex="-1" role="dialog"
             aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content col-md-9 col-md-offset-8">



                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel">Cadastrar</h4>
                    </div>

                    <div class="modal-body">
                        <form id="system_cadastro_form">
                            <div>
                                <div class="form-signin form">

                                    <div class="row">
                                        <div class="col-xs-6">
                                            <div class="form-group">
                                                <label for="nm_usuario"><span class="glyphicon glyphicon glyphicon-user" aria-hidden="true"></span> Nome de Usuário</label>
                                                <input required="required"  type="text" class="form-control" id="nm_usuario" name="nm_usuario" placeholder="Digite seu nome">
                                            </div>
                                        </div>
                                        <div class="col-xs-6">
                                            <div class="form-group">
                                                <label for="senha"><span class="glyphicon glyphicon glyphicon-lock" aria-hidden="true"></span> Senha</label>
                                                <input required="required" type="password" class="form-control" id="senha" name="senha" placeholder="Senha">
                                            </div>                                </div>
                                        <!-- onClick="parent.location = 'cadastrar.php'" -->
                                    </div>

                                    <div class="form-group">
                                        <label for="email"><span class="glyphicon glyphicon glyphicon-envelope" aria-hidden="true"></span> Endereço de Email</label>
                                        <input required="required" type="email" class="form-control" id="email" name="email" placeholder="Digite seu Email">
                                    </div>

                                </div>

                            </div>

                            <div class="modal-footer">

                                <div class="row">
                                    <div class="col-xs-6">
                                        <button   id="submit" class="btn  btn-block btn-success btn-block" type="submit">Cadastrar</button>
                                    </div>
                                    <div class="col-xs-6">
                                        <button class="btn btn-block btn-social btn-facebook" id="login" type="button" onclick="fb_login()"><span class="fa fa-facebook"></span>Entrar com Facebook</button>
                                    </div>

                                    <!-- onClick="parent.location = 'cadastrar.php'" -->
                                </div>
                                
                                

                            </div>

                        </form>




                    </div>

                </div>
                <div class="modal-content col-md-9 col-md-offset-8 error_cadastro">
                    <div class="alert alert-danger" role="alert" id="error_cadastro" style="display: none">error</div>
                </div>
            </div>




    </body>

</html>
