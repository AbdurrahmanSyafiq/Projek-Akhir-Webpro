<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengelolaan Layanan dan Dokter</title>
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
                <a href="#layanan" class="text-gray-700 hover:text-red-500">Layanan</a>
                <a href="#dokter" class="text-gray-700 hover:text-red-500">Dokter</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto mt-8 p-4 bg-white shadow-md rounded-lg">
        <h1 class="text-2xl font-semibold mb-6">Pengelolaan Layanan dan Dokter</h1>

        <!-- Pengelolaan Layanan -->
        <section id="layanan" class="mb-8">
            <h2 class="text-xl font-semibold mb-4">Layanan</h2>
            <!-- Tambahkan Layanan -->
            <button onclick="showModal('modalTambahLayanan')" class="bg-gray-500 text-white px-4 py-2 rounded-lg shadow-md hover:bg-gray-600">
                + Tambahkan Layanan
            </button>
            <!-- Tabel Informasi Layanan -->
            <table class="w-full text-left border-collapse mt-4">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="py-2 px-4 border-b">Nama Layanan</th>
                        <th class="py-2 px-4 border-b">Deskripsi</th>
                        <th class="py-2 px-4 border-b">Harga</th>
                        <th class="py-2 px-4 border-b">Aksi</th>
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

                    // Ambil data dari tabel layanan
                    $sql = "SELECT * FROM layanan";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td class='py-2 px-4 border-b'>{$row['nama_layanan']}</td>";
                            echo "<td class='py-2 px-4 border-b'>{$row['deskripsi']}</td>";
                            echo "<td class='py-2 px-4 border-b'>Rp " . number_format($row['harga'], 0, ',', '.') . "</td>";
                            echo "<td class='py-2 px-4 border-b'>
                                    <button onclick=\"editLayanan({$row['id']})\" class='bg-blue-500 text-white px-4 py-1 rounded-md hover:bg-blue-600'>Edit</button>
                                    <button onclick=\"hapusLayanan({$row['id']})\" class='bg-red-500 text-white px-4 py-1 rounded-md hover:bg-red-600'>Hapus</button>
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4' class='text-center py-2 px-4 border-b'>Tidak ada data layanan</td></tr>";
                    }

                    $conn->close();
                    ?>
                </tbody>
            </table>
        </section>

        <!-- Pengelolaan Dokter -->
        <section id="dokter">
            <h2 class="text-xl font-semibold mb-4">Dokter</h2>
            <!-- Tambahkan Dokter -->
            <button onclick="showModal('modalTambahDokter')" class="bg-gray-500 text-white px-4 py-2 rounded-lg shadow-md hover:bg-gray-600">
                + Tambahkan Dokter
            </button>
            <!-- Tabel Informasi Dokter -->
            <table class="w-full text-left border-collapse mt-4">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="py-2 px-4 border-b">Nama Dokter</th>
                        <th class="py-2 px-4 border-b">Spesialisasi</th>
                        <th class="py-2 px-4 border-b">Kontak</th>
                        <th class="py-2 px-4 border-b">Aksi</th>
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
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td class='py-2 px-4 border-b'>{$row['nama']}</td>";
                            echo "<td class='py-2 px-4 border-b'>{$row['spesialisasi']}</td>";
                            echo "<td class='py-2 px-4 border-b'>{$row['kontak']}</td>";
                            echo "<td class='py-2 px-4 border-b'>
                                    <button onclick=\"editDokter({$row['id']})\" class='bg-blue-500 text-white px-4 py-1 rounded-md hover:bg-blue-600'>Edit</button>
                                    <button onclick=\"hapusDokter({$row['id']})\" class='bg-red-500 text-white px-4 py-1 rounded-md hover:bg-red-600'>Hapus</button>
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4' class='text-center py-2 px-4 border-b'>Tidak ada data dokter</td></tr>";
                    }

                    $conn->close();
                    ?>
                </tbody>
            </table>
        </section>
    </div>

    <!-- Modal Templates -->
    <div id="modalTambahLayanan" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
            <h2 class="text-lg font-semibold mb-4">Tambah Layanan</h2>
            <form method="POST" action="tambah_layanan.php">
                <div class="mb-4">
                    <label for="nama_layanan" class="block text-sm font-medium text-gray-700">Nama Layanan</label>
                    <input type="text" id="nama_layanan" name="nama_layanan" class="mt-1 p-2 border rounded-lg w-full">
                </div>
                <div class="mb-4">
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                    <textarea id="deskripsi" name="deskripsi" class="mt-1 p-2 border rounded-lg w-full"></textarea>
                </div>
                <div class="mb-4">
                    <label for="harga" class="block text-sm font-medium text-gray-700">Harga</label>
                    <input type="number" id="harga" name="harga" class="mt-1 p-2 border rounded-lg w-full">
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Simpan</button>
                    <button type="button" class="ml-2 bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600" onclick="closeModal('modalTambahLayanan')">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <div id="modalTambahDokter" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
            <h2 class="text-lg font-semibold mb-4">Tambah Dokter</h2>
            <form method="POST" action="tambah_dokter.php">
                <div class="mb-4">
                    <label for="nama" class="block text-sm font-medium text-gray-700">Nama Dokter</label>
                    <input type="text" id="nama" name="nama" class="mt-1 p-2 border rounded-lg w-full">
                </div>
                <div class="mb-4">
                    <label for="spesialisasi" class="block text-sm font-medium text-gray-700">Spesialisasi</label>
                    <input type="text" id="spesialisasi" name="spesialisasi" class="mt-1 p-2 border rounded-lg w-full">
                </div>
                <div class="mb-4">
                    <label for="kontak" class="block text-sm font-medium text-gray-700">Kontak</label>
                    <input type="text" id="kontak" name="kontak" class="mt-1 p-2 border rounded-lg w-full">
                </div>
                <div class="flex justify-end">
                    <button type="submit" x`class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Simpan</button>
                    <button type="button" class="ml-2 bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600" onclick="closeModal('modalTambahDokter')">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }
    </script>
</body>
</html>
