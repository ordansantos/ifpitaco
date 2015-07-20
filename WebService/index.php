<?php

require_once 'header.php';

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
//$app->response()->header('Content-Type', 'application/json;charset=utf-8');

$app->get('/', function () { 
	echo "It's working";
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

$app->post('/alterarDados/', 'alterarDados');

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
$app->get('/getNPostsLessThanMid/:n/:m', 'getNPostsLessThanMid');
//Retorna os posts inseridos após o id do post N
$app->get('/getAllPostsGreaterThanNid/:n', 'getAllPostsGreaterThanNid');
//Retorna os N últimos Posts
$app->get('/getNPosts/:n', 'getNPosts');
//Retorna a quantidade de likes do post e uma flag, que responde se o usuário curtiu
$app->get('/getCntLaikesAndUserFlagByPostIdAndUserId/:post_id/:usuario_id', getCntLaikesAndUserFlagByPostIdAndUserId);
//Retorna o usuário que criou o post
$app->get('/getUsuarioByPostId/:post_id', 'getUsuarioByPostId');
//Retorna uma enquete pelo id
$app->get('/getEnquete/:enquete_id', 'getEnquete');
//Retorna o id de todas as enquetes
$app->get('/getEnqueteIds/', 'getEnqueteIds');
//Retorna as enquetes que o usuário não votou
$app->get('/getEnqueteIdsWhereUserDidNotVote/:usuario_id', 'getEnqueteIdsWhereUserDidNotVote');
//Retorna as informações de um usuário
$app->get('/getUsuarioById/:usuario_id', 'getUsuarioById');
//Busca um usuário em relação a um nome
$app->get('/getBuscaUsuario/:nome', 'getBuscaUsuario');

$app->get('/curiarPost/:id', 'curiarPost');

$app->get('/curiarEnquete/:id', 'curiarEnquete');

$app->get('/getLastAccess/:id', 'getLastAccess');

$app->run();


/*
 * Envia uma proposta.
Post:
	'comentario'
	'usuario_id'
	'ramo_id'
*/
function postProposta (){
    echo (new Proposta())->post();
}

/*Envia uma imagem para o cloudinary.*/
function sendToCloudinary120_120($path, $x, $y, $w, $h){
	
	$w = intval($w);
	$h = intval($h);
	$x = intval($x);
	$y = intval($y);
	require 'cloudinary_src/Cloudinary.php';
	require 'cloudinary_src/Uploader.php';
	require 'cloudinary_src/Api.php';
	
	\Cloudinary::config(array(
			"cloud_name" => "hikttgesy",
			"api_key" => "259727914439314",
			"api_secret" => "zCpYfezoRI9Zd8rRW6A9ITAsMVA"
	));
	
	
	$img = \Cloudinary\Uploader::upload($path,
		array(
		   array("crop" => "crop",
			"width" => $w, "height" => $h, "x" => $x, "y" => $y),
			array("crop" => "fill",
				"width" => 120, "height" => 120)
		));
	
	/*On heroku: return $img['secure_url'];*/
	return $img['url'];
}


function postFiscalizacao(){
    echo (new Fiscalizacao())->post();
}


function postUsuario(){
	
    echo (new Usuario())->post();

}

/*
 * Faz o login do usuário
POST
	'email'
	'senha'

*/
function postLogin(){

	//Fazendo o select no banco com as informações enviadas
	$sql = "SELECT id_usuario FROM tb_usuario WHERE email = :email AND senha = :senha";
	$conn = getConn();
	$stmt = $conn->prepare($sql);
	$stmt->bindParam("email", $_POST['email']);
	$stmt->bindParam("senha", $_POST['senha']);
	$stmt->execute();
	$result = $stmt->fetch();
	if (!$result) echo MsgEnum::ERRO; else{ echo $result['id_usuario']; updateLastAccess($result['id_usuario']); }
	$conn = null;
}


/*
Json(Array) a Receber:

{"ramos":[
	{"nm_ramo":"","id_ramo":""}
]}
*/
function getRamos(){
	
	$stmt = getConn()->query("SELECT * FROM tb_ramo");
	
	$result = $stmt->fetchAll(PDO::FETCH_OBJ);
	
	echo '{"ramos":'.utf8_encode(json_encode($result))."}";	
}

/*
 * Envia id e retorna o nome de usuário
 */
function getNomeById($id){
	
	$sql = "SELECT nm_usuario FROM tb_usuario WHERE id_usuario=:id";
	$conn = getConn();
	$stmt = $conn->prepare($sql);
	$stmt->bindParam("id", $id);
	
	$stmt->execute();
	
	$result = $stmt->fetch();
	
	echo $result['nm_usuario'];
	$conn = null;
}


//Conexão com o banco
function getConn(){
	return new PDO('mysql:host=localhost;dbname=bd_ifpitaco', 'ifpitaco', 'ifpitacopass', 
	array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
}


function getFotoPerfilById($id){
	
	$sql = "
	SELECT perfil FROM tb_imagem_usuario, tb_usuario
	WHERE tb_imagem_usuario.usuario_id = tb_usuario.id_usuario AND tb_usuario.id_usuario = :id";
	$conn = getConn();
	$stmt = $conn->prepare($sql);
	$stmt->bindParam("id", $id);
	
	$stmt->execute();
	
	$result = $stmt->fetch();
	
	echo $result['perfil'];
	$conn = null;
}

function postComentario (){
	
	$usuario_id = $_POST['usuario_id'];
	$post_id = $_POST['post_id'];
	$comentario = $_POST['comentario'];
	
	if (strlen($comentario) == 0 || trim($comentario) == ''){
		echo ERRO_STRING_VAZIA;
		return;
	}
	
	$sql = "INSERT INTO tb_comentario_post (usuario_id, post_id, comentario) values (:usuario_id, :post_id, :comentario)";
	
	$conn = getConn();
	
	$stmt = $conn->prepare($sql);
	
	$stmt->bindParam("usuario_id", $usuario_id);
	$stmt->bindParam("post_id", $post_id);
	$stmt->bindParam("comentario", $comentario);
	
	if ($stmt->execute())
		echo MsgEnum::SUCESSO;
	else
		echo MsgEnum::ERRO;
	$conn = null;
}

function postExists ($id){
	$conn = getConn();
	$sql = "SELECT EXISTS(SELECT 1 FROM tb_post WHERE post_id = :id) as cnt";
	$stmt = $conn->prepare($sql);
	$stmt->bindParam ('id', $id);
	$stmt->execute();
	$result = $stmt->fetch();
	return $result['cnt'];
	$conn = null;
}

function getComentariosById ($id){

	if (postExists($id) == 0){
		echo '{"flag":"0"}';
		return;
	}
	
	$conn = getConn();
	
	$sql = "SELECT nm_usuario, id_usuario, 
			comentario, CONVERT_TZ(`data_hora`, @@session.time_zone, '+00:00') as data_hora, comentario_post_id, perfil FROM tb_usuario,
			tb_comentario_post, tb_imagem_usuario WHERE tb_comentario_post.usuario_id = id_usuario 
			AND tb_imagem_usuario.usuario_id = id_usuario AND post_id = :id ORDER BY comentario_post_id";
	
	$stmt = $conn->prepare($sql);
	
	$stmt->bindParam ('id', $id);
	
	$stmt->execute();
	$result = $stmt->fetchAll(PDO::FETCH_OBJ);
	echo '{ "flag": "1", "comentarios":'.utf8_encode(json_encode($result))."}";
	$conn = null;
}



function postDeleteComentario (){
	
	$conn = getConn();
	$sql = "DELETE FROM tb_comentario_post WHERE comentario_post_id = :comentario_post_id";
	$stmt = $conn->prepare($sql);
	$stmt->bindParam ('comentario_post_id', $_POST['comentario_post_id']);
	
	if ($stmt->execute())
		echo MsgEnum::SUCESSO;
	else 	
		echo MsgEnum::ERRO;
	
	$conn = null;
}

function getUsuarioByComentarioPostId($comentario_post_id){
	$conn = getConn();
	$sql = "SELECT usuario_id FROM tb_comentario_post WHERE comentario_post_id = :id";
	$stmt = $conn->prepare($sql);
	$stmt->bindParam ('id', $comentario_post_id);
	$stmt->execute();
	$result = $stmt->fetch();
	echo $result['usuario_id'];
	$conn = null;
}

//Ordenado do Maior para o menor
function getNPostsLessThanMid($n, $m){
	
	$conn = getConn();
	
	//Linha utilizada para usar o LIMIT
	$conn->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );

	
	$sql = "SELECT comentario, imagem, nm_usuario, nm_ramo, CONVERT_TZ(`data_hora`, @@session.time_zone, '+00:00') as data_hora, post_id, perfil, tb_post.usuario_id as usuario_id, tipo
			FROM tb_post, tb_usuario, tb_ramo, tb_imagem_usuario
			WHERE ramo_id = id_ramo AND tb_post.usuario_id = tb_usuario.id_usuario AND 
			tb_post.usuario_id = tb_imagem_usuario.usuario_id 
			AND post_id < :m ORDER BY post_id DESC LIMIT :n";
	
	$stmt = $conn->prepare($sql);
	
	$stmt->bindParam('n', $n);
	$stmt->bindParam('m', $m);
	 
    $stmt->execute();
	
	$posts = $stmt->fetchAll(PDO::FETCH_OBJ);

	echo '{"posts":'.utf8_encode(json_encode($posts))."}";
	$conn = null;
}

//Ordenado do menor para o maior
function getAllPostsGreaterThanNid($n){

	$conn = getConn();

	$sql = "SELECT comentario, imagem, nm_usuario, nm_ramo, CONVERT_TZ(`data_hora`, @@session.time_zone, '+00:00') as data_hora, post_id, perfil, tb_post.usuario_id as usuario_id, tipo
			FROM tb_post, tb_usuario, tb_ramo, tb_imagem_usuario
			WHERE ramo_id = id_ramo AND tb_post.usuario_id = tb_usuario.id_usuario AND
			tb_post.usuario_id = tb_imagem_usuario.usuario_id
			AND post_id > :n ORDER BY post_id";

	$stmt = $conn->prepare($sql);

	$stmt->bindParam('n', $n);

	$stmt->execute();

	$posts = $stmt->fetchAll(PDO::FETCH_OBJ);

	echo '{"posts":'.utf8_encode(json_encode($posts))."}";
	$conn = null;
}


//Ordenado do Maior para o menor
function getNPosts($n){
	
	$conn = getConn();
	
	//Linha utilizada para usar o LIMIT
	$conn->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );

	
	$sql = "SELECT comentario, imagem, nm_usuario, nm_ramo, CONVERT_TZ(`data_hora`, @@session.time_zone, '+00:00') as data_hora, post_id, perfil, tb_post.usuario_id as usuario_id, tipo
			FROM tb_post, tb_usuario, tb_ramo, tb_imagem_usuario
			WHERE ramo_id = id_ramo AND tb_post.usuario_id = tb_usuario.id_usuario AND 
			tb_post.usuario_id = tb_imagem_usuario.usuario_id 
			ORDER BY post_id DESC LIMIT :n";
	
	$stmt = $conn->prepare($sql);
	
	$stmt->bindParam('n', $n, PDO::PARAM_INT);
	 
    $stmt->execute();
	
	$posts = $stmt->fetchAll(PDO::FETCH_OBJ);

	echo '{"posts":'.utf8_encode(json_encode($posts))."}";
	$conn = null;
}

function deleteLaike(){
	$conn = getConn();
	$sql = "DELETE FROM tb_laikar WHERE post_id = :post_id AND usuario_id = :usuario_id";
	$stmt = $conn->prepare($sql);
	$stmt->bindParam ('usuario_id', $_POST['usuario_id']);
	$stmt->bindParam ('post_id', $_POST['post_id']);
	
	if ($stmt->execute())
		return MsgEnum::SUCESSO;
	else 	
		return MsgEnum::ERRO;
	$conn = null;
}

//Se enviar um post de like existente, o mesmo é apagado
function postLaike (){
	
	$conn = getConn();
	
	$sql = "INSERT INTO tb_laikar (usuario_id, post_id) values (:usuario_id, :post_id)";
	$stmt = $conn->prepare($sql);
	$stmt->bindParam ('usuario_id', $_POST['usuario_id']);
	$stmt->bindParam ('post_id', $_POST['post_id']);

	if ($stmt->execute())
		echo MsgEnum::SUCESSO;
	else 	
		echo deleteLaike();
	$conn = null;
}

//Flag responde se usuário curtiu

function doesUserLaike($usuario_id, $post_id){
	$conn = getConn();
	$sql = "SELECT EXISTS(SELECT 1 FROM tb_laikar WHERE post_id = :post_id AND usuario_id = :usuario_id) as cnt";
	$stmt = $conn->prepare($sql);
	$stmt->bindParam ('post_id', $post_id);
	$stmt->bindParam ('usuario_id', $usuario_id);
	$stmt->execute();
	$result = $stmt->fetch();
	return $result['cnt'];
	$conn = null;
}

function getCntLaikesAndUserFlagByPostIdAndUserId ($post_id, $usuario_id){
	
	$conn = getConn();
	$sql = "SELECT COUNT(*) as cnt FROM tb_laikar WHERE post_id = :post_id";
	$stmt = $conn->prepare($sql);
	$stmt->bindParam ('post_id', $post_id);
	$stmt->execute();
	$result = $stmt->fetch();
	echo '{"flag":"'.doesUserLaike($usuario_id, $post_id).'", "cnt":"'.$result['cnt'].'"}';
	$conn = null;
}

function getUsuarioByPostId($post_id){
	$conn = getConn();
	$sql = "SELECT usuario_id FROM tb_post WHERE post_id = :id";
	$stmt = $conn->prepare($sql);
	$stmt->bindParam ('id', $post_id);
	$stmt->execute();
	$result = $stmt->fetch();
	echo $result['usuario_id'];
	$conn = null;
}

function postDeletePublicacao (){
	
	$conn = getConn();
	$sql = "DELETE FROM tb_post WHERE post_id = :post_id";
	$stmt = $conn->prepare($sql);
	$stmt->bindParam ('post_id', $_POST['post_id']);
	
	if ($stmt->execute())
		if (deleteComentarioByPost($_POST['post_id'])){
			echo deleteLaikeByPost($_POST['post_id']);
			return;
		}
	
	echo MsgEnum::ERRO;

}


function deleteComentarioByPost ($post_id){

	$conn = getConn();
	$sql = "DELETE FROM tb_comentario_post WHERE post_id = :post_id";
	$stmt = $conn->prepare($sql);
	$stmt->bindParam ('post_id', $_POST['post_id']);

	if ($stmt->execute())
		return MsgEnum::SUCESSO;
	$conn = null;
	return	ERRO;
}

function deleteLaikeByPost ($post_id){

	$conn = getConn();
	$sql = "DELETE FROM tb_laikar WHERE post_id = :post_id";
	$stmt = $conn->prepare($sql);
	$stmt->bindParam ('post_id', $_POST['post_id']);

	if ($stmt->execute())
		return MsgEnum::SUCESSO;
	$conn = null;
	return	ERRO;
}


function saveEnqueteImage($id){
	
	$path = '';
	
	if(!empty($_FILES))
	if ($_FILES['imagem']['name'])
		$path = sendToCloudinary($_FILES['imagem']['tmp_name']);
	
	$sql = "INSERT INTO tb_imagem_enquete (enquete_id, imagem) values (:id, :imagem)";
	$conn = getConn();
	$stmt = $conn->prepare($sql);
	$stmt->bindParam("id", $id);
	$stmt->bindParam("imagem", $path);
	
	$conn = null;
	if ($stmt->execute()) return true;
	
	return false;
}


function postEnquete(){
	
	$conn = getConn();
	
	$sql = "INSERT INTO tb_enquete
			(usuario_id, titulo, opt_1, opt_2, opt_3, opt_4, opt_5, qtd_opt)
			VALUES (:usuario_id, :titulo, :opt_1, :opt_2, :opt_3, :opt_4, :opt_5, :qtd_opt)";
	
	$opt = array ("", "", "", "", "", "");
	
	for ($i = 0; $i < $_POST['qtd_opt']; $i++)
		$opt[$i] = $_POST['opt_'.($i + 1)];
	
	$stmt = $conn->prepare($sql);
	
	$stmt->bindParam('usuario_id', $_POST['usuario_id']);
	$stmt->bindParam('titulo', $_POST['titulo']);
	$stmt->bindParam('qtd_opt', $_POST['qtd_opt']);
	
	for ($i = 0; $i < $_POST['qtd_opt']; $i++)
		$opt[$i] = $_POST['opt_'.($i + 1)];
	
	for ($i = 1; $i <= 5; $i++)
		$stmt->bindParam('opt_'.$i, $opt[$i-1]);	
	
	if ($stmt->execute()){
		$id_enquete = $conn->lastInsertId('id_enquete');
		saveEnqueteImage($id_enquete);
		echo $id_enquete;
	}
	else 
		echo MsgEnum::ERRO;
	$con = null;
}

function getEnquete($id){
	
	$sql = "
		SELECT 
		e.qtd_opt, e.opt_1, e.opt_2, e.opt_3, e.opt_4, e.opt_5, e.titulo, e.id_enquete, e.usuario_id, 
		CONVERT_TZ(`data_hora`, @@session.time_zone, '+00:00') as data_hora,
		i_e.imagem as e_imagem, u.nm_usuario,
		iu.perfil,
		
		(SELECT COUNT(*) FROM tb_enquete_voto WHERE enquete_id = :id AND voto = 1) as qtd_opt_1,
		(SELECT COUNT(*) FROM tb_enquete_voto WHERE enquete_id = :id AND voto = 2) as qtd_opt_2,
		(SELECT COUNT(*) FROM tb_enquete_voto WHERE enquete_id = :id AND voto = 3) as qtd_opt_3,
		(SELECT COUNT(*) FROM tb_enquete_voto WHERE enquete_id = :id AND voto = 4) as qtd_opt_4,
		(SELECT COUNT(*) FROM tb_enquete_voto WHERE enquete_id = :id AND voto = 5) as qtd_opt_5
		
		FROM tb_enquete as e, tb_imagem_enquete as i_e, tb_usuario as u, tb_imagem_usuario as iu
		
		WHERE i_e.enquete_id = e.id_enquete AND u.id_usuario = e.usuario_id AND iu.usuario_id = e.usuario_id 
		AND e.id_enquete = :id ORDER BY e.id_enquete
			";
	
	$conn = getConn();
	$stmt = $conn->prepare($sql);
	$stmt->bindParam("id", $id);
	$stmt->execute();
	$enquete = $stmt->fetchAll(PDO::FETCH_OBJ);
	echo utf8_encode(json_encode($enquete));
	$conn = null;
}

function postVoto(){

	$sql = "INSERT INTO tb_enquete_voto (usuario_id, enquete_id, voto) VALUES (:usuario_id, :enquete_id, :voto)";
	
	$conn = getConn();
	$stmt = $conn->prepare($sql);
	$stmt->bindParam ('usuario_id', $_POST['usuario_id']);
	$stmt->bindParam ('enquete_id', $_POST['enquete_id']);
	$stmt->bindParam ('voto', $_POST['voto']);
	
	if ($stmt->execute())
		echo MsgEnum::SUCESSO;
	else 
		echo MsgEnum::ERRO;
	$conn = null;
}

function getEnqueteIds(){
	
	$sql = "SELECT id_enquete FROM tb_enquete";
	$conn = getConn();
	$stmt = $conn->prepare($sql);
	$stmt->execute();
	$ids = $stmt->fetchAll(PDO::FETCH_OBJ);
	echo '{"ids":'.utf8_encode(json_encode($ids))."}";
	$conn = null;
}

function getEnqueteIdsWhereUserDidNotVote($id){
	
	$sql = "SELECT id_enquete FROM tb_enquete WHERE id_enquete NOT IN (
			SELECT enquete_id FROM tb_enquete_voto WHERE usuario_id = :id)";
	$conn = getConn();
	$stmt = $conn->prepare($sql);
	$stmt->bindParam('id', $id);
	$stmt->execute();
	$ids = $stmt->fetchAll(PDO::FETCH_OBJ);
	echo '{"ids":'.utf8_encode(json_encode($ids))."}";
	$conn = null;
}

function getUsuarioById($id){
	$sql = "SELECT nm_usuario, usuario_tipo, curso, ano_periodo, grau_academico, perfil
			FROM tb_usuario, tb_imagem_usuario
			WHERE usuario_id = :id AND id_usuario = :id";
	$conn = getConn();
	$stmt = $conn->prepare($sql);
	$stmt->bindParam("id", $id);
	$stmt->execute();
	$usuario = $stmt->fetchAll(PDO::FETCH_OBJ);
	echo utf8_encode(json_encode($usuario));
	$conn = null;
}


function getBuscaUsuario ($nome){
	
	$sql = 'SELECT nm_usuario, id_usuario, usuario_tipo, perfil
			FROM tb_usuario, tb_imagem_usuario
			WHERE usuario_id = id_usuario';
	
	$conn = getConn();
	$stmt = $conn->prepare($sql);
	$stmt->execute();
	$usuarios = $stmt->fetchAll(PDO::FETCH_OBJ);
	$conn = null;
	
	$t = strlen($nome);
	$nome = strtolower($nome);
	usort ($usuarios, function($a, $b) use ($nome, $t){
		return  levenshtein($nome, substr(strtolower($a->nm_usuario), 0, $t)) - levenshtein($nome, substr(strtolower($b->nm_usuario), 0, $t));
	});
	
	$usuarios = array_slice($usuarios, 0, 5);
	
	echo '{"usuarios":'.utf8_encode(json_encode($usuarios))."}";
	
}

/* Salva a foto ao cadastrar um usuário */
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

function curiarPost($id){
	$sql = 'SELECT u.id_usuario, i.perfil, u.nm_usuario, u.usuario_tipo 
			FROM tb_usuario as u, tb_imagem_usuario as i, tb_laikar as l
			WHERE u.id_usuario = i.usuario_id AND post_id = :id AND u.id_usuario = l.usuario_id';
	$conn = getConn();
	$stmt = $conn->prepare($sql);
	$stmt->bindParam('id', $id);
	
	$stmt->execute();
	$usuario = $stmt->fetchAll(PDO::FETCH_OBJ);
	echo utf8_encode(json_encode($usuario));
	$conn = null;
}

function curiarEnquete($id){
	
	$sql = '
			SELECT u.id_usuario, i.perfil, u.nm_usuario, u.usuario_tipo, voto
			FROM tb_usuario as u, tb_imagem_usuario as i, tb_enquete_voto as e
			WHERE u.id_usuario = i.usuario_id AND e.usuario_id = u.id_usuario
			AND e.enquete_id = :id';
	
	$conn = getConn();
	$stmt = $conn->prepare($sql);
	$stmt->bindParam('id', $id);
	$stmt->execute();
	$votos = $stmt->fetchAll(PDO::FETCH_OBJ);

	$votos =  '"usuarios":'.utf8_encode(json_encode($votos));

	$sql = '
			SELECT e.qtd_opt, e.opt_1, e.opt_2, e.opt_3, e.opt_4, e.opt_5
			FROM tb_enquete as e
			WHERE id_enquete = :id';
	
	$stmt = $conn->prepare($sql);
	$stmt->bindParam('id', $id);
	$stmt->execute();
	$opts = $stmt->fetch(PDO::FETCH_OBJ);
	$opts = utf8_encode(json_encode($opts));

	$opts = str_replace('{', "", $opts);
	$opts = str_replace('}', "", $opts);

	$json = '{'.$opts.','.$votos.'}';
	echo $json;
	$conn = null;
}

function postUpdateLastAccess(){
	
	$id = $_POST['usuario_id'];
	updateLastAccess($id);
}

function updateLastAccess($id){

	$sql = 'SELECT time FROM tb_last_access WHERE usuario_id = :id';
	$conn = getConn();
	$stmt = $conn->prepare($sql);
	$stmt->bindParam('id', $id);
	$stmt->execute();
	$time = $stmt->fetch(PDO::FETCH_OBJ);
	if (!$time){
		$sql = 'INSERT INTO tb_last_access (usuario_id) VALUES (:id)';
		$stmt = $conn->prepare($sql);
		$stmt->bindParam('id', $id);
		$stmt->execute();
		$conn = null;
		return;
	}
	
	$sql = 'UPDATE tb_last_access SET time = now() WHERE usuario_id = :id';
	$stmt = $conn->prepare($sql);
	$stmt->bindParam('id', $id);
	$stmt->execute();
	$conn = null;
}

function getLastAccess($id){
	$sql = 'SELECT time FROM tb_last_access WHERE usuario_id = :id';
	$conn = getConn();
	$stmt = $conn->prepare($sql);
	$stmt->bindParam('id', $id);
	$stmt->execute();
	$time = $stmt->fetch(PDO::FETCH_OBJ);
	if (!$time){
		$sql = 'INSERT INTO tb_last_access (usuario_id) VALUES (:id)';
		$stmt = $conn->prepare($sql);
		$stmt->bindParam('id', $id);
		$stmt->execute();
		getLastAccess($id);
		$conn = null;
		return;
	}
        

	$check = utf8_encode(json_encode(isOnline($time->time)));
        
	echo $check;
        

}

function isOnline ($time){
    
    $now = new DateTime();
    $access = new DateTime($time);
    
    return ['check' => ($now->getTimestamp() - $access->getTimestamp()) <= 120? 'online' : 'offline'];
}


