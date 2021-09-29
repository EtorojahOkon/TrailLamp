<?php

/* 
Default Controller class to be inherited by other controllers
*/
class TrailLamp{
    //private properties
    private $link;
    
    // view method
    public function view($file) {
        $directory = $_SERVER["DOCUMENT_ROOT"]."/view/".$file.".php";
        if (!file_exists($directory)) {
            $error = new Exceptions();
            $error->logerror("No such file ".$file.".php found");
        } else {
            include($directory);
        }
        
    }
    
     //redirect method
    public function redirect($route) {
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
            $link = 'https';
        } else {
            $link = 'http';
        }
        $link .= "://".$_SERVER['HTTP_HOST'].rtrim($_SERVER['REQUEST_URI'], '/').$route;
        header("location:".$link);
        exit;
                
    }
    
}

?>
