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
    
}
