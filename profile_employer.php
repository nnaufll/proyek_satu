<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    echo "Anda harus login terlebih dahulu.";
    exit;
}

$user_id = $_SESSION['user_id'];

// Query untuk mengambil data employer berdasarkan role
$sql = "SELECT * FROM users WHERE id = ? AND role = 'employer'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Periksa apakah data ditemukan
if ($result->num_rows === 0) {
    echo "Data employer tidak ditemukan.";
    exit;
}

$employer = $result->fetch_assoc();

// Cek apakah profil sudah lengkap
$profileComplete = !empty($employer['address']) && !empty($employer['company']) && !empty($employer['phone']);

// Jika form disubmit, update data profil
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['address']) && isset($_POST['company']) && isset($_POST['phone'])) {
    $address = $_POST['address'];
    $company = $_POST['company'];
    $phone = $_POST['phone'];

    // Update data di database
    $update_sql = "UPDATE users SET address = ?, company = ?, phone = ? WHERE id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("sssi", $address, $company, $phone, $user_id);

    if ($stmt->execute()) {
        // Redirect atau tampilkan pesan sukses
        header("Location: profile_employer.php"); // Redirect ke halaman profil
        exit;
    } else {
        echo "Terjadi kesalahan saat mengupdate profil.";
    }
}

// Tambahkan foto profil jika form di-submit
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_picture'])) {
    $target_dir = "uploads/profile_pictures/";
    $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    // Cek apakah file gambar valid
    $check = getimagesize($_FILES["profile_picture"]["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        echo "File bukan gambar.";
        $uploadOk = 0;
    }

    // Cek jika file sudah ada
    if (file_exists($target_file)) {
        echo "Maaf, file sudah ada.";
        $uploadOk = 0;
    }

    // Batasi ukuran file (misalnya 5MB)
    if ($_FILES["profile_picture"]["size"] > 5000000) {
        echo "Maaf, file terlalu besar.";
        $uploadOk = 0;
    }

    // Izinkan ekstensi file tertentu
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        echo "Maaf, hanya file JPG, JPEG, PNG & GIF yang diizinkan.";
        $uploadOk = 0;
    }

    // Cek jika $uploadOk = 0 karena error
    if ($uploadOk == 0) {
        echo "Maaf, file Anda gagal diunggah.";
    } else {
        if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
            // Simpan path file di database
            $profile_picture_path = $target_file; 
            $sql = "UPDATE users SET profile_picture = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $profile_picture_path, $user_id);

            if ($stmt->execute()) {
                echo "Foto profil berhasil diunggah.";
            } else {
                echo "Terjadi kesalahan saat menyimpan foto profil.";
            }
        } else {
            echo "Maaf, terjadi error saat mengunggah file Anda.";
        }
    }
}

// Tambahkan sertifikat jika form di-submit
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['certificate'])) {
    $target_dir = "uploads/certificates/";
    $target_file = $target_dir . basename($_FILES["certificate"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    // Cek apakah file gambar valid
    $check = getimagesize($_FILES["certificate"]["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        echo "File bukan gambar.";
        $uploadOk = 0;
    }

    // Cek jika file sudah ada
    if (file_exists($target_file)) {
        echo "Maaf, file sudah ada.";
        $uploadOk = 0;
    }

    // Batasi ukuran file (misalnya 5MB)
    if ($_FILES["certificate"]["size"] > 5000000) {
        echo "Maaf, file terlalu besar.";
        $uploadOk = 0;
    }

    // Izinkan ekstensi file tertentu
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        echo "Maaf, hanya file JPG, JPEG, PNG & GIF yang diizinkan.";
        $uploadOk = 0;
    }

    // Cek jika $uploadOk = 0 karena error
    if ($uploadOk == 0) {
        echo "Maaf, file Anda gagal diunggah.";
    } else {
        if (move_uploaded_file($_FILES["certificate"]["tmp_name"], $target_file)) {
            // Simpan path file di database
            $certificate_path = $target_file;
            $sql = "INSERT INTO certificates (user_id, certificate_path) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("is", $user_id, $certificate_path);

            if ($stmt->execute()) {
                echo "Foto berhasil diunggah.";
            } else {
                echo "Terjadi kesalahan saat menyimpan sertifikat.";
            }
        } else {
            echo "Maaf, terjadi error saat mengunggah file Anda.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Employer</title>
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

    form select {
     width: 100%;
     padding: 10px;
     margin-bottom: 15px;
     border: 1px solid #ddd;
     border-radius: 5px;
     box-sizing: border-box;
    }

    form input[type="file"], 
    form button { 
     margin-bottom: 15px; 
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
        <?php if (!$profileComplete) : ?> 
            <h2>Lengkapi Profil Anda</h2>
            <form action="profile_employer.php" method="post">
                <label for="address">Alamat:</label>
                <input type="text" name="address" id="address" value="<?php echo htmlspecialchars($employer['address']); ?>" required><br>

                <label for="company">Perusahaan:</label>
                <input type="text" name="company" id="company" value="<?php echo htmlspecialchars($employer['company']); ?>" required><br>

                <label for="phone">Nomor Telepon:</label>
                <input type="text" name="phone" id="phone" value="<?php echo htmlspecialchars($employer['phone']); ?>" required><br>

                <button type="submit">Simpan Profil</button>
            </form>
        <?php else : ?>
            <div style="text-align: center;">
                <img src="<?php echo !empty($employer['profile_picture']) ? htmlspecialchars($employer['profile_picture']) : 'default-profile.png'; ?>" alt="Foto Profil" class="profile-picture">

                <h1><?php echo htmlspecialchars($employer['name']); ?></h1>
                <p><strong>Alamat:</strong> <?php echo htmlspecialchars($employer['address']); ?></p>
                <p><strong>Perusahaan:</strong> <?php echo htmlspecialchars($employer['company']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($employer['email']); ?></p>
                <p><strong>Nomor Telepon:</strong> <?php echo htmlspecialchars($employer['phone']); ?></p>
            </div>

            <form action="profile_employer.php" method="post" enctype="multipart/form-data">
                <label for="profile_picture">Unggah Foto Profil:</label>
                <input type="file" name="profile_picture" id="profile_picture">
                <button type="submit">Simpan Foto Profil</button>
            </form>

            <h2>Foto Perusahaan</h2>
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
                    echo "<p>Belum ada foto.</p>";
                }
                ?>
            </div>

            <form action="profile_employer.php" method="post" enctype="multipart/form-data">
                <label for="certificate">Unggah Foto Perusahaan:</label>
                <input type="file" name="certificate" id="certificate">
                <button type="submit">Tambah Foto</button>
            </form>

        <?php endif; ?> 

        <a href="dashboard_employer.php" class="back-button">Kembali ke Dashboard</a>
    </div>
</body>
</html>

<?php
$conn->close();
?>