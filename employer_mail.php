<?php
session_start();
include 'db_connect.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); // Menampilkan error MySQL

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'employer') {
    echo "Anda harus login sebagai employer untuk mengakses halaman ini.";
    exit;
}

$employer_id = $_SESSION['user_id'];

try {
    // Ambil data lamaran pekerjaan untuk employer ini
    $sql = "SELECT a.*, j.title AS job_title, u.name AS jobseeker_name 
            FROM applications a
            JOIN jobs j ON a.job_id = j.id
            JOIN users u ON a.jobseeker_id = u.id
            WHERE a.employer_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $employer_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Periksa hasil query
    if ($result->num_rows > 0) {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Mail Employer</title>
            <link rel="stylesheet" href="style.css">
        </head>
        <body>
        <style>
        .back-button {
        display: inline-block;
        margin-top: 20px;
        padding: 10px 15px;
        color: white;
        background-color: #007bff;
        text-decoration: none;
        border-radius: 5px;
        }
        table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

th, td {
  padding: 15px;
  text-align: left;
  border-bottom: 1px solid #ddd;
}

th {
  background-color: #f5f5f5;
  font-weight: bold;
}

tr:nth-child(even) {
  background-color: #f9f9f9;
}

tr:hover {
  background-color: #f0f0f0; 
}

a {
  color: #28a745;
  text-decoration: none;
}

a:hover {
  text-decoration: underline;
}
        
        </style>
        <br>
            <h1 style="text-align: center;">Mail Employer</h1>

            <table>
                <thead>
                    <tr>
                        <th>Job Title</th>
                        <th>Jobseeker Name</th>
                        <th>CV</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($application = $result->fetch_assoc()) { 
                        // var_dump($application); // Untuk debugging
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($application['job_title']); ?></td>
                            <td><?php echo htmlspecialchars($application['jobseeker_name']); ?></td>
                            <td><a href="<?php echo htmlspecialchars($application['cv_path']); ?>" download>Download CV</a></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <a href="dashboard_employer.php" class="back-button">Kembali ke Dashboard</a>
        
        </body>
        </html>
        <?php
    } else {
        echo "Tidak ada lamaran yang ditemukan.";
    }

} catch (mysqli_sql_exception $e) {
    echo "Error: " . $e->getMessage();
}
?>