

<?php
    error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
if ($_SESSION['id_usuario'] == '') {
    header("location: index.php");
}

require_once '../services/redirect.php';
require_once '../services/getRoot.php';
$url = getRoot();
$json = redirectGet($url . 'WebService/getAllPosts/' . $_SESSION['grupo']);
$posts = json_decode($json)->posts;

?>

<html>

    <head>

        <link rel="shortcut icon" href="../images/favicon.png">
        <title>IFPitaco</title>
        <meta charset="utf-8"/>

        <!-- JQuery -->
        <script src="../jquery/jquery-1.11.3.min.js"></script>

        <!-- Bootstrap -->
        <link href="../bootstrap-3.3.5/css/bootstrap.min.css" rel="stylesheet">
        <script src="../bootstrap-3.3.5/js/bootstrap.min.js"></script>
        <link rel="stylesheet" type="text/css" href="../css/home.css">
        <link href="admin.css" rel="stylesheet">
    </head>
    
    <body>
        
        <nav id="bar" class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="../home.php">
                        <img alt="Brand" src="../images/logo2.png" id="logo">
                    </a>
                </div>

                <div>
                         <button onClick="parent.location = '../services/logout.php'"
                            type="submit" class="glyphicon glyphicon-log-out btn btn-default navbar-right"
                            style="margin: 8px"> Sair</button>
                            
                </div>


            </div>
        </nav>
        
        <div class="col-md-9 col-md-offset-2 publicacoes_table">
            <table class="table  table-bordered table-striped table-hover">
                <tr>
                    <th>
                        id
                    </th>
                    <th>
                        usuário
                    </th>    
                    <th>
                        tipo
                    </th>
                    <th>
                        comentário
                    </th>
                    <th>
                        data/hora
                    </th>
                    <th>
                        ramo
                    </th>
                    <th>
                        imagem
                    </th>

                </tr>
                <?php

                   foreach ($posts as $post){
                       echo '<tr class="'.($post->deletado ? 'danger' : "").'">';

                            echo '<td><a style="color: #337ab7; text-decoration: underline;" href="gerencia_publicacao.php?post_id=' . $post->post_id . '">' . $post->post_id. '</a></td>';
                            echo '<td>' . $post->nm_usuario . '/' . $post->usuario_id .'</td>';
                            echo '<td class="' . ($post->tipo == 0? 'success">Proposta' : 'warning">Fiscalização') . '</td>';
                            echo '<td>' . $post->comentario . '</td>';
                            echo '<td>' . $post->data_hora . '</td>';
                            echo '<td>' . $post->nm_ramo . '</td>';


                            $imagem = explode('/', $post->imagem);

                            echo '<td><a href="../'.$post->imagem.'">' . $imagem[count($imagem) - 1] . '</a></td>';

                       echo '</tr>';
                   }

                ?>


            </table>
        </div>
    </body>
</html>




