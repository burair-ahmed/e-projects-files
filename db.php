<?php
$servername = "sql309.infinityfree.com";
$username = "if0_42486494";
$password = "armash12345";
$database = "if0_42486494_shine_utensils";

// Database Connection
$conn = new mysqli($servername, $username, $password, $database);

// Check Connection
if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}
?>