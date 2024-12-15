<?php
session_start();

// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "penjadwalan_konsultasi");
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Handle form tambah dokter
if (isset($_POST['tambah_dokter'])) {
    $nama = $_POST['nama'];
    $spesialisasi = $_POST['spesialisasi'];
    $kontak = $_POST['kontak'];
    $status = $_POST['status'];

    $query = "INSERT INTO dokter (nama, spesialisasi, kontak, status) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssss", $nama, $spesialisasi, $kontak, $status);
    $stmt->execute();
    header("Location: dokter.php");
    exit();
}

// Handle form update dokter
if (isset($_POST['update_dokter'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $spesialisasi = $_POST['spesialisasi'];
    $kontak = $_POST['kontak'];
    $status = $_POST['status'];

    $query = "UPDATE dokter SET nama=?, spesialisasi=?, kontak=?, status=? WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssi", $nama, $spesialisasi, $kontak, $status, $id);
    $stmt->execute();
    header("Location: dokter.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Dokter</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <!-- Navbar -->
    <nav class="bg-gray-200 p-4 shadow-md">
        <div class="container mx-auto flex justify-between items-center">
            <div class="text-xl font-bold text-red-500">Logo</div>
            <div class="space-x-4">
                <a href="index.php" class="text-gray-700 hover:text-red-500">Home</a>
                <a href="dokter.php" class="text-gray-700 hover:text-red-500">Dokter</a>
                <a href="pasien.php" class="text-gray-700 hover:text-red-500">User</a>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <div class="container mx-auto mt-8 p-4 bg-white shadow-md rounded-lg">
        <h1 class="text-2xl font-semibold mb-6">Dashboard Dokter</h1>

        <!-- Tambahkan Dokter -->
        <div class="mb-4">
            <button onclick="document.getElementById('modalTambah').classList.remove('hidden')"
                class="bg-gray-500 text-white px-4 py-2 rounded-lg shadow-md hover:bg-gray-600">
                + Tambahkan dokter
            </button>
        </div>

        <!-- Tabel Dokter -->
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
                $result = $conn->query("SELECT * FROM dokter");
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td class='py-2 px-4 border-b'>{$row['nama']}</td>
                        <td class='py-2 px-4 border-b'>{$row['spesialisasi']}</td>
                        <td class='py-2 px-4 border-b'>{$row['kontak']}</td>
                        <td class='py-2 px-4 border-b'>
                            <button onclick=\"editDokter(" . htmlspecialchars(json_encode($row)) . ")\"
                                class='bg-blue-500 text-white px-4 py-1 rounded-md hover:bg-blue-600'>Edit</button>
                        </td>
                        <td class='py-2 px-4 border-b'>
                            <span class='bg-" . ($row['status'] == 'tersedia' ? "green" : "red") . "-500 text-white px-4 py-1 rounded-md'>"
                            . ucfirst($row['status']) . "</span>
                        </td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Modal Tambah Dokter -->
    <div id="modalTambah" class="hidden fixed inset-0 bg-gray-700 bg-opacity-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-md w-96">
            <h2 class="text-xl font-bold mb-4">Tambah Dokter</h2>
            <form method="POST">
                <input type="text" name="nama" placeholder="Nama Dokter" required class="w-full mb-2 p-2 border rounded">
                <input type="text" name="spesialisasi" placeholder="Spesialisasi" required class="w-full mb-2 p-2 border rounded">
                <input type="text" name="kontak" placeholder="Kontak" required class="w-full mb-2 p-2 border rounded">
                <select name="status" required class="w-full mb-4 p-2 border rounded">
                    <option value="tersedia">Tersedia</option>
                    <option value="melayani">Melayani</option>
                </select>
                <button type="submit" name="tambah_dokter" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Simpan</button>
                <button type="button" onclick="document.getElementById('modalTambah').classList.add('hidden')"
                    class="ml-2 bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500">Batal</button>
            </form>
        </div>
    </div>

    <!-- Modal Edit Dokter -->
    <div id="modalEdit" class="hidden fixed inset-0 bg-gray-700 bg-opacity-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-md w-96">
            <h2 class="text-xl font-bold mb-4">Edit Dokter</h2>
            <form method="POST">
                <input type="hidden" name="id" id="editId">
                <input type="text" name="nama" id="editNama" required class="w-full mb-2 p-2 border rounded">
                <input type="text" name="spesialisasi" id="editSpesialisasi" required class="w-full mb-2 p-2 border rounded">
                <input type="text" name="kontak" id="editKontak" required class="w-full mb-2 p-2 border rounded">
                <select name="status" id="editStatus" required class="w-full mb-4 p-2 border rounded">
                    <option value="tersedia">Tersedia</option>
                    <option value="melayani">Melayani</option>
                </select>
                <button type="submit" name="update_dokter" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Update</button>
                <button type="button" onclick="document.getElementById('modalEdit').classList.add('hidden')"
                    class="ml-2 bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500">Batal</button>
            </form>
        </div>
    </div>

    <script>
        function editDokter(data) {
            document.getElementById('editId').value = data.id;
            document.getElementById('editNama').value = data.nama;
            document.getElementById('editSpesialisasi').value = data.spesialisasi;
            document.getElementById('editKontak').value = data.kontak;
            document.getElementById('editStatus').value = data.status;
            document.getElementById('modalEdit').classList.remove('hidden');
        }
    </script>
</body>
</html>
