<?php
session_start();
include 'inc_koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Cek apakah ada peminjaman yang belum diajukan pengembalian
$query = "SELECT p.*, r.nama_ruangan 
          FROM peminjaman_ruangan p 
          JOIN ruangan r ON p.ruangan_id = r.ruangan_id 
          WHERE p.user_id = $user_id AND p.status = 'Disetujui'";
$result = mysqli_query($koneksi, $query);

if (isset($_POST['ajukan'])) {
    $peminjaman_id = intval($_POST['peminjaman_id']);
    $update = mysqli_query($koneksi, "UPDATE peminjaman_ruangan SET status='Menunggu Pengembalian' WHERE peminjaman_id=$peminjaman_id AND user_id=$user_id");

    if ($update) {
        $pesan = "Pengajuan pengembalian berhasil diajukan!";
    } else {
        $pesan = "Terjadi kesalahan saat mengajukan pengembalian.";
    }

    // Refresh data setelah update
    $result = mysqli_query($koneksi, $query);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Ajukan Pengembalian</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #FBF4EB;
            margin: 0;
            padding: 20px;
        }
        .container {
            background-color: #fff;
            max-width: 700px;
            margin: 30px auto;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        h2 {
            color: #F283AF;
            margin-bottom: 20px;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #eee;
            text-align: center;
        }
        th {
            background-color: #FBD9E5;
            color: #C43670;
        }
        .btn {
            padding: 8px 16px;
            background-color: #F283AF;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #C43670;
        }
        .message {
            margin-bottom: 15px;
            padding: 10px;
            background-color: #D4EDDA;
            color: #155724;
            border-radius: 8px;
            text-align: center;
        }
        a.btn {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Ajukan Pengembalian Ruangan</h2>

        <?php if (isset($pesan)): ?>
            <div class="message"><?= $pesan ?></div>
        <?php endif; ?>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <form method="post">
                <table>
                    <tr>
                        <th>Ruangan</th>
                        <th>Tanggal</th>
                        <th>Mulai</th>
                        <th>Durasi</th>
                        <th>Ajukan</th>
                    </tr>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= $row['nama_ruangan'] ?></td>
                            <td><?= $row['tanggal'] ?></td>
                            <td><?= $row['waktu_mulai'] ?></td>
                            <td><?= $row['durasi_pinjam'] ?> jam</td>
                            <td>
                                <button class="btn" type="submit" name="ajukan" value="1" onclick="document.getElementById('id_<?= $row['peminjaman_id'] ?>').checked = true;">Ajukan</button>
                                <input type="radio" id="id_<?= $row['peminjaman_id'] ?>" name="peminjaman_id" value="<?= $row['peminjaman_id'] ?>" style="display:none;" required>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            </form>
        <?php else: ?>
            <p style="text-align:center; color: #555;">Tidak ada peminjaman aktif yang dapat diajukan pengembalian.</p>
        <?php endif; ?>

        <div style="text-align:center;">
            <a href="dashboard_user.php" class="btn">Kembali ke Dashboard</a>
        </div>
    </div>
</body>
</html>
