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
        <h2 class="text-center text-2xl font-semibold text-gray-700 mb-4">Selamat Datang,</h2>
        <p class="text-center text-gray-500 mb-6">Silahkan login terlebih dahulu</p>

        <!-- Login Form -->
        <form action="" method="POST" class="space-y-4">
            <div>
                <label for="username" class="block text-gray-600 font-medium">Username</label>
                <input type="text" id="username" name="username" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2" placeholder="Masukkan username" required>
            </div>
            <div>
                <label for="password" class="block text-gray-600 font-medium">Password</label>
                <input type="password" id="password" name="password" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2" placeholder="Masukkan password" required>
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white rounded-lg p-2 font-semibold hover:bg-blue-600 focus:ring-2 focus:ring-blue-400">Login</button>
        </form>
        <!-- Register Link -->
        <p class="text-center text-gray-500 text-sm mt-4">
            Belum terdaftar sebagai pasien? <a href="register.php" class="text-blue-500 hover:underline">Daftar disini</a>
        </p>
    </div>
</body>
</html>