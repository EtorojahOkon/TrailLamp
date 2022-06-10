<?php
require_once 'vendor/autoload.php';
include("./app/server/Encryption.php");
include("./app/server/Mail.php");

/** 
    *@Default Controller class to be inherited by other controllers
*/
class Controller{
    /**
        *@private properties
    */
    private $link;

    /**
        * @view method
    */
    public function view($file, $parameters=[]) {
        $exception = new Exceptions();

        if (strpos($file, ".") !== FALSE) {
            $paths = explode(".",$file);
            $f = end($paths);
            $len = count($paths) - 1;
            $paths[$len] = "";
            $path = "/".implode("/", $paths);
            $final_path = './app/views'.$path;
            $loader = new \Twig\Loader\FilesystemLoader($final_path);
        }
        else{
            $f = $file;
            $final_path = './app/views';
            $loader = new \Twig\Loader\FilesystemLoader($final_path);
        }
        
        $twig = new \Twig\Environment($loader, [
            'cache' => false,
        ]);

        $fil = $final_path."/".$f.".lamp";
        
        if (!file_exists($fil)) {
            $exception->logerror("View <b>".$f."</b> not found in <b>".$final_path."</b>.\n Please create a <b>". $f.".lamp</b> file in the above directory or check your file extension or the directory it is placed in if this file exists.");
        }
        else{
            if (!is_array($parameters)) {
                $exception->logerror("view method expects parameter 2 to be an array.\n Check Controller method for this route <b>".$_SERVER["REQUEST_URI"]."</b>");
               die;
            }
            $template = $twig->load($f.'.lamp');
            echo $template->render($parameters);
        }
               
    }

    /**
        * @redirect method
    */
    public function redirect($route) {
        $env = new Env();

        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
            $link = 'https';
        } 
        else {
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

    /**
        *@encrypt method
    */
    public function encrypt($text){
        $enc = new Encryption();
        return $enc->encryptText($text);
    }

    /**
        *@decrypt method
    */
    public function decrypt($hash){
        $enc = new Encryption();
        return $enc->decryptHash($hash);
    }

    /**
        * @send mail method
    */
    public function sendMail($email, $subject, $message){
        $exception = new Exceptions();
        if ($subject === "") {
            $exception->logerror("Empty subject parameter in <b>sendMail</b> method call in Controller method for route <b>".$_SERVER["REQUEST_URI"])."</b>";
            die;
        } 
        else if($message === ""){
            $exception->logerror("Empty message parameter in <b>sendMail</b> method call in Controller method for route <b>".$_SERVER["REQUEST_URI"])."</b>";
            die;
        }
        else if($email === ""){
            $exception->logerror("Empty email parameter in <b>sendMail</b> method call in Controller method for route <b>".$_SERVER["REQUEST_URI"])."</b>";
            die;
        }
        else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $exception->logerror("Invalid email format in <b>sendMail</b> method call in Controller method for route <b>".$_SERVER["REQUEST_URI"])."</b>";
            die;
        }
        else {
            $email = filter_var($email, FILTER_SANITIZE_EMAIL);
            $mail = new Mail();
            return $mail->sendMail($email, $subject, $message);
        }
        
    }

    /**
        * @load model method
    */
    public function loadModel($callback){
        $exception = new Exceptions();
        if (strpos($callback, "@") !== FALSE) {
            $class = explode("@", $callback);
            $model_path = "./app/models/".$class[0].".php";
            $model_func = $class[1];
            if (!file_exists($model_path)) {
                $exception->logerror("No such Model class ".$class[0]." file found!\n Please create a file ".$class[0].".php in app/models/");
            } 
            else {
                include($model_path);

                if(!class_exists($class[0])) {
                    $exception->logerror("No such Model class ".$class[0]." found!\n Ensure your Model classname matches the Model filename");
                }
                else{
                    $model = new $class[0]();
                    if (!method_exists($model, $model_func)) {
                        $exception->logerror("No such  method <b>".$model_func."</b> found in class <b>".$class[0]."</b>\n Ensure your Model class method matches that specified in the loadModel method call");
                    } 
                    else {
                        try {
                            $value = call_user_func(array($model, $model_func));
                            return $value;
                        } 
                        catch (\Throwable $th) {
                            $exception->logerror("Unknown error: Could not call <b>".$class[0]."</b> class method <b>".$model_func."</b>.\n Try refreshing your page or check your code again");
                        }
                        
                    }
                }
            }
        } 
        else {
            $exception->logerror("Invalid loadModel parameter in Controller method for route ".$_SERVER["REQUEST_URI"]."\n Expects Model@method");
        }
        
    }

  
}

?>