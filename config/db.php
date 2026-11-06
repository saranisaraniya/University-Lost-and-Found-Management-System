<?php
//this is our laptop localcost and we put the port only the databse connect so we put the port also
$conn=new mysqli("127.0.0.1", "root", "", "unifind_db", 3308);

if ($conn->connect_error) {
    die("Database Connection failed: " . $conn->connect_error);
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
