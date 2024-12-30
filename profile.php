<?php
session_start();

// Koneksi ke database
$conn = new mysqli("localhost", "username", "password", "portoline_db");

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Pastikan user telah login
if (!isset($_SESSION['user_id'])) {
    echo "User ID tidak ditemukan di sesi. Silakan login terlebih dahulu.";
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil data user dari database
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Periksa apakah user ditemukan
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    // Periksa apakah data profil lengkap
    if (
        empty($user['name']) ||
        empty($user['address']) ||
        empty($user['birth_date']) ||
        empty($user['gender'])
    ) {
        // Jika data belum lengkap, tampilkan formulir untuk melengkapi data
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Ambil data dari formulir
            $name = $conn->real_escape_string($_POST['name']);
            $address = $conn->real_escape_string($_POST['address']);
            $birth_date = $conn->real_escape_string($_POST['birth_date']);
            $gender = $conn->real_escape_string($_POST['gender']);

            // Update data ke database
            $update_sql = "UPDATE users SET name = ?, address = ?, birth_date = ?, gender = ? WHERE id = ?";
            $stmt = $conn->prepare($update_sql);
            $stmt->bind_param("ssssi", $name, $address, $birth_date, $gender, $user_id);
            if ($stmt->execute()) {
                header("Location: profile.php");
                exit;
            } else {
                echo "Terjadi kesalahan: " . $conn->error;
            }
        }
        ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lengkapi Profil</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }

        .profile-container {
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .profile-container h2 {
            color: #333;
        }

        form {
            margin-top: 20px;
        }

        form label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }

        form input[type="text"],
        form input[type="date"],
        form select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }

        form button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <h2>Lengkapi Profil Anda</h2>
        <form action="profile.php" method="post">
            <label for="name">Nama:</label>
            <input type="text" name="name" id="name" required><br><br>

            <label for="address">Alamat:</label>
            <input type="text" name="address" id="address" required><br><br>

            <label for="birth_date">Tanggal Lahir:</label>
            <input type="date" name="birth_date" id="birth_date" required><br><br>

            <label for="gender">Jenis Kelamin:</label>
            <select name="gender" id="gender" required>
                <option value="Laki-laki">Laki-laki</option>
                <option value="Perempuan">Perempuan</option>
            </select><br><br>

            <button type="submit">Simpan</button>
        </form>
    </div>
</body>
</html>


        <?php
        exit;
    }

    // Tambahkan foto profil jika form di-submit
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_picture'])) {
        $target_dir = "uploads/profile_pictures/";
        $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
        $upload_ok = 1;
        $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Validasi file gambar
        $check = getimagesize($_FILES["profile_picture"]["tmp_name"]);
        if ($check !== false) {
            $upload_ok = 1;
        } else {
            echo "File bukan gambar.";
            $upload_ok = 0;
        }

        // Validasi tipe file
        if ($image_file_type != "jpg" && $image_file_type != "png" && $image_file_type != "jpeg") {
            echo "Hanya file JPG, JPEG, dan PNG yang diperbolehkan.";
            $upload_ok = 0;
        }

        // Upload file
        if ($upload_ok == 1) {
            if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
                // Update path di database
                $update_sql = "UPDATE users SET profile_picture = ? WHERE id = ?";
                $stmt = $conn->prepare($update_sql);
                $stmt->bind_param("si", $target_file, $user_id);
                $stmt->execute();
                header("Location: profile.php");
                exit;
            } else {
                echo "Terjadi kesalahan saat mengunggah file.";
            }
        }
    }

    // Tambahkan sertifikat jika form di-submit
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['certificate'])) {
        $target_dir = "uploads/certificates/";
        $target_file = $target_dir . basename($_FILES["certificate"]["name"]);
        $upload_ok = 1;
        $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Validasi file gambar
        $check = getimagesize($_FILES["certificate"]["tmp_name"]);
        if ($check !== false) {
            $upload_ok = 1;
        } else {
            echo "File bukan gambar.";
            $upload_ok = 0;
        }

        // Validasi tipe file
        if ($image_file_type != "jpg" && $image_file_type != "png" && $image_file_type != "jpeg") {
            echo "Hanya file JPG, JPEG, dan PNG yang diperbolehkan.";
            $upload_ok = 0;
        }

        // Upload file
        if ($upload_ok == 1) {
            if (move_uploaded_file($_FILES["certificate"]["tmp_name"], $target_file)) {
                // Simpan path di database
                $insert_sql = "INSERT INTO certificates (user_id, certificate_path) VALUES (?, ?)";
                $stmt = $conn->prepare($insert_sql);
                $stmt->bind_param("is", $user_id, $target_file);
                $stmt->execute();
                header("Location: profile.php");
                exit;
            } else {
                echo "Terjadi kesalahan saat mengunggah file.";
            }
        }
    }

    ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }

        .profile-container {
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .profile-picture {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 20px;
        }

        h1 {
            font-size: 24px;
            margin: 0;
            color: #333;
        }

        p {
            margin: 5px 0;
            color: #555;
        }

        .certificates {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 20px;
        }

        .certificate-image {
            width: 150px;
            height: 200px;
            object-fit: cover;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .back-button {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 15px;
            color: white;
            background-color: #007bff;
            text-decoration: none;
            border-radius: 5px;
        }

        form {
            margin-top: 20px;
        }

        form label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }

        form input[type="file"],
        form button {
            margin-bottom: 15px;
        }

        form button {
            display: inline-block;
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <div style="text-align: center;">
            <img src="<?php echo !empty($user['profile_picture']) ? htmlspecialchars($user['profile_picture']) : 'default-profile.png'; ?>" alt="Foto Profil" class="profile-picture">

            <h1><?php echo htmlspecialchars($user['name']); ?></h1>
            <p><strong>Alamat:</strong> <?php echo htmlspecialchars($user['address']); ?></p>
            <p><strong>Tanggal Lahir:</strong> <?php echo htmlspecialchars($user['birth_date']); ?></p>
            <p><strong>Jenis Kelamin:</strong> <?php echo htmlspecialchars($user['gender']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
        </div>

        <form action="profile.php" method="post" enctype="multipart/form-data">
            <label for="profile_picture">Unggah Foto Profil:</label>
            <input type="file" name="profile_picture" id="profile_picture" required>
            <button type="submit">Simpan Foto Profil</button>
        </form>

        <h2>Certificate/Portofolio</h2>
        <div class="certificates">
            <?php
            // Ambil sertifikat user
            $cert_sql = "SELECT * FROM certificates WHERE user_id = ?";
            $stmt = $conn->prepare($cert_sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $cert_result = $stmt->get_result();

            if ($cert_result->num_rows > 0) {
                while ($cert = $cert_result->fetch_assoc()) {
                    echo "<img src='" . htmlspecialchars($cert['certificate_path']) . "' alt='Sertifikat' class='certificate-image'>";
                }
            } else {
                echo "<p>Belum ada sertifikat.</p>";
            }
            ?>
        </div>

        <form action="profile.php" method="post" enctype="multipart/form-data">
            <label for="certificate">Unggah Sertifikat:</label>
            <input type="file" name="certificate" id="certificate" required>
            <button type="submit">Tambah Sertifikat</button>
        </form>

        <a href="dashboard_jobseeker.php" class="back-button">Kembali ke Dashboard</a>
    </div>
</body>
</html>

    <?php
} else {
    echo "User tidak ditemukan.";
}

$conn->close();
?>
