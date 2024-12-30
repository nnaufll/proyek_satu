<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION['name'] = $_POST['name'];
    $_SESSION['job_title'] = $_POST['job_title'];
    $_SESSION['phone'] = $_POST['phone'];
    $_SESSION['email'] = $_POST['email'];
    $_SESSION['address'] = $_POST['address']; // Menyimpan tempat tinggal
    $_SESSION['insta'] = $_POST['insta'];
    $_SESSION['profile'] = $_POST['profile'];
    $_SESSION['education'] = $_POST['education'];
    $_SESSION['experience'] = $_POST['experience'];
    $_SESSION['skills'] = $_POST['skills'];
    $_SESSION['soskills'] = $_POST['soskills'];
    $_SESSION['languages'] = $_POST['languages'];

    // Redirect to generate_cv.php
    header("Location: generate_cv.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create CV</title>
    <link rel="stylesheet" href="style.css">
    <style>
    .cv-form-container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: calc(100vh - 80px);
    background: url('illustration.png') no-repeat center center fixed; /* Menambahkan background image */
    background-size: cover; /* Agar background memenuhi layar */
    }

    .form-card {
    background-color: rgba(255, 255, 255, 0.9); /* Transparansi untuk form card */
    padding: 30px;
    border-radius: 20px;
    box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
    width: 600px;
    backdrop-filter: blur(10px); /* Menambahkan efek blur untuk background form */
    }

        .form-card:hover {
            box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.2);
            transform: translateY(-5px);
        }
        .form-card h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-size: 14px;
            color: #333;
        }
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            outline: none;
            transition: border-color 0.3s ease;
        }
        .form-group input:focus,
        .form-group textarea:focus {
            border-color: #007bff;
        }
        .form-actions {
            text-align: center;
        }
        .btn-primary {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }
        .btn-secondary {
        background-color: #6c757d;
        color: #fff;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease, transform 0.2s ease;
        text-decoration: none;
        display: inline-block;
        margin-top: 10px;
        }

        .btn-secondary:hover {
        background-color: #565e64;
        transform: translateY(-2px);
        }

    </style>
</head>
<body>
    <div class="cv-form-container">
        <div class="form-card">
            <h2>Create Your CV</h2>
            <form action="create_cv.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="job_title">Job Title:</label>
                    <input type="text" id="job_title" name="job_title" required>
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number:</label>
                    <input type="text" id="phone" name="phone" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="insta">Instagram:</label>
                    <input type="insta" id="insta" name="insta" required>
                </div>
                <div class="form-group">
                    <label for="address">Tempat Tinggal:</label>
                    <input type="text" id="address" name="address" required>
                </div>
                <div class="form-group">
                    <label for="profile">Profile:</label>
                    <textarea id="profile" name="profile" required></textarea>
                </div>
                <div class="form-group">
                    <label for="education">Education:</label>
                    <textarea id="education" name="education" required></textarea>
                </div>
                <div class="form-group">
                    <label for="experience">Work Experience:</label>
                    <textarea id="experience" name="experience" required></textarea>
                </div>
                <div class="form-group">
                    <label for="skills">Hard Skills (comma-separated):</label>
                    <input type="text" id="skills" name="skills" required>
                </div>
                <div class="form-group">
                    <label for="soskills">Soft Skills (comma-separated):</label>
                    <input type="text" id="soskills" name="soskills" required>
                </div>
                <div class="form-group">
                    <label for="languages">Languages (comma-separated):</label>
                    <input type="text" id="languages" name="languages" required>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn-primary">Generate CV</button>
                </div>
                <a href="dashboard_jobseeker.php" class="btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</body>
</html>
