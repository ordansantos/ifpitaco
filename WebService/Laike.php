<?php

class Laike{
    
    //Se enviar um post de like existente, o mesmo é apagado
    public function post ($laike){
	
	$conn = Database::getConn();
	
	$sql = "INSERT INTO tb_laikar (usuario_id, post_id) values (:usuario_id, :post_id)";
	$stmt = $conn->prepare($sql);
	$stmt->bindParam ('usuario_id', $laike->usuario_id);
	$stmt->bindParam ('post_id', $laike->post_id);

	if ($stmt->execute()) {
            return MsgEnum::SUCESSO;
        } else {
            return $this->delete($laike);
        }
    }
    
    private function delete($laike){
        
        $conn = Database::getConn();
        $sql = "DELETE FROM tb_laikar WHERE post_id = :post_id AND usuario_id = :usuario_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam ('usuario_id', $laike->usuario_id);
        $stmt->bindParam ('post_id', $laike->post_id);

        if ($stmt->execute()) {
            return MsgEnum::SUCESSO;
        } else {
            return MsgEnum::ERRO;
        }
    }
    
    public function getCntLaikesAndUserFlagByPostIdAndUserId ($laike){

            $conn = Database::getConn();
            $sql = "SELECT COUNT(*) as cnt FROM tb_laikar WHERE post_id = :post_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam ('post_id', $laike->post_id);
            $stmt->execute();
            $result = $stmt->fetch();
            return '{"flag":"'. $this->didUserLaike($laike) . 
                    '", "cnt":"'.$result['cnt'].'"}';
    }
    
    //Flag responde se usuário curtiu
    private function didUserLaike($laike){
            $conn = Database::getConn();
            $sql = "SELECT EXISTS(SELECT 1 FROM tb_laikar WHERE post_id = :post_id AND usuario_id = :usuario_id) as cnt";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam ('post_id', $laike->post_id);
            $stmt->bindParam ('usuario_id', $laike->usuario_id);
            $stmt->execute();
            $result = $stmt->fetch();
            return $result['cnt'];
    }
    
    public function getLaikes($id_post){
	$sql = 'SELECT u.id_usuario, i.perfil, u.nm_usuario, u.usuario_tipo 
		FROM tb_usuario as u, tb_imagem_usuario as i, tb_laikar as l
		WHERE u.id_usuario = i.usuario_id AND post_id = :id 
                AND u.id_usuario = l.usuario_id';
        
	$conn = Database::getConn();
	$stmt = $conn->prepare($sql);
	$stmt->bindParam('id', $id_post);
	
	$stmt->execute();
	$usuario = $stmt->fetchAll(PDO::FETCH_OBJ);
	return utf8_encode(json_encode($usuario));
    }
    
}
