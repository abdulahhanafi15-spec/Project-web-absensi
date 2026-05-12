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
    <meta charset="UTF-8">
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
            <li><a href="index.php?page=absensi"><span>📅</span><span class="text">Absensi</span></a></li>
            <li><a href="index.php?page=laporan"><span>📊</span><span class="text">Laporan</span></a></li>
            <li><a href="index.php?page=setting_admin"><span>⚙️</span><span class="text">Pengaturan</span></a></li>
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

        <!-- CONTENT -->
        <div class="content-card">
            <h1 style="text-align: center;">
                <img src="img/Cahaya.png" alt="logo Cahaya Cakra" style="width: 250px; height: auto;"><br>
                Selamat datang, <?php echo $_SESSION['username']; ?>
            </h1>
            <p>
                Organisasi ini mengelola sistem absensi berbasis web untuk meningkatkan
                efisiensi dan transparansi.
            </p>
            <p>
                Dashboard ini membantu admin memantau aktivitas dan data secara real-time.
            </p>
        </div>

    </div>

    <script src="script.js"></script>
</body>
</html>