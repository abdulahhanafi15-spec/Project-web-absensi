<?php

if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'user') {

    header("Location: ../../public/index.php");
    exit;
}

$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "cahaya_cakra"
);

$query = mysqli_query($conn,"
SELECT
    sekolah.id_sekolah,
    sekolah.nama_sekolah,
    COUNT(murid.id_murid) AS jumlah_murid
FROM sekolah
LEFT JOIN murid
ON sekolah.id_sekolah = murid.id_sekolah
GROUP BY sekolah.id_sekolah
ORDER BY sekolah.nama_sekolah ASC
");

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struktur</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<div class="sidebar" id="sidebar">

    <button class="toggle-btn" onclick="toggleMenu()">☰</button>

    <ul>
        <li><a href="index.php?page=user_dashboard"><span>🏠</span><span class="text">Dashboard</span></a></li>
        <li><a href="index.php?page=user_struktur"><span>👥</span><span class="text">Struktur</span></a></li>
        <li><a href="index.php?page=user_pelatih"><span>📋</span><span class="text">Karyawan</span></a></li>
        <li><a href="index.php?page=user_sekolah"><span>🏫</span><span class="text">Sekolah</span></a></li>
        <li><a href="index.php?page=user_absensi"><span>📅</span><span class="text">Absensi</span></a></li>
        <li><a href="index.php?page=user_setting"><span>⚙️</span><span class="text">Pengaturan</span></a></li>
        <li><a href="index.php?page=user_penilaian"><span>⭐</span><span class="text">Penilaian</span></a></li>
        <li><a href="index.php?page=logout"><span>🚪</span><span class="text">Logout</span></a></li>
    </ul>

</div>

<div class="main">

    <div class="header">
        <img src="img/Cahaya.png" alt="Logo 1">
        <img src="img/Perpani.png" alt="Logo 2">
    </div>

    <div class="content-card">
        <h2>Data Sekolah</h2>

    <input
        type="text"
        id="searchSekolah"
        placeholder="Cari Sekolah..."
    >

    <br><br>
<div class="table-container">
    <table class="karyawan-table">

        <thead>
            <tr>
                <th>No</th>
                <th>Nama Sekolah</th>
                <th>Jumlah Murid</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody id="tableSekolah">

        <?php
        $no = 1;
        while($row = mysqli_fetch_assoc($query)):
        ?>

            <tr>

                <td><?= $no++ ?></td>

                <td class="nama-sekolah">
                    <?= $row['nama_sekolah'] ?>
                </td>

                <td>
                    <?= $row['jumlah_murid'] ?>
                </td>

                <td>

                    <a href="index.php?page=user_detail_absensi&id=<?= $row['id_sekolah'] ?>">
                        <button
                                type="button"
                                class="btn detail"
                            >
                        Detail
                        </button>
                    </a>

                </td>

            </tr>

        <?php endwhile; ?>

        </tbody>

    </table>
</div>
    </div>

</div>

<script src="/js/script.js"></script>
<script src="/js/search_sekolah.js"></script>
</body>
</html>