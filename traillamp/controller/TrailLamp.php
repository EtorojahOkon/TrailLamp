<?php

/* 
Default Controller class to be inherited by other controllers
*/
class TrailLamp{
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
    
}

?>