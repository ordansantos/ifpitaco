<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'header.php';

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
//$app->response()->header('Content-Type', 'application/json;charset=utf-8');

date_default_timezone_set('America/Recife');

$app->get('/', function () { 
   echo 'Web Service is working...';
});

//Envia uma proposta
$app->post('/postProposta/', 'postProposta' );
//Verifica se o login está correto
$app->post('/postLogin/', 'postLogin');
//Envia uma fiscalização
$app->post('/postFiscalizacao/', 'postFiscalizacao');
//Envia um comentário de um post
$app->post('/postComentario/', 'postComentario');
//Delete um comentário específico

$app->post('/postDeleteComentario/', 'postDeleteComentario');
//Envia um 'laike' de um post

$app->post('/postDeleteComentarioReverte/', 'postDeleteComentarioReverte');
//Envia um 'laike' de um post


$app->post('/postLaike/', 'postLaike');
//Deleta um post
$app->post('/postDeletePublicacao/', 'postDeletePublicacao');
//Envia uma nova Enquete

$app->post('/postDeletePublicacaoReverte/', 'postDeletePublicacaoReverte');

$app->post('/postEnquete/', 'postEnquete');
//Envia o voto de uma enquete
$app->post('/postVoto/', 'postVoto');

//$app->post('/alterarDados/', 'alterarDados');

$app->post('/postUpdateLastAccess/', 'postUpdateLastAccess');

$app->post('/systemCadastroPost/', 'systemCadastroPost');

$app->post('/completarCadastro/', 'completarCadastro');

$app->post('/getStatus/', 'getStatus');

$app->post('/postFbLogin/', 'postFbLogin');

$app->post('/logout/', 'logout');

//Retorna os ramos
$app->get('/getRamos/', 'getRamos');
//Retorna o nome de usuário usando seu id
$app->get('/getNomeById/:id', 'getNomeById');
//Retorna a foto de perfil usando o id do usuário
$app->get('/getFotoPerfilById/:id', 'getFotoPerfilById');
//Retorna os comentários de um post e também uma flag, que responde se o post foi deletado
$app->get('/getComentariosById/:id', 'getComentariosById');

$app->get('/adminGetComentariosById/:id', 'adminGetComentariosById');

//Retorna o id do usuário que realizou o comentário
$app->get('/getUsuarioByComentarioPostId/:comentario_post_id', 'getUsuarioByComentarioPostId');
//Retorna N posts inseridos antes o id do post M
$app->get('/getNPostsLessThanMid/:n/:m/:g', 'getNPostsLessThanMid');
//Retorna os posts inseridos após o id do post N
$app->get('/getAllPostsGreaterThanNid/:n/:g', 'getAllPostsGreaterThanNid');
//Retorna os N últimos Posts
$app->get('/getNPosts/:n/:g', 'getNPosts');

$app->get('/getAllPosts/:g', 'getAllPosts');

$app->get('/getPostById/:id', 'getPostById');

$app->get('/adminGetPostById/:id', 'adminGetPostById');

//Retorna a quantidade de likes do post e uma flag, que responde se o usuário curtiu
$app->get('/getCntLaikesAndUserFlagByPostIdAndUserId/:post_id/:usuario_id', 'getCntLaikesAndUserFlagByPostIdAndUserId');
//Retorna o usuário que criou o post
$app->get('/getUsuarioByPostId/:post_id', 'getUsuarioByPostId');
//Retorna uma enquete
$app->get('/getEnquete/:usuario_id/:last_enquete_id/:g', 'getEnquete');
//Retorna uma enquete pelo id

$app->get('/getEnqueteById/:enquete_id/:usuario_id', 'getEnqueteById');
//Retorna o id de todas as enquetes

$app->get('/getUsuarioById/:usuario_id', 'getUsuarioById');
//Busca um usuário em relação a um nome
$app->get('/getBuscaUsuario/:nome', 'getBuscaUsuario');

$app->get('/curiarPost/:id', 'curiarPost');

$app->get('/curiarEnquete/:id', 'curiarEnquete');

$app->get('/getLastAccess/:id', 'getLastAccess');

$app->get('/getGrupos', 'getGrupos');

$app->run();


function postProposta (){
    
    $id = (new Auth)->Authorization();
    
    if ($id == false){
        echo MsgEnum::JSON_UNAUTHORIZED;
        return;
    }
    
    $proposta = new stdClass();
    $proposta->comentario = filter_input(INPUT_POST, 'comentario');
    $proposta->usuario_id = $id;
    $proposta->ramo_id = filter_input(INPUT_POST, 'ramo_id');
    
    echo (new Proposta())->post($proposta);
}

function postFiscalizacao(){
    
    $id = (new Auth)->Authorization();
    
    if ($id == false){
        echo MsgEnum::JSON_UNAUTHORIZED;
        return;
    }
    
    $fiscalizacao = new stdClass();
    
    $fiscalizacao->comentario = filter_input(INPUT_POST, 'comentario');
    $fiscalizacao->usuario_id = $id;
    $fiscalizacao->ramo_id = filter_input(INPUT_POST, 'ramo_id');
    $fiscalizacao->base64_string = filter_input(INPUT_POST, 'imagem');
    
    echo (new Fiscalizacao())->post($fiscalizacao);
}

function systemCadastroPost(){
 
    $usuario = new stdClass();
     
    $usuario->name = filter_input(INPUT_POST, 'nm_usuario');
    $usuario->senha = filter_input(INPUT_POST, 'senha');
    $usuario->email = filter_input(INPUT_POST, 'email');
    
    echo (new Usuario())->cadastrarSystem($usuario);
}

function completarCadastro(){
    
    $id = (new Auth)->Authorization();
    
    if ($id == false){
        echo MsgEnum::JSON_UNAUTHORIZED;
        return;
    }
    
    $usuario = new stdClass();
    $usuario->id_usuario = $id;
    
    $usuario->grupo = filter_input(INPUT_POST, 'grupo');
    
    $usuario->usuario_tipo = filter_input(INPUT_POST, 'usuario_tipo');
    $usuario->curso = filter_input(INPUT_POST, 'curso');
    $usuario->ano_periodo = filter_input(INPUT_POST, 'ano_periodo');
    $usuario->grau_academico = filter_input(INPUT_POST, 'grau_academico');
    
    $foto = new stdClass();

    $foto->base64_string = filter_input(INPUT_POST, 'imagem');
    $foto->x_percent = filter_input(INPUT_POST, 'x');
    $foto->y_percent = filter_input(INPUT_POST, 'y');
    $foto->w_percent = filter_input(INPUT_POST, 'w');
    $foto->h_percent = filter_input(INPUT_POST, 'h');
    $foto->modify = filter_input(INPUT_POST, 'modify');
            
    echo (new Usuario())->completar($usuario, $foto);
}

function postLogin(){
    $login = new stdClass();
    
    $login->email = filter_input(INPUT_POST, 'email');
    $login->senha = filter_input(INPUT_POST, 'senha');
    
    echo (new Auth())->system_login($login);
}

function postFbLogin(){
    
    $token = filter_input(INPUT_POST, 'token');
    
    if (!$token){
        echo MsgEnum::JSON_ERROR;
        return;
    } else{
        echo (new Auth)->fb_login($token);
    }
    
}

function getRamos(){
    echo Database::getRamos();
}

function getNomeById($id){
    echo (new Usuario())->getNomeById($id);
}

function getFotoPerfilById($id){
    echo (new Usuario())->getFotoPerfilById($id);
}

function postComentario (){

    $id = (new Auth)->Authorization();
    
    if ($id == false){
        echo MsgEnum::JSON_UNAUTHORIZED;
        return;
    }
    
    $comentario = new stdClass();

    $comentario->usuario_id = $id;
    $comentario->post_id = filter_input(INPUT_POST, 'post_id');
    $comentario->comentario = filter_input(INPUT_POST, 'comentario');

    echo (new Comentario())->post($comentario);
}

function getComentariosById ($id){

    echo (new Comentario())->getComentariosById($id);
    
}

function adminGetComentariosById($id){
    echo (new Comentario())->adminGetComentariosById($id);
}

function postDeleteComentario (){
    
    $id = (new Auth)->Authorization();
    
    if ($id == false){
        echo MsgEnum::JSON_UNAUTHORIZED;
        return;
    }
    
    $comentario = new stdClass();

    $comentario->comentario_post_id = filter_input(INPUT_POST, 'comentario_post_id');
    
    $comentario->id_usuario = $id;
    
    echo (new Comentario())->delete($comentario);
}

function postDeleteComentarioReverte (){
    
    $id = (new Auth)->Authorization();
    
    if ($id == false){
        echo MsgEnum::JSON_UNAUTHORIZED;
        return;
    }    
    
    $comentario = new stdClass();

    $comentario->comentario_post_id = filter_input(INPUT_POST, 'comentario_post_id');
    
    $comentario->id_usuario = $id;
    
    echo (new Comentario())->reverte($comentario);
}


function getUsuarioByComentarioPostId($comentario_post_id){
    
    $comentario = new stdClass();
    
    $comentario->comentario_post_id = $comentario_post_id;
    
    echo (new Comentario())->getUsuarioByComentarioPostId($comentario);
    
}

//Ordenado do Maior para o menor
function getNPostsLessThanMid($n, $m, $g){
    echo (new Publicacao())->getNPostsLessThanMid($n, $m, $g);
}

function getAllPostsGreaterThanNid($n, $g){
    echo (new Publicacao())->getAllPostsGreaterThanNid($n, $g);
}

function getNPosts($n, $g){
    echo (new Publicacao())->getNPosts($n, $g);
}

//Se enviar um post de like existente, o mesmo é apagado
function postLaike (){
    
    $id = (new Auth)->Authorization();
    
    if ($id == false){
        echo MsgEnum::JSON_UNAUTHORIZED;
        return;
    }    
    
    $laike = new stdClass();
    
    $laike->usuario_id = $id;
    
    $laike->post_id = filter_input(INPUT_POST, 'post_id');
    
    echo (new Laike())->post($laike);
    
}

function getCntLaikesAndUserFlagByPostIdAndUserId ($post_id, $usuario_id){
    
    $laike = new stdClass();
    
    $laike->usuario_id = $usuario_id;
    
    $laike->post_id = $post_id;
    
    echo (new Laike())->getCntLaikesAndUserFlagByPostIdAndUserId($laike);
}

function getUsuarioByPostId($post_id){
    echo (new Publicacao())->getUsuarioByPostId($post_id);
}

function postDeletePublicacao (){
    
    $id = (new Auth)->Authorization();
    
    if ($id == false){
        echo MsgEnum::JSON_UNAUTHORIZED;
        return;
    }    
    
    $delete = new stdClass();
           
    $delete->usuario_id = $id;
    
    $delete->post_id = filter_input(INPUT_POST, 'post_id');
    
    echo (new Publicacao())->delete($delete);
}

function postDeletePublicacaoReverte (){
   
    $id = (new Auth)->Authorization();
    
    if ($id == false){
        echo MsgEnum::JSON_UNAUTHORIZED;
        return;
    }    
    
    $reverte = new stdClass();
           
    $reverte->usuario_id = $id;
    
    $reverte->post_id = filter_input(INPUT_POST, 'post_id');
    
    echo (new Publicacao())->reverte($reverte);
}

function postEnquete(){
    
    $id = (new Auth)->Authorization();
    
    if ($id == false){
        echo MsgEnum::JSON_UNAUTHORIZED;
        return;
    }    
    
    $enquete = new stdClass();
    $enquete->opt = array ("", "", "", "", "", "");
    
    $enquete->qtd_opt = filter_input(INPUT_POST, 'qtd_opt');
    
    for ($i = 1; $i <= $enquete->qtd_opt; $i++){
        $enquete->opt[$i] = filter_input(INPUT_POST, 'opt_'.$i);
    }
    
    $enquete->usuario_id = $id;
    $enquete->titulo = filter_input(INPUT_POST, 'titulo');
    $enquete->base64_string = filter_input(INPUT_POST, 'imagem');
    
    echo (new Enquete())->post($enquete);
    
}

function getEnqueteById($enquete_id, $usuario_id){
    echo (new Enquete())->getById($enquete_id, $usuario_id);
}

function getEnquete($usuario_id, $last_enquete_id, $g){
    echo (new Enquete())->get($usuario_id, $last_enquete_id, $g);
}

function postVoto(){
    
    $id = (new Auth)->Authorization();
    
    if ($id == false){
        echo MsgEnum::JSON_UNAUTHORIZED;
        return;
    }    
    
    $voto = new stdClass();
    $voto->usuario_id = $id;
    $voto->enquete_id = filter_input(INPUT_POST, 'enquete_id');
    $voto->voto = filter_input(INPUT_POST, 'voto');
	
    echo (new Enquete())->postVoto($voto);
}

function getUsuarioById($id){
    echo (new Usuario())->getUsuarioById($id);
}


function getBuscaUsuario ($nome){
    echo (new Busca())->get($nome);
}

function curiarPost($id){
    echo (new Curiar())->post($id);
}

function curiarEnquete($id){
    echo (new Curiar())->enquete($id);
}

function postUpdateLastAccess(){
    
    $id = (new Auth)->Authorization();
    
    if ($id == false){
        return;
    }    
    
    (new Usuario())->updateLastAccess($id);

}

function getLastAccess($id){

    echo (new Usuario())->getLastAccess($id);

}

function getGrupos(){
    echo Database::getGrupos();
}

function getAllPosts($g){
    echo (new Publicacao())->getAllPosts($g);
}

function getPostById($id){
    echo (new Publicacao())->getPostById($id);
}

function adminGetPostById($id){
    echo (new Publicacao())->adminGetPostById($id);
}

function getStatus(){
    
    $id = (new Auth)->Authorization();
    
    if ($id === false){
        echo MsgEnum::JSON_UNAUTHORIZED;
        return;
    }
    
    echo (new Usuario())->getStatus($id);
    
}

function logout(){
    
    $id = (new Auth)->Authorization();
 
    if ($id != false){
    
        (new Auth())->expireSession($id);

    }
}