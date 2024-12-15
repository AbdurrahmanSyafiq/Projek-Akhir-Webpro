<?php
session_start();
require_once 'db.php'; // Koneksi ke database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password']; // Password tanpa hashing
    $role = 'user'; // Role default "user"

    // Cek apakah username sudah ada
    $checkQuery = "SELECT id FROM pengguna WHERE username = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Jika username sudah ada
        $_SESSION['error'] = "Username sudah digunakan. Silakan gunakan username lain.";
        header('Location: register.php');
        exit();
    }

    // Insert data ke database
    $insertQuery = "INSERT INTO pengguna (nama, email, username, password, role, created_at) VALUES (?, ?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("sssss", $nama, $email, $username, $password, $role);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Registrasi berhasil! Silakan login.";
        header('Location: login.php');
        exit();
    } else {
        $_SESSION['error'] = "Terjadi kesalahan. Gagal mendaftar!";
        header('Location: register.php');
        exit();
    }
} else {
    // Jika user mengakses file ini langsung
    header('Location: register.php');
    exit();
}
?>
