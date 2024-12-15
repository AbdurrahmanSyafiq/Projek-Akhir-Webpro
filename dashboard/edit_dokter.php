<?php
// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "penjadwalan_konsultasi");

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Cek jika form dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $nama = $_POST['nama'];
    $spesialisasi = $_POST['spesialisasi'];
    $kontak = $_POST['kontak'];

    // SQL untuk memperbarui data dokter
    $sql = "UPDATE dokter SET nama = ?, spesialisasi = ?, kontak = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $nama, $spesialisasi, $kontak, $id);

    if ($stmt->execute()) {
        header("Location: pengelolaan.php?msg=success_updated_dokter");
    } else {
        echo "Gagal memperbarui data dokter.";
    }

    $stmt->close();
}

$conn->close();
?>
