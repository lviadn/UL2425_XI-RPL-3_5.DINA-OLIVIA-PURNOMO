<?php
session_start();
include 'inc_koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['setujui_peminjaman'])) {
    $id = intval($_GET['setujui_peminjaman']);
    mysqli_query($koneksi, "UPDATE peminjaman_ruangan SET status='Disetujui' WHERE peminjaman_id = $id");
}
if (isset($_GET['tolak_peminjaman'])) {
    $id = intval($_GET['tolak_peminjaman']);
    mysqli_query($koneksi, "UPDATE peminjaman_ruangan SET status='Ditolak' WHERE peminjaman_id = $id");
}
if (isset($_GET['setujui_pengembalian'])) {
    $id = intval($_GET['setujui_pengembalian']);
    mysqli_query($koneksi, "UPDATE peminjaman_ruangan SET status='Selesai' WHERE peminjaman_id = $id");
}
if (isset($_GET['tolak_pengembalian'])) {
    $id = intval($_GET['tolak_pengembalian']);
    mysqli_query($koneksi, "UPDATE peminjaman_ruangan SET status='Pengembalian Ditolak' WHERE peminjaman_id = $id");
}
if (isset($_GET['hapus_ruangan'])) {
    $id = intval($_GET['hapus_ruangan']);
    mysqli_query($koneksi, "DELETE FROM ruangan WHERE ruangan_id = $id");
}

$page = $_GET['page'] ?? 'data';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Admin Panel</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0; font-family: 'Inter', sans-serif;
            background-color: #FBF4EB;
        }
        .sidebar {
            position: fixed;
            top: 0; left: 0;
            width: 220px; height: 100vh;
            background-color: #FBD9E5;
            padding: 30px 20px;
            box-shadow: 2px 0 10px rgba(0,0,0,0.05);
        }
        .sidebar h2 {
            color: #C43670;
            text-align: center;
            margin-bottom: 30px;
        }
        .sidebar a {
            display: block;
            color: #C43670;
            text-decoration: none;
            padding: 12px 16px;
            margin-bottom: 10px;
            background-color: #FFF;
            border-radius: 8px;
            font-weight: 600;
        }
        .sidebar a:hover {
            background-color: #F3CC97;
        }
        .content {
            margin-left: 240px;
            padding: 40px;
        }
        h1 {
            color: #F283AF;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #eee;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #FBD9E5;
            color: #C43670;
        }
        .btn {
            padding: 6px 12px;
            background-color: #F283AF;
            color: #fff;
            text-decoration: none;
            border-radius: 6px;
            margin: 4px;
            border: none;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #C43670;
        }
        .status {
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 6px;
        }
        .status-menunggu { background: #FFF3CD; color: #856404; }
        .status-disetujui { background: #D4EDDA; color: #155724; }
        .status-ditolak { background: #F8D7DA; color: #721C24; }
        .status-selesai { background: #CCE5FF; color: #004085; }
        .status-pengembalian-ditolak { background: #F8D7DA; color: #721C24; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>ADMIN</h2>
        <a href="?page=data">Data Peminjaman</a>
        <a href="?page=approve_peminjaman">Approve Peminjaman</a>
        <a href="?page=approve_pengembalian">Approve Pengembalian</a>
        <a href="?page=crud_ruangan">Manajemen Ruangan</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="content">
        <?php if ($page == 'approve_peminjaman'): ?>
            <h1>Approve Peminjaman</h1>
            <table>
                <tr><th>User</th><th>Ruangan</th><th>Tanggal</th><th>Mulai</th><th>Durasi</th><th>Status</th><th>Aksi</th></tr>
                <?php
                $q = mysqli_query($koneksi, "SELECT p.*, u.username, r.nama_ruangan FROM peminjaman_ruangan p 
                    JOIN users u ON p.user_id=u.user_id 
                    JOIN ruangan r ON p.ruangan_id=r.ruangan_id 
                    WHERE p.status='Menunggu'");
                while ($row = mysqli_fetch_assoc($q)):
                ?>
                <tr>
                    <td><?= $row['username'] ?></td>
                    <td><?= $row['nama_ruangan'] ?></td>
                    <td><?= $row['tanggal'] ?></td>
                    <td><?= $row['waktu_mulai'] ?></td>
                    <td><?= $row['durasi_pinjam'] ?> jam</td>
                    <td><span class="status status-menunggu"><?= $row['status'] ?></span></td>
                    <td>
                        <a class="btn" href="?setujui_peminjaman=<?= $row['peminjaman_id'] ?>&page=approve_peminjaman">Setujui</a>
                        <a class="btn" href="?tolak_peminjaman=<?= $row['peminjaman_id'] ?>&page=approve_peminjaman">Tolak</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>

        <?php elseif ($page == 'approve_pengembalian'): ?>
            <h1>Approve Pengembalian</h1>
            <table>
                <tr><th>User</th><th>Ruangan</th><th>Tanggal</th><th>Durasi</th><th>Status</th><th>Aksi</th></tr>
                <?php
                $q = mysqli_query($koneksi, "SELECT p.*, u.username, r.nama_ruangan FROM peminjaman_ruangan p 
                    JOIN users u ON p.user_id=u.user_id 
                    JOIN ruangan r ON p.ruangan_id=r.ruangan_id 
                    WHERE p.status='Menunggu Pengembalian'");
                while ($row = mysqli_fetch_assoc($q)):
                ?>
                <tr>
                    <td><?= $row['username'] ?></td>
                    <td><?= $row['nama_ruangan'] ?></td>
                    <td><?= $row['tanggal'] ?></td>
                    <td><?= $row['durasi_pinjam'] ?> jam</td>
                    <td><span class="status status-menunggu"><?= $row['status'] ?></span></td>
                    <td>
                        <a class="btn" href="?setujui_pengembalian=<?= $row['peminjaman_id'] ?>&page=approve_pengembalian">Setujui</a>
                        <a class="btn" href="?tolak_pengembalian=<?= $row['peminjaman_id'] ?>&page=approve_pengembalian">Tolak</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>

        <?php elseif ($page == 'crud_ruangan'): ?>
            <h1>Manajemen Ruangan</h1>
            <a class="btn" href="tambah_ruangan.php">+ Tambah Ruangan</a>
            <table>
                <tr><th>Nama</th><th>Lokasi</th><th>Kapasitas</th><th>Aksi</th></tr>
                <?php
                $q = mysqli_query($koneksi, "SELECT * FROM ruangan");
                while ($row = mysqli_fetch_assoc($q)):
                ?>
                <tr>
                    <td><?= $row['nama_ruangan'] ?></td>
                    <td><?= $row['lokasi'] ?></td>
                    <td><?= $row['kapasitas'] ?></td>
                    <td>
                        <a class="btn" href="edit_ruangan.php?id=<?= $row['ruangan_id'] ?>">Edit</a>
                        <a class="btn" href="?hapus_ruangan=<?= $row['ruangan_id'] ?>&page=crud_ruangan" onclick="return confirm('Yakin hapus ruangan ini?')">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>

        <?php else: ?>
            <h1>Data Peminjaman</h1>
            <button onclick="downloadPDF()" class="btn">Export PDF</button>
            <table id="laporan">
                <tr><th>User</th><th>Ruangan</th><th>Tanggal</th><th>Waktu</th><th>Durasi</th><th>Status</th></tr>
                <?php
                $q = mysqli_query($koneksi, "SELECT p.*, u.username, r.nama_ruangan FROM peminjaman_ruangan p 
                    JOIN users u ON p.user_id=u.user_id 
                    JOIN ruangan r ON p.ruangan_id=r.ruangan_id 
                    ORDER BY p.tanggal DESC");
                while ($row = mysqli_fetch_assoc($q)):
                    $status = $row['status'];
                    $class = match($status) {
                        'Disetujui' => 'status-disetujui',
                        'Ditolak' => 'status-ditolak',
                        'Selesai' => 'status-selesai',
                        'Pengembalian Ditolak' => 'status-pengembalian-ditolak',
                        default => 'status-menunggu',
                    };
                ?>
                <tr>
                    <td><?= $row['username'] ?></td>
                    <td><?= $row['nama_ruangan'] ?></td>
                    <td><?= $row['tanggal'] ?></td>
                    <td><?= $row['waktu_mulai'] ?></td>
                    <td><?= $row['durasi_pinjam'] ?> jam</td>
                    <td><span class="status <?= $class ?>"><?= $status ?></span></td>
                </tr>
                <?php endwhile; ?>
            </table>
        <?php endif; ?>
    </div>

    <!-- jsPDF Export Script -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script>
        async function downloadPDF() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            doc.setFontSize(16);
            doc.text("LAPORAN PEMINJAMAN RUANGAN", 105, 20, null, null, "center");
            doc.setFontSize(12);
            doc.text("Tanggal: " + new Date().toLocaleDateString(), 105, 28, null, null, "center");

            const table = document.querySelector("#laporan").cloneNode(true);
            table.querySelectorAll("td:last-child, th:last-child").forEach(el => el.remove());

            let rows = [];
            table.querySelectorAll("tr").forEach(tr => {
                let row = [];
                tr.querySelectorAll("th, td").forEach(td => {
                    row.push(td.innerText.trim());
                });
                rows.push(row);
            });

            let startY = 40;
            const cellPadding = 4;
            const colWidths = [30, 30, 30, 25, 25, 40];

            rows.forEach(row => {
                let x = 10;
                row.forEach((cell, i) => {
                    const width = colWidths[i] || 30;
                    doc.rect(x, startY, width, 10);
                    doc.text(cell, x + cellPadding, startY + 7);
                    x += width;
                });
                startY += 10;
                if (startY > 270) {
                    doc.addPage();
                    startY = 20;
                }
            });

            doc.save("laporan_peminjaman_ruangan.pdf");
        }
    </script>
</body>
</html>
