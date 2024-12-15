<?php
// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "penjadwalan_konsultasi");

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Cek jika form dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $nama_layanan = $_POST['nama_layanan'];
    $deskripsi = $_POST['deskripsi'];
    $harga = floatval($_POST['harga']);

    // SQL untuk memperbarui data layanan
    $sql = "UPDATE layanan SET nama_layanan = ?, deskripsi = ?, harga = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdi", $nama_layanan, $deskripsi, $harga, $id);

    if ($stmt->execute()) {
        header("Location: layanan.php?msg=success_updated");
    } else {
        echo "Gagal memperbarui data layanan.";
    }

    $stmt->close();
}

$conn->close();
?>
