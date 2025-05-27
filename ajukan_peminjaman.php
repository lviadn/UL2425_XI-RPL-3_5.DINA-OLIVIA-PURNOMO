<?php
session_start();
include 'inc_koneksi.php';

// Cek apakah user sudah login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $ruangan_id = $_POST['ruangan_id'];
    $tanggal = $_POST['tanggal'];
    $waktu_mulai = $_POST['waktu_mulai'];
    $durasi_pinjam = $_POST['durasi_pinjam'];
    $waktu_selesai = date('H:i:s', strtotime("$waktu_mulai +{$durasi_pinjam} hours"));

    $status = 'Menunggu';

    $stmt = mysqli_prepare($koneksi, "INSERT INTO peminjaman_ruangan (user_id, ruangan_id, tanggal, waktu_mulai, durasi_pinjam, waktu_selesai, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, 'iississ', $user_id, $ruangan_id, $tanggal, $waktu_mulai, $durasi_pinjam, $waktu_selesai, $status);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: dashboard_user.php?success=1");
        exit();
    } else {
        echo "Terjadi kesalahan: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Ajukan Peminjaman Ruangan</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #fcefe8;
            color: #444;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            background-color: #fff8f0;
            margin: 80px auto;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 6px 16px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #ff8c94;
        }

        form label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }

        form input[type="text"],
        form input[type="date"],
        form input[type="time"],
        form input[type="number"],
        form select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 10px;
            border: 1px solid #ddd;
            background-color: #fff;
        }

        form input[type="submit"] {
            margin-top: 25px;
            width: 100%;
            background-color: #ffd3b6;
            color: #333;
            border: none;
            padding: 12px;
            border-radius: 12px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        form input[type="submit"]:hover {
            background-color: #ffaaa5;
            color: white;
        }

        .back-link {
            display: block;
            margin-top: 20px;
            text-align: center;
            color: #999;
            text-decoration: none;
        }

        .back-link:hover {
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Form Peminjaman Ruangan</h2>
        <form method="POST" action="">
            <label>Ruangan:</label>
            <select name="ruangan_id" required>
                <option value="">-- Pilih Ruangan --</option>
                <?php
                $result = mysqli_query($koneksi, "SELECT * FROM ruangan");
                while ($r = mysqli_fetch_assoc($result)) {
                    echo "<option value='{$r['ruangan_id']}'>{$r['nama_ruangan']} ({$r['lokasi']})</option>";
                }
                ?>
            </select>

            <label>Tanggal:</label>
            <input type="date" name="tanggal" required>

            <label>Waktu Mulai:</label>
            <input type="time" name="waktu_mulai" required>

            <label>Durasi (jam):</label>
            <input type="number" name="durasi_pinjam" min="1" required>

            <input type="submit" value="Ajukan Peminjaman">
        </form>

        <a class="back-link" href="dashboard_user.php">← Kembali ke Dashboard</a>
    </div>
</body>
</html>
