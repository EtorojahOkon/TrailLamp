<?php
/**
    * @Exceptions class
    *
    * Editing this file may cause breakage in your application
*/
class Exceptions{
    private $log;
    private $time;
    private $final;

    /**
        * @Log Error class method
    */

    public function logerror($error, $init = false) {
        $this->time = date("jS M,Y h:ia");
        $f = "app/errors/error.inc";
        try {
            if (file_exists($f)) {
                $this->log = file_get_contents($f);
                $this->final = $this->log.$this->time." ".str_replace("\n", "",$error)."\r\n";
            } else {
                $this->final = $this->time." ".str_replace("\n", "",$error)."\r\n";
            }
            if ($init == false) {
                file_put_contents($f, $this->final);
            }
            
            echo nl2br("<br/><b>Error</b><br/>".$error);
            
        } catch (\Throwable $th) {}
       
    }
}
