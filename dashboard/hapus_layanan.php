<?php
// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "penjadwalan_konsultasi");

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil ID dari parameter GET
$id = intval($_GET['id']);

// SQL untuk menghapus layanan
$sql = "DELETE FROM layanan WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

// Eksekusi perintah
if ($stmt->execute()) {
    header("Location: layanan.php?msg=success_deleted"); // Redirect setelah sukses
} else {
    echo "Gagal menghapus data layanan.";
}

// Tutup koneksi
$stmt->close();
$conn->close();
?>
