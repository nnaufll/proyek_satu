<?php
require_once('vendor/tecnickcom/tcpdf/tcpdf.php');

function generateCVTemplate2($name, $job_title, $phone, $email, $profile, $education, $experience, $skills, $languages, $photo_path) {
    $pdf = new TCPDF();
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor($name);
    $pdf->SetTitle('CV - ' . $name);
    $pdf->SetMargins(15, 15, 15);
    $pdf->SetAutoPageBreak(true, 10);
    $pdf->AddPage();

    // Set warna background header
    $pdf->SetFillColor(0, 0, 0); // Warna hitam
    $pdf->Rect(0, 0, 210, 40, 'F');

    // Tambahkan foto profil
    if (!empty($photo_path)) {
        $pdf->Image($photo_path, 15, 10, 30, 30);
    }

    // Tambahkan nama
    $pdf->SetTextColor(255, 255, 255); // Warna putih
    $pdf->SetFont('helvetica', 'B', 18);
    $pdf->SetXY(50, 15);
    $pdf->Cell(0, 10, $name, 0, 1, 'L');

    // Tambahkan job title
    $pdf->SetTextColor(255, 255, 255); // Warna putih
    $pdf->SetFont('helvetica', '', 14);
    $pdf->SetXY(50, 25);
    $pdf->Cell(0, 10, $job_title, 0, 1, 'L');

    // Tambahkan tahun di sebelah kanan header
    $pdf->SetTextColor(255, 255, 255); // Warna putih
    $pdf->SetFont('helvetica', '', 14);
    $pdf->SetXY(170, 15);
    $pdf->Cell(0, 10, date("Y"), 0, 1, 'R');

    // ... (tambahkan elemen lain sesuai template)

    return $pdf->Output('', 'S');
}
?>