<?php
session_start();
require_once 'db.php'; // Pastikan file koneksi database ada di sini

// Hapus pesan error atau success lama jika ada
$error_message = isset($_SESSION['error']) ? $_SESSION['error'] : null;
$success_message = isset($_SESSION['success']) ? $_SESSION['success'] : null;
unset($_SESSION['error']);
unset($_SESSION['success']);

// Proses form login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validasi input
    if (empty($username) || empty($password)) {
        $_SESSION['error'] = "Username dan password harus diisi!";
        header("Location: login.php");
        exit();
    }

    // Query untuk mengecek username dan password
    $query = "SELECT * FROM pengguna WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Set session berdasarkan data user
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['id'] = $user['id']; // Jika ID user ingin disimpan di session

        // Redirect berdasarkan role
        if ($user['role'] === 'admin') {
            header('Location: dashboard/index.php'); // Admin menuju halaman dashboard
        } else {
            header('Location: index.php'); // User menuju halaman utama
        }
        exit();
    } else {
        $_SESSION['error'] = "Username atau password salah!";
        header("Location: login.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
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
        <h2 class="text-center text-2xl font-semibold text-gray-700 mb-4">Selamat Datang</h2>
        <p class="text-center text-gray-500 mb-6">Silahkan login terlebih dahulu</p>

        <!-- Notifikasi Pesan -->
        <?php if ($success_message): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
                <?php echo $success_message; ?>
            </div>
        <?php elseif ($error_message): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <!-- Login Form -->
        <form action="login.php" method="POST" class="space-y-4">
            <div>
                <label for="username" class="block text-gray-600 font-medium">Username</label>
                <input type="text" id="username" name="username" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2" placeholder="Masukkan username" required>
            </div>
            <div>
                <label for="password" class="block text-gray-600 font-medium">Password</label>
                <input type="password" id="password" name="password" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2" placeholder="Masukkan password" required>
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white rounded-lg p-2 font-semibold hover:bg-blue-600 focus:ring-2 focus:ring-blue-400">
                Login
            </button>
        </form>

        <!-- Register Link -->
        <p class="text-center text-gray-500 text-sm mt-4">
            Belum memiliki akun? <a href="register.php" class="text-blue-500 hover:underline">Daftar di sini</a>
        </p>
    </div>
</body>
</html>
