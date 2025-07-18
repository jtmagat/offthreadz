<?php
$conn = new mysqli("localhost", "root", "", "offthreadz_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
