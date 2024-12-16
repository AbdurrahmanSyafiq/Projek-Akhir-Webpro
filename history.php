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

// Periksa koneksi
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

// Query untuk mengambil history berdasarkan id_pengguna
$query_history = "SELECT 
                    lk.jadwal_konsultasi, 
                    lk.status, 
                    lk.catatan, 
                    d.nama AS nama_dokter, 
                    d.spesialisasi AS spesialisasi_dokter
                  FROM layanan_konsultasi lk
                  INNER JOIN dokter d ON lk.id_dokter = d.id
                  WHERE lk.id_pengguna = ?";
$stmt_history = $conn->prepare($query_history);
$stmt_history->bind_param("i", $id_pengguna);
$stmt_history->execute();
$result_history = $stmt_history->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History Konsultasi</title>
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
    <div class="container mx-auto mt-6 p-4 bg-white shadow-lg rounded-lg">
        <h2 class="text-2xl font-bold mb-4 text-gray-800">History Konsultasi Anda</h2>

        <!-- Card Container -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php
            if ($result_history->num_rows > 0) {
                while ($row = $result_history->fetch_assoc()) {
                    echo "
                    <div class='bg-gray-50 border border-gray-200 p-4 rounded-lg shadow'>
                        <h3 class='text-lg font-bold mb-2 text-gray-700'>Dokter: {$row['nama_dokter']}</h3>
                        <p class='text-sm text-gray-600'><strong>Spesialisasi:</strong> {$row['spesialisasi_dokter']}</p>
                        <p class='text-sm text-gray-600'><strong>Jadwal:</strong> {$row['jadwal_konsultasi']}</p>
                        <p class='text-sm text-gray-600'><strong>Status:</strong> 
                            <span class='inline-block px-2 py-1 rounded-md text-white " .
                            ($row['status'] === 'menunggu' ? 'bg-yellow-500' : 
                            ($row['status'] === 'selesai' ? 'bg-green-500' : 'bg-red-500')) . "'>
                                " . ucfirst($row['status']) . "
                            </span>
                        </p>
                        <p class='text-sm text-gray-600'><strong>Catatan:</strong> {$row['catatan']}</p>
                    </div>
                    ";
                }
            } else {
                echo "<p class='text-gray-500'>Anda belum memiliki riwayat konsultasi.</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>
