<?php
include 'db_connect.php';

// Ambil semua pekerjaan
$sql = "SELECT jobs.*, users.name as employer_name FROM jobs 
        JOIN users ON jobs.employer_id = users.id";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jobs</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Available Jobs</h1>
    <?php while ($job = $result->fetch_assoc()) { ?>
        <div>
            <h3><?php echo htmlspecialchars($job['title']); ?></h3>
            <p>Employer: <?php echo htmlspecialchars($job['employer_name']); ?></p>
            <p>Kategori: <?php echo htmlspecialchars($job['category']); ?></p>
            <img src="<?php echo htmlspecialchars($job['image_path']); ?>" alt="Job Image" style="width:200px;height:auto;">
            <p>Lokasi: <?php echo htmlspecialchars($job['location']); ?></p>
            <p>Deskripsi: <?php echo htmlspecialchars($job['description']); ?></p>
        </div>
    <?php } ?>
</body>
</html>
