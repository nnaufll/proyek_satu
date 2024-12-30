<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id']) && $_SESSION['role'] === 'jobseeker') {
    $job_id = $_POST['job_id'];
    $jobseeker_id = $_SESSION['user_id'];

    $cv_path = null;
    if (isset($_FILES['cv']) && $_FILES['cv']['error'] === 0) {
        $target_dir = "uploads/cvs/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $cv_path = $target_dir . time() . '_' . basename($_FILES['cv']['name']);
        $cv_file_type = strtolower(pathinfo($cv_path, PATHINFO_EXTENSION));

        if (in_array($cv_file_type, ["pdf", "doc", "docx"])) { 
            if (!move_uploaded_file($_FILES["cv"]["tmp_name"], $cv_path)) {
                echo "Gagal mengunggah CV.";
                exit;
            }
        } else {
            echo "Format CV tidak valid. Format yang didukung: PDF, DOC, DOCX.";
            exit;
        }
    } else {
        echo "Gagal mengunggah CV.";
        exit;
    }

    // Ambil employer_id dari tabel jobs
    $sql = "SELECT employer_id FROM jobs WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $job_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $employer_id = $row['employer_id'];

    // Simpan data lamaran ke database (tabel applications)
    $sql = "INSERT INTO applications (job_id, jobseeker_id, employer_id, cv_path) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiis", $job_id, $jobseeker_id, $employer_id, $cv_path);

    if ($stmt->execute()) {
        echo "Lamaran pekerjaan berhasil dikirim.";
        // Redirect ke halaman dashboard atau halaman lain
        header("Location: dashboard_jobseeker.php"); 
        exit;
    } else {
        echo "Terjadi kesalahan: " . $conn->error;
    }
} else {
    echo "Permintaan tidak valid.";
}