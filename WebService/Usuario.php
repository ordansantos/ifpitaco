<?php

// TODO: Dinamizar a criação de diversos tipos de usuários

class Usuario{
    
    // em segundos
    private static $online_interval = 240;
    
    public function completar ($usuario, $foto){



        $sql = "UPDATE tb_usuario
                SET usuario_tipo = :usuario_tipo,
                    curso = :curso,
                    ano_periodo = :ano_periodo,
                    grau_academico = :grau_academico,
                    grupo = :grupo
                WHERE id_usuario = :id_usuario";

	$conn = Database::getConn();
	
	$stmt = $conn->prepare($sql);

	$stmt->bindParam("usuario_tipo", $usuario->usuario_tipo);
	$stmt->bindParam("curso", $usuario->curso);
	$stmt->bindParam("ano_periodo", $usuario->ano_periodo);
	$stmt->bindParam("grau_academico", $usuario->grau_academico);
        $stmt->bindParam("grupo", $usuario->grupo);
        $stmt->bindParam("id_usuario", $usuario->id_usuario);
        
	if ($stmt->execute()) {
            
            $this->saveFotoPerfil($usuario->id_usuario, $foto);
            
            return '{"status":"success"}';
        } else {
            return '{"status":"error"}';
        }
    }
    
    public function cadastrar($usuario){
        $check = $this->check($usuario);

        if ($check !== true) {
            return '{"status":"error", "msg":"'.$check.'"}';
        }
        
	$sql = "INSERT INTO 
                tb_usuario (nm_usuario, senha, email) 
                values (:nm_usuario, :senha, :email)";

	$conn = Database::getConn();
	
	$stmt = $conn->prepare($sql);

	$stmt->bindParam("nm_usuario", $usuario->name);
        $usuario->senha = (new Auth())->cryptographPass($usuario->senha);
	$stmt->bindParam("senha", $usuario->senha);
	$stmt->bindParam("email", $usuario->email);
        
	if ($stmt->execute()) {
            
            $id = $this->getIdByEmail($usuario->email);
            
            $this->addUserToTbLastAccess($id);
            
            $token = (new Auth())->createTokenEntry($id);
            
            return '{"status":"success", "id_usuario":"'.$id.'", "token":"'.$token.'"}';
        } else {
            return '{"status":"error","msg":"erro"}';
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
	$conn = Database::getConn();
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
	$conn = Database::getConn();
	$stmt = $conn->prepare($sql);
	$stmt->bindParam('id', $id);
	$stmt->execute();
	$time = $stmt->fetch(PDO::FETCH_OBJ);

	$check = utf8_encode(json_encode($this->isOnline($time->time)));
        
	return $check;
        
    }
    
    private function isOnline ($time){
     
        $now = (new DateTime())->getTimestamp() ;
        $access = (new DateTime($time))->getTimestamp() ;
       
        return ['check' => ($now - $access) <= self::$online_interval? "online": "offline"];
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
    
    public function getUsuarioById($id){

        $sql = "SELECT id_usuario, nm_usuario, usuario_tipo, curso, ano_periodo, grau_academico, perfil, grupo
                FROM tb_usuario, tb_imagem_usuario
                WHERE usuario_id = :id AND id_usuario = :id";

        $conn = Database::getConn();
        $stmt = $conn->prepare($sql);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_OBJ);
        $usuario->is_admin = ($this->isAdmin($id)) ? 1 : 0;
        return utf8_encode(json_encode($usuario));
    }
    
    public function isAdmin($id){
        
	$sql = "SELECT id_usuario FROM tb_admins WHERE id_usuario = :id";
        
	$conn = Database::getConn();
	
        $stmt = $conn->prepare($sql);
	
        $stmt->bindParam("id", $id);
	
        $stmt->execute();
	
        $usuario = $stmt->fetch(PDO::FETCH_OBJ);
	
        if (!$usuario){
            return false;
        } else{
            return true;
        }
    }
    
    public function isCadastroCompleto($id){
        $sql = "SELECT grupo FROM tb_usuario WHERE id_usuario = :id";
        $conn = Database::getConn();
        $stmt = $conn->prepare($sql);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $grupo = $stmt->fetch(PDO::FETCH_OBJ);
        if (!$grupo || $grupo->grupo == 0){
            return false;
        }
        return true;
    }
    
    
    public function getStatus($id){
        

        if ((new Usuario())->isCadastroCompleto($id) === false) {
            return '{"status":"uncomplete"}';
        }

        return '{"status":"success", "data": ' . $this->getUsuarioById($id) . '}';
    }
    
}

