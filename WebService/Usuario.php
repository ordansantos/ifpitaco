<?php

// TODO: Dinamizar a criação de diversos tipos de usuários

class Usuario{
    
    public function post ($usuario, $foto){

        $check = $this->check($usuario);

        if ($check !== true) {
            return $check;
        }

	$sql = "INSERT INTO tb_usuario (nm_usuario, senha, email, usuario_tipo, 
                curso, ano_periodo, grau_academico) values (:nm_usuario, :senha, 
                :email, :usuario_tipo, :curso, :ano_periodo, :grau_academico)";

	$conn = Database::getConn();
	
	$stmt = $conn->prepare($sql);

	$stmt->bindParam("nm_usuario", $usuario->name);
	$stmt->bindParam("senha", $usuario->senha);
	$stmt->bindParam("email", $usuario->email);
	$stmt->bindParam("usuario_tipo", $usuario->usuario_tipo);
	$stmt->bindParam("curso", $usuario->curso);
	$stmt->bindParam("ano_periodo", $usuario->ano_periodo);
	$stmt->bindParam("grau_academico", $usuario->grau_academico);

	if ($stmt->execute()) {
            
            $id = $this->getIdByEmail($usuario->email);
            
            $this->saveFotoPerfil($id, $foto);
            
            $this->addUserToTbLastAccess($id);
            
            return MsgEnum::SUCESSO;
        } else {
            return MsgEnum::ERRO;
        }
    }
    
    private function check ($usuario){
        
        if (strlen($usuario->name) == 0 || strlen($usuario->senha) == 0 || strlen($usuario->email) == 0){
            return MsgEnum::STRING_VAZIA;
        }
	
	if (strlen($usuario->senha) < 6){
            return MsgEnum::SENHA_INVALIDA;
	}
        
        if ($this->existente($usuario) === true){
           
            return MsgEnum::EMAIL_EXISTENTE;
        }
        
        return true;
    }
    
    private function existente($usuario){
	$sql = "SELECT * FROM (tb_usuario) WHERE email = :email";
	$conn = getConn();
	$stmt = $conn->prepare ($sql);
	$stmt->bindParam("email", $usuario->email);
	$stmt->execute();
	$result = $stmt->fetch();
        
	if ($result) {
            return true;
        } else {
            return false;
        }
    }
    
    public function saveFotoPerfil($id, $foto){
        
	$path = $this->getFotoPath($foto);
	$sql = "INSERT INTO tb_imagem_usuario (usuario_id, perfil) values (:id, :perfil)";
	$conn = Database::getConn();
	$stmt = $conn->prepare($sql);
	$stmt->bindParam("id", $id);
	$stmt->bindParam("perfil", $path);
	$stmt->execute();
        
    }
    
    private function getFotoPath ($foto){
        
	if ($foto->base64_string != ''){

            return Image::saveThumbnail($foto->base64_string, $foto->x_percent, 
                    $foto->y_percent, $foto->w_percent, $foto->h_percent);                            
	}
        
        return Image::getDefaultPath();
    }
    
    private function getIdByEmail($email){
        
            $sql = "SELECT id_usuario FROM tb_usuario WHERE email=:email";
            $conn = Database::getConn();
            $stmt = $conn->prepare($sql);
            $stmt->bindParam("email", $email);

            $stmt->execute();

            $result = $stmt->fetch();

            return $result['id_usuario'];
            
    }
    
    public function login($login){

	$sql = "SELECT id_usuario FROM tb_usuario WHERE email = :email AND senha = :senha";
	$conn = getConn();
	$stmt = $conn->prepare($sql);
	$stmt->bindParam("email", $login->email);
	$stmt->bindParam("senha", $login->senha);
	$stmt->execute();
	$result = $stmt->fetch();
	if (!$result) {
            return MsgEnum::ERRO;
        } else {
            
            $this->updateLastAccess($result['id_usuario']);
            return $result['id_usuario'];
            
        }
    }
    
    public function updateLastAccess($id){
        
            $conn = Database::getConn();
            $sql = 'UPDATE tb_last_access SET time = now() WHERE usuario_id = :id';
            $stmt = $conn->prepare($sql);
            $stmt->bindParam('id', $id);
            $stmt->execute();
            
    }
    
    private function addUserToTbLastAccess ($id){
        
        $conn = Database::getConn();
        $sql = 'INSERT INTO tb_last_access (usuario_id) VALUES (:id)';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam('id', $id);
        $stmt->execute();
        
    }
    
    public function getLastAccess ($id){
       
	$sql = 'SELECT time FROM tb_last_access WHERE usuario_id = :id';
	$conn = getConn();
	$stmt = $conn->prepare($sql);
	$stmt->bindParam('id', $id);
	$stmt->execute();
	$time = $stmt->fetch(PDO::FETCH_OBJ);

	$check = utf8_encode(json_encode($this->isOnline($time->time)));
        
	return $check;
        
    }
    
    private function isOnline ($time){

        $now = new DateTime();
        $access = new DateTime($time);

        return ['check' => ($now->getTimestamp() - $access->getTimestamp()) <= 120? 'online' : 'offline'];
    }

    public function getNomeById($id){
	$sql = "SELECT nm_usuario FROM tb_usuario WHERE id_usuario=:id";
	$conn = Database::getConn();
	$stmt = $conn->prepare($sql);
	$stmt->bindParam("id", $id);
	
	$stmt->execute();
	
	$result = $stmt->fetch();
	
	return $result['nm_usuario'];
    }
    
    public function getFotoPerfilById($id){
	$sql = "
	SELECT perfil FROM tb_imagem_usuario, tb_usuario
	WHERE tb_imagem_usuario.usuario_id = 
        tb_usuario.id_usuario AND tb_usuario.id_usuario = :id";
	
        $conn = Database::getConn();
	$stmt = $conn->prepare($sql);
	$stmt->bindParam("id", $id);
	
	$stmt->execute();
	
	$result = $stmt->fetch();
	
	return $result['perfil'];
    }
    
}

