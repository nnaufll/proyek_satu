<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'employer') {
    echo "Anda harus login sebagai employer untuk mengakses halaman ini.";
    exit;
}

$employer_id = $_SESSION['user_id'];

// Proses tambah pekerjaan 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $conn->real_escape_string($_POST['title']);
    $category = $conn->real_escape_string($_POST['category']);
    $min_age = (int)$_POST['min_age'];
    $max_age = (int)$_POST['max_age'];
    $gender = $conn->real_escape_string($_POST['gender']);
    $location = $conn->real_escape_string($_POST['location']);
    $description = $conn->real_escape_string($_POST['description']);

    $image_path = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $target_dir = "uploads/job_images/";
        // Periksa apakah folder ada, jika tidak, buat folder
        if (!is_dir($target_dir)) {
            if (!mkdir($target_dir, 0777, true)) {
                die("Gagal membuat folder untuk menyimpan gambar.");
            }
        }

        $image_path = $target_dir . time() . '_' . basename($_FILES['image']['name']);
        $image_file_type = strtolower(pathinfo($image_path, PATHINFO_EXTENSION));

        // Validasi gambar
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false && in_array($image_file_type, ["jpg", "jpeg", "png"])) {
            if (!move_uploaded_file($_FILES["image"]["tmp_name"], $image_path)) {
                echo "Gagal mengunggah file gambar.";
                $image_path = null;
            }
        } else {
            echo "File bukan gambar atau format tidak valid. Format yang didukung: JPG, JPEG, PNG.";
            $image_path = null;
        }
    }

    $sql = "INSERT INTO jobs (employer_id, title, category, min_age, max_age, gender, location, description, image_path) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issssssss", $employer_id, $title, $category, $min_age, $max_age, $gender, $location, $description, $image_path);

    if ($stmt->execute()) {
        echo "Pekerjaan berhasil ditambahkan.";
    } else {
        echo "Terjadi kesalahan: " . $conn->error;
    }
}

// Ambil semua pekerjaan yang telah diposting employer
$sql = "SELECT * FROM jobs WHERE employer_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $employer_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Jobs</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            background-color: #f4f4f4;
        }

        h1 {
            color: #333;
        }

        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            width: 100%;
        }

        .job-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .job-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            background-color: #fff;
            text-align: center;
        }

        .job-card img {
            width: 100%;
            height: 200px; /* Tinggi gambar tetap */
            object-fit: cover;
        }

        .job-card:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .job-card h3 {
            font-size: 1.5rem;
            margin: 16px 0 8px;
        }

        .job-card p {
            color: #666;
            font-size: 1rem;
            margin: 8px 16px;
        }

        .job-card .location {
            font-weight: bold;
            color: #333;
            margin-bottom: 16px;
        }

        .back-button {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        form {
            margin-top: 20px;
            display: flex;
            flex-direction: column;
            width: 100%;
        }

        form label {
            margin-bottom: 5px;
        }

        form input, form textarea, form select {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        form button {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Manage Jobs</h1>

        <form action="manage_jobs.php" method="POST" enctype="multipart/form-data">
            <label for="title">Judul Pekerjaan:</label>
            <input type="text" name="title" id="title" required><br>

            <label for="category">Kategori:</label>
            <input type="text" name="category" id="category" required><br>

            <label for="min_age">Usia Minimum:</label>
            <input type="number" name="min_age" id="min_age" required><br>

            <label for="max_age">Usia Maksimum:</label>
            <input type="number" name="max_age" id="max_age" required><br>

            <label for="gender">Jenis Kelamin:</label>
            <select name="gender" id="gender">
                <option value="Semua">Semua</option>
                <option value="Laki-laki">Laki-laki</option>
                <option value="Perempuan">Perempuan</option>
            </select><br>

            <label for="location">Lokasi:</label>
            <input type="text" name="location" id="location" required><br>

            <label for="description">Deskripsi:</label>
            <textarea name="description" id="description" required></textarea><br>

            <label for="image">Gambar (Rasio 9:16):</label>
            <input type="file" name="image" id="image" accept="image/*"><br>

            <button type="submit">Tambahkan Pekerjaan</button>
        </form>

        <h2>Daftar Pekerjaan Anda</h2>
        <div class="job-list">
            <?php while ($job = $result->fetch_assoc()) { ?>
                <div class="job-card">
                    <img src="<?php echo htmlspecialchars($job['image_path']); ?>" alt="Job Image">
                    <h3><?php echo htmlspecialchars($job['title']); ?></h3>
                    <p><?php echo htmlspecialchars($job['category']); ?></p>
                    <p><?php echo htmlspecialchars($job['description']); ?></p>
                    <p class="location">Lokasi: <?php echo htmlspecialchars($job['location']); ?></p>
                </div>
            <?php } ?>
        </div>
    </div>
    <a href="dashboard_employer.php" class="back-button">Kembali ke Dashboard</a>
</body>
</html>