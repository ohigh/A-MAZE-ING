<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cph_game_db";

try {
    $conn = new PDO("mysql:host=$servername; dbname=$dbname; charset=utf8", $username, $password);
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) { 
    echo "Connection Failed: " . $e->getMessage();
}
session_start();
ob_start();