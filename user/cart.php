<?php
session_start();
include('../config/db.php');

if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
if (!isset($_SESSION['user_id'])) {
    header("Location: cart.php");
    exit;
}

// Tambah qty
if (isset($_GET['plus'])) {
    $id = $_GET['plus'];
    $_SESSION['cart'][$id]['qty']++;
    header("Location: cart.php");
    exit;
}

// Kurang qty
if (isset($_GET['minus'])) {
    $id = $_GET['minus'];
    if ($_SESSION['cart'][$id]['qty'] > 1) {
        $_SESSION['cart'][$id]['qty']--;
    } else {
        unset($_SESSION['cart'][$id]);
    }
    header("Location: cart.php");
    exit;
}

// Pesan sekarang
if (isset($_POST['order_now'])) {
    $user_id = $_SESSION['user_id'];
    $alamat = $_POST['alamat'];
    $payment = $_POST['payment'];
    $total = 0;

    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['qty'];
    }

    // Simpan ke tabel orders
    $stmt = $conn->prepare("INSERT INTO orders (user_id, total_harga, status_order, alamat_kirim, payment_method) VALUES (?, ?, 'pending', ?, ?)");
    $stmt->bind_param("idss", $user_id, $total, $alamat, $payment);
    $stmt->execute();
    $order_id = $stmt->insert_id;

    // Simpan ke order_detail
    foreach ($_SESSION['cart'] as $item) {
        $subtotal = $item['price'] * $item['qty'];
        $stmtDetail = $conn->prepare("INSERT INTO order_detail (order_id, produk_id, jumlah, subtotal) VALUES (?, ?, ?, ?)");
        $stmtDetail->bind_param("iiid", $order_id, $item['id'], $item['qty'], $subtotal);
        $stmtDetail->execute();
    }

    unset($_SESSION['cart']); // Kosongkan cart
    header("Location: success.php");
    exit;
}

$cart = $_SESSION['cart'];
$total = 0;
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Keranjang Belanja</title>
    <link rel="stylesheet" href="../assets/css/cart.css">
</head>

<body>
    <div class="cart-container">
        <h2>🛒 Keranjang Belanja</h2>

        <?php if (empty($cart)): ?>
            <p>Keranjang masih kosong.</p>
            <a href="home.php" class="btn-back">← Kembali Belanja</a>
        <?php else: ?>
            <form method="POST">
                <table>
                    <tr>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th>Qty</th>
                        <th>Total</th>
                    </tr>
                    <?php foreach ($cart as $id => $item):
                        $subtotal = $item['price'] * $item['qty'];
                        $total += $subtotal;
                    ?>
                        <tr>
                            <td>
                                <img src="../images/products/<?= htmlspecialchars($item['image']) ?>" width="60">
                                <?= htmlspecialchars($item['name']) ?>
                            </td>
                            <td>Rp <?= number_format($item['price'], 0, ',', '.') ?></td>
                            <td>
                                <a href="cart.php?minus=<?= $id ?>" class="qty-btn">−</a>
                                <?= $item['qty'] ?>
                                <a href="cart.php?plus=<?= $id ?>" class="qty-btn">+</a>
                            </td>
                            <td>Rp <?= number_format($subtotal, 0, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <th colspan="3" style="text-align:right;">Total Belanja:</th>
                        <th>Rp <?= number_format($total, 0, ',', '.') ?></th>
                    </tr>
                </table>

                <div class="order-section">
                    <label>Alamat Pengiriman:</label>
                    <textarea name="alamat" required></textarea>

                    <label>Metode Pembayaran:</label>
                    <select name="payment" required>
                        <option value="COD">COD</option>
                        <option value="Transfer">Transfer Bank</option>
                    </select>

                    <button type="submit" name="order_now" class="btn-order">Pesan Sekarang</button>
                </div>

                <a href="home.php" class="btn-back">← Kembali Belanja</a>
            </form>
        <?php endif; ?>
    </div>
</body>

</html>