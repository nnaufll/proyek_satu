<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

require_once('tcpdf/tcpdf.php'); // Sertakan library TCPDF

// Terima pilihan template dan data lainnya dari form
$template = $_GET['template'];
$name = $_GET['name'];
$job_title = $_GET['job_title'];
$phone = $_GET['phone'];
$email = $_GET['email'];
$profile = $_GET['profile'];
$education = $_GET['education'];
$experience = $_GET['experience'];
$skills = $_GET['skills'];
$languages = $_GET['languages'];
$photo_path = $_GET['photo_path']; // Pastikan Anda mengirimkan path foto juga

// Fungsi untuk membuat PDF CV (sama seperti di choose_template.php)
function generateCVTemplate1($name, $job_title, $phone, $email, $profile, $education, $experience, $skills, $languages, $photo_path) {
    $pdf = new TCPDF();
    // ... (kode untuk membuat CV dengan template 1 menggunakan TCPDF) ...
    return $pdf->Output('', 'S'); // Kembalikan PDF sebagai string
}

function generateCVTemplate2($name, $job_title, $phone, $email, $profile, $education, $experience, $skills, $languages, $photo_path) {
    $pdf = new TCPDF();
    // ... (kode untuk membuat CV dengan template 2 menggunakan TCPDF) ...
    return $pdf->Output('', 'S'); // Kembalikan PDF sebagai string
}

function generateCVTemplate3($name, $job_title, $phone, $email, $profile, $education, $experience, $skills, $languages, $photo_path) {
    $pdf = new TCPDF();
    // ... (kode untuk membuat CV dengan template 3 menggunakan TCPDF) ...
    return $pdf->Output('', 'S'); // Kembalikan PDF sebagai string
}

// Generate PDF berdasarkan pilihan template
switch ($template) {
    case '1':
        $pdf = generateCVTemplate1($name, $job_title, $phone, $email, $profile, $education, $experience, $skills, $languages, $photo_path);
        break;
    case '2':
        $pdf = generateCVTemplate2($name, $job_title, $phone, $email, $profile, $education, $experience, $skills, $languages, $photo_path);
        break;
    case '3':
        $pdf = generateCVTemplate3($name, $job_title, $phone, $email, $profile, $education, $experience, $skills, $languages, $photo_path);
        break;
    default:
        // Handle kasus template tidak valid
        echo "Template tidak valid.";
        exit;
}

// Set header untuk download PDF
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="cv.pdf"');

// Output PDF
echo $pdf;
?>