<?php
session_start();

// Cek role dan sesi login
if (!isset($_SESSION['username']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php'); // Redirect ke login jika bukan admin
    exit();
}

// Ambil data session
$username = $_SESSION['username'];
?>

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
            <div class="text-xl font-bold text-red-500">MedPulse Dashboard</div>
            <!-- Navigation Links -->
            <div class="space-x-4">
                <a href="index.php" class="text-gray-700 hover:text-red-500">Home</a>
                <a href="dokter.php" class="text-gray-700 hover:text-red-500">Dokter</a>
                <a href="pasien.php" class="text-gray-700 hover:text-red-500">User</a>
                <span class="text-blue-500 font-semibold"><?php echo htmlspecialchars($username); ?></span>
                <a href="../logout.php" class="text-red-500 hover:text-red-700">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Dashboard Content -->
    <div class="container mx-auto mt-8 p-4 bg-white shadow-md rounded-lg">
        <h1 class="text-xl text-gray-800 mb-4">Selamat Datang di Dashboard</h1>

        <!-- Dashboard Menu -->
    </div>
</body>
</html>
