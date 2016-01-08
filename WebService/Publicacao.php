<?php


class Publicacao{
      
    public function isTherePublicacao ($id){
        $conn = Database::getConn();
        $sql = "SELECT EXISTS(SELECT 1 FROM tb_post WHERE post_id = :id) as cnt";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam ('id', $id);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['cnt'];
    }
    
    //Ordenado do Maior para o menor
    public function getNPostsLessThanMid($n, $m, $g){

        $conn = Database::getConn();

        //Linha utilizada para usar o LIMIT
        $conn->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );


        $sql = "SELECT comentario, imagem, nm_usuario, nm_ramo, 
                CONVERT_TZ(`data_hora`, @@session.time_zone, '+00:00') 
                as data_hora, post_id, perfil, tb_post.usuario_id as usuario_id, tipo
                FROM tb_post, tb_usuario, tb_ramo, tb_imagem_usuario
                WHERE ramo_id = id_ramo AND tb_post.usuario_id = tb_usuario.id_usuario AND 
                tb_post.usuario_id = tb_imagem_usuario.usuario_id AND tb_post.deletado = 0
                AND post_id < :m AND tb_usuario.grupo = :g ORDER BY post_id DESC LIMIT :n";

        $stmt = $conn->prepare($sql);

        $stmt->bindParam('n', $n);
        $stmt->bindParam('m', $m);
        $stmt->bindParam('g', $g);
        
        $stmt->execute();

        $posts = $stmt->fetchAll(PDO::FETCH_OBJ);

        return '{"posts":'.utf8_encode(json_encode($posts))."}";

    }
    
    //Ordenado do menor para o maior
    public function getAllPostsGreaterThanNid($n, $g){

            $conn = Database::getConn();

            $sql = "SELECT comentario, imagem, nm_usuario, nm_ramo, 
                    CONVERT_TZ(`data_hora`, @@session.time_zone, '+00:00') 
                    as data_hora, post_id, perfil, tb_post.usuario_id as usuario_id, tipo
                    FROM tb_post, tb_usuario, tb_ramo, tb_imagem_usuario
                    WHERE ramo_id = id_ramo AND tb_post.usuario_id = tb_usuario.id_usuario AND
                    tb_post.usuario_id = tb_imagem_usuario.usuario_id AND tb_post.deletado = 0
                    AND post_id > :n AND tb_usuario.grupo = :g ORDER BY post_id";

            $stmt = $conn->prepare($sql);

            $stmt->bindParam('n', $n);
            $stmt->bindParam('g', $g);

            $stmt->execute();

            $posts = $stmt->fetchAll(PDO::FETCH_OBJ);

            return '{"posts":'.utf8_encode(json_encode($posts))."}";

    }

    //Ordenado do Maior para o menor
    public function getNPosts($n, $g){

        $conn = Database::getConn();

        //Linha utilizada para usar o LIMIT
        $conn->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );


        $sql = "SELECT comentario, imagem, nm_usuario, nm_ramo, 
                CONVERT_TZ(`data_hora`, @@session.time_zone, '+00:00') as data_hora, post_id, perfil, tb_post.usuario_id as usuario_id, tipo
                FROM tb_post, tb_usuario, tb_ramo, tb_imagem_usuario
                WHERE ramo_id = id_ramo AND tb_post.usuario_id = tb_usuario.id_usuario AND 
                tb_post.usuario_id = tb_imagem_usuario.usuario_id  AND tb_post.deletado = 0
                AND tb_usuario.grupo = :g
                ORDER BY post_id DESC LIMIT :n";

        $stmt = $conn->prepare($sql);

        $stmt->bindParam('n', $n, PDO::PARAM_INT);
        $stmt->bindParam('g', $g);

        $stmt->execute();

        $posts = $stmt->fetchAll(PDO::FETCH_OBJ);

        return '{"posts":'.utf8_encode(json_encode($posts))."}";
    
    }
    
    public function getPostById($id){
        
        $conn = Database::getConn();
        
        $sql = "SELECT comentario, imagem, nm_usuario, nm_ramo, 
                CONVERT_TZ(`data_hora`, @@session.time_zone, '+00:00') as data_hora, post_id, perfil, tb_post.usuario_id as usuario_id, tipo
                FROM tb_post, tb_usuario, tb_ramo, tb_imagem_usuario
                WHERE ramo_id = id_ramo AND tb_post.usuario_id = tb_usuario.id_usuario AND 
                tb_post.usuario_id = tb_imagem_usuario.usuario_id AND tb_post.post_id = :id
                AND tb_post.deletado = 0";
        
        $stmt = $conn->prepare($sql);
        
        $stmt->bindParam('id', $id);
        
        $stmt->execute();
        
        $posts = $stmt->fetch(PDO::FETCH_OBJ);
        
        return utf8_encode(json_encode($posts));
    }
    
    public function adminGetPostById($id){
        
        $conn = Database::getConn();
        
        $sql = "SELECT comentario, imagem, nm_usuario, nm_ramo, deletado,
                CONVERT_TZ(`data_hora`, @@session.time_zone, '+00:00') as data_hora, post_id, perfil, tb_post.usuario_id as usuario_id, tipo
                FROM tb_post, tb_usuario, tb_ramo, tb_imagem_usuario
                WHERE ramo_id = id_ramo AND tb_post.usuario_id = tb_usuario.id_usuario AND 
                tb_post.usuario_id = tb_imagem_usuario.usuario_id AND tb_post.post_id = :id
                ";
        
        $stmt = $conn->prepare($sql);
        
        $stmt->bindParam('id', $id);
        
        $stmt->execute();
        
        $posts = $stmt->fetch(PDO::FETCH_OBJ);
        
        return utf8_encode(json_encode($posts));
    }
    
    public function getAllPosts ($g){
        $conn = Database::getConn();

        $sql = "SELECT comentario, imagem, nm_usuario, nm_ramo, deletado,
                CONVERT_TZ(`data_hora`, @@session.time_zone, '+00:00') as data_hora, post_id, perfil, tb_post.usuario_id as usuario_id, tipo
                FROM tb_post, tb_usuario, tb_ramo, tb_imagem_usuario
                WHERE ramo_id = id_ramo AND tb_post.usuario_id = tb_usuario.id_usuario AND 
                tb_post.usuario_id = tb_imagem_usuario.usuario_id
                AND tb_usuario.grupo = :g
                ORDER BY post_id DESC";

        $stmt = $conn->prepare($sql);

        $stmt->bindParam('g', $g);

        $stmt->execute();

        $posts = $stmt->fetchAll(PDO::FETCH_OBJ);

        return '{"posts":'.utf8_encode(json_encode($posts))."}";
    }
    
    public function getUsuarioByPostId($post_id){
        $conn = Database::getConn();
	$sql = "SELECT usuario_id FROM tb_post WHERE post_id = :id";
	$stmt = $conn->prepare($sql);
	$stmt->bindParam ('id', $post_id);
	$stmt->execute();
	$result = $stmt->fetch();
	return $result['usuario_id'];
    }
    
    public function delete($delete){

        $usuario_post = $this->getUsuarioByPostId($delete->post_id);
        
        if ($delete->usuario_id != $usuario_post && !(new Usuario())->isAdmin($delete->usuario_id)){
            return MsgEnum::JSON_ERROR;
        } else{
        
            $conn = Database::getConn();

            $sql = "UPDATE tb_post SET deletado = 1 WHERE post_id = :post_id";
            
            $stmt = $conn->prepare($sql);
            
            $stmt->bindParam ('post_id', $delete->post_id);

            if ($stmt->execute()) {
                echo MsgEnum::JSON_SUCCESS;
            } else {
                echo MsgEnum::JSON_ERROR;
            }
        }
    }
    
    public function reverte($reverte){

        $usuario_post = $this->getUsuarioByPostId($reverte->post_id);
        
        if ($reverte->usuario_id != $usuario_post && !(new Usuario())->isAdmin($reverte->usuario_id)){
            return MsgEnum::ERRO;
        } else{
        
            $conn = Database::getConn();

            $sql = "UPDATE tb_post SET deletado = 0 WHERE post_id = :post_id";
            
            $stmt = $conn->prepare($sql);
            
            $stmt->bindParam ('post_id', $reverte->post_id);

            if ($stmt->execute()) {
                echo MsgEnum::SUCESSO;
            } else {
                echo MsgEnum::ERRO;
            }
        }
    }
    
}