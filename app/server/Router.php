<?php
include("Exceptions.php");
include("./app/server/Env.php");
/**
    * @Router Class 
    *
    * Editing this file may cause breakage in your application
*/

class Router {

    /** 
        * @Basic Url matching method
    */
    private function route($route, $callback, $middleware, $method) {
        $env = new Env();
        $exception = new Exceptions();
        $final_router = new Router();
        
        if ($env->SUBDOMAIN == true) {
            $uri = str_replace("/".explode("/", $_SERVER['REQUEST_URI'])[1], "", $_SERVER['REQUEST_URI']);
        }
        else{
            $uri = $_SERVER['REQUEST_URI'];
        }
        
        preg_match_all("/\{[a-zA-Z0-9_]+\}/", $route, $matches);
        $match_str = "";

        foreach ($matches[0] as $key => $value) {
            $match_str .= preg_replace("/(\{|\})/", "", $value)." ";
        }
        
        if ($uri == $route) {
            if ($middleware === false) {
                $final_router->controller_routing($callback, $method, false);
            }
            else {
                $final_router->middleware_routing($middleware, $method, false, $callback);
                
            }
        }
        elseif (count($matches[0]) > 0) {
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
               
                if ($middleware === false) {
                    $final_router->controller_routing($callback, $method, $op);
                }
                else {
                    $final_router->middleware_routing($middleware, $method, $op, $callback);
                    
                }
            }     
            
        }
        else {
            header('HTTP/1.0 404 Not Found');
        }
    }

    /** 
        * @Middleware callback
    */
    private function middleware_routing($callback, $method, $options, $controller=false) {
        $final_router = new Router();
        $exception = new Exceptions();

        //check for middleware class methods
        if (!is_callable($callback)) {
            $class = explode("@", $callback);
            $middleware_path = "./app/middlewares/".$class[0].".php";
            $middleware_func = $class[1];

            if (!file_exists($middleware_path)) {
                $exception->logerror("No such Middleware class ".$class[0]." file found!\n Please create a file ".$class[0].".php in app/middlewares/");
            } 
            else {
                include($middleware_path);

                if(!class_exists($class[0])) {
                    $exception->logerror("No such Middleware class ".$class[0]." found!\n Ensure your Middleware classname matches the Middleware filename");
                }
                else{
                    $middleware = new $class[0]();
                    $input = json_decode(file_get_contents("php://input"));

                    //check options
                    if ($options !== false) {
                        foreach ($options as $key => $value) {
                            $middleware->{$key} = $value;
                        }
                        
                    }

                    if ($method == "POST") {

                        if (count($_POST) == 0) {
                            $middleware->request = (object) $input;
                        }
                        else {
                            $middleware->request = (object) $_POST;
                        }
                    }
                    else if ($method == "GET"){

                        if (count($_GET) == 0) {
                            $middleware->request = (object) $input;
                        } else {
                            unset($_GET["page"]);
                            $middleware->request = (object) $_GET;
                        }
                        
                    }
                    else {
                        $middleware->request = (object) $input;
                    }
                    $middleware->files = $_FILES;
                    
                    //check if method exists
                    if (!method_exists($middleware, $middleware_func)) {
                        $exception->logerror("No such  method <b>".$middleware_func."</b> found in class <b>".$class[0]."</b>\n Ensure your Middleware class method matches that in <b>routes.php</b>");
                    } 
                    else {
                        try {
                            $status = call_user_func(array($middleware, $middleware_func));
                        } catch (\Throwable $th) {
                            $exception->logerror("Unknown error: Could not call <b>".$class[0]."</b> class method <b>".$middleware_func."</b>.\n Try refreshing your page or check your code again");
                        }

                        if ($status == 1) {
                            $final_router->controller_routing($controller, $method, $options);
                        }
                        else{
                            $exception->logerror("No Middleware status response 1 in <b>".$class[0]."</b> class method <b>".$middleware_func."</b>.\n Remember to return true in the callback function. \n Stack trace: <b>".$middleware_path."</b>");
                        }
                       
                    }
                    
                }
            }
        }
        else {
            if ($options === false) {
               $status = call_user_func($callback);
            }
            else {
                $keys = array_keys($options);
                if (count($keys) == 1) {
                    $status = call_user_func($callback, $options[$keys[0]]);
                }
                elseif (count($keys) == 2) {
                    $status = call_user_func($callback, $options[$keys[0]], $options[$keys[1]]);
                }
                elseif (count($keys) == 3) {
                    $status = call_user_func($callback, $options[$keys[0]], $options[$keys[1]], $options[$keys[2]]);
                }
                elseif (count($keys) == 4) {
                    $status = call_user_func($callback, $options[$keys[0]], $options[$keys[1]], $options[$keys[2]], $options[$keys[3]]);
                }
                
            }
            if ($status == 1) {
                $final_router->controller_routing($controller, $method, $options);
            }
            else{
                $exception->logerror("No Middleware status response 1 in callback function for route ".$_SERVER["REQUEST_URI"]." in routes.php.\n Remember to return true in the callback function.");
            }
            
           
        }
    }

    /** 
        * @Controller callback
    */
    private function controller_routing($callback, $method, $options) {
        $exception = new Exceptions();
        //check for controller class methods
        if (!is_callable($callback)) {
            $class = explode("@", $callback);
            $controller_path = "./app/controllers/".$class[0].".php";
            $controller_func = $class[1];
            //check for file
            if (!file_exists($controller_path)) {
                $exception->logerror("No such Controller class <b>".$class[0]."</b> file found!\n Please create a file ".$class[0].".php in <b>app/contollers/</b>");
            } 
            else {
                include($controller_path);
                
                 //check for class
                if(!class_exists($class[0])) {
                    $exception->logerror("No such Controller class <b>".$class[0]."</b> found!\n Ensure your Controller classname matches the Controller filename");
                }
                else {
                    //create instance of controller class
                    $controller = new $class[0]();
                    $input = json_decode(file_get_contents("php://input"));

                    //check options
                    if ($options !== false) {

                        foreach ($options as $key => $value) {
                            $controller->{$key} = $value;
                        }
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
                    else if ($method == "GET"){

                        if (count($_GET) == 0) {
                            $controller->request = (object) $input;
                        } 
                        else {
                            unset($_GET["page"]);
                            $controller->request = (object) $_GET;
                        }
                        
                    }
                    else {
                        $controller->request = (object) $input;
                    }
                    $controller->files = $_FILES;

                    //check if method exists
                    if (!method_exists($controller, $controller_func)) {
                        $exception->logerror("No such  method <b>".$controller_func."</b> found in class <b>".$class[0]."</b>\n Ensure your Controller class method matches that in <b>routes.php</b>");
                    } 
                    else {

                        try {
                            call_user_func(array($controller, $controller_func));
                        } catch (\Throwable $th) {
                            $exception->logerror($th);
                            //$exception->logerror("Unknown error: Could not call <b>".$class[0]."</b> class method <b>".$controller_func."</b>.\n Try refreshing your page or check your code again");
                        }
                    }
                    
                }
            }
        }
        else{
            if ($options === false) {
                call_user_func($callback);
            }
            else {
                $keys = array_keys($options);
                if (count($keys) == 1) {
                    call_user_func($callback, $options[$keys[0]]);
                }
                elseif (count($keys) == 2) {
                    call_user_func($callback, $options[$keys[0]], $options[$keys[1]]);
                }
                elseif (count($keys) == 3) {
                    call_user_func($callback, $options[$keys[0]], $options[$keys[1]], $options[$keys[2]]);
                }
                elseif (count($keys) == 4) {
                    call_user_func($callback, $options[$keys[0]], $options[$keys[1]], $options[$keys[2]], $options[$keys[3]]);
                }
                 
             }
        }
    }

    /**
        * @Router Class method for GET requests
    */
    static function get($route, $callback, $middleware = false) {
        $routes = new Router();
        $routes->route($route, $callback, $middleware, "GET");
    }

    /**
        * @Router Class method for POST requests
    */
    static function post($route, $callback, $middleware = false) {
        $routes = new Router();
        $routes->route($route, $callback, $middleware, "POST");
    }

    /**
        * @Router Class method for PUT requests
    */
    static function put($route, $callback, $middleware = false) {
        $routes = new Router();
        $routes->route($route, $callback, $middleware, "PUT");
    }

    /**
        * @Router Class method for HEAD requests
    */
    static function head($route, $callback, $middleware = false) {
        $routes = new Router();
        $routes->route($route, $callback, $middleware, "HEAD");
    }

    /**
        * @Router Class method for PATCH requests
    */
    static function patch($route, $callback, $middleware = false) {
        $routes = new Router();
        $routes->route($route, $callback, $middleware, "PATCH");
    }

    /**
        * @Router Class method for DELETE requests
    */
    static function delete($route, $callback, $middleware = false) {
        $routes = new Router();
        $routes->route($route, $callback, $middleware, 'DELETE');
    }



}


?>