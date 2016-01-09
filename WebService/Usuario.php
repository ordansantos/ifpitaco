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
    
    private function addUsuarioEntry($nm_usuario){
        $conn = Database::getConn();
        $sql = "INSERT INTO tb_usuario (nm_usuario)
                VALUES (:nm_usuario)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam("nm_usuario", $nm_usuario);
        $stmt->execute();
        return $conn->lastInsertId();
    }
    
    public function cadastrarSystem($usuario){
        $check = $this->check($usuario);

        if ($check !== true) {
            return '{"status":"error", "msg":"'.$check.'"}';
        }
        
	$sql = "INSERT INTO 
                tb_login_system (senha, email, id_usuario) 
                values (:senha, :email, :id_usuario)";

	$conn = Database::getConn();
	
	$stmt = $conn->prepare($sql);
        
        $id_usuario = $this->addUsuarioEntry($usuario->name);

        $usuario->senha = (new Auth())->cryptographPass($usuario->senha);
	$stmt->bindParam("senha", $usuario->senha);
	$stmt->bindParam("email", $usuario->email);
        $stmt->bindParam("id_usuario", $id_usuario);
        
	if ($stmt->execute()) {
            
            $this->addUserToTbLastAccess($id_usuario);
            
            $token = (new Auth())->getNewToken($id_usuario);
            
            return '{"status":"success", "id_usuario":"'.$id_usuario.'", "token":"'.$token.'"}';
        } else {
            return '{"status":"error","msg":"erro"}';
        }
    }
    
    public function cadastrarFb($token){

        $graph_url = "https://graph.facebook.com/me?fields=id,name&access_token=" . $token;
        
        $user_fb = json_decode(file_get_contents($graph_url));
	
	if( isset($user_fb->name) && $user_fb->name &&
            isset($user_fb->id) && $user_fb->id){
            
            $id_usuario = $this->addUsuarioEntry($user_fb->name);
            
            $conn = Database::getConn();
            
            $sql = "INSERT INTO tb_login_fb (id_usuario, id_usuario_fb)
                    VALUES (:id_usuario, :id_usuario_fb)";
            
            $stmt = $conn->prepare($sql);
            
            $stmt->bindParam("id_usuario", $id_usuario);
            $stmt->bindParam("id_usuario_fb", $user_fb->id);
            
            if ($stmt->execute()){
                
                $this->addUserToTbLastAccess($id_usuario);
                
                $picture = "http://graph.facebook.com/".$user_fb->id."/picture?type=large";
                
                $this->addImagePerfilEntry($id_usuario, $picture);
                
                $token = (new Auth())->getNewToken($id_usuario);
                
                return '{"status":"success", "id_usuario":"'.$id_usuario.'", "token":"'.$token.'"}';
            } else{
                return '{"status":"error","msg":"erro"}';
            }
		
	} else{
            return '{"status":"Token inválido","msg":"erro"}';
        }
    }
    
    private function check ($usuario){
        
        if (strlen($usuario->name) == 0 || strlen($usuario->senha) == 0 || strlen($usuario->email) == 0){
            return MsgEnum::STRING_VAZIA;
        }
	
	if (strlen($usuario->senha) < 6){
            return MsgEnum::SENHA_INVALIDA;
	}
        
        if ($this->existenteSystem($usuario) === true){
           
            return MsgEnum::EMAIL_EXISTENTE;
        }
        
        return true;
    }
    
    private function existenteSystem($usuario){
	$sql = "SELECT * FROM (tb_login_system) WHERE email = :email";
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
    
    // Image stored in system
    public function saveFotoPerfil($id, $foto){
	$path = $this->getFotoPath($foto);
        if ($foto->modify){
            $this->updateImage ($id, $path);
        } else{
            $this->addImagePerfilEntry($id, $path);
        }
    }   
    
    public function updateImage($id, $path){
        $sql = "UPDATE tb_imagem_usuario SET perfil = :perfil WHERE usuario_id = :id";
	$conn = Database::getConn();
	$stmt = $conn->prepare($sql);
	$stmt->bindParam("id", $id);
	$stmt->bindParam("perfil", $path);
	$stmt->execute();
    }
    
    public function addImagePerfilEntry($id, $path){
        $sql = "INSERT INTO tb_imagem_usuario (usuario_id, perfil) values (:id, :perfil)";
	$conn = Database::getConn();
	$stmt = $conn->prepare($sql);
	$stmt->bindParam("id", $id);
	$stmt->bindParam("perfil", $path);
	$stmt->execute();
    }
    
    private function getFotoPath ($foto){
        
	if ($foto->base64_string){

            return Image::saveThumbnail($foto->base64_string, $foto->x_percent, 
                    $foto->y_percent, $foto->w_percent, $foto->h_percent);                            
	}
        
        return Image::getDefaultPath();
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
            if ($this->isFromFb($id)){
                return '{"status":"fb_uncomplete"}';
            } else{
                return '{"status":"uncomplete"}';
            }
        }

        return '{"status":"success", "data": ' . $this->getUsuarioById($id) . '}';
    }
    
    private function isFromFb($id){
        
        $sql = "SELECT * FROM tb_login_fb WHERE id_usuario = :id_usuario";
        $conn = Database::getConn();
        $stmt = $conn->prepare($sql);
        $stmt->bindParam("id_usuario", $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        if ($result){
            return true;
        } else{
            return false;
        }
    }
    
}

