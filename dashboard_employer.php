<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'employer') {
    echo "Anda harus login sebagai employer untuk mengakses halaman ini.";
    exit;
}

$employer_id = $_SESSION['user_id'];

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
    <title>Employer Dashboard</title>
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

        /* Tambahkan CSS untuk .job-list jika diperlukan */
        .job-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <header>
            <div class="logo">
                <img src="logo.png" alt="Company Logo">
            </div>
            <nav>
                <a href="profile_employer.php">Profile</a>
                <a href="manage_jobs.php">Manage Jobs</a>
                <a href="employer_mail.php">Mail</a>
                <a href="index.html">Logout</a>
            </nav>
        </header>

        <section class="banner">
            <br>
            <br>
            <h1>Welcome to Your Employer Dashboard</h1>
            <br>
            <h1>Let's Make Your Dream Come True!!!</h1>
        </section>

        <section class="job-category">
            <h2>Manage Your Job Listings</h2>
            <div class="job-list"> 
                <?php 
                if ($result->num_rows > 0) {
                    while ($job = $result->fetch_assoc()) { ?>
                        <div class="job-card">
                            <img src="<?php echo htmlspecialchars($job['image_path']); ?>" alt="Job Image">
                            <h3><?php echo htmlspecialchars($job['title']); ?></h3>
                            <p><?php echo htmlspecialchars($job['category']); ?></p>
                            <p><?php echo htmlspecialchars($job['description']); ?></p>
                            <p class="location">Lokasi: <?php echo htmlspecialchars($job['location']); ?></p>
                            <a href="job_detail.php?id=<?php echo $job['id']; ?>">Lihat Detail</a>
                        </div>
                <?php } 
                } else {
                    echo "<p>Belum ada job yang kamu buat.</p>";
                }
                ?>
            </div>
        </section>

    

        <footer>
            <p>&copy; 2024 Portoline. All rights reserved.</p>
        </footer>
    </div>
</body>
</html>