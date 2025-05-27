<?php
session_start();
include 'inc_koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$query = mysqli_query($koneksi, "SELECT * FROM peminjaman_ruangan 
    JOIN ruangan ON peminjaman_ruangan.ruangan_id = ruangan.ruangan_id 
    WHERE peminjaman_ruangan.user_id = $user_id 
    ORDER BY peminjaman_ruangan.tanggal DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Pengguna</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #fdf6fd;
            margin: 0;
            padding: 0;
        }
        .navbar {
            background-color: #cdb4db;
            padding: 1rem;
            color: #fff;
            text-align: center;
            font-size: 24px;
        }
        .container {
            max-width: 1000px;
            margin: 2rem auto;
            padding: 1rem;
            background-color: #fff0f5;
            border-radius: 12px;
        }
        h2 {
            color: #6a4c93;
            margin-bottom: 1rem;
        }
        .button-group {
            margin-bottom: 2rem;
        }
        .button-group a {
            text-decoration: none;
            padding: 10px 16px;
            margin-right: 12px;
            background-color: #b5ead7;
            color: #333;
            border-radius: 8px;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 12px;
            text-align: center;
        }
        th {
            background-color: #ffdac1;
        }
        tr:nth-child(even) {
            background-color: #fcefee;
        }
        tr:hover {
            background-color: #ffe0e9;
        }
    </style>
</head>
<body>

<div class="navbar">
    Dashboard Pengguna
</div>

<div class="container">
    <h2>Selamat Datang, <?= $_SESSION['username']; ?>!</h2>

    <div class="button-group">
        <a href="ajukan_peminjaman.php">Ajukan Peminjaman</a>
        <a href="ajukan_pengembalian.php">Ajukan Pengembalian</a>
        <a href="logout.php" style="background-color:#ffb3c1;">Logout</a>
    </div>

    <h3>Riwayat Peminjaman Anda</h3>
    <table border="1">
        <tr>
            <th>No</th>
            <th>Nama Ruangan</th>
            <th>Tanggal</th>
            <th>Jam</th>
            <th>Durasi</th>
            <th>Status</th>
        </tr>
        <?php
        $no = 1;
        while ($data = mysqli_fetch_assoc($query)) {
        ?>
        <tr>
            <td><?= $no++; ?></td>
            <td><?= htmlspecialchars($data['nama_ruangan']); ?></td>
            <td><?= $data['tanggal']; ?></td>
            <td><?= substr($data['waktu_mulai'], 0, 5) . " - " . substr($data['waktu_selesai'], 0, 5); ?></td>
            <td><?= $data['durasi_pinjam']; ?> jam</td>
            <td><?= ucfirst($data['status']); ?></td>
        </tr>
        <?php } ?>
    </table>
</div>

</body>
</html>
