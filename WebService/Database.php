<?php

class Database {

    public static function getConn() {
        return new PDO('mysql:host=localhost;dbname=bd_ifpitaco', 
                        'ifpitaco', 'ifpitacopass', 
                        [PDO::MYSQL_ATTR_INIT_COMMAND =>
                        "SET NAMES utf8"]);
    }

}
