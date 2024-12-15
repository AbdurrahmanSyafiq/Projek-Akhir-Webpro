<?php
// Sertakan file koneksi
include 'koneksi.php';

// Query untuk mendapatkan data rekam medis
$sql = "
    SELECT 
        layanan_konsultasi.id AS id_rekam_medis,
        pengguna.nama AS nama_pasien,
        dokter.nama AS nama_dokter,
        layanan_konsultasi.jadwal_konsultasi,
        layanan_konsultasi.catatan,
        layanan_konsultasi.status
    FROM layanan_konsultasi
    JOIN pengguna ON layanan_konsultasi.id_pengguna = pengguna.id
    JOIN dokter ON layanan_konsultasi.id_dokter = dokter.id
    ORDER BY layanan_konsultasi.jadwal_konsultasi DESC
";

$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Rekam Medis</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        nav {
            background-color: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .logo img {
            height: 40px;
            width: auto;
        }
        .nav-menu {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
            flex-grow: 1;
            justify-content: flex-end;
        }
        .nav-menu li {
            margin: 0 15px;
        }
        .nav-menu li a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
            font-size: 16px;
        }
        .nav-menu li a:hover {
            color: #007BFF;
        }
        .login-register a {
            color: red;
            text-decoration: none;
            font-weight: bold;
        }
        .login-register a:hover {
            color: darkred;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 18px;
            text-align: left;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f4f4f4;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>
    <nav>
        <div class="logo">
            <img src="logo.jpg" alt="Logo Klinik">
        </div>
        <ul class="nav-menu">
            <li><a href="#">Home</a></li>
            <li><a href="#">Penjadwalan Konsultasi</a></li>
            <li><a href="#">Rekam Medis</a></li>
            <li><a href="#">Dashboard (Admin Only)</a></li>
        </ul>
        <div class="login-register">
            <a href="#">Login/Register</a>
        </div>
    </nav>

    <h1>Data Rekam Medis Pasien</h1>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Pasien</th>
                <th>Nama Dokter</th>
                <th>Jadwal Konsultasi</th>
                <th>Catatan</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id_rekam_medis']; ?></td>
                        <td><?php echo htmlspecialchars($row['nama_pasien']); ?></td>
                        <td><?php echo htmlspecialchars($row['nama_dokter']); ?></td>
                        <td><?php echo $row['jadwal_konsultasi']; ?></td>
                        <td><?php echo htmlspecialchars($row['catatan']); ?></td>
                        <td><?php echo ucfirst($row['status']); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">Tidak ada data rekam medis.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>

<?php
// Tutup koneksi
$conn->close();
?>