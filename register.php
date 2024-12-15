<?php
session_start();

// Tangkap pesan sukses atau error jika ada
$success_message = isset($_SESSION['success']) ? $_SESSION['success'] : null;
$error_message = isset($_SESSION['error']) ? $_SESSION['error'] : null;

// Hapus session agar pesan hanya tampil sekali
unset($_SESSION['success']);
unset($_SESSION['error']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Page</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 h-screen flex items-center justify-center">
    <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-md">
        <!-- Logo -->
        <div class="flex justify-center mb-6">
            <div class="bg-gray-300 rounded-full h-20 w-20 flex items-center justify-center overflow-hidden">
                <img src="image/logo.jpg" alt="Logo Medpulse" class="h-full w-full object-cover">
            </div>
        </div>
        <!-- Welcome Text -->
        <h2 class="text-center text-2xl font-semibold text-gray-700 mb-4">Daftar Akun Baru</h2>
        <p class="text-center text-gray-500 mb-6">Silahkan isi data Anda untuk mendaftar</p>

        <!-- Pesan Sukses -->
        <?php if ($success_message): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>

        <!-- Pesan Error -->
        <?php if ($error_message): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <!-- Register Form -->
        <form action="process_register.php" method="POST" class="space-y-4">
            <div>
                <label for="nama" class="block text-gray-600 font-medium">Nama Lengkap</label>
                <input type="text" id="nama" name="nama" class="w-full border-gray-300 rounded-lg p-2" placeholder="Masukkan nama lengkap" required>
            </div>
            <div>
                <label for="email" class="block text-gray-600 font-medium">Email</label>
                <input type="email" id="email" name="email" class="w-full border-gray-300 rounded-lg p-2" placeholder="Masukkan email" required>
            </div>
            <div>
                <label for="username" class="block text-gray-600 font-medium">Username</label>
                <input type="text" id="username" name="username" class="w-full border-gray-300 rounded-lg p-2" placeholder="Masukkan username" required>
            </div>
            <div>
                <label for="password" class="block text-gray-600 font-medium">Password</label>
                <input type="password" id="password" name="password" class="w-full border-gray-300 rounded-lg p-2" placeholder="Masukkan password" required>
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white rounded-lg p-2 hover:bg-blue-600">Daftar</button>
        </form>

        <!-- Link ke Login -->
        <p class="text-center text-gray-500 text-sm mt-4">
            Sudah memiliki akun? <a href="login.php" class="text-blue-500 hover:underline">Login disini</a>
        </p>
    </div>
</body>
</html>
