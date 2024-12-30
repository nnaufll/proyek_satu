<?php
require_once('vendor/tecnickcom/tcpdf/tcpdf.php');

function generateCVTemplate1($name, $job_title, $phone, $email, $profile, $education, $experience, $skills, $languages, $photo_path) {
    $pdf = new TCPDF();
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor($name);
    $pdf->SetTitle('CV - ' . $name);
    $pdf->SetMargins(15, 15, 15);
    $pdf->SetAutoPageBreak(true, 10);
    $pdf->AddPage();

    // Tambahkan foto profil (jika ada)
    if (!empty($photo_path)) {
        $pdf->Image($photo_path, 15, 15, 30, 30);
    }

    // Tambahkan nama dan jabatan
    $pdf->SetFont('times', 'B', 20);
    $pdf->SetXY(50, 15);
    $pdf->Cell(0, 10, $name, 0, 1, 'L');

    $pdf->SetFont('times', '', 14);
    $pdf->SetXY(50, 25);
    $pdf->Cell(0, 10, $job_title, 0, 1, 'L');

    // Tambahkan informasi kontak
    $pdf->SetFont('times', '', 12);
    $pdf->SetXY(50, 35);
    $pdf->Cell(0, 10, $phone, 0, 1, 'L');
    $pdf->SetXY(50, 45);
    $pdf->Cell(0, 10, $email, 0, 1, 'L');

    // Tambahkan profil
    $pdf->SetFont('times', 'B', 14);
    $pdf->SetXY(15, 60);
    $pdf->Cell(0, 10, 'Profile', 0, 1, 'L');
    $pdf->SetFont('times', '', 12);
    $pdf->SetXY(15, 70);
    $pdf->MultiCell(0, 10, $profile, 0, 'L');

    // Tambahkan education
    $pdf->SetFont('times', 'B', 14);
    $pdf->SetY(100); // Atur posisi Y sesuai kebutuhan
    $pdf->Cell(0, 10, 'Education', 0, 1, 'L');
    $pdf->SetFont('times', '', 12);
    $pdf->SetY(110); // Atur posisi Y sesuai kebutuhan
    $pdf->MultiCell(0, 10, $education, 0, 'L');

    // Tambahkan experience
    $pdf->SetFont('times', 'B', 14);
    $pdf->SetY(140); // Atur posisi Y sesuai kebutuhan
    $pdf->Cell(0, 10, 'Experience', 0, 1, 'L');
    $pdf->SetFont('times', '', 12);
    $pdf->SetY(150); // Atur posisi Y sesuai kebutuhan
    $pdf->MultiCell(0, 10, $experience, 0, 'L');

    // Tambahkan skills
    $pdf->SetFont('times', 'B', 14);
    $pdf->SetY(180); // Atur posisi Y sesuai kebutuhan
    $pdf->Cell(0, 10, 'Skills', 0, 1, 'L');
    $pdf->SetFont('times', '', 12);
    $pdf->SetY(190); // Atur posisi Y sesuai kebutuhan
    $pdf->MultiCell(0, 10, $skills, 0, 'L');

    // Tambahkan languages
    $pdf->SetFont('times', 'B', 14);
    $pdf->SetY(220); // Atur posisi Y sesuai kebutuhan
    $pdf->Cell(0, 10, 'Languages', 0, 1, 'L');
    $pdf->SetFont('times', '', 12);
    $pdf->SetY(230); // Atur posisi Y sesuai kebutuhan
    $pdf->MultiCell(0, 10, $languages, 0, 'L');

    return $pdf->Output('', 'S');
}
?>