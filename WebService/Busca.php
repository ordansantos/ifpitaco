<?php

class Busca{
    
    public function get($nome){
	
	$sql = 'SELECT nm_usuario, id_usuario, usuario_tipo, perfil
                FROM tb_usuario, tb_imagem_usuario
                WHERE usuario_id = id_usuario';
	
	$conn = Database::getConn();
	$stmt = $conn->prepare($sql);
	$stmt->execute();
	$usuarios = $stmt->fetchAll(PDO::FETCH_OBJ);
	
	$t = strlen($nome);
	$nome_lowercase = strtolower($nome);
	usort ($usuarios, function($a, $b) use ($nome_lowercase, $t){
		return  levenshtein($nome_lowercase, substr(strtolower($a->nm_usuario), 0, $t)) - levenshtein($nome_lowercase, substr(strtolower($b->nm_usuario), 0, $t));
	});
	
	$busca = array_slice($usuarios, 0, 5);
	
	return '{"usuarios":'.utf8_encode(json_encode($busca))."}";
    }
    
}