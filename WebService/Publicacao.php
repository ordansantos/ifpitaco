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
                tb_post.usuario_id = tb_imagem_usuario.usuario_id 
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
                    tb_post.usuario_id = tb_imagem_usuario.usuario_id
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
                tb_post.usuario_id = tb_imagem_usuario.usuario_id 
                ORDER BY post_id DESC LIMIT :n";

        $stmt = $conn->prepare($sql);

        $stmt->bindParam('n', $n, PDO::PARAM_INT);

        $stmt->execute();

        $posts = $stmt->fetchAll(PDO::FETCH_OBJ);

        return '{"posts":'.utf8_encode(json_encode($posts))."}";
    
    }
    
}