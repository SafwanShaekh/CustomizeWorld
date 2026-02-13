<?php
// File: db.php

// 1. Strict Mode On: Errors ko Exception bana kar pakra jaye
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // Connection Variables (Behtar hai ke production mein inhe environment variables se uthaya jaye)
    $host = "localhost";
    $user = "root"; // PRO TIP: Production ke liye alag DB user banao, root mat use karo
    $pass = "";     // PRO TIP: Strong password zaroor lagao
    $db   = "customizeworld_db";

    // 2. Connection Establish
    $conn = mysqli_connect($host, $user, $pass, $db);

    // 3. Security Charset: Special characters (SQL Injection risk) ko handle karne ke liye
    mysqli_set_charset($conn, "utf8mb4");

} catch (Exception $e) {
    // 4. Security Fix: Asli error user se chupana
    // Asli error ko server ke error_log file mein save karein
    error_log("Database Error: " . $e->getMessage()); 
    
    // User ko sirf ye simple message dikhayein
    exit("System Error: Database connection failed. Please try again later.");
}
?>