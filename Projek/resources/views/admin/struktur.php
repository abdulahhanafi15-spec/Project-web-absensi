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
    <title>Struktur</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<div class="sidebar" id="sidebar">
    <button class="toggle-btn" onclick="toggleMenu()">☰</button>

     <ul>
            <li><a href="index.php?page=admin_dashboard"><span>🏠</span><span class="text">Dashboard</span></a></li>
            <li><a href="index.php?page=structure"><span>👥</span><span class="text">Struktur</span></a></li>
            <li><a href="index.php?page=pelatih_admin"><span>📋</span><span class="text">Karyawan</span></a></li>
            <li><a href="index.php?page=attendance"><span>📅</span><span class="text">Absensi</span></a></li>
            <li><a href="index.php?page=reports"><span>📊</span><span class="text">Laporan</span></a></li>
            <li><a href="index.php?page=setting_admin"><span>⚙️</span><span class="text">Pengaturan</span></a></li>
            <li><a href="index.php?page=logout"><span>🚪</span><span class="text">Logout</span></a></li>
    </ul>
</div>

<div class="main">

    <div class="header">
        <img src="img/Cahaya.png" alt="Logo 1">
        <img src="img/Perpani.png" alt="Logo 2">
    </div>

    <div class="content-card">
        <h1>Halaman Struktur</h1>
    </div>

</div>

<script src="/js/script.js"></script>
</body>
</html>