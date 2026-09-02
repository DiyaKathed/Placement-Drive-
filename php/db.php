<?php
// =========================================================
// DATABASE CONNECTION
// XAMPP defaults: user "root", empty password
// =========================================================

$host = "localhost";
$username = "root";
$password = "";
$database = "placement_cell";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    header("Content-Type: application/json");
    die(json_encode([
        "success" => false,
        "message" => "Database connection failed: " . $conn->connect_error
    ]));
}

$conn->set_charset("utf8mb4");
?>
