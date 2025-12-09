<?php
session_start();
include('../config/db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil semua pesanan user
$query = "SELECT * FROM orders WHERE user_id = ? ORDER BY tanggal_order DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Riwayat Pesanan</title>
    <link rel="stylesheet" href="../assets/css/orders.css">
</head>

<body>
    <div class="orders-container">
        <h2>📦 Riwayat Pesanan Saya</h2>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($order = $result->fetch_assoc()): ?>
                <div class="order-card">
                    <div class="order-header">
                        <h3>ID Pesanan: <?= $order['order_id'] ?></h3>
                        <span class="status <?= $order['status_order'] ?>">
                            <?= ucfirst($order['status_order']) ?>
                        </span>
                    </div>

                    <p><strong>Tanggal Order:</strong> <?= date('d M Y, H:i', strtotime($order['tanggal_order'])) ?></p>
                    <p><strong>Total Harga:</strong> Rp <?= number_format($order['total_harga'], 0, ',', '.') ?></p>


                    <?php
                    $order_id = $order['order_id'];
                    $detailQuery = "
                        SELECT p.nama_produk, p.harga, od.jumlah, od.subtotal 
                        FROM order_detail od
                        JOIN products p ON od.produk_id = p.produk_id
                        WHERE od.order_id = ?";
                    $detailStmt = $conn->prepare($detailQuery);
                    $detailStmt->bind_param("i", $order_id);
                    $detailStmt->execute();
                    $details = $detailStmt->get_result();
                    ?>

                    <table class="detail-table">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($d = $details->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($d['nama_produk']) ?></td>
                                    <td>Rp <?= number_format($d['harga'], 0, ',', '.') ?></td>
                                    <td><?= $d['jumlah'] ?></td>
                                    <td>Rp <?= number_format($d['subtotal'], 0, ',', '.') ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>

                    <div class="order-footer">
                        <p><strong>Metode Pembayaran:</strong> <?= $order['payment_method'] ?: '-' ?></p>
                        <p><strong>Alamat Kirim:</strong> <?= $order['alamat_kirim'] ?: '-' ?></p>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="empty">Belum ada pesanan</p>
        <?php endif; ?>

        <a href="home.php" class="btn-back">← Kembali Belanja</a>
    </div>
</body>

</html>