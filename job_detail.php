<?php
session_start();
include 'db_connect.php';

if (isset($_GET['id'])) {
    $job_id = $_GET['id'];

    $sql = "SELECT * FROM jobs WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $job_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $job = $result->fetch_assoc();

    if (!$job) {
        echo "Pekerjaan tidak ditemukan.";
        exit;
    }
} else {
    echo "ID pekerjaan tidak valid.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pekerjaan</title>
    <style>
        body {
            font-family: sans-serif;
            margin: 20px;
        }

        h1 {
            color: #333;
        }

        img {
            max-width: 300px; 
            height: auto; 
            display: block; 
            margin: 20px auto; 
            border: 1px solid #ddd; 
            transition: transform 0.3s ease; /* Tambahkan transisi */
        }

        img:hover {
            transform: scale(1.1); /* Perbesar gambar saat dihover */
        }

        .job-details {
            border: 1px solid #ddd;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Tambahkan box shadow */
            transition: box-shadow 0.3s ease; /* Tambahkan transisi */
        }

        .job-details:hover {
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); /* Pertebal box shadow saat dihover */
        }

        .job-details p {
            margin-bottom: 10px;
        }

        .job-details strong {
            font-weight: bold;
        }

        button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease; /* Tambahkan transisi */
        }

        button:hover {
            background-color: #0056b3; /* Ubah warna tombol saat dihover */
        }

        .back-button {
            display: inline-block;
            margin-top: 20px;
            background-color: #6c757d; 
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            text-decoration: none; 
            transition: background-color 0.3s ease; /* Tambahkan transisi */
        }

        .back-button:hover {
            background-color: #5a6268; /* Ubah warna tombol saat dihover */
        }
    </style>
</head>
<body>

    <h1><?php echo htmlspecialchars($job['title']); ?></h1>

    <img src="<?php echo htmlspecialchars($job['image_path']); ?>" alt="Job Image">

    <div class="job-details">
        <p><strong>Kategori:</strong> <?php echo htmlspecialchars($job['category']); ?></p>
        <p><strong>Deskripsi:</strong> <?php echo htmlspecialchars($job['description']); ?></p>
        <p><strong>Lokasi:</strong> <?php echo htmlspecialchars($job['location']); ?></p>
        <p><strong>Usia:</strong> <?php echo htmlspecialchars($job['min_age']) . " - " . htmlspecialchars($job['max_age']); ?> tahun</p>
        <p><strong>Jenis Kelamin:</strong> <?php echo htmlspecialchars($job['gender']); ?></p>
    </div>

    <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'jobseeker') { ?>
        <form action="apply_job.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="job_id" value="<?php echo $job['id']; ?>">
            <input type="file" name="cv" accept=".pdf, .doc, .docx" required>
            <button type="submit">Lamar Pekerjaan</button>
        </form>
    <?php } ?>

    <a href="dashboard_jobseeker.php" class="back-button">Kembali ke Dashboard</a> 

</body>
</html>