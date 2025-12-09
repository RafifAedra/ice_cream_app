<?php
session_start();
require_once "../config/db.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'];
$stmt = $conn->prepare("DELETE FROM products WHERE product_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: dashboard.php");
exit;
