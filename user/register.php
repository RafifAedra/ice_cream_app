<?php
include('../config/db.php');

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    if (!$conn) {
        die("Koneksi ke database gagal: " . mysqli_connect_error());
    }

    
    $check = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
    if (!$check) {
        die("Query cek email gagal: " . $conn->error);
    }
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $error = "Email sudah digunakan. Silakan pakai email lain.";
        $check->close();
    } else {
        $check->close();

        // Insert user baru
        $query = $conn->prepare("INSERT INTO users (nama, email, password) VALUES (?, ?, ?)");
        if (!$query) {
            die("Query insert gagal: " . $conn->error);
        }

        $query->bind_param("sss", $nama, $email, $password);
        if ($query->execute()) {
            $success = "Pendaftaran berhasil! Silakan login.";
        } else {
            $error = "Gagal mendaftar. Silakan coba lagi.";
        }

        $query->close();
    }

    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Register | IceCream Store</title>
    <link rel="stylesheet" href="../assets/css/user-auth.css">
</head>

<body>
    <div class="container">
        <h2>Daftar Akun</h2>
        <?php if (!empty($success)) echo "<p class='success' style='color:green;'>$success</p>"; ?>
        <?php if (!empty($error)) echo "<p class='error' style='color:red;'>$error</p>"; ?>
        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST">
            <input type="text" name="name" placeholder="Nama Lengkap" required><br>
            <input type="email" name="email" placeholder="Email" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit">Daftar</button>
        </form>
        <p>Sudah punya akun? <a href="login.php">Login</a></p>
    </div>
</body>

</html>