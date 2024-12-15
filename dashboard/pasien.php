<?php
// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "penjadwalan_konsultasi");

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Proses update status
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_layanan = $_POST['id_layanan'];
    $status = $_POST['status'];

    // Query update status
    $update_query = "UPDATE layanan_konsultasi SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("si", $status, $id_layanan);

    if ($stmt->execute()) {
        $success_message = "Status berhasil diperbarui!";
    } else {
        $error_message = "Gagal memperbarui status: " . $stmt->error;
    }
}

// Query untuk mendapatkan data lengkap
$sql = "SELECT 
            lk.id, 
            lk.jadwal_konsultasi, 
            lk.status, 
            lk.catatan, 
            p.nama AS nama_pasien, 
            d.nama AS nama_dokter
        FROM layanan_konsultasi lk
        INNER JOIN pengguna p ON lk.id_pengguna = p.id
        INNER JOIN dokter d ON lk.id_dokter = d.id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pasien</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <!-- Navbar -->
    <nav class="bg-gray-200 p-4 shadow-md">
        <div class="container mx-auto flex justify-between items-center">
            <!-- Logo -->
            <div class="text-xl font-bold text-red-500">Logo</div>
            <!-- Navigation Links -->
            <div class="space-x-4">
                <a href="dokter.php" class="text-gray-700 hover:text-red-500">Dokter</a>
                <a href="pasien.php" class="text-gray-700 hover:text-red-500">User</a>
                <a href="../index.php" class="text-gray-700 hover:text-red-500">Admin</a>
            </div>
        </div>
    </nav>

    <!-- Dashboard Content -->
    <div class="container mx-auto mt-8 p-4 bg-white shadow-md rounded-lg">
        <h1 class="text-2xl font-semibold mb-6">Data Pasien</h1>

        <!-- Notifikasi -->
        <?php if (isset($success_message)): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 mb-4 rounded">
                <?php echo $success_message; ?>
            </div>
        <?php elseif (isset($error_message)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 mb-4 rounded">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <!-- Tabel Informasi Pasien -->
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-200">
                    <th class="py-2 px-4 border-b">Nama Pasien</th>
                    <th class="py-2 px-4 border-b">Nama Dokter</th>
                    <th class="py-2 px-4 border-b">Jadwal Konsultasi</th>
                    <th class="py-2 px-4 border-b">Status</th>
                    <th class="py-2 px-4 border-b">Catatan</th>
                    <th class="py-2 px-4 border-b">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    // Tampilkan setiap baris data
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td class='py-2 px-4 border-b'>{$row['nama_pasien']}</td>";
                        echo "<td class='py-2 px-4 border-b'>{$row['nama_dokter']}</td>";
                        echo "<td class='py-2 px-4 border-b'>{$row['jadwal_konsultasi']}</td>";
                        echo "<td class='py-2 px-4 border-b'>
                                <span class='bg-" . 
                                ($row['status'] === 'menunggu' ? "yellow" : ($row['status'] === 'selesai' ? "green" : "red")) 
                                . "-500 text-white px-4 py-1 rounded-md'>" . ucfirst($row['status']) . "</span>
                              </td>";
                        echo "<td class='py-2 px-4 border-b'>{$row['catatan']}</td>";
                        echo "<td class='py-2 px-4 border-b'>
                                <form method='POST' action=''>
                                    <input type='hidden' name='id_layanan' value='{$row['id']}'>
                                    <select name='status' class='border-gray-300 rounded p-1'>
                                        <option value='menunggu' " . ($row['status'] === 'menunggu' ? 'selected' : '') . ">Menunggu</option>
                                        <option value='selesai' " . ($row['status'] === 'selesai' ? 'selected' : '') . ">Selesai</option>
                                        <option value='batal' " . ($row['status'] === 'batal' ? 'selected' : '') . ">Batal</option>
                                    </select>
                                    <button type='submit' class='bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600'>Update</button>
                                </form>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' class='text-center py-2 px-4 border-b'>Tidak ada data pasien</td></tr>";
                }

                // Tutup koneksi
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
