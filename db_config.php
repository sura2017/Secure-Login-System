<?php
$host = "127.0.0.1:3307"; // This matches your custom port
$user = "root";
$pass = "";
$dbname = "user_system";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>