<?php
include 'db_connect.php';  // Pastikan koneksi ke database sudah benar

$popup_message = "";  // Variabel untuk menyimpan pesan popup

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Cek apakah email ada di database
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Email ditemukan, lanjutkan untuk mengubah password
        if ($new_password === $confirm_password) {
            // Hash password baru
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Update password di database
            $update_sql = "UPDATE users SET password = '$hashed_password' WHERE email = '$email'";

            if ($conn->query($update_sql) === TRUE) {
                $popup_message = "Password berhasil diubah. Silakan login dengan password baru.";
                header("Location: login.php");  // Redirect ke halaman login setelah reset password
                exit();
            } else {
                $popup_message = "Terjadi kesalahan saat mengubah password: " . $conn->error;
            }
        } else {
            $popup_message = "Password baru dan konfirmasi password tidak cocok!";
        }
    } else {
        $popup_message = "Email tidak ditemukan!";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portoline - Reset Password</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-container">
        <div class="image-section">
            <!-- Background image applied via CSS -->
        </div>
        <div class="login-section">
            <h2>Reset Password</h2>
            <p>Masukkan email dan password baru Anda</p>
            <form action="reset_password.php" method="POST">
                <input type="email" name="email" placeholder="Masukkan email" required>
                <input type="password" name="new_password" placeholder="Password Baru" required>
                <input type="password" name="confirm_password" placeholder="Konfirmasi Password" required>
                <button type="submit" class="btn reset-btn">Reset Password</button>
            </form>
        </div>
    </div>
</body>
</html>
