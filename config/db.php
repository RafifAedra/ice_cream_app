<?php
$host = "localhost";
$user = "root";
$pass = "1234";
$db   = "simple_icecream_db";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
