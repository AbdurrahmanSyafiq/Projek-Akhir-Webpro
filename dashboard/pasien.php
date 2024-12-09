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

        <!-- Tabel Informasi Pasien -->
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-200">
                    <th class="py-2 px-4 border-b">Nama Pasien</th>
                    <th class="py-2 px-4 border-b">Nama Dokter</th>
                    <th class="py-2 px-4 border-b">Jadwal Konsultasi</th>
                    <th class="py-2 px-4 border-b">Status</th>
                    <th class="py-2 px-4 border-b">Catatan</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Koneksi ke database
                $conn = new mysqli("localhost", "root", "", "penjadwalan_konsultasi");

                // Periksa koneksi
                if ($conn->connect_error) {
                    die("Koneksi gagal: " . $conn->connect_error);
                }

                // Query untuk mendapatkan data lengkap
                $sql = "SELECT 
                            lk.jadwal_konsultasi, 
                            lk.status, 
                            lk.catatan, 
                            p.nama AS nama_pasien, 
                            d.nama AS nama_dokter
                        FROM layanan_konsultasi lk
                        INNER JOIN pengguna p ON lk.id_pengguna = p.id
                        INNER JOIN dokter d ON lk.id_dokter = d.id";
                $result = $conn->query($sql);

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
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' class='text-center py-2 px-4 border-b'>Tidak ada data pasien</td></tr>";
                }

                // Tutup koneksi
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
