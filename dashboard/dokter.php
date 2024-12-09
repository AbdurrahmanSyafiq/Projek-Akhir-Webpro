<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
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
                <a href="index.php" class="text-gray-700 hover:text-red-500">Home</a>
                <a href="dokter.php" class="text-gray-700 hover:text-red-500">Dokter</a>
                <a href="pasien.php" class="text-gray-700 hover:text-red-500">User</a>
            </div>
        </div>
    </nav>

    <!-- Dashboard Content -->
    <div class="container mx-auto mt-8 p-4 bg-white shadow-md rounded-lg">
        <h1 class="text-2xl font-semibold mb-6">Dashboard</h1>

        <!-- Tambahkan Dokter -->
        <div class="mb-4">
            <button class="bg-gray-500 text-white px-4 py-2 rounded-lg shadow-md hover:bg-gray-600">
                + Tambahkan dokter
            </button>
        </div>

        <!-- Tabel Informasi Dokter -->
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-200">
                    <th class="py-2 px-4 border-b">Nama Dokter</th>
                    <th class="py-2 px-4 border-b">Spesialisasi</th>
                    <th class="py-2 px-4 border-b">Kontak</th>
                    <th class="py-2 px-4 border-b">Aksi</th>
                    <th class="py-2 px-4 border-b">Status</th>
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

                // Ambil data dari tabel dokter
                $sql = "SELECT * FROM dokter";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // Tampilkan setiap baris data
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td class='py-2 px-4 border-b'>{$row['nama']}</td>";
                        echo "<td class='py-2 px-4 border-b'>{$row['spesialisasi']}</td>";
                        echo "<td class='py-2 px-4 border-b'>{$row['kontak']}</td>";
                        echo "<td class='py-2 px-4 border-b'>
                                <button class='bg-blue-500 text-white px-4 py-1 rounded-md hover:bg-blue-600'>Manage</button>
                              </td>";
                        echo "<td class='py-2 px-4 border-b'>
                                <span class='bg-" . ($row['status'] === 'tersedia' ? "green" : "red") . "-500 text-white px-4 py-1 rounded-md'>"
                              . ucfirst($row['status']) . "</span>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' class='text-center py-2 px-4 border-b'>Tidak ada data dokter</td></tr>";
                }

                // Tutup koneksi
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
