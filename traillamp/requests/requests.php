<?php
include("TrailRequests.php");
$req = new TrailRequests();
/*
All Ajax and fetch requests made here

*/
$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
if ($contentType === "application/json") {
    $request = json_decode(trim(file_get_contents("php://input")), true);
    //your fetch requests
}
else {
    //your Ajax requests
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        //post requests
    } else {
        //get_requests
            
    }
    
}
?>