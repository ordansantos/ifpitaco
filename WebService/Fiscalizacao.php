<?php


class Fiscalizacao{
    
    
    public function post (){
        
        $conn = getConn();
        
        $sql = "INSERT INTO tb_post (comentario, usuario_id, ramo_id, tipo) " .
                "values (:comentario, :usuario_id, :ramo_id, 1)";

	$stmt = $conn->prepare($sql);
	
	$stmt->bindParam("comentario", filter_input(INPUT_POST, 'comentario'));
	$stmt->bindParam("usuario_id", filter_input(INPUT_POST, 'usuario_id'));
	$stmt->bindParam("ramo_id", filter_input(INPUT_POST, 'ramo_id'));

	if ($stmt->execute()) {
            
            if ($this->trySaveImage($conn) === false){
                return MsgEnum::IMAGEM_INVALIDA;
            }
            
            return MsgEnum::SUCESSO;
            
        } else {
            
            return MsgEnum::ERRO;
            
        }
    }
    
    private function trySaveImage ($conn){
        
        $base64_string = filter_input(INPUT_POST, 'imagem');
        
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
