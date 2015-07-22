<?php

class Comentario {
    
    
    public function post($comentario){

	if (strlen($comentario->comentario) == 0 || trim($comentario->comentario) == ''){
		return MsgEnum::STRING_VAZIA;
	}
	
	$sql = "INSERT INTO tb_comentario_post "
                . "(usuario_id, post_id, comentario) "
                . "values (:usuario_id, :post_id, :comentario)";
	
	$conn = Database::getConn();
	
	$stmt = $conn->prepare($sql);
	
	$stmt->bindParam("usuario_id", $comentario->usuario_id);
	$stmt->bindParam("post_id", $comentario->post_id);
	$stmt->bindParam("comentario", $comentario->comentario);
	
	if ($stmt->execute()) {
            return MsgEnum::SUCESSO;
        } else {
            return MsgEnum::ERRO;
        }
    }
    
    public function delete ($comentario){
        
        $conn = Database::getConn();
	$sql = "DELETE FROM tb_comentario_post WHERE comentario_post_id = :comentario_post_id";
	$stmt = $conn->prepare($sql);
	$stmt->bindParam ('comentario_post_id', $comentario->comentario_post_id);
	
	if ($stmt->execute()) {
            return MsgEnum::SUCESSO;
        } else {
            return MsgEnum::ERRO;
        }
    }
    
    public function getComentariosById($id){
        
	if ( (new Publicacao())->isTherePublicacao($id) == 0){
		return '{"flag":"0"}';
	}
	
	$conn = Database::getConn();
	
	$sql = "SELECT nm_usuario, id_usuario, 
			comentario, CONVERT_TZ(`data_hora`, @@session.time_zone, '+00:00') 
                        as data_hora, comentario_post_id, perfil FROM tb_usuario,
			tb_comentario_post, tb_imagem_usuario WHERE 
                        tb_comentario_post.usuario_id = id_usuario 
			AND tb_imagem_usuario.usuario_id = id_usuario 
                        AND post_id = :id ORDER BY comentario_post_id";
	
	$stmt = $conn->prepare($sql);
	
	$stmt->bindParam ('id', $id);
	
	$stmt->execute();
        
	$result = $stmt->fetchAll(PDO::FETCH_OBJ);
        
	echo '{ "flag": "1", "comentarios":'.utf8_encode(json_encode($result))."}";
        
        
    }
    
    function getUsuarioByComentarioPostId($comentario){
            $conn = Database::getConn();
            $sql = "SELECT usuario_id FROM tb_comentario_post WHERE comentario_post_id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam ('id', $comentario->comentario_post_id);
            $stmt->execute();
            $result = $stmt->fetch();
            return $result['usuario_id'];
    }
   
    
}