<?php

class Image{
    
    private static function base64_to_file ($base64_string){
        
        $output_file = '../storage/' . md5(uniqid(""));

        $ifp = fopen($output_file, "wb"); 

        $data = explode(',', $base64_string);

        fwrite($ifp, base64_decode($data[1])); 

        fclose($ifp); 

        return $output_file; 
        
    }
    
    public static function save ($base64_string){
        
        return self::base64_to_file($base64_string);

        
    }
    
}