<?php
session_start();
require_once "../config/db.php";

// Cek login
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    $image = null;
    if (!empty($_FILES['image']['name'])) {
        $image = time() . "_" . basename($_FILES['image']['name']);
        $target = "../images/products/" . $image;
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
    }

    $stmt = $conn->prepare("INSERT INTO products (name, description, price, stock, image) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdis", $name, $description, $price, $stock, $image);
    $stmt->execute();

    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tambah Produk Baru | Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/admin-dashboard.css">
    <style>
        body {
            background-color: #f5f7fa;
            font-family: "Poppins", sans-serif;
        }

        .form-container {
            width: 480px;
            margin: 80px auto;
            background: #fff;
            border-radius: 16px;
            padding: 30px 35px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 25px;
        }

        label {
            display: block;
            font-weight: 600;
            margin-top: 10px;
            margin-bottom: 5px;
            color: #444;
        }

        input[type="text"],
        input[type="number"],
        textarea,
        input[type="file"] {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
            margin-bottom: 12px;
        }

        textarea {
            resize: vertical;
            height: 90px;
        }

        .btn {
            display: inline-block;
            background-color: #00bfff;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: 0.2s;
        }

        .btn:hover {
            background-color: #009cd3;
        }

        .btn-back {
            background-color: #aaa;
            margin-left: 10px;
        }

        .btn-back:hover {
            background-color: #888;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <h2>➕ Tambah Produk Baru</h2>

        <form method="POST" enctype="multipart/form-data">
            <label>Nama Produk</label>
            <input type="text" name="name" required>

            <label>Deskripsi</label>
            <textarea name="description" required></textarea>

            <label>Harga</label>
            <input type="number" name="price" step="0.01" required>

            <label>Stok</label>
            <input type="number" name="stock" required>

            <label>Gambar Produk</label>
            <input type="file" name="image" accept="image/*">

            <div style="text-align:center; margin-top:20px;">
                <button type="submit" class="btn">💾 Simpan</button>
                <a href="dashboard.php" class="btn btn-back">← Kembali</a>
            </div>
        </form>
    </div>
</body>

</html>