<?php

require_once 'header.php';

class Proposta {

    public function post() {

        $sql = "INSERT INTO tb_post (comentario, usuario_id, ramo_id, tipo) " .
                "values (:comentario, :usuario_id, :ramo_id, 0)";

        $conn = Database::getConn();

        $stmt = $conn->prepare($sql);
        

        $stmt->bindParam("comentario", filter_input(INPUT_POST, 'comentario'));
        
        $stmt->bindParam("usuario_id", filter_input(INPUT_POST, 'usuario_id'));
        
        $stmt->bindParam("ramo_id", filter_input(INPUT_POST, 'ramo_id'));

        if ($stmt->execute()) {
            
            return MsgEnum::SUCESO;
            
        } else{
            
            return MsgEnum::ERRO;
            
        }
    }

}
