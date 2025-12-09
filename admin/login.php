<?php
session_start();
require_once "../config/db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT admin_id, username, password FROM admins WHERE username = ?");
    if (!$stmt) {
        die("Prepare gagal: " . $conn->error);
    }
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['admin_id'] = $row['admin_id'];
            $_SESSION['admin_username'] = $row['username'];
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "⚠️ Password salah!";
        }
    } else {
        $error = "⚠️ Username tidak ditemukan!";
    }
    $stmt->close();
}
?>




<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Admin Login | IceCream Store</title>
    <link rel="stylesheet" href="../assets/css/admin-auth.css">
</head>

<body>
    <div class="login-container">
        <h2>Admin Login</h2>
        <?php if (!empty($error)): ?>
            <p class="error-message"><?php echo $error; ?></p>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit">Login</button>
        </form>
        <p>Belum punya akun admin? <a href="register.php">Daftar</a></p>
    </div>
</body>

</html>