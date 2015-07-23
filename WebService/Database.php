<?php

class Database {
    
    public static $host = 'localhost';
    
    public static $db_name = 'bd_ifpitaco';
    
    private static $username = 'ifpitaco';
    
    private static $password = 'ifpitacopass';
    
    public static function getConn() {
        
        return new PDO('mysql:host='.Database::$host.';dbname='.Database::$db_name, 
                        Database::$username, Database::$password, 
                        [PDO::MYSQL_ATTR_INIT_COMMAND =>
                        "SET NAMES utf8"]);
    }

}
