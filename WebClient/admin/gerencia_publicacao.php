<?php

session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../services/redirect.php';
require_once '../services/getRoot.php';
$url = getRoot();
$json = redirectGet($url . 'WebService/adminGetPostById/' . $_GET['post_id']);
$post = json_decode($json);

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
        
        <link href="admin.css" rel="stylesheet">
    </head>
    
    <body>
        <div class="col-md-9 col-md-offset-2 publicacoes_table">
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
            <?php
                if ($post->deletado == 0){
                    echo '
                    <form action="delete_publicacao.php" method="POST">

                        <input type="hidden" name="post_id" value="'. $post->post_id .'"/>
                        <input class="btn btn-lg btn-danger" type="submit" value="Deletar"/>
                    </form>';
                } else{            
                echo '
                <form action="delete_publicacao_reverte.php" method="POST">
                    
                    <input type="hidden" name="post_id" value="'.$post->post_id.'"/>
                    <input class="btn btn-lg btn-success" type="submit" value="Deletado/Restaurar"/>
                </form>';
                }
            ?>
        </div>
    </body>
</html>
