<?php
session_start();  // Start session

include 'db_connect.php';  // Ensure database connection is correct

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query to check if the email exists in the database
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // If email is found
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Check if the entered password matches the one in the database
        if (password_verify($password, $user['password'])) {
            // Set session variables to store user information
            $_SESSION['user_id'] = $user['id']; // Save user_id to session
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            // Redirect to the appropriate dashboard based on the role
            if ($user['role'] == 'employer') {
                header("Location: dashboard_employer.php");
                exit();
            } elseif ($user['role'] == 'jobseeker') {
                header("Location: dashboard_jobseeker.php");
                exit();
            } else {
                echo "Invalid role!";
            }
        } else {
            echo "Incorrect password!";
        }
    } else {
        echo "User not found!";
    }

    $stmt->close();
    $conn->close();
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portoline - Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-container">
        <div class="image-section">
            <!-- Transparent box remains here but without img tag, background will be applied via CSS -->
        </div>
        <div class="login-section">
            <div class="logo">
                <img src="logo.png" alt="Portoline Logo">
            </div>
            <h2>Login to Portoline</h2>
            <p>Please enter your account</p>
            <form action="login.php" method="POST"> <!-- Correct the form action -->
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit" class="btn login-btn">Login</button>
    <br>
    <br>
    <a href="reset_password.php" class="forgot-password">Forgot Password?</a>
</form>

            <!-- Hyperlink ke halaman signup -->
            <p><a href="signup.php" class="forgot-password" style="font-size: 13px;">Don't have an account? Sign up here</a></p>
        </div>
    </div>
</body>
</html>
