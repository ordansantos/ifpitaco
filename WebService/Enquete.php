<?php

class Enquete{
    
    public function post($enquete){
   
	$conn = Database::getConn();
	
	$sql = "INSERT INTO tb_enquete
		(usuario_id, titulo, opt_1, opt_2, opt_3, opt_4, opt_5, qtd_opt)
		VALUES (:usuario_id, :titulo, :opt_1, :opt_2, :opt_3, :opt_4, :opt_5, :qtd_opt)";
	
	$stmt = $conn->prepare($sql);
	
	$stmt->bindParam('usuario_id', $enquete->usuario_id);

        $titulo = StringFilter::getInstance()->filter($enquete->titulo);
        
	$stmt->bindParam('titulo', $titulo);
	$stmt->bindParam('qtd_opt', $enquete->qtd_opt);
	
	for ($i = 1; $i <= 5; $i++){
            $enquete->opt[$i] = StringFilter::getInstance()->filter($enquete->opt[$i]);
            $stmt->bindParam('opt_'.$i, $enquete->opt[$i]);	
        }
	
	if ($stmt->execute()){
            $id_enquete = $conn->lastInsertId('id_enquete');
            if ($this->saveImage($id_enquete, $enquete->base64_string) === False){
                return MsgEnum::JSON_ERROR; 
            }
            return '{"status":"success", "id_enquete":"'.$id_enquete.'"}';
	}
	else{
            return MsgEnum::JSON_ERROR;
        }
    }
    
    private function saveImage($id_enquete, $base64_string){
        
        $path = "";
        
        if ($base64_string != ''){
            $path = Image::save($base64_string);
        }
	
	$sql = "INSERT INTO tb_imagem_enquete (enquete_id, imagem) values (:id, :imagem)";
	$conn = Database::getConn();
	$stmt = $conn->prepare($sql);
	$stmt->bindParam("id", $id_enquete);
	$stmt->bindParam("imagem", $path);
        
	if ($stmt->execute()){
            return true;
        } else{
            return false;
        }
        
    }
    
    public function postVoto($voto){
        
	$sql = "INSERT INTO tb_enquete_voto (usuario_id, enquete_id, voto) VALUES (:usuario_id, :enquete_id, :voto)";
	
	$conn = Database::getConn();
	$stmt = $conn->prepare($sql);
	$stmt->bindParam ('usuario_id', $voto->usuario_id);
	$stmt->bindParam ('enquete_id', $voto->enquete_id);
	$stmt->bindParam ('voto', $voto->voto);
	
	if ($stmt->execute()){
            return MsgEnum::JSON_SUCCESS;
        }
	else{
            return MsgEnum::JSON_UNAUTHORIZED;
        }
        
    }
    
    public function getById($enquete_id, $usuario_id){
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
	
	$conn = Database::getConn();
	$stmt = $conn->prepare($sql);
	$stmt->bindParam("id", $enquete_id);
	$stmt->execute();
	$enquete = $stmt->fetch(PDO::FETCH_OBJ);
        $enquete->to_vote = $this->voted($usuario_id, $enquete_id)? 0 : 1;
	return utf8_encode(json_encode($enquete));
    }
    
    public function voted($usuario_id, $enquete_id){
        $sql = "
            SELECT id_enquete FROM tb_enquete 
            WHERE id_enquete NOT IN (SELECT enquete_id FROM tb_enquete_voto WHERE usuario_id = :usuario_id)
            AND id_enquete = :id_enquete
        ";
        $conn = Database::getConn();
        $stmt = $conn->prepare($sql);
        $stmt->bindParam('usuario_id', $usuario_id);
        $stmt->bindParam('id_enquete', $enquete_id);
        $stmt->execute();
        $enquete = $stmt->fetch(PDO::FETCH_OBJ);
        if (!$enquete){
            return true;
        } else{
            return false;
        }
    }
    
    public function get($usuario_id, $last_enquete_id, $g){

        $enquete = $this->getBestEnquete ($usuario_id, $last_enquete_id, $g);

        if ($enquete == false){
            return '{ "is_there": "0" } ';
        }

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

        $conn = Database::getConn();
        $stmt = $conn->prepare($sql);
        $stmt->bindParam("id", $enquete->id);
        $stmt->execute();
        $data = utf8_encode (json_encode($stmt->fetchAll(PDO::FETCH_OBJ)[0]));

        return '{"is_there":"1", "to_vote":"'.$enquete->to_vote.'", "data":'.$data.'}';
    }
    
    private function getBestEnquete ($usuario_id, $last_enquete, $g){

        $enquete_to_vote = $this->getBestToVote($usuario_id, $last_enquete, $g);
        
        if ($enquete_to_vote != false){
            $enquete_to_vote->to_vote = 1;
            return $enquete_to_vote;
        }
        
        $enquete_voted = $this->getBestVoted($last_enquete, $g);

        if ($enquete_voted != false){
            $enquete_voted->to_vote = 0;
            return $enquete_voted;
        }
        
        return false;
    }
    
    private function getBestToVote($usuario_id, $last_enquete, $g){
        
        $enquetes_sem_voto = $this->getEnqueteIdsToVote($usuario_id, $g);
        
        if (!count($enquetes_sem_voto)){
            return false;
        }
        
        $enquete = new stdClass();

        if ($last_enquete == 0 || $enquetes_sem_voto[0]->id_enquete == $last_enquete){
            $enquete->id = $enquetes_sem_voto[count($enquetes_sem_voto) - 1]->id_enquete;
            return $enquete;
        }
        
        $enquete->id = $enquetes_sem_voto[0]->id_enquete;
        
        foreach ($enquetes_sem_voto as $i){
            if ($i->id_enquete < $last_enquete){
                $enquete->id = $i->id_enquete;
            } else{
                break;
            }
        }

        return $enquete;
    }
    
    private function getBestVoted($last_enquete, $g){
        
        $enquetes_com_voto = $this->getEnqueteIds($g);
        
        if (!count($enquetes_com_voto)){
            return false;
        }
        
        $enquete = new stdClass();
        
        if ($last_enquete == 0 || $enquetes_com_voto[0]->id_enquete == $last_enquete){
            $enquete->id = $enquetes_com_voto[count($enquetes_com_voto) - 1]->id_enquete;
            return $enquete;
        }

        foreach ($enquetes_com_voto as $i) {
            if ($i->id_enquete < $last_enquete){
                $enquete->id = $i->id_enquete;
            } else{
                break;
            }
        }

        return $enquete;
        
    }

    private function getEnqueteIds($g){
            $sql = "
                    SELECT id_enquete FROM tb_enquete, tb_usuario
                    WHERE tb_enquete.usuario_id = tb_usuario.id_usuario
                    AND tb_usuario.grupo = :g
                    ";
            $conn = Database::getConn();
            $stmt = $conn->prepare($sql);
            $stmt->bindParam('g', $g);
            $stmt->execute();
            $ids = $stmt->fetchAll(PDO::FETCH_OBJ);
            return $ids;
    }

    private function getEnqueteIdsToVote($usuario_id, $g){

            $sql = "
                SELECT id_enquete FROM tb_enquete, tb_usuario 
                WHERE tb_enquete.usuario_id = tb_usuario.id_usuario
                AND id_enquete NOT IN (SELECT enquete_id FROM tb_enquete_voto 
                                       WHERE usuario_id = :id)
                AND tb_usuario.grupo = :g
            ";
            $conn = Database::getConn();
            $stmt = $conn->prepare($sql);
            $stmt->bindParam('id', $usuario_id);
            $stmt->bindParam('g', $g);
            $stmt->execute();
            $ids = $stmt->fetchAll(PDO::FETCH_OBJ);
            return $ids;

    }
    
    public function getVotos ($id_enquete){
        
        $sql = 'SELECT u.id_usuario, i.perfil, u.nm_usuario, u.usuario_tipo, voto
                FROM tb_usuario as u, tb_imagem_usuario as i, tb_enquete_voto as e
                WHERE u.id_usuario = i.usuario_id AND e.usuario_id = u.id_usuario
                AND e.enquete_id = :id';

        $conn = Database::getConn();
        $stmt = $conn->prepare($sql);
        $stmt->bindParam('id', $id_enquete);
        $stmt->execute();
        $votos = $stmt->fetchAll(PDO::FETCH_OBJ);

        return '"usuarios":'.utf8_encode(json_encode($votos));
            
    }
    
    public function getOpts ($id_enquete){
        
        $conn = Database::getConn();
        
        $sql = 'SELECT e.qtd_opt, e.opt_1, e.opt_2, e.opt_3, e.opt_4, e.opt_5
                FROM tb_enquete as e
                WHERE id_enquete = :id';

        $stmt = $conn->prepare($sql);
        $stmt->bindParam('id', $id_enquete);
        $stmt->execute();
        $opts = $stmt->fetch(PDO::FETCH_OBJ);
        $opts = utf8_encode(json_encode($opts));
    
        $opts = str_replace('{', "", $opts);
        $opts = str_replace('}', "", $opts);
        
        return $opts;
    }
    
    public function getUsuarioByEnquetetId($id_enquete){
        $conn = Database::getConn();
	$sql = "SELECT usuario_id FROM tb_enquete WHERE id_enquete = :id_enquete";
	$stmt = $conn->prepare($sql);
	$stmt->bindParam ('id_enquete', $id_enquete);
	$stmt->execute();
	$result = $stmt->fetch();
	return $result['usuario_id'];
    }
    
    public function delete($delete){

        $usuario_enquete = $this->getUsuarioByEnquetetId($delete->enquete_id);
        
        if ($delete->usuario_id != $usuario_enquete && !(new Usuario())->isAdmin($delete->usuario_id)){
            return MsgEnum::ERRO;
        } else{
        
            $conn = Database::getConn();

            $sql = "UPDATE tb_enquete SET deletado = 1 WHERE enquete_id = :enquete_id";
            
            $stmt = $conn->prepare($sql);
            
            $stmt->bindParam ('enquete_id', $delete->enquete_id);

            if ($stmt->execute()) {
                echo MsgEnum::SUCESSO;
            } else {
                echo MsgEnum::ERRO;
            }
        }
    }
    
    public function reverte($reverte){

        $usuario_enquete = $this->getUsuarioByEnquetetId($reverte->enquete_id);
        
        if ($reverte->usuario_id != $usuario_enquete && !(new Usuario())->isAdmin($reverte->usuario_id)){
            return MsgEnum::ERRO;
        } else{
        
            $conn = Database::getConn();

            $sql = "UPDATE tb_enquete SET deletado = 0 WHERE enquete_id = :enquete_id";
            
            $stmt = $conn->prepare($sql);
            
            $stmt->bindParam ('enquete_id', $reverte->enquete_id);

            if ($stmt->execute()) {
                echo MsgEnum::SUCESSO;
            } else {
                echo MsgEnum::ERRO;
            }
        }
    }
    
}