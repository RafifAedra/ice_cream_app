<?php
session_start();
require_once "../config/db.php";

// Cek  admin sudah login
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// Ambil semua data produk
$result = $conn->query("SELECT * FROM products ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin | IceCream Store</title>
    <link rel="stylesheet" href="../assets/css/admin-dashboard.css">
</head>

<body>
    <div class="dashboard-container">
        <h1>🍦 Dashboard Admin</h1>
        <p>Selamat datang, <strong><?php echo $_SESSION['admin_username']; ?></strong></p>
        <a href="add_product.php" class="btn">+ Tambah Produk</a>
        <a href="logout.php" class="btn btn-logout">Logout</a>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Produk</th>
                    <th>Deskripsi</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Gambar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['product_id']; ?></td>
                        <td><?= htmlspecialchars($row['name']); ?></td>
                        <td><?= htmlspecialchars($row['description']); ?></td>
                        <td>Rp <?= number_format($row['price'], 0, ',', '.'); ?></td>
                        <td><?= $row['stock']; ?></td>
                        <td>
                            <?php
                            $imagePath = "../images/products/" . $row['image'];
                            if (!empty($row['image']) && file_exists($imagePath)): ?>
                                <img src="<?= $imagePath; ?>" alt="<?= htmlspecialchars($row['name']); ?>" class="product-img">
                            <?php else: ?>
                                <em style="color: #777;">Tidak ada gambar</em>
                            <?php endif; ?>
                        </td>

                        <td>
                            <a href="edit_product.php?id=<?= $row['product_id']; ?>" class="btn-edit">Edit</a>
                            <a href="delete_product.php?id=<?= $row['product_id']; ?>" class="btn-delete" onclick="return confirm('Yakin mau hapus?')">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>

</html>