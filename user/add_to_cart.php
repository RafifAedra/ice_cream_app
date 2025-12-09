<?php
session_start();
include('../config/db.php');

if (isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);

    $query = $conn->query("SELECT * FROM products WHERE product_id = $product_id");
    $product = $query->fetch_assoc();

    if ($product) {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Tambah produk ke keranjang
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['qty']++;
            $_SESSION['flash_message'] = "Jumlah produk <strong>{$product['name']}</strong> berhasil ditambah di keranjang!";
        } else {
            $_SESSION['cart'][$product_id] = [
                'name' => $product['name'],
                'price' => $product['price'],
                'qty' => 1,
                'image' => $product['image']
            ];
            $_SESSION['flash_message'] = "Produk <strong>{$product['name']}</strong> berhasil ditambahkan ke keranjang!";
        }
    }
}

// Redirect kembali ke halaman home
header("Location: home.php");
exit;
