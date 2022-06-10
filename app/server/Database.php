<?php
/**
 * @Database Conenction file
 */
class Database{
    /**
     * @Database class property
     */
    public $db;

    public function __construct($init = false) {

        if ($init == "schema") {
            include("Env.php");
            include("Exceptions.php");
            $env = new Env("schema");
        }
        else{
            $env = new Env();
        }

        
        $exception = new Exceptions();

        try {
            $conn = new PDO("mysql:host=$env->DATABASE_HOST;dbname=$env->DATABASE_NAME", $env->DATABASE_USER,$env->DATABASE_PASSWORD );
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db = $conn;
        } 
        catch (PDOException $error) {
            $exception->logerror("Database connection failed\n".$error->getMessage(), "schema");
        }
    }
}
?>