<?php
session_start();
require('vendor/setasign/fpdf/fpdf.php'); // Pastikan path ke fpdf.php benar

// Cek sesi login
if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    header('Location: login.php');
    exit();
}

// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "penjadwalan_konsultasi");
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil ID pengguna dari session
$username = $_SESSION['username'];
$query_user = "SELECT id FROM pengguna WHERE username = ?";
$stmt_user = $conn->prepare($query_user);
$stmt_user->bind_param("s", $username);
$stmt_user->execute();
$result_user = $stmt_user->get_result();

if ($result_user->num_rows > 0) {
    $row_user = $result_user->fetch_assoc();
    $id_pengguna = $row_user['id'];
} else {
    die("Pengguna tidak ditemukan.");
}

// Ambil data history konsultasi
$query = "SELECT d.nama AS nama_dokter, d.spesialisasi, lk.jadwal_konsultasi, lk.status, lk.catatan
          FROM layanan_konsultasi lk
          INNER JOIN dokter d ON lk.id_dokter = d.id
          WHERE lk.id_pengguna = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_pengguna);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Tidak ada data konsultasi.");
}

// Ambil data pertama (asumsi satu record)
$data = $result->fetch_assoc();

// Buat PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 20);

// Logo
$pdf->Image('image/logo.jpg', 80, 10, 50); // Pastikan path gambar benar
$pdf->Ln(30);

// Judul
$pdf->Cell(0, 10, 'MEDPULSE', 0, 1, 'C');
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Detail Konsultasi', 0, 1, 'C');
$pdf->Ln(10);

// Detail Dokter dan Konsultasi
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, 'Dokter: ' . $data['nama_dokter'], 0, 1);
$pdf->Cell(0, 10, 'Spesialisasi: ' . $data['spesialisasi'], 0, 1);
$pdf->Cell(0, 10, 'Jadwal: ' . $data['jadwal_konsultasi'], 0, 1);
$pdf->Cell(0, 10, 'Status: ' . $data['status'], 0, 1);
$pdf->Ln(5);

// Catatan
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Catatan:', 0, 1);
$pdf->SetFont('Arial', '', 11);
$pdf->MultiCell(0, 10, $data['catatan']);

// Output PDF
$pdf->Output('I', 'history_konsultasi.pdf');

$conn->close();
?>
