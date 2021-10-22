<?php

include("../server/DB.php");
$db = new DB();
$query = '';
//check if file exists
if (file_exists('migration.sql')) {
    
    //filename must be migration.sql
    $sql_file = file('migration.sql');
    foreach ($sql_file as $line) {
        $start = substr(trim($line), 0, 2);
        $end = substr(trim($line), -1, 1);

        //check for  comments and unnecessary parameters
        if (empty($line) || $start == "--" || substr(trim($line), 0, 3) === "SET" || substr(trim($line), 0, 5) === "START" || $start == "/*" || $start == "//") {
            continue;
        }

        $query = $query.$line;
        if ($end == ";") {
           $db->query($query);
            $query = '';
        }
    }
    
    //remove migration file
    unlink('migration.sql');

    //output done
    echo "Migration done";
}
else {
    //no migration file
    echo "No migration file found!";
}

?>