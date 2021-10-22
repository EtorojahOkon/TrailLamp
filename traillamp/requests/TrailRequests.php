<?php
include("../server/Exceptions.php");
/*
Default Request class
*/
class TrailRequests{
    public function attachController($callback, $method) {
        $exception = new Exceptions();
        //check for controllers
        if (!is_callable($callback)) {
            $class = explode("@", $callback)[0];
            $controller_path = $_SERVER["DOCUMENT_ROOT"]."/controller/".explode("@", $callback)[0].".php";
            $func = explode("@", $callback)[1];
            //check for file
            if (!file_exists($controller_path)) {
                $exception->logerror("No such class file found!");
            } else {
                include($controller_path);

                //check for class
                if(!class_exists($class)) {
                    $exception->logerror("No such class <b>".$class."</b> found!");
                }
                else {
                    //create instance of controller class
                    $controller = new $class();
                    $input = json_decode(file_get_contents("php://input"));

                   //check method
                    if ($method == "POST") {
                        if (count($_POST) == 0) {
                            $controller->request = (object) $input;
                        }
                        else {
                            $controller->request = (object) $_POST;
                        }
                    }
                    else {
                        if (count($_GET) == 0) {
                            $controller->request = (object) $input;
                        } else {
                            $controller->request = (object) $_GET;
                        }
                        
                    }
                    $controller->files = $_FILES;
                    //check if method exists
                    if (!method_exists($controller, $func)) {
                        $exception->logerror("No such method <b>".$func."</b> found in class <b>".$class."</b> ");
                    } else {
                        try {
                            call_user_func(array($controller, $func));
                        } catch (\Throwable $th) {
                            $exception->logerror($th);
                        }
                    }
                    
                    

                }
            }
            
        } else {
            call_user_func($callback);
        }
    }
}
?>