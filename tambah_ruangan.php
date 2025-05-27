<?php
session_start();
include 'inc_koneksi.php';

// Cek login dan role admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_ruangan = trim($_POST['nama_ruangan']);
    $lokasi = trim($_POST['lokasi']);
    $kapasitas = intval($_POST['kapasitas']);

    if (empty($nama_ruangan) || empty($lokasi) || $kapasitas <= 0) {
        $error = "Semua field harus diisi dengan benar.";
    } else {
        // Insert ke database
        $stmt = mysqli_prepare($koneksi, "INSERT INTO ruangan (nama_ruangan, lokasi, kapasitas) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssi", $nama_ruangan, $lokasi, $kapasitas);
        if (mysqli_stmt_execute($stmt)) {
            $success = "Ruangan berhasil ditambahkan.";
            // Bersihkan input
            $nama_ruangan = $lokasi = '';
            $kapasitas = '';
        } else {
            $error = "Gagal menambahkan ruangan: " . mysqli_error($koneksi);
        }
        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Tambah Ruangan - Admin Panel</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap');

        * {
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            box-sizing: border-box;
        }

        html, body {
            margin: 0; padding: 0;
            background: linear-gradient(145deg, #f0e9e9, #d9d2d2);
            color: #333;
            height: 100%;
        }

        .container {
            max-width: 500px;
            margin: 60px auto;
            background: #fff;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 10px 20px rgb(240 240 240 / 0.7);
        }

        h1 {
            text-align: center;
            color: #d35454;
            margin-bottom: 30px;
            font-weight: 600;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 8px;
            font-weight: 600;
            color: #b03c3c;
        }

        input[type="text"],
        input[type="number"] {
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #fff9f9;
            transition: border 0.3s;
            font-size: 14px;
        }

        input[type="text"]:focus,
        input[type="number"]:focus {
            outline: none;
            border-color: #ff8888;
        }

        input[type="submit"] {
            background-color: #ff8888;
            color: white;
            font-weight: 600;
            padding: 14px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: #ff6b6b;
        }

        .message {
            text-align: center;
            margin-bottom: 20px;
            font-weight: 600;
            padding: 10px;
            border-radius: 10px;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        a.back-link {
            display: block;
            margin-top: 20px;
            text-align: center;
            color: #b03c3c;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.3s;
        }

        a.back-link:hover {
            color: #ff6b6b;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Tambah Ruangan</h1>

        <?php if ($error): ?>
            <div class="message error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="message success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <label for="nama_ruangan">Nama Ruangan</label>
            <input type="text" id="nama_ruangan" name="nama_ruangan" value="<?= htmlspecialchars($nama_ruangan ?? '') ?>" required />

            <label for="lokasi">Lokasi</label>
            <input type="text" id="lokasi" name="lokasi" value="<?= htmlspecialchars($lokasi ?? '') ?>" required />

            <label for="kapasitas">Kapasitas</label>
            <input type="number" id="kapasitas" name="kapasitas" min="1" value="<?= htmlspecialchars($kapasitas ?? '') ?>" required />

            <input type="submit" value="Tambah Ruangan" />
        </form>

        <a href="dashboard_admin.php?page=crud_ruangan" class="back-link">Kembali ke Manajemen Ruangan</a>
    </div>
</body>
</html>