
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
?>

<?php
$json = redirectGet($url . 'WebService/getUsuarioById/' . $_GET['id']);

$usuario = json_decode($json);

if (sizeof($usuario) == 0)
    header("location: index.php");

$usuario = $usuario[0];

$nome = $usuario->nm_usuario;
$tipo = $usuario->usuario_tipo;
$curso = $usuario->curso;
$ano_periodo = $usuario->ano_periodo;
$grau_academico = $usuario->grau_academico;
$image_perfil = $usuario->perfil;
$status = json_decode(redirectGet($url . 'WebService/getLastAccess/' . $_GET['id']))->check;
?>

<!DOCTYPE html>

<html>

    <head>

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

        <script src="js/ifpitaco/searchController.js"></script>
        <script src="js/ifpitaco/sessionController.js"></script>
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
                        <a href="userProfile.php?id=<?php echo $id ?>"><img src="<?php echo $foto ?>"  alt="..." class="f120x120"></a>
                    </div>
                    <a href="userProfile.php?id=<?php echo $id ?>"><h3><?php echo htmlentities($user) ?></h3></a>

                </div>

                <!-- Meio -->
                <div class="col-md-4 col-md-offset-2" id="mid" >

                    <div id="feed" class="text-center">

                        <img  src="<?php echo $image_perfil ?>"  alt="..." class="f120x120 img-thumbnail">

                        <div class="dados">
                            <h2><?php echo htmlentities($nome); ?></h2>
                            <?php
                            echo '<h5><span class="' . $status . '">' . $status . '</span></h5>';
                            ?>
                            <?php
                            if ($tipo == 'Aluno') {
                                echo '<h4>' . $curso . '</h4>';
                                echo '<h4>' . $ano_periodo . 'º Ano/Período</h4>';
                            } else if ($tipo == 'Professor')
                                echo '<h4>' . $grau_academico . '</h4>';
                            echo '<h5>' . $tipo . '</h5>';
                            ?>

                        </div>

                    </div>

                </div>

            </div>

        </div>


    </body>

</html>