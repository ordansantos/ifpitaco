<?php

require_once 'header.php';

class Proposta {

    public function post($proposta) {

        $sql = "INSERT INTO tb_post (comentario, usuario_id, ramo_id, tipo) " .
                "values (:comentario, :usuario_id, :ramo_id, 0)";

        $conn = Database::getConn();

        $stmt = $conn->prepare($sql);
        

        $stmt->bindParam("comentario", $proposta->comentario);
        
        $stmt->bindParam("usuario_id", $proposta->usuario_id);
        
        $stmt->bindParam("ramo_id", $proposta->ramo_id);

        if ($stmt->execute()) {
            
            return MsgEnum::SUCESSO;
            
        } else{
            
            return MsgEnum::ERRO;
            
        }
    }

}
