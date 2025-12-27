<?php
$host = getenv('DB_HOST') ?: "mysql";
$user = getenv('DB_USER') ?: "root";
$pass = getenv('DB_PASSWORD') ?: "1234";
$db   = getenv('DB_NAME') ?: "simple_icecream_db";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
