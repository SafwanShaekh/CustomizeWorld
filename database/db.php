<?php
// File: db.php
$conn = mysqli_connect("localhost", "root", "", "customizeworld_db");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>