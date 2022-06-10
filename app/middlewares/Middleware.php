<?php

/** 
    *@Default Middleware class to be inherited by other middlewares
    *
    *Editing this file may break your application
*/
class Middleware{
    //private properties
    private $link;
    
    //redirect method
    public function redirect($route) {
        $env = new Env();
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
            $link = 'https';
        } else {
            $link = 'http';
        }
        if ($env->SUBDOMAIN == true) {
            $link .= "://".$_SERVER['HTTP_HOST']."/".explode("/", $_SERVER['REQUEST_URI'])[1].$route;
        }
        else {
            $link .= "://".$_SERVER['HTTP_HOST'].$route;
        }
        
        header("location:".$link);
        exit;
                
    }

    
}

?>