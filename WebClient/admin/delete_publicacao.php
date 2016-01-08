<?php

require_once '../services/redirect.php';
require_once '../services/getRoot.php';
include_once '../services/prepareAuth.php';


if (!prepare()) {
    header("Location: ../home.php");
} else{
    redirectPost(getRoot() . 'WebService/postDeletePublicacao/');
    header('Location: gerencia_publicacao.php?post_id='. $_POST['post_id']);
}