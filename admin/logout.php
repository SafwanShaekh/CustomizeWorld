<?php
session_start();
session_destroy(); // Session khatam
header("Location: login.php"); // Login page par wapis
?>