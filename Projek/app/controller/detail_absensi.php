<?php

$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "cahaya_cakra"
);

$id_sekolah = $_GET['id'];

$sekolah = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT * FROM sekolah WHERE id_sekolah='$id_sekolah'"
    )
);

$murid = mysqli_query(
    $conn,
    "SELECT *
    FROM murid
    WHERE id_sekolah='$id_sekolah'
    ORDER BY nama_murid ASC"
);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Detail Pelatih</title>

    <link rel="stylesheet" href="/css/style.css">

</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar" id="sidebar">

    <button class="toggle-btn" onclick="toggleMenu()">☰</button>

    <ul>
        <li><a href="index.php?page=admin_dashboard"><span>🏠</span><span class="text">Dashboard</span></a></li>
        <li><a href="index.php?page=struktur"><span>👥</span><span class="text">Struktur</span></a></li>
        <li><a href="index.php?page=pelatih_admin"><span>📋</span><span class="text">Karyawan</span></a></li>
        <li><a href="index.php?page=sekolah_admin"><span>🏫</span><span class="text">Sekolah</span></a></li>
        <li><a href="index.php?page=absensi"><span>📅</span><span class="text">Absensi</span></a></li>
        <li><a href="index.php?page=laporan"><span>📊</span><span class="text">Laporan</span></a></li>
        <li><a href="index.php?page=setting_admin"><span>⚙️</span><span class="text">Pengaturan</span></a></li>
        <li><a href="index.php?page=penilaian"><span>⭐</span><span class="text">Penilaian</span></a></li>
        <li><a href="index.php?page=logout"><span>🚪</span><span class="text">Logout</span></a></li>
    </ul>

</div>

<!-- MAIN -->
<div class="main">

    <!-- HEADER -->
    <div class="header">

        <img src="img/Cahaya.png" alt="Logo 1">

        <img src="img/Perpani.png" alt="Logo 2">

    </div>

    <!-- CARD -->
    <div class="content-card">

        <h2>
            Absensi Murid - <?= $sekolah['nama_sekolah']; ?>
        </h2>

        <form
            action="index.php?page=simpan_absensi"
            method="POST"
        >

        <table class="karyawan-table">

        <thead>
            <tr>
                <th>No</th>
                <th>Nama Murid</th>
                <th>Kelas</th>
                <th>Tanggal</th>
                <th>P1</th>
                <th>P2</th>
                <th>P3</th>
                <th>P4</th>
                <th>Nilai Absensi</th>
            </tr>
        </thead>

        <tbody>

            <?php
            $no = 1;

            while($row = mysqli_fetch_assoc($murid)):
            ?>

            <tr>

                <td><?= $no++ ?></td>

                <td>
                    <?= $row['nama_murid']; ?>

                    <input
                        type="hidden"
                        name="id_murid[]"
                        value="<?= $row['id_murid']; ?>"
                    >
                </td>

                <td><?= $row['kelas']; ?></td>

                <td>
                    <input
                        type="date"
                        name="tanggal[]"
                        value="<?= date('Y-m-d'); ?>"
                        required
                    >
                </td>

                <td>
                    <select class="kehadiran" name="p1[]">
                        <option value="0">Tidak Hadir</option>
                        <option value="25">Hadir</option>
                    </select>
                </td>

                <td>
                    <select class="kehadiran" name="p2[]">
                        <option value="0">Tidak Hadir</option>
                        <option value="25">Hadir</option>
                    </select>
                </td>

                <td>
                    <select class="kehadiran" name="p3[]">
                        <option value="0">Tidak Hadir</option>
                        <option value="25">Hadir</option>
                    </select>
                </td>

                <td>
                    <select class="kehadiran" name="p4[]">
                        <option value="0">Tidak Hadir</option>
                        <option value="25">Hadir</option>
                    </select>
                </td>

                <td class="nilai-absensi">
                    0
                </td>

            </tr>

            <?php endwhile; ?>

            </tbody>

                </table>

                <br>

                <button type="submit">
                Simpan Absensi
                </button>

                </form>
    </div>

</div>

<script src="/js/script.js"></script>
<script src="/js/hitung_absensi.js"></script>

</body>
</html>