<?php
// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "penjadwalan_konsultasi");

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil ID dari parameter GET
$id = intval($_GET['id']);

// SQL untuk menghapus dokter
$sql = "DELETE FROM dokter WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

// Eksekusi perintah
if ($stmt->execute()) {
    header("Location: pengelolaan.php?msg=success_deleted_dokter");
} else {
    echo "Gagal menghapus data dokter.";
}

$stmt->close();
$conn->close();
?>
