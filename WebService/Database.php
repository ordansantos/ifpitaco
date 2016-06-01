<?php

class Database {
    
    public static $host = 'localhost';
    
    public static $db_name = 'bd_ifpitaco';
    
    private static $username = 'root';
    
    private static $password = 'ifpbinfo';
    
    public static function getConn() {
        
       
		return new PDO('mysql:host='.Database::$host.';dbname='.Database::$db_name, 
                        Database::$username, Database::$password, 
                        [PDO::MYSQL_ATTR_INIT_COMMAND =>
                        "SET NAMES utf8"]);
    }
  
   public static function getRamos(){
       
       	$stmt = Database::getConn()->query("SELECT * FROM tb_ramo");
	
		$result = $stmt->fetchAll(PDO::FETCH_OBJ);
	
		return '{"ramos":'.utf8_encode(json_encode($result))."}";	
        
   }

   public static function getGrupos(){
       
       	$stmt = Database::getConn()->query("SELECT * FROM tb_grupos");
	
	$result = $stmt->fetchAll(PDO::FETCH_OBJ);
	
	return '{"grupos":'.utf8_encode(json_encode($result))."}";	
        
   }
   
}
