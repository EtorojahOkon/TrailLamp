<?php
/*
    Exception class for error handling
*/
class Exceptions{
    private $log;
    private $time;
    private $final;
    public function logerror($error) {
        $this->time = date("d m,y h:i:sa");
        try {
            $this->log = file_get_contents("./error.inc");
            $this->final = $this->log.$this->time." ".$error."\r\n";
            file_put_contents("error.inc", $this->final);
            echo new Exception($error, 1);
            
        } catch (\Throwable $th) {}
       
    }
}

?>