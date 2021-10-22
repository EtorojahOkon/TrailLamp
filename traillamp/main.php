<?php
//entry point of application
include("./server/Router.php");
$router = new Router();
include("./routes.php");
?>