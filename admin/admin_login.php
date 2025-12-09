<?php
include '../config/db.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM admin WHERE username='$username'");
    $row = $result->fetch_assoc();

    if ($row && password_verify($password, $row['password'])) {
        session_start();
        $_SESSION['admin_id'] = $row['id_admin'];
        header("Location: admin_dashboard.php");
    } else {
        echo "Username atau password salah!";
    }
}
