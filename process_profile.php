<?php
session_start();
include 'db_connect.php';

// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    echo "Silakan login terlebih dahulu.";
    exit;
}

$user_id = $_SESSION['user_id'];
$name = $_POST['name'];
$birth_date = $_POST['birth_date'];
$address = $_POST['address'];
$gender = $_POST['gender'];
$about = $_POST['about'];

// Upload foto profil
$profile_picture = $_FILES['profile_picture'];
$target_dir = "uploads/";
$target_file = $target_dir . basename($profile_picture["name"]);
move_uploaded_file($profile_picture["tmp_name"], $target_file);

// Simpan data ke database
$sql = "UPDATE users SET 
        name = ?, birth_date = ?, address = ?, gender = ?, about = ?, profile_picture = ? 
        WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssi", $name, $birth_date, $address, $gender, $about, $target_file, $user_id);

if ($stmt->execute()) {
    header("Location: profile.php");
} else {
    echo "Terjadi kesalahan saat menyimpan data.";
}

$stmt->close();
$conn->close();
?>
