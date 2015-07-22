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
    
}