<?php
session_start();
require('vendor/setasign/fpdf/fpdf.php'); // Pastikan fpdf.php tersedia di folder 'libs'

// Cek jika user sudah login
if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    header('Location: login.php'); // Redirect ke login jika belum login
    exit();
}

// Ambil data session
$username = $_SESSION['username'];
$role = $_SESSION['role'];

// Koneksi database
$conn = new mysqli("localhost", "root", "", "penjadwalan_konsultasi");
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil id pengguna berdasarkan session username
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

// Ambil data history konsultasi
$query = "SELECT d.nama AS nama_dokter, d.spesialisasi, lk.jadwal_konsultasi, lk.status, lk.catatan
          FROM layanan_konsultasi lk
          INNER JOIN dokter d ON lk.id_dokter = d.id
          WHERE lk.id_pengguna = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_pengguna);
$stmt->execute();
$result = $stmt->get_result();
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
            <div class="text-lg font-bold">
                <div class="bg-gray-300 rounded-full h-12 w-12 flex items-center justify-center overflow-hidden">
                    <img src="image/logo.jpg" alt="Logo Medpulse" class="h-full w-full object-cover">
                </div>
            </div>
            <!-- Menu Items -->
            <ul class="flex space-x-6 text-gray-600">
                <li><a href="index.php" class="hover:text-gray-900">Home</a></li>
                <li><a href="penjadwalan.php" class="hover:text-gray-900">Penjadwalan Konsultasi</a></li>
                <li><a href="#" class="hover:text-gray-900">History</a></li>
                <li>
                    <span class="text-blue-500 font-semibold"><?php echo htmlspecialchars($username); ?></span>
                    <a href="logout.php" class="text-red-500 hover:text-red-700 ml-4">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Content -->
    <div class="container mx-auto py-10 px-4">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6">History Konsultasi Anda</h2>
        
        <!-- Tampilkan Data History -->
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="bg-white p-6 rounded-lg shadow-md mb-4">
                    <h3 class="text-lg font-bold mb-2">Dokter: <?php echo htmlspecialchars($row['nama_dokter']); ?></h3>
                    <p><strong>Spesialisasi:</strong> <?php echo htmlspecialchars($row['spesialisasi']); ?></p>
                    <p><strong>Jadwal:</strong> <?php echo htmlspecialchars($row['jadwal_konsultasi']); ?></p>
                    <p><strong>Status:</strong> 
                        <span class="bg-yellow-500 text-white px-2 py-1 rounded">
                            <?php echo htmlspecialchars($row['status']); ?>
                        </span>
                    </p>
                    <p><strong>Catatan:</strong> <?php echo htmlspecialchars($row['catatan']); ?></p>
                </div>
            <?php endwhile; ?>
            
            <!-- Tombol Download PDF -->
            <form action="export_pdf.php" method="POST">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                    Download PDF
                </button>
            </form>
        <?php else: ?>
            <p class="text-gray-700">Anda belum memiliki riwayat konsultasi.</p>
        <?php endif; ?>
    </div>
</body>
</html>
<?php $conn->close(); ?>
