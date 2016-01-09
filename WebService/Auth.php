<?php

class Auth{
    
    // in seconds
    private static $token_expire_time = 60 * 60 * 24 * 3; 
    
    private function generate(){
        return md5(uniqid(rand(), true));
    }
    
    public function system_login($login){

        $sql = "SELECT senha, id_usuario FROM tb_login_system
                WHERE email = :email";
        
	$conn = Database::getConn();
	
        $stmt = $conn->prepare($sql);
	
        $stmt->bindParam("email", $login->email);
	
        $stmt->execute();
	
        $usuario = $stmt->fetch(PDO::FETCH_OBJ);
	
        if (!$usuario){
            return '{"msg":"Email não cadastrado", "status":"error"}';
        }
           
        // Hashing the password with its hash as the salt returns the same hash
        if (hash_equals($usuario->senha, crypt($login->senha, $usuario->senha))){
            (new Usuario())->updateLastAccess($usuario->id_usuario);
            $id_usuario = $usuario->id_usuario;
            $token = $this->getNewToken($id_usuario);
            return '{"status":"success", "token":"'.$token.'", "id_usuario":"'.$id_usuario.'"}';
        }
        
        return'{"msg":"Senha incorreta", "status":"error"}';
        
    }
    
    public function fb_login($token){
        
        $graph_url = "https://graph.facebook.com/me?fields=id&access_token=" . $token;
        
        $user_fb = json_decode(file_get_contents($graph_url));
	
	if(isset($user_fb->id) && $user_fb->id){
            
            $conn = Database::getConn();
            $sql = "SELECT id_usuario FROM tb_login_fb WHERE id_usuario_fb = :id_usuario_fb";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam("id_usuario_fb", $user_fb->id);
            $stmt->execute();
	
            $usuario = $stmt->fetch(PDO::FETCH_OBJ);
            
            if (!$usuario){
                return (new Usuario())->cadastrarFb($token);
            } else{
                $system_token = $this->getNewToken($usuario->id_usuario);
                return '{"status":"success", "token":"'.$system_token.'", "id_usuario":"'.$usuario->id_usuario.'"}';
            }
            
	} else{
            return '{"status":"error","msg":"Token inválido"}';
        }
    }
    
    private function updateTokenTime($id_usuario){
        $conn = Database::getConn();
        $sql = "UPDATE tb_token SET time = now() WHERE id_usuario = :id_usuario";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam ('id_usuario', $id_usuario);
        $stmt->execute();
    }
    
    public function Authorization(){
        
        if (!filter_input(INPUT_POST, 'token')) return false;
        
        $token = filter_input(INPUT_POST, 'token');
        
        $sql = "SELECT time, id_usuario FROM tb_token WHERE token = :token";
        
	$conn = Database::getConn();
	
        $stmt = $conn->prepare($sql);

        $stmt->bindParam("token", $token);
	
        $stmt->execute();
	
        $result = $stmt->fetch(PDO::FETCH_OBJ);
	
        if (!$result || !$this->isTokenExpired($result->time)){
            return false;
        }
        $this->updateTokenTime($result->id_usuario);
        return $result->id_usuario;
    }
    
    private function isTokenExpired($time){
        $now = (new DateTime())->getTimestamp();
        $token_time = (new DateTime($time))->getTimestamp();
        return ($now - $token_time) < self::$token_expire_time;
    }
    
    public function getNewToken($id_usuario){
        $this->expireSession($id_usuario);
        $conn = Database::getConn();
        $sql = 'INSERT INTO tb_token (id_usuario, token) VALUES (:id_usuario, :token)';
        $token = $this->generate();
        $stmt = $conn->prepare($sql);
        $stmt->bindParam('id_usuario', $id_usuario);
        $stmt->bindParam('token', $token);
        $stmt->execute();
        
        return $token;
    }
    
    public function cryptographPass ($password){
        
        // A higher "cost" is more secure but consumes more processing power
        $cost = 10;
        
        // Create a random salt
        $salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');
        
        // Prefix information about the hash so PHP knows how to verify it later.
        // "$2a$" Means we're using the Blowfish algorithm. The following two digits are the cost parameter.
        $salt = sprintf("$2a$%02d$", $cost) . $salt;
        
        // Hash the password with the salt
        $hash = crypt($password, $salt);
        
        return $hash;
        
    }
    
    public function expireSession ($id){
        $conn = Database::getConn();
        $sql = "DELETE FROM tb_token WHERE id_usuario = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam("id", $id);
        $stmt->execute();
    }
    
}