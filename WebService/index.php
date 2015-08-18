<?php

require_once 'header.php';

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
//$app->response()->header('Content-Type', 'application/json;charset=utf-8');

$app->get('/', function () { 
   echo 'Web Service is working...';
});

//Envia uma proposta
$app->post('/postProposta/', 'postProposta' );
//Verifica se o login está correto
$app->post('/postLogin/', 'postLogin');
//Cadastra um novo usuário
$app->post('/postUsuario/', 'postUsuario');
//Envia uma fiscalização
$app->post('/postFiscalizacao/', 'postFiscalizacao');
//Envia um comentário de um post
$app->post('/postComentario/', 'postComentario');
//Delete um comentário específico
$app->post('/postDeleteComentario/', 'postDeleteComentario');
//Envia um 'laike' de um post
$app->post('/postLaike/', 'postLaike');
//Deleta um post
$app->post('/postDeletePublicacao/', 'postDeletePublicacao');
//Envia uma nova Enquete
$app->post('/postEnquete/', 'postEnquete');
//Envia o voto de uma enquete
$app->post('/postVoto/', 'postVoto');

//$app->post('/alterarDados/', 'alterarDados');

$app->post('/postUpdateLastAccess/', 'postUpdateLastAccess');

//Retorna os ramos
$app->get('/getRamos/', 'getRamos');
//Retorna o nome de usuário usando seu id
$app->get('/getNomeById/:id', 'getNomeById');
//Retorna a foto de perfil usando o id do usuário
$app->get('/getFotoPerfilById/:id', 'getFotoPerfilById');
//Retorna os comentários de um post e também uma flag, que responde se o post foi deletado
$app->get('/getComentariosById/:id', 'getComentariosById');
//Retorna o id do usuário que realizou o comentário
$app->get('/getUsuarioByComentarioPostId/:comentario_post_id', 'getUsuarioByComentarioPostId');
//Retorna N posts inseridos antes o id do post M
$app->get('/getNPostsLessThanMid/:n/:m/:g', 'getNPostsLessThanMid');
//Retorna os posts inseridos após o id do post N
$app->get('/getAllPostsGreaterThanNid/:n/:g', 'getAllPostsGreaterThanNid');
//Retorna os N últimos Posts
$app->get('/getNPosts/:n/:g', 'getNPosts');
//Retorna a quantidade de likes do post e uma flag, que responde se o usuário curtiu
$app->get('/getCntLaikesAndUserFlagByPostIdAndUserId/:post_id/:usuario_id', getCntLaikesAndUserFlagByPostIdAndUserId);
//Retorna o usuário que criou o post
$app->get('/getUsuarioByPostId/:post_id', 'getUsuarioByPostId');
//Retorna uma enquete
$app->get('/getEnquete/:usuario_id/:last_enquete_id/:g', 'getEnquete');
//Retorna uma enquete pelo id

$app->get('/getEnqueteById/:enquete_id', 'getEnqueteById');
//Retorna o id de todas as enquetes

$app->get('/getUsuarioById/:usuario_id', 'getUsuarioById');
//Busca um usuário em relação a um nome
$app->get('/getBuscaUsuario/:nome', 'getBuscaUsuario');

$app->get('/curiarPost/:id', 'curiarPost');

$app->get('/curiarEnquete/:id', 'curiarEnquete');

$app->get('/getLastAccess/:id', 'getLastAccess');

$app->run();


function postProposta (){
    
    $proposta = new stdClass();
    
    $proposta->comentario = filter_input(INPUT_POST, 'comentario');
    $proposta->usuario_id = filter_input(INPUT_POST, 'usuario_id');
    $proposta->ramo_id = filter_input(INPUT_POST, 'ramo_id');
    
    echo (new Proposta())->post($proposta);
}

function postFiscalizacao(){
    
    $fiscalizacao = new stdClass();
    
    $fiscalizacao->comentario = filter_input(INPUT_POST, 'comentario');
    $fiscalizacao->usuario_id = filter_input(INPUT_POST, 'usuario_id');
    $fiscalizacao->ramo_id = filter_input(INPUT_POST, 'ramo_id');
    $fiscalizacao->base64_string = filter_input(INPUT_POST, 'imagem');
    
    echo (new Fiscalizacao())->post($fiscalizacao);
}


function postUsuario(){
    
    $usuario = new stdClass();
    
    $usuario->name = filter_input(INPUT_POST, 'nm_usuario');
    $usuario->senha = filter_input(INPUT_POST, 'senha');
    $usuario->email = filter_input(INPUT_POST, 'email');
    $$usuario->grupo = filter_input(INPUT_POST, 'grupo');
    
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
    
    echo (new Usuario())->post($usuario, $foto);

}

function postLogin(){
    
    $login = new stdClass();
    
    $login->email = filter_input(INPUT_POST, 'email');
    $login->senha = filter_input(INPUT_POST, 'senha');
    
    echo (new Usuario())->login($login);
}

function getRamos(){
    echo (new Publicacao())->getRamos();
}

function getNomeById($id){
    echo (new Usuario())->getNomeById($id);
}

//Conexão com o banco
function getConn(){
	return new PDO('mysql:host=localhost;dbname=bd_ifpitaco', 'ifpitaco', 'ifpitacopass', 
	array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
}

function getFotoPerfilById($id){
    echo (new Usuario())->getFotoPerfilById($id);
}

function postComentario (){
        
    $comentario = new stdClass();

    $comentario->usuario_id = filter_input(INPUT_POST, 'usuario_id');
    $comentario->post_id = filter_input(INPUT_POST, 'post_id');
    $comentario->comentario = filter_input(INPUT_POST, 'comentario');

    echo (new Comentario())->post($comentario);
}

function getComentariosById ($id){

    echo (new Comentario())->getComentariosById($id);
    
}

function postDeleteComentario (){
	
    $comentario = new stdClass();

    $comentario->comentario_post_id = filter_input(INPUT_POST, 'comentario_post_id');
    
    $comentario->id_usuario = filter_input(INPUT_POST, 'id_usuario');
    
    echo (new Comentario())->delete($comentario);
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
	
    $laike = new stdClass();
    
    $laike->usuario_id = filter_input(INPUT_POST, 'usuario_id');
    
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
	
    $delete = new stdClass();
           
    $delete->usuario_id = filter_input(INPUT_POST, 'id_usuario');
    
    $delete->post_id = filter_input(INPUT_POST, 'post_id');
    
    echo (new Publicacao())->delete($delete);
}

function postEnquete(){
    
    $enquete = new stdClass();
    $enquete->opt = array ("", "", "", "", "", "");
    
    $enquete->qtd_opt = filter_input(INPUT_POST, 'qtd_opt');
    
    for ($i = 1; $i <= $enquete->qtd_opt; $i++){
        $enquete->opt[$i] = filter_input(INPUT_POST, 'opt_'.$i);
    }
    
    $enquete->usuario_id = filter_input(INPUT_POST, 'id_usuario');
    $enquete->titulo = filter_input(INPUT_POST, 'titulo');
    $enquete->base64_string = filter_input(INPUT_POST, 'imagem');
    
    echo (new Enquete())->post($enquete);
    
}

function getEnqueteById($enquete_id){
    echo (new Enquete())->getById($enquete_id);
}

function getEnquete($usuario_id, $last_enquete_id, $g){
    echo (new Enquete())->get($usuario_id, $last_enquete_id, $g);
}

function postVoto(){

    $voto = new stdClass();
    $voto->usuario_id = filter_input(INPUT_POST, 'usuario_id');
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


/* Salva a foto ao cadastrar um usuário */
/*
function alteraFotoUsuario($id){


	if(!empty($_FILES))
		if ($_FILES['foto']['name']){
			if (isset($_POST['x']) && isset($_POST['y']) && isset($_POST['w']) && isset($_POST['h']) ){
				list($width, $height) = getimagesize($_FILES['foto']['tmp_name']);
				$url = sendToCloudinary120_120($_FILES['foto']['tmp_name'], $_POST['x'] * $width, $_POST['y'] * $height, $_POST['w'] * $width, $_POST['h'] * $height);
			
				$sql = "UPDATE tb_imagem_usuario
						SET perfil = :img
						WHERE usuario_id = :id
						";
				$conn = getConn();
				$stmt = $conn->prepare($sql);
				$stmt->bindParam("id", $id);
				$stmt->bindParam("img", $url);
				$stmt->execute();
				$conn = null;
			}
	}

	
	
}

function alterarDados(){

	$name = $_POST['nm_usuario'];

	$usuario_tipo = $_POST['usuario_tipo'];

	$id = $_POST['usuario_id'];

	$curso = '';
	$ano_periodo = '';
	$grau_academico = '';
	
	if(isset($_POST['curso']))
		$curso = $_POST['curso'];

	if(isset($_POST['ano_periodo']))
		$ano_periodo = $_POST['ano_periodo'];

	if(isset($_POST['grau_academico']))
		$grau_academico = $_POST['grau_academico'];

	if (strlen($name) == 0){
		echo MsgEnum::STRING_VAZIA;
		return;
	}
	
	$sql = "UPDATE tb_usuario 
			SET  nm_usuario = :nm_usuario, usuario_tipo = :usuario_tipo, 
			curso = :curso, ano_periodo = :ano_periodo, grau_academico = :grau_academico
			WHERE id_usuario = :id";
	
	$conn = getConn();
	
	$stmt = $conn->prepare($sql);
	
	
	$stmt->bindParam("nm_usuario", $name);

	$stmt->bindParam("usuario_tipo", $usuario_tipo);
	$stmt->bindParam("curso", $curso);
	$stmt->bindParam("ano_periodo", $ano_periodo);
	$stmt->bindParam("grau_academico", $grau_academico);
	$stmt->bindParam("id", $id);
	

	if ($stmt->execute()){
		alteraFotoUsuario($id);
		echo MsgEnum::SUCESSO;
	}
	
	else
		echo MsgEnum::ERRO;
	
	$conn = null;
}
*/

function curiarPost($id){
    echo (new Curiar())->post($id);
}

function curiarEnquete($id){
    echo (new Curiar())->enquete($id);
}

function postUpdateLastAccess(){
    
    $id = filter_input(INPUT_POST, 'usuario_id');
    
    (new Usuario())->updateLastAccess($id);

}

function getLastAccess($id){

    echo (new Usuario())->getLastAccess($id);

}



