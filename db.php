<?php
// used to connect to the database
$host = "localhost";
$db_name = "metro";
$username = "root";
$password = "root";
 
$con = new mysqli($host, $username, $password, $db_name);
 
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
} 

?>