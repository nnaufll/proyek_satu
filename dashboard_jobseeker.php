<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'jobseeker') {
    echo "Anda harus login sebagai jobseeker untuk mengakses halaman ini.";
    exit;
}

// Ambil semua pekerjaan (Anda bisa menambahkan filter di sini)
$sql = "SELECT * FROM jobs";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portoline - Jobseeker Dashboard</title>
    <link rel="stylesheet" href="style.css"> 
    <style>
        .job-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin: 16px;
            max-width: 300px;
            background-color: #fff;
            display: inline-block;
            vertical-align: top;
            text-align: center;
        }

        .job-card img {
            width: 100%;
            height: auto;
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
    </style>
</head>
<body>
    <div class="dashboard-container">
        <header>
            <div class="logo">
                <img src="logo.png" alt="Portoline Logo">
            </div>
            <nav>
                <a href="create_cv.php">Create CV</a>
                <a href="job_seeker.php">Jobs</a>
                <a href="profile.php">Profile</a>
                <a href="index.html">Logout</a>
            </nav>
        </header>

        <h2>Rekomendasi Pekerjaan</h2> 
        <div class="job-list">  
            <?php while ($job = $result->fetch_assoc()) { ?>
                <div class="job-card">
                    <img src="<?php echo htmlspecialchars($job['image_path']); ?>" alt="Job Image">
                    <h3><?php echo htmlspecialchars($job['title']); ?></h3>
                    <p><?php echo htmlspecialchars($job['category']); ?></p>
                    <p><?php echo htmlspecialchars($job['description']); ?></p>
                    <p class="location">Lokasi: <?php echo htmlspecialchars($job['location']); ?></p>
                    <a href="job_detail.php?id=<?php echo $job['id']; ?>">Lihat Detail</a> 
                </div>
            <?php } ?>
        </div>

    </div>
</body>
</html>