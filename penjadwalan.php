<?php
session_start();

// // Cek apakah user sudah login
// if (!isset($_SESSION['id']) || !isset($_SESSION['username'])) {
//     header('Location: login.php'); // Redirect ke halaman login jika belum login
//     exit();
// }

// Ambil username dari session
// Ambil data session
$username = $_SESSION['username'];
$role = $_SESSION['role'];

// Jika role adalah admin, redirect ke dashboard
if ($role === 'admin') {
    header('Location: login.php');
    exit();
}

// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "penjadwalan_konsultasi");
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil id pengguna berdasarkan username
$query_user = "SELECT id FROM pengguna WHERE username = ?";
$stmt_user = $conn->prepare($query_user);
$stmt_user->bind_param("s", $username);
$stmt_user->execute();
$result_user = $stmt_user->get_result();

if ($result_user->num_rows > 0) {
    $row_user = $result_user->fetch_assoc();
    $id_pengguna = $row_user['id'];
} else {
    die("Error: Pengguna tidak ditemukan.");
}

// Proses form input
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_dokter = $_POST['id_dokter'];
    $jadwal_konsultasi = $_POST['jadwal_konsultasi'];
    $catatan = $_POST['catatan'];

    // Query insert data penjadwalan
    $query = "INSERT INTO layanan_konsultasi (id_pengguna, id_dokter, jadwal_konsultasi, status, catatan, created_at)
              VALUES (?, ?, ?, 'menunggu', ?, NOW())";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iiss", $id_pengguna, $id_dokter, $jadwal_konsultasi, $catatan);

    if ($stmt->execute()) {
        // Update status dokter menjadi "melayani"
        $update_dokter = "UPDATE dokter SET status = 'melayani' WHERE id = ?";
        $stmt_update = $conn->prepare($update_dokter);
        $stmt_update->bind_param("i", $id_dokter);

        if ($stmt_update->execute()) {
            $success_message = "Jadwal konsultasi berhasil dibuat";
        } else {
            $error_message = "Gagal memperbarui status dokter: " . $stmt_update->error;
        }
    } else {
        $error_message = "Gagal membuat jadwal konsultasi: " . $stmt->error;
    }
}

// Ambil daftar dokter yang tersedia
$dokter_result = $conn->query("SELECT id, nama FROM dokter WHERE status = 'tersedia'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Jadwal Konsultasi</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <!-- Navbar -->
    <nav class="bg-white shadow-md py-4">
        <div class="container mx-auto flex justify-between items-center px-4">
            <!-- Logo -->
            <div class="flex items-center space-x-3">
                <div class="bg-gray-300 rounded-full h-12 w-12 flex items-center justify-center overflow-hidden">
                    <img src="image/logo.jpg" alt="Logo Medpulse" class="h-full w-full object-cover">
                </div>
                <span class="text-red-500 font-bold text-xl">MED<span class="text-blue-500">PULSE</span></span>
            </div>
            <!-- Menu Items -->
            <ul class="flex space-x-6 text-gray-600">
                <li><a href="index.php" class="hover:text-gray-900">Home</a></li>
                <li><a href="penjadwalan.php" class="hover:text-gray-900">Penjadwalan Konsultasi</a></li>
                <li><a href="rekam_medis.php" class="hover:text-gray-900">Rekam Medis</a></li>
                <li class="text-blue-500 font-semibold"><?php echo htmlspecialchars($username); ?></li>
                <li>
                    <a href="logout.php" class="text-red-500 hover:text-red-700">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Content -->
    <div class="container mx-auto bg-white p-8 shadow-lg rounded-lg mt-6">
        <h2 class="text-2xl font-bold mb-4 text-gray-800">Buat Jadwal Konsultasi</h2>

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

        <!-- Form Penjadwalan -->
        <form method="POST" action="" class="space-y-4">
            <!-- Pilih Dokter -->
            <div>
                <label for="id_dokter" class="block font-medium text-gray-700 mb-1">Pilih Dokter</label>
                <select id="id_dokter" name="id_dokter" required class="w-full border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500">
                    <option value="" disabled selected>-- Pilih Dokter --</option>
                    <?php while ($dokter = $dokter_result->fetch_assoc()): ?>
                        <option value="<?php echo $dokter['id']; ?>"><?php echo htmlspecialchars($dokter['nama']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <!-- Input Jadwal Konsultasi -->
            <div>
                <label for="jadwal_konsultasi" class="block font-medium text-gray-700 mb-1">Waktu Konsultasi</label>
                <input type="datetime-local" id="jadwal_konsultasi" name="jadwal_konsultasi" required 
                       class="w-full border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Input Catatan -->
            <div>
                <label for="catatan" class="block font-medium text-gray-700 mb-1">Catatan</label>
                <textarea id="catatan" name="catatan" rows="4" required 
                          class="w-full border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500"></textarea>
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 focus:ring-2 focus:ring-blue-400">
                    Simpan Jadwal
                </button>
            </div>
        </form>
    </div>
</body>
</html>
