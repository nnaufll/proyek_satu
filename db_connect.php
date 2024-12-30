<?php
$servername = "localhost";  // Nama server database, biasanya localhost
$username = "root";  // Ganti dengan username database Anda
$password = "";  // Ganti dengan password database Anda jika ada
$dbname = "portoline_db";  // Nama database Anda

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Mengecek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
