<?php


class Fiscalizacao{
    
    
    public function post ($fiscalizacao){
        
        $conn = Database::getConn();
        
        $sql = "INSERT INTO tb_post (comentario, usuario_id, ramo_id, tipo) " .
                "values (:comentario, :usuario_id, :ramo_id, 1)";

	$stmt = $conn->prepare($sql);
	
        $comentario = StringFilter::getInstance()->filter($fiscalizacao->comentario);
        
	$stmt->bindParam("comentario", $comentario);
	$stmt->bindParam("usuario_id", $fiscalizacao->usuario_id);
	$stmt->bindParam("ramo_id", $fiscalizacao->ramo_id);

	if ($stmt->execute()) {
            
            if ($this->trySaveImage($conn, $fiscalizacao->base64_string) === false){
                return MsgEnum::IMAGEM_INVALIDA;
            }
            
            return MsgEnum::JSON_SUCCESS;
            
        } else {
            
            return MsgEnum::JSON_ERROR;
            
        }
    }
    
    private function trySaveImage ($conn, $base64_string){
        
        if ($base64_string == ''){
            return true;
        }
           
        $url_img = Image::save($base64_string);
        
        if ($url_img === false){
            return false;
        }
    
        if ($url_img != ''){
                $post_id = $conn->lastInsertId('post_id');
                $sql = "UPDATE tb_post SET tipo = 2, imagem = :img WHERE post_id = :post_id";
                $stmt = $conn->prepare ($sql);
                $stmt->bindParam ('post_id', $post_id);
                $stmt->bindParam ('img', $url_img);
                $stmt->execute();
        }
        
        return true;
        
    }
    
}
