<?php
include("./server/env_params.php");
/* 
Default Model class
*/

class TrailModel{
    public $q;
    public function query($query) {
        $env = new Env();
        $this->q = mysqli_connect($env->MYSQLI_HOST, $env->MYSQLI_USER, $env->MYSQLI_PASSWORD, $env->MYSQLI_DATABASE);;
       
        //attempt query
        try {
            return mysqli_query($this->q, $query);
         } catch (\Throwable $th) {
            echo ("Error performing query: ".$th);
        }
    }
}

?>