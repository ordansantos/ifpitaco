<?php

// TODO: Dinamizar a criação de diversos tipos de usuários

class Usuario{
    
    public function post (){
    
        $user = $this->createUser();

        $check = $this->check($user);

        if ($check !== true) {
            return $check;
        }

	$sql = "INSERT INTO tb_usuario (nm_usuario, senha, email, usuario_tipo, 
                curso, ano_periodo, grau_academico) values (:nm_usuario, :senha, 
                :email, :usuario_tipo, :curso, :ano_periodo, :grau_academico)";

	$conn = Database::getConn();
	
	$stmt = $conn->prepare($sql);

	$stmt->bindParam("nm_usuario", $user->name);
	$stmt->bindParam("senha", $user->senha);
	$stmt->bindParam("email", $user->email);
	$stmt->bindParam("usuario_tipo", $user->usuario_tipo);
	$stmt->bindParam("curso", $user->curso);
	$stmt->bindParam("ano_periodo", $user->ano_periodo);
	$stmt->bindParam("grau_academico", $user->grau_academico);

	if ($stmt->execute()) {
            $this->saveFotoPerfil($user->email);
            return MsgEnum::SUCESSO;
        } else {
            return MsgEnum::ERRO;
        }
    }
    
    private function createUser (){
        
        $user = new stdClass();
        
	$user->name = filter_input(INPUT_POST, 'nm_usuario');
	$user->senha = filter_input(INPUT_POST, 'senha');
	$user->email = filter_input(INPUT_POST, 'email');
        
	$user->usuario_tipo = filter_input(INPUT_POST, 'usuario_tipo');
	$user->curso = filter_input(INPUT_POST, 'curso');
	$user->ano_periodo = filter_input(INPUT_POST, 'ano_periodo');
        $user->grau_academico = filter_input(INPUT_POST, 'grau_academico');
        
        return $user;
    }
    
    private function check ($user){
        
        if (strlen($user->name) == 0 || strlen($user->senha) == 0 || strlen($user->email) == 0){
            return MsgEnum::STRING_VAZIA;
        }
	
	if (strlen($user->senha) < 6){
            return MsgEnum::SENHA_INVALIDA;
	}
        
        if ($this->existente($user) === true){
           
            return MsgEnum::EMAIL_EXISTENTE;
        }
        
        return true;
    }
    
    private function existente($user){
	$sql = "SELECT * FROM (tb_usuario) WHERE email = :email";
	$conn = getConn();
	$stmt = $conn->prepare ($sql);
	$stmt->bindParam("email", $user->email);
	$stmt->execute();
	$result = $stmt->fetch();
        
	if ($result) {
            return true;
        } else {
            return false;
        }
    }
    
    public function saveFotoPerfil($email){
        
	//Default
	$novo_nome = $this->getFotoPath();

	$sql = "INSERT INTO tb_imagem_usuario (usuario_id, perfil) values (:id, :perfil)";
        
	$conn = Database::getConn();
	
	$stmt = $conn->prepare($sql);
        
        $id = $this->getIdByEmail($email);

	$stmt->bindParam("id", $id);
        
	$stmt->bindParam("perfil", $novo_nome);
	
	$stmt->execute();
        
    }
    
    private function getFotoPath (){
        
        $default = '../storage/default';
        
	if (($base64_string = filter_input(INPUT_POST, 'imagem')) != ''){

            if (($x_percent = filter_input(INPUT_POST, 'x')) === false){
                return $default;
            }
            
            if (($y_percent = filter_input(INPUT_POST, 'y')) === false){
                return $default;
            }
            
            if (($w_percent = filter_input(INPUT_POST, 'w')) === false){
                return $default;
            }
            
            if (($h_percent = filter_input(INPUT_POST, 'h')) === false){
                return $default;
            }
            

            return Image::saveThumbnail($base64_string, $x_percent, 
                    $y_percent, $w_percent, $h_percent);                            
	}
        
        return $default;
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
    
    
}

