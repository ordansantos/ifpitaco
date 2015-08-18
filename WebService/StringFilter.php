<?php

class StringFilter
{

    private static $instance;
    
    private static $patterns;
    
    private static $file = "lista-palavroes.txt";
    
    private static $token = "****";
    
    private function __construct(){
        self::$patterns = file(self::$file, FILE_IGNORE_NEW_LINES);
    }

    public static function getInstance(){
        if ( is_null( self::$instance ) ){
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function filter($string){
        
        return str_ireplace(self::$patterns, self::$token, $string);
    }

}