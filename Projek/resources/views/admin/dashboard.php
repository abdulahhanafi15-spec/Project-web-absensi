<?php

if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../../public/index.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
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

        <div class="header-logo">
            <img src="img/Cahaya.png" alt="Logo Cahaya">
        </div>

        <div class="header-logo">
            <img src="img/Perpani.png" alt="Logo Perpani">
        </div>

    </div>

    <!-- CONTENT -->
    <div class="content-card">

        <h1 class="dashboard-title">

            <img src="img/Cahaya.png" alt="Logo Cahaya">

            <br>

            Selamat Datang,
            <?php echo $_SESSION['username']; ?>

        </h1>

        <p>
            Organisasi ini mengelola sistem absensi berbasis web
            untuk meningkatkan efisiensi dan transparansi.
        </p>

        <p>
            Dashboard ini membantu admin memantau aktivitas
            dan data secara real-time.
        </p>

    </div>

</div>

<script src="js/script.js"></script>
</body>
</html>