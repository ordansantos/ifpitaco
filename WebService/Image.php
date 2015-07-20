<?php

class Image{
    
    private static $path = '../storage/';

    private static function base64ToFile ($base64_string){
        
        $img_name = md5(uniqid(""));
        
        $output_file = self::$path . $img_name;

        $ifp = fopen($output_file, "wb"); 

        $data = explode(',', $base64_string);

        fwrite($ifp, base64_decode($data[1])); 

        fclose($ifp); 

        return $output_file; 
        
    }
    
    private static function convertToJpg ($img_path, $x_percent = 0, 
            $y_percent = 0, $w_percent = 1, $h_percent = 1){
        
            
        if (($img_info = getimagesize($img_path)) === FALSE){
            return false;
        }

        switch ($img_info[2]) {
          case IMAGETYPE_GIF  : $src = imagecreatefromgif($img_path);  break;
          case IMAGETYPE_JPEG : $src = imagecreatefromjpeg($img_path); break;
          case IMAGETYPE_PNG  : $src = imagecreatefrompng($img_path);  break;
          default : return false;
        }
        
        $width = $img_info[0];
        $height = $img_info[1];
        
        $new_x = intval($x_percent * $width);
        $new_y = intval($y_percent * $height);
        $new_width = intval($w_percent * $width);
        $new_height = intval($h_percent * $height);

        
        $tmp = imagecreatetruecolor($new_width, $new_height);
        
        imagecopyresampled($tmp, $src, $new_x, $new_y, 0, 0, $new_width, 
                $new_height, $width, $height);
        
        imagejpeg($tmp, $img_path . '.jpg');
        
        return true;
    }
    
    public static function save ($base64_string){
        
        $img_path =  self::base64ToFile($base64_string);
        
        if (self::convertToJpg($img_path) == false){
            unlink($img_path);
            return false;
        }
        
        unlink($img_path);
        
        return self::$path . $img_path . '.jpg';
    }
    

    
}