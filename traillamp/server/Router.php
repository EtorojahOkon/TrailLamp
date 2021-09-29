<?php
include("Exceptions.php");
/*
    @Router class
    @Editing this file may cause breakage in your application
*/    
        
class Router {
        
    private function route($route, $callback, $method) {
        $exception = new Exceptions();
        $final_router = new Router();
        $uri = str_replace("/main", "", $_SERVER['REQUEST_URI']);
        preg_match_all("/\{[a-zA-Z0-9_]+\}/", $route, $matches);
        $match_str = "";
        foreach ($matches[0] as $key => $value) {
            $match_str .= preg_replace("/(\{|\})/", "", $value)." ";
        }
        
       //check matches
        if (count($matches[0]) > 0) {
            $route = preg_replace("/(\{|\})/", "", $route);
            $route_arr = explode("/", $route);
            $uri_arr = explode("/", $uri);
            $op = [];
            if (count($uri_arr) !== count($route_arr)) {
                header('HTTP/1.0 404 Not Found');
            }
            else {
                for ($i=0; $i < count($route_arr); $i++) { 
                    if ($route_arr[$i] !== $uri_arr[$i]) {
                        $op[$route_arr[$i]] = $uri_arr[$i];
                    }
                } 
                $final_router->final_routing($callback, $method, $op);
            }
         
            
        }
        
        elseif ($uri == $route) {
            //check if uri matches route
            $final_router->final_routing($callback, $method, false);
        }
        else {
            header('HTTP/1.0 404 Not Found');
        }
     }

    private function final_routing($callback, $method, $options) {
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

                    //check options
                    if ($options !== false) {
                        $controller->params = $options;
                    }

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
    
    static function get($route, $callback) {
        $routes = new Router();
        $routes->route($route, $callback, "GET");
    }

    static function post($route, $callback) {
        $routes = new Router();
        $routes->route($route, $callback, "POST");
    }
}
?>
