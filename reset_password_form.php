<?php
include 'db_connect.php';  // Koneksi ke database

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Cek apakah token ada di database dan belum kadaluarsa
    $sql = "SELECT * FROM users WHERE reset_token = '$token' AND expires_at > NOW()";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);

            // Update password dan hapus token
            $sql = "UPDATE users SET password = '$new_password', reset_token = NULL, expires_at = NULL WHERE reset_token = '$token'";
            if ($conn->query($sql) === TRUE) {
                echo "Password has been reset successfully! <a href='login.php'>Login here</a>";
            } else {
                echo "Error updating password: " . $conn->error;
            }
        }
    } else {
        echo "Invalid or expired token.";
    }
} else {
    echo "No token provided.";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portoline - New Password</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-container">
        <div class="login-section">
            <h2>Enter New Password</h2>
            <form action="" method="POST">
                <input type="password" name="new_password" placeholder="New Password" required>
                <button type="submit" class="btn reset-btn">Reset Password</button>
            </form>
        </div>
    </div>
</body>
</html>
