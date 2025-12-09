<?php
session_start();
include('../config/db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}


$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM orders WHERE user_id = ? ORDER BY order_id DESC LIMIT 1";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();

if (!$order) {
    header("Location: home.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Pesanan Berhasil</title>
    <link rel="stylesheet" href="../assets/css/success.css">
</head>

<body>
    <div class="success-container">
        <div class="icon-check">✅</div>
        <h2>Pesanan Berhasil Dibuat!</h2>
        <p>Terima kasih telah berbelanja di <strong>IceCream App</strong> 🍦</p>

        <div class="order-info">
            <p><strong>ID Pesanan:</strong> <?= $order['order_id'] ?></p>
            <p><strong>Total Pembayaran:</strong> Rp <?= number_format($order['total_harga'], 0, ',', '.') ?></p>
            <p><strong>Status:</strong> <?= ucfirst($order['status_order']) ?></p>
        </div>

        <div class="success-buttons">
            <a href="home.php" class="btn-back">← Kembali Belanja</a>
            
        </div>
    </div>
</body>

</html>