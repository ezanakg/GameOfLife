<?php
$host = "localhost"; 
$user = "egebru2";  
$pass = "egebru2";  
$dbname = "egebru2"; 

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
