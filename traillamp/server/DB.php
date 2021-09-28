<?php
include("env_params.php");
/* 
Database class to be used in models
*/
class DB{
    public $db;
    //constructor method
    public function __construct() {
        $env = new Env();
        $this->db = mysqli_connect($env->MYSQLI_HOST, $env->MYSQLI_USER, $env->MYSQLI_PASSWORD, $env->MYSQLI_DATABASE) or die("Error connecting to database");
        return $this->db ;
    }

    //query method
    public function query($query) {
        return mysqli_query($this->db,$query);
    }
    
}

?>