<?php
$host = 'localhost';
$user = 'root';
$password = ''; // Ganti dengan password MySQL-mu
$dbname = 'penjadwalan_konsultasi';

// Membuat koneksi
$conn = new mysqli($host, $user, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
