<?php
include("./app/server/Database.php");
/**
 * @Default Model class inherited by other models
 * Editing this file may break your application
*/

class Model {
    /**
        *@private properties
    */
    public $db;

    public function __construct(){
        $d = new Database();
        $this->db = $d->db;
    }
}

?>