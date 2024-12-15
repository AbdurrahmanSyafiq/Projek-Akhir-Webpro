<?php
session_start();
include("koneksi.php");
if (!isset($_SESSION['admin_username'])) {
    header("location:loginuser.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
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
                <li><a href="#" class="hover:text-gray-900">Home</a></li>
                <li><a href="#" class="hover:text-gray-900">Penjadwalan Konsultasi</a></li>
                <li><a href="#" class="hover:text-gray-900">Rekam Medis</a></li>
                <li><a href="#" class="hover:text-gray-900">Dashboard (Admin Only)</a></li>
                <li>
                    <a href="logout.php" class="text-red-500 hover:text-red-700">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Banner -->
    <div class="bg-white text-black py-20">
        <div class="container mx-auto text-center">
            <h1 class="text-4xl font-bold mb-4">Selamat Datang di <span class="text-red-500">Med</span><span class="text-blue-500">Pulse</span></h1>
            <p class="text-lg">Kami siap membantu Anda dengan pelayanan terbaik.</p>
        </div>
    </div>

    <!-- Additional Content (Optional) -->
    <div class="container mx-auto py-10 px-4">
        <h2 class="text-2xl font-semibold text-gray-700 mb-6">Fitur Utama</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-bold mb-2">Pendaftaran Pasien Baru</h3>
                <p>Daftarkan diri Anda dengan mudah dan cepat melalui sistem kami.</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-bold mb-2">Penjadwalan Konsultasi</h3>
                <p>Pilih jadwal konsultasi yang sesuai dengan ketersediaan Anda.</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-bold mb-2">Dashboard Admin</h3>
                <p>Akses khusus untuk admin untuk mengelola layanan dan data.</p>
            </div>
        </div>
    </div>
</body>
</html>
