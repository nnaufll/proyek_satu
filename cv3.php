<?php
require_once('vendor/tecnickcom/tcpdf/tcpdf.php');

function generateCVTemplate3($name, $job_title, $phone, $email, $profile, $education, $experience, $skills, $languages, $photo_path) {
    $pdf = new TCPDF();
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor($name);
    $pdf->SetTitle('CV - ' . $name);
    $pdf->SetMargins(15, 15, 15);
    $pdf->SetAutoPageBreak(true, 10);
    $pdf->AddPage();

    // Tambahkan header (nama dan job title)
    $pdf->SetFont('helvetica', 'B', 24);
    $pdf->Cell(0, 10, $name, 0, 1, 'C');

    $pdf->SetFont('helvetica', '', 16);
    $pdf->Cell(0, 10, $job_title, 0, 1, 'C');

    // ... (tambahkan elemen lain sesuai template)

    return $pdf->Output('', 'S');
}
?>