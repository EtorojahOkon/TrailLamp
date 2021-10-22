<?php
include("TrailLamp.php");

/* 
Default Controller class
*/
class Controller extends TrailLamp{
    /* 
        Default properties.. 
        Do not delete $params if this controller handles a parameterized route 
    */
    public $request,$files,$params;
    /* 
        Your methods here
    */
    public function main() {
      $this->view("welcome");
    }

           

}
    


?>