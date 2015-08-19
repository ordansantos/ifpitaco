<?php

session_start();

$_POST['id_usuario'] = $_SESSION['id_usuario'];

require_once '../services/redirect.php';
require_once '../services/getRoot.php';

$url = getRoot();

redirectPost($url . 'WebService/postDeleteComentario/');

header('Location: gerencia_publicacao.php?post_id='. $_POST['post_id']);
