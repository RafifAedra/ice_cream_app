<?php

session_start();

// wajib login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// koneksi db
include('../config/db.php');

$result = $conn->query("SELECT * FROM products ORDER BY created_at DESC");

// ambil nama user
$nama = $_SESSION['nama'] ?? ($_SESSION['user']['nama'] ?? 'Pengguna');

// ambil semua produk
$products = [];
if ($conn instanceof mysqli) {
    $sql = "SELECT product_id, name, description, price, stock, image FROM products ORDER BY created_at DESC";
    $res = $conn->query($sql);
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $products[] = $row;
        }
        $res->free();
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Home | IceCream Store</title>
    <link rel="stylesheet" href="../assets/css/user-home.css">
</head>

<body>

    <?php if (isset($_SESSION['flash_message'])): ?>
        <div class="alert success">
            <?= $_SESSION['flash_message']; ?>
        </div>
        <?php unset($_SESSION['flash_message']); ?>
    <?php endif; ?>



    <header class="site-header">
        <div class="container">
            <h1 class="brand">GelatinUmai</h1>
            <nav class="nav">
                <span class="welcome">Halo, <?= htmlspecialchars($nama) ?></span>
                <a href="cart.php" class="btn success small">🛒 Keranjang</a>
                <a href="logout.php" class="btn danger small">Logout</a>
            </nav>
        </div>
    </header>

    <main class="container main-content">
        <section class="hero">
            <h2>Selamat datang di IceCream Store</h2>
            <p>Nikmati pilihan rasa es krim terbaik — dibuat segar setiap hari.</p>
            <div class="hero-cta">
                <a href="#products" class="btn">Lihat Produk</a>
            </div>
        </section>

        <section id="products" class="products">
            <h3>Produk Unggulan</h3>

            <?php if (empty($products)): ?>
                <p class="muted">Belum ada produk. Admin bisa menambahkan produk lewat dashboard admin atau phpMyAdmin.</p>
            <?php else: ?>
                <div class="grid">
                    <?php foreach ($products as $p):
                        // Pastikan path gambar benar
                        $imgPath = "../images/products/" . htmlspecialchars($p['image']);
                        // Cek apakah file gambar benar-benar ada
                        if (!file_exists(__DIR__ . "/../images/products/" . $p['image'])) {
                            $imgPath = "../assets/images/no-image.jpg";
                        }
                    ?>
                        <div class="card">
                            <img src="<?= $imgPath ?>" alt="<?= htmlspecialchars($p['name']) ?>">
                            <h4><?= htmlspecialchars($p['name']) ?></h4>
                            <p class="desc"><?= htmlspecialchars($p['description']) ?></p>
                            <div class="meta">
                                <span class="price">Rp <?= number_format($p['price'], 0, ',', '.') ?></span>
                                <span class="stock"><?= intval($p['stock']) > 0 ? "Stok: " . intval($p['stock']) : "Habis" ?></span>
                            </div>

                            <?php if (intval($p['stock']) > 0): ?>
                                <form action="add_to_cart.php" method="POST">
                                    <input type="hidden" name="product_id" value="<?= $p['product_id'] ?>">
                                    <button type="submit" class="btn add-cart">+ Tambah ke Keranjang</button>
                                </form>
                            <?php else: ?>
                                <button class="btn disabled" disabled>Stok Habis</button>
                            <?php endif; ?>
                        </div>


                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </main>

    <footer class="site-footer">
        <div class="container">
            <p>&copy; <?= date('Y') ?> IceCream Store. All rights reserved.</p>
        </div>
    </footer>
</body>

</html>