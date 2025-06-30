<?php
// Database connection

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
    $mysqli = new mysqli(
        "127.0.0.1", 
        "root", 
        "", 
        "video_games"
    );
    $mysqli->set_charset("utf8mb4");
} catch (mysqli_sql_exception $e) {
    die("Connection error: " . $e->getMessage());
}