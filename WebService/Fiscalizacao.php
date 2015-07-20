<?php


class Fiscalizacao{
    
    
    public function post (){
       
        $sql = "INSERT INTO tb_post (comentario, usuario_id, ramo_id, tipo) " .
                "values (:comentario, :usuario_id, :ramo_id, 1)";

	$conn = getConn();
	$stmt = $conn->prepare($sql);
	
	$stmt->bindParam("comentario", filter_input(INPUT_POST, 'comentario'));
	$stmt->bindParam("usuario_id", filter_input(INPUT_POST, 'usuario_id'));
	$stmt->bindParam("ramo_id", filter_input(INPUT_POST, 'ramo_id'));

	if ($stmt->execute()) {
            $this->trySaveImage($conn);
            return MsgEnum::SUCESSO;
        } else {
            return MsgEnum::ERRO;
        }
    }
    
    private function trySaveImage ($conn){

        $url_img = $this->saveFiscalizacaoImage(filter_input(INPUT_POST, 'imagem'));
        
        if ($url_img != ''){
                $post_id = $conn->lastInsertId('post_id');
                $sql = "UPDATE tb_post SET tipo = 2, imagem = :img WHERE post_id = :post_id";
                $stmt = $conn->prepare ($sql);
                $stmt->bindParam ('post_id', $post_id);
                $stmt->bindParam ('img', $url_img);
                $stmt->execute();
        }
        
    }
    
    private function saveFiscalizacaoImage($base64_string){

            if ($base64_string != ''){
                    $nome = Image::save($base64_string);
                    return $nome;
            }

            return '';
    }
    
}
