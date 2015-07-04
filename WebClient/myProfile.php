

<?php
session_start();
if ($_SESSION['id_usuario'] == '') {
    header("location: index.php");
}


include("services/redirect.php");
include("services/getRoot.php");
$url = getRoot();
$id = $_SESSION['id_usuario'];
$foto = redirectGet($url . 'WebService/getFotoPerfilById/' . $id);
$user = redirectGet($url . 'WebService/getNomeById/' . $id);


$json = redirectGet($url . 'WebService/getUsuarioById/' . $id);

$usuario = json_decode($json);

if (sizeof($usuario) == 0)
    header("location: index.php");

$usuario = $usuario[0];

$nome = $usuario->nm_usuario;
$tipo = $usuario->usuario_tipo;
$curso = $usuario->curso;
$ano_periodo = $usuario->ano_periodo;
$grau_academico = $usuario->grau_academico;
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
        <script src="//code.jquery.com/jquery-1.11.2.min.js"></script>

        <script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" rel="stylesheet">
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>


        <script src="js/ifpitaco/toPlainText.js"></script>

        <link rel="stylesheet" type="text/css"  href="css/user_profile.css">
        <link rel="stylesheet" type="text/css"  href="css/search.css">
        <link rel="stylesheet" type="text/css" href="css/foto_size.css">

        <link href="js/pace/themes/blue/pace-theme-barber-shop.css" rel="stylesheet" />
        <script data-pace-options='{ "ajax": true }' src='js/pace/pace.js'></script>
        <link href="jcrop/css/jquery.Jcrop.css" rel="stylesheet" type="text/css" />
        <script src="jcrop/js/jquery.Jcrop.js"></script>

        <script src="js/ifpitaco/searchController.js"></script>
        <script src="js/ifpitaco/sessionController.js"></script>

        <script src="js/ifpitaco/tools/image_cropper.js"></script>

        <!-- Cropper Plugin-->
        <link  href="js/cropper-master/dist/cropper.css" rel="stylesheet">
        <script src="js/cropper-master/dist/cropper.js"></script>

        <script src="js/ifpitaco/services/alterarDadosPOST.js"></script>

        <script src="js/ifpitaco/cadastrarForm.js"></script>
        <script src="js/ifpitaco/myProfileForm.js"></script>

        <script>
            prepare({
                tipo: '<?php echo $tipo ?>',
                curso: '<?php echo $curso ?>',
                ano_periodo: '<?php echo $ano_periodo ?>',
                grau_academico: '<?php echo $grau_academico ?>'
            });
        </script>
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
                    <button onClick="parent.location = 'services/logout.php'" type="submit" class="btn btn-default navbar-right" style="margin: 8px">Sair</button>
                </div>

                <!-- Inline block -->
                <div>
                    <form  role="search" onsubmit="return SEARCH.doSearchSubmitted()" id="search_form">
                        <div class="inner-addon right-addon">
                            <i class="glyphicon glyphicon-search"></i>	
                            <input type="text" id='search_input' class="navbar-left form-control" placeholder="Procurar Usuário"  onkeyup="SEARCH.doSearch(this.value)">
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
                        <a href="userProfile.php?id=<?php echo $id ?>"><img src="<?php echo $foto ?>"  alt="..." class="f120x120"></a>
                    </div>
                    <a href="userProfile.php?id=<?php echo $id ?>"><h3><?php echo htmlentities($user) ?></h3></a>

                </div>

                <!-- Meio -->
                <div class="col-md-4 col-md-offset-2" id="mid" >


                    <h2><span class="glyphicon glyphicon-pencil"></span> Editar</h2>	
                    <form class="form-signin" id="form">

                        <div class="form-group">
                            <label for="nm_usuario"><span style="margin-right:10px" class="glyphicon glyphicon glyphicon-user" aria-hidden="true"></span>Nome de Usuário</label>
                            <input required="required"  type="text" 
                                   class="form-control" id="nm_usuario" name="nm_usuario" 
                                   placeholder="Digite seu nome" value='<?php echo $nome ?>'>
                        </div>



                        <div class="radios text-center">
                            <label class="radio-inline">
                                <input type="radio" name="usuario_tipo" onClick="option1Click()" id="option1" value="Aluno" checked> Aluno
                            </label>

                            <label class="radio-inline">
                                <input type="radio" name="usuario_tipo" onClick="option2Click()" id="option2" value="Professor"> Professor
                            </label>

                            <label class="radio-inline">
                                <input type="radio" name="usuario_tipo" onClick="option3Click()" id="option3" value="Servidor"> Servidor
                            </label>
                        </div>

                        <div id="aluno">
                            <label style="display: block"><span style="margin-right: 10px" class="glyphicon glyphicon-book" aria-hidden="true"></span>Curso</label>
                            <span >
                                <select  style="display: inline" class="curso form-control" name="curso">

                                    <optgroup label="Integrado">
                                        <option value="Informática Integrado" id="c1">Informática</option>
                                        <option value="Manutenção e Suporte em Informática Integrado" id="c2">Manutenção e Suporte em Informática</option>
                                        <option value="Mineração Integrado" id="c3">Mineração</option>
                                        <option value="Petróleo e Gás Integrado" id="c4">Petróleo e Gás</option>

                                    </optgroup>

                                    <optgroup label="Subsequente">
                                        <option value="Manutenção e Suporte em Informática Subsequente" id="c5">Manutenção e Suporte em Informática</option>
                                        <option value="Mineração Subsequente" id="c6">Mineração</option>
                                    </optgroup>

                                    <optgroup label="Superior">
                                        <option value="Construção de Edifícios Superior" id="c7">Construção de Edifícios</option>
                                        <option value="Física Superior" id="c8">Física</option>
                                        <option value="Letras em Língua Portuguesa Superior" id="c9">Letras em Língua Portuguesa</option>
                                        <option value="Matemática Superior" id="c10">Matemática</option>
                                    </optgroup>

                                    <optgroup label="Proeja">
                                        <option value="Operação de Microcomputadores Proeja" id="c11">Operação de Microcomputadores</option>
                                    </optgroup>
                                    <option value="Outro" id="c12">Outro</option>	
                                </select>

                                <select style="display: inline; padding : 5px;" tabindex="0" data-trigger="focus" class="ano_periodo form-control" id="pop" name="ano_periodo" class="btn btn-default" data-container="body" data-toggle="popover" data-placement="right" data-content="Ano/Período" >
                                    <option value="1" id='a1'>1º</option>
                                    <option value="2" id='a2'>2º</option>
                                    <option value="3" id='a3'>3º</option>
                                    <option value="4" id='a4'>4º</option>
                                    <option value="5" id='a5'>5º</option>
                                    <option value="6" id='a6'>6º</option>
                                    <option value="7" id='a7'>7º</option>
                                    <option value="8" id='a8'>8º</option>
                                </select>
                            </span>
                        </div>		

                        <div id="professor">
                            <label><span style="margin-right: 10px" class="glyphicon glyphicon-book" aria-hidden="true"></span>Grau Acadêmico</label>
                            <select class="form-control" name="grau_academico">
                                <option value="Especialização" id="g1">Especialização</option>
                                <option value="Doutorado" id="g2">Doutorado</option>
                                <option value="Mestrado" id="g3">Mestrado</option>
                                <option value="Outro" id="g4">Outro</option>
                            </select>
                        </div>

                        <div class="form-group">

                            <label for="foto"><span style="margin-right: 10px" class="glyphicon glyphicon glyphicon-camera" aria-hidden="true"></span>Imagem de Perfil</label>
                            <input accept="image/x-png, image/gif, image/jpeg"type="file" id="image_input" name="foto" class="button">
                            <p class="help-block">Escolha uma imagem para usar no seu perfil</p>

                        </div>


                        <button id="submit" class="btn btn-lg btn-primary btn-block" type="submit">Alterar Dados</button>


                        <!-- Coordenadas da foto  -->
                        <input type="hidden" id="crop_x" name="x" value="1"/>
                        <input type="hidden" id="crop_y" name="y" value="1"/>
                        <input type="hidden" id="crop_w" name="w" value="1"/>
                        <input type="hidden" id="crop_h" name="h" value="1"/>
                        <input type="hidden" name="usuario_id" value="<?php echo $id ?>"/>
                    </form> 


                </div>

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