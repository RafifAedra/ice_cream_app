<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit;
}
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Dashboard Admin</title>
</head>

<body>
    <h2>Selamat datang, <?= htmlentities($_SESSION['admin_name']) ?></h2>
    <p>Role: <?= htmlentities($_SESSION['admin_role']) ?></p>

    <ul>
        <li><a href="manage_produk.php">Manage Produk (belum dibuat)</a></li>
        <li><a href="manage_orders.php">Manage Orders (belum dibuat)</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>

    <p>Contoh ambil data admin dari DB:</p>
    <?php
    require __DIR__ . '/config.php';
    $stmt = $pdo->query("SELECT COUNT(*) AS total_produk FROM produk");
    $row = $stmt->fetch();
    echo "<p>Total produk: " . ($row['total_produk'] ?? 0) . "</p>";
    ?>
</body>

</html>