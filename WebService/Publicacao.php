<?php


class Publicacao{
      
   public function getRamos(){
       
       	$stmt = getConn()->query("SELECT * FROM tb_ramo");
	
	$result = $stmt->fetchAll(PDO::FETCH_OBJ);
	
	return '{"ramos":'.utf8_encode(json_encode($result))."}";	
        
   }
   
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
    public function getNPostsLessThanMid($n, $m){

        $conn = Database::getConn();

        //Linha utilizada para usar o LIMIT
        $conn->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );


        $sql = "SELECT comentario, imagem, nm_usuario, nm_ramo, 
                CONVERT_TZ(`data_hora`, @@session.time_zone, '+00:00') 
                as data_hora, post_id, perfil, tb_post.usuario_id as usuario_id, tipo
                FROM tb_post, tb_usuario, tb_ramo, tb_imagem_usuario
                WHERE ramo_id = id_ramo AND tb_post.usuario_id = tb_usuario.id_usuario AND 
                tb_post.usuario_id = tb_imagem_usuario.usuario_id AND tb_post.deletado = 0
                AND post_id < :m ORDER BY post_id DESC LIMIT :n";

        $stmt = $conn->prepare($sql);

        $stmt->bindParam('n', $n);
        $stmt->bindParam('m', $m);

        $stmt->execute();

        $posts = $stmt->fetchAll(PDO::FETCH_OBJ);

        return '{"posts":'.utf8_encode(json_encode($posts))."}";

    }
    
    //Ordenado do menor para o maior
    public function getAllPostsGreaterThanNid($n){

            $conn = Database::getConn();

            $sql = "SELECT comentario, imagem, nm_usuario, nm_ramo, 
                    CONVERT_TZ(`data_hora`, @@session.time_zone, '+00:00') 
                    as data_hora, post_id, perfil, tb_post.usuario_id as usuario_id, tipo
                    FROM tb_post, tb_usuario, tb_ramo, tb_imagem_usuario
                    WHERE ramo_id = id_ramo AND tb_post.usuario_id = tb_usuario.id_usuario AND
                    tb_post.usuario_id = tb_imagem_usuario.usuario_id AND tb_post.deletado = 0
                    AND post_id > :n ORDER BY post_id";

            $stmt = $conn->prepare($sql);

            $stmt->bindParam('n', $n);

            $stmt->execute();

            $posts = $stmt->fetchAll(PDO::FETCH_OBJ);

            return '{"posts":'.utf8_encode(json_encode($posts))."}";

    }

    //Ordenado do Maior para o menor
    public function getNPosts($n){

        $conn = Database::getConn();

        //Linha utilizada para usar o LIMIT
        $conn->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );


        $sql = "SELECT comentario, imagem, nm_usuario, nm_ramo, 
                CONVERT_TZ(`data_hora`, @@session.time_zone, '+00:00') as data_hora, post_id, perfil, tb_post.usuario_id as usuario_id, tipo
                FROM tb_post, tb_usuario, tb_ramo, tb_imagem_usuario
                WHERE ramo_id = id_ramo AND tb_post.usuario_id = tb_usuario.id_usuario AND 
                tb_post.usuario_id = tb_imagem_usuario.usuario_id  AND tb_post.deletado = 0
                ORDER BY post_id DESC LIMIT :n";

        $stmt = $conn->prepare($sql);

        $stmt->bindParam('n', $n, PDO::PARAM_INT);

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
        
        if ($delete->usuario_id != $usuario_post){
            return MsgEnum::ERRO;
        } else{
        
            $conn = Database::getConn();

            $sql = "UPDATE tb_post SET deletado = 1 WHERE post_id = :post_id";
            
            $stmt = $conn->prepare($sql);
            
            $stmt->bindParam ('post_id', $delete->post_id);

            if ($stmt->execute()) {
                echo MsgEnum::SUCESSO;
            } else {
                echo MsgEnum::ERRO;
            }
        }
    }
}