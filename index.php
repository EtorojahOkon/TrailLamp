<?php
/** 
    * Application entry point
    * @Do not make changes to this file
*/

include("./app/server/Router.php");
$router = new Router();

/** 
    *Multiple routes configuration
*/
foreach(glob('app/routes/*.php') as $file) {
    include($file);
}

?>