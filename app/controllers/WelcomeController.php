<?php
include("Controller.php");

/** 
    *@Welcome Controller class
*/
class WelcomeController extends Controller{
    /**
        *Default Controller properties.. 
        *
        *@Access parameters with $this->parameter if this middleware handles a parameterized route 
    */
    public $request,$method,$files;

    /** 
        * @your code here
    */
    public function main() {
        $this->view("welcome", ["name" => "TrailLamp v1.1.0"]);
    }

           

}
    


?>