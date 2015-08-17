<?php

class Image{
    
    private static $path = '../storage/';
    
    private static $default = 'default';
    
    private static $thumbnail_width = 120;
    
    private static $thumbnail_height = 120;
    
    private static function base64ToFile ($base64_string){
        
        $img_name = md5(uniqid(""));
        
        $output_file = self::$path . $img_name;

        $ifp = fopen($output_file, "wb"); 

        $data = explode(',', $base64_string);

        fwrite($ifp, base64_decode($data[1])); 

        fclose($ifp); 

        return $output_file; 
        
    }
    
    private static function convertToJpg ($img_path){
        
            
        if (($img_info = getimagesize($img_path)) === false){
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
   
        $tmp = imagecreatetruecolor($width, $height);
        
        imagecopyresampled($tmp, $src, 0, 0, 0, 0, $width, 
                $height, $width, $height);
        
        imagejpeg($tmp, $img_path . '.jpg');
        
        return true;
    }
    
    private static function convertToJpg_Thumbnail ($img_path, $x_percent, 
            $y_percent, $w_percent, $h_percent){
            
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
        
        $x = intval($x_percent * $width); // Crop Start X position in original image
        $y = intval($y_percent * $height); // Crop Srart Y position in original image
        $w = intval($w_percent * $width); //  width
        $h = intval($h_percent * $height); // height

        
        $new_image = imagecreatetruecolor($w, $h);
        
        imagecopyresampled($new_image, $src, 0, 0, $x, $y, $w, $h, $w, $h);
        
        imagejpeg($new_image, $img_path . '.jpg');
        
        return true;
    }
    
    public static function save ($base64_string){

        $img_path =  self::base64ToFile($base64_string);
                
        if (self::convertToJpg($img_path) === false){
            unlink($img_path);
            return false;
        }
        
        unlink($img_path);
        
        return $img_path . '.jpg';
    }
    
    public static function saveThumbnail ($base64_string, $x_percent, $y_percent, 
            $w_percent, $h_percent){
        
        $img_path =  self::base64ToFile($base64_string);
        
        if (self::convertToJpg_Thumbnail($img_path, $x_percent, $y_percent,
                $w_percent, $h_percent) === false){
            unlink($img_path);
            return false;
        }
        
        unlink($img_path);
        
        return $img_path . '.jpg';
        
    }
    
    public static function getDefaultPath (){
        return self::$path . self::$default;
    }
    
}