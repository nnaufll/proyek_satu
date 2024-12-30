<?php
require_once __DIR__ . '/vendor/autoload.php';

session_start();

if (isset($_SESSION['email'])) {
    $name = $_SESSION['name'];
    $job_title = $_SESSION['job_title'];
    $phone = $_SESSION['phone'];
    $email = $_SESSION['email'];
    $insta = $_SESSION['insta'];
    $address = $_SESSION['address'];
    $profile = $_SESSION['profile'];
    $education = $_SESSION['education'];
    $experience = $_SESSION['experience'];
    $skills = $_SESSION['skills'];
    $soskills = $_SESSION['soskills'];


    $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor($name);
    $pdf->SetTitle('CV - ' . $name);
    $pdf->SetMargins(15, 15, 15);
    $pdf->SetAutoPageBreak(true, 5);
    $pdf->AddPage();

    // Header
    $pdf->SetFont('helvetica', '', 10);
    $pdf->Cell(0, 6, "$address | $email | $phone | IG:$insta", 0, 1, 'C');
    $pdf->SetFont('helvetica', 'B', 16);
    $pdf->Cell(0, 10, $name, 0, 1, 'C');
    $pdf->Cell(0, 10, $job_title, 0, 1, 'C');
    $pdf->Ln(3);


    // Profile Section
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(0, 6, 'PROFILE', 0, 1);
    $pdf->SetFont('helvetica', '', 10);
    $pdf->MultiCell(0, 6, $profile);
    $pdf->Ln(3);

    // Work Experience
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(0, 6, 'WORK EXPERIENCE', 0, 1);
    $pdf->SetFont('helvetica', '', 10);
    $experiences = explode("\n", $experience);
    foreach ($experiences as $exp) {
        $pdf->MultiCell(0, 6, $exp, 0, 'L');
    }
    $pdf->Ln(3);

    // Education
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(0, 6, 'EDUCATION', 0, 1);
    $pdf->SetFont('helvetica', '', 10);
    $educations = explode("\n", $education);
    foreach ($educations as $edu) {
        $pdf->MultiCell(0, 6, $edu, 0, 'L');
    }
    $pdf->Ln(3);

    // Skills
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(0, 6, 'HARD SKILLS', 0, 1);
    $pdf->SetFont('helvetica', '', 10);
    $skillsList = explode(",", $skills);
    foreach ($skillsList as $skill) {
        $pdf->MultiCell(0, 6, '- ' . trim($skill), 0, 'L');
    }
    $pdf->Ln(3);

    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(0, 6, 'SOFT SKILLS', 0, 1);
    $pdf->SetFont('helvetica', '', 10);
    $skillsList = explode(",", $soskills);
    foreach ($skillsList as $skill) {
        $pdf->MultiCell(0, 6, '- ' . trim($skill), 0, 'L');
    }

    $pdf->Output('CV_' . $name . '.pdf', 'I');
} else {
    header("Location: login.php");
    exit;
}
?>
