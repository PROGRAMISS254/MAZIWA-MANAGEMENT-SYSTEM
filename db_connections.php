<?php
$servername = "localhost";
$username = "root"; // Update if different
$password = "";     // Update with your DB password
$dbname = "milk_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
