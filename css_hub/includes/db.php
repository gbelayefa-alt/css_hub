<?php
/*
 * Name: Amanda Gbe
 * Date: March 29, 2026
 * Description: This file establishes a connection to the MySQL database using PDO.
 */
$host = "localhost";
$username = "root";
$password = "";
$dbname = "css_hub_db";

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4", 
        $username, 
        $password
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>