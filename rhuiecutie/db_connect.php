<?php
$servername = "localhost";  // or "127.0.0.1"
$username = "roif0_40218496ot";         // default XAMPP username
$password = "rJl4xzGIsxE";             // default XAMPP has no password
$dbname = "f0_40218496_db_rhuie";    // the database name you created

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
