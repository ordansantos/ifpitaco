<?php

class Curiar{
    
    function enquete($id_enquete){
        $enquete = new Enquete();
        return'{'.$enquete->getOpts($id_enquete).','.$enquete->getVotos($id_enquete).'}';

    }
    
    function post($id_post){
        $laikes = new Laike();
        return $laikes->getLaikes($id_post);
    }
    
}