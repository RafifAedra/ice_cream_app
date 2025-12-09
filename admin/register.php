<?php
require_once "../config/db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);      
    $email = trim($_POST["email"] ?? '');     
    $password_plain = trim($_POST["password"]);

    
    $hashed_password = password_hash($password_plain, PASSWORD_DEFAULT);

    
    $query = "INSERT INTO admins (username, password, email, created_at) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die("Prepare gagal: " . $conn->error);
    }
    $stmt->bind_param("sss", $username, $hashed_password, $email);

    if ($stmt->execute()) {
        $message = "✅ Pendaftaran berhasil! Silakan login.";
    } else {
        $message = "❌ Gagal mendaftar: " . htmlspecialchars($stmt->error);
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Register Admin | IceCream Store</title>
    <link rel="stylesheet" href="../assets/css/admin-auth.css">
</head>

<body>
    <div class="login-wrapper">
        <div class="login-container">
            <h2>Daftar Admin</h2>

            <?php if (!empty($message)) echo "<p class='message'>$message</p>"; ?>

            <form method="POST">
                <input type="text" name="username" placeholder="Username" required><br>
                <input type="email" name="email" placeholder="Email"><br>
                <input type="password" name="password" placeholder="Password" required><br>
                <button type="submit">Daftar</button>
            </form>

            <p style="text-align:center; margin-top:10px;">
                Sudah punya akun?
                <a href="login.php" style="color:#00cfff; text-decoration:none; font-weight:bold;">Login</a>
            </p>
        </div>
    </div>
</body>

</html>