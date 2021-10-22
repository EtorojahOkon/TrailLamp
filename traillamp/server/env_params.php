<?php
/*
    Environment variable files
*/
class Env {
    public function __construct() {
        $env = fopen($_SERVER["DOCUMENT_ROOT"]."/.env", "r");
        while (!feof($env)) {
           $result = fgets($env);
           $key_value_pair = explode("=", trim($result));
           $key = $key_value_pair[0];
           $value = $key_value_pair[1];
           $this->$key = $value;
        }
        fclose($env);
    }
}
?>