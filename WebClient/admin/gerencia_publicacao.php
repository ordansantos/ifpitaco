<?php

session_start();

if ($_SESSION['id_usuario'] == '' || !$_SESSION['is_admin']) {
    header("location: ../index.php");
}

//error_reporting(E_ALL);
//ini_set('display_errors', 1);

require_once '../services/redirect.php';
require_once '../services/getRoot.php';
$url = getRoot();
$json = redirectGet($url . 'WebService/adminGetPostById/' . $_GET['post_id']);
$post = json_decode($json);

$json = redirectGet($url . 'WebService/adminGetComentariosById/' . $_GET['post_id']);
$comentarios = json_decode($json)->comentarios;

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
            <?php
                if ($post->deletado == 0){
           
                    echo '
                    <form style="display: inline" action="delete_publicacao.php" method="POST">

                        <input type="hidden" name="post_id" value="'. $post->post_id .'"/>
                        <input class="btn btn-lg btn-danger" type="submit" value="Deletar Publicação"/>
                    </form>';
                    
                    echo '
                    <a style="display: inline" href ="../publicacao.php?id='.$post->post_id.'" 
                    class="glyphicon glyphicon-share-alt btn btn-lg btn-primary"
                    style="margin: 8px"> Acessar</a>
                    ';
         
                } else{            
                echo '
                <form style="display: inline" action="delete_publicacao_reverte.php" method="POST">
                    
                    <input type="hidden" name="post_id" value="'.$post->post_id.'"/>
                    <input class="btn btn-lg btn-success" type="submit" value="Restaurar Publicação"/>
                </form>';
                }
                
                
            ?>
            
            <a style="display: inline" href ="./publicacao.php" 
            class="glyphicon glyphicon-chevron-left btn btn-lg btn-default pull-right"
            style="margin: 8px"> Voltar</a>
            
            <table class="table  table-bordered table-striped table-hover">
                <tr>
                    <th>Id</th>
                    <td><?php echo $post->post_id ?></td>
                </tr>
                <tr>
                    <th>Usuário</th>
                    <td><?php echo $post->nm_usuario . '/' . $post->usuario_id ?></td>
                </tr>
                <tr>
                    <th>Tipo</th>
                    <td><?php echo ($post->tipo == 0? 'Proposta' : 'Fiscalização') ?></td>
                </tr>
                <tr>
                    <th>Comentário</th>
                    <td><?php echo $post->comentario  ?></td>
                </tr>
                <tr>
                    <th>Data/Hora</th>
                    <td><?php echo $post->data_hora ?></td>
                </tr>
                <tr>
                    <th>Ramo</th>
                    <td><?php echo $post->nm_ramo ?></td>
                </tr>
                <tr>
                    <th>Imagem</th>
                    <td><?php $imagem = explode('/', $post->imagem); echo '<a href="../'.$post->imagem.'">' . $imagem[count($imagem) - 1] . '</a>' ?></td>
                </tr>
            </table>

            <h2 style="color: white">Comentários</h2>
            
            <table class="table  table-bordered table-striped table-hover">
                
                <tr>
                    <th>Usuário</th>
                    <th>Comentário</th>
                    <th>Ação</th>
                </tr>
                <?php
                        
                    foreach ($comentarios as $comentario){
                        echo '
                            
                                <tr>
                                    <td>'. $comentario->nm_usuario . '</td>
                                    <td>'. $comentario->comentario . '</td>
                                    <td>';
                            if ($comentario->deletado == 0){
                                echo '
                                <form action="delete_comentario.php" method="POST">

                                    <input type="hidden" name="post_id" value="'. $post->post_id .'"/>
                                    <input type="hidden" name="comentario_post_id" value="'. $comentario->comentario_post_id .'"/>
                                    <input class="btn btn-sm btn-danger" type="submit" value="Deletar"/>
                                </form>';
                            } else{            
                                echo '
                                <form action="delete_comentario_reverte.php" method="POST">

                                    <input type="hidden" name="post_id" value="'.$post->post_id.'"/>
                                    <input type="hidden" name="comentario_post_id" value="'. $comentario->comentario_post_id .'"/>
                                    <input class="btn btn-sm btn-success" type="submit" value="Restaurar"/>
                                </form>';
                            }
                        echo    '</td>
                                </tr>'

                        ;
                    }
                    
                ?>
                <tr>
                    
                </tr>
                
            </table>
        </div>
    </body>
</html>
