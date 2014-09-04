

<?php
	require '../Slim/Slim/Slim.php';
	\Slim\Slim::registerAutoloader();
	$app = new \Slim\Slim();
	$app->response()->header('Content-Type', 'application/json;charset=utf-8');
	$app->get('/', function () {
	echo "WebServer";
});


$app->get('/getComentario/','getComentario');
$app->post('/postComentario','postComentario');

$app->run();

function getConn(){
	return new PDO('mysql:host=localhost;dbname=ifpitacodb', 'root', '', array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
}

function getComentario()
{
	$stmt = getConn()->query("SELECT * FROM comentarios");
	$comentarios = $stmt->fetchAll(PDO::FETCH_OBJ);
	echo '{"comentarios":'.utf8_encode(json_encode($comentarios))."}";
}

function postComentario()
{
	
	$request = \Slim\Slim::getInstance()->request();
	$comentario = json_decode($request->getBody());
	
	$sql = "INSERT INTO comentarios (comentario, nome) values (:comentario, :nome) ";
	
	$conn = getConn();
	$stmt = $conn->prepare($sql);
	
	$stmt->bindParam("comentario", $comentario->comentario);
	$stmt->bindParam("nome", $comentario->nome);
	$stmt->execute();
	
	//$comentario->id = $conn->lastInsertId();
	
	//echo json_encode($comentario);

}



