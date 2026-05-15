<?php

if (!isset($_SESSION)) {
    session_start();
}

/* VALIDASI ADMIN */
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {

    header("Location: ../../public/index.php");
    exit;
}

/* KONEKSI DATABASE */
$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "cahaya_cakra"
);

/* AMBIL ID */
$id = $_GET['id'];

/* QUERY DATA */
$query = mysqli_query(

    $conn,

    "SELECT * FROM pelatih
    WHERE id = '$id'"
);

$data = mysqli_fetch_assoc($query);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Detail Pelatih</title>

    <link rel="stylesheet" href="/css/style.css">

</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar" id="sidebar">

    <button class="toggle-btn" onclick="toggleMenu()">
        ☰
    </button>

    <ul>

        <li>
            <a href="index.php?page=admin_dashboard">
                <span>🏠</span>
                <span class="text">Dashboard</span>
            </a>
        </li>

        <li>
            <a href="index.php?page=structure">
                <span>👥</span>
                <span class="text">Struktur</span>
            </a>
        </li>

        <li>
            <a href="index.php?page=pelatih_admin">
                <span>📋</span>
                <span class="text">Karyawan</span>
            </a>
        </li>

        <li>
            <a href="index.php?page=attendance">
                <span>📅</span>
                <span class="text">Absensi</span>
            </a>
        </li>

        <li>
            <a href="index.php?page=reports">
                <span>📊</span>
                <span class="text">Laporan</span>
            </a>
        </li>

        <li>
            <a href="index.php?page=setting_admin">
                <span>⚙️</span>
                <span class="text">Pengaturan</span>
            </a>
        </li>

        <li>
            <a href="index.php?page=logout">
                <span>🚪</span>
                <span class="text">Logout</span>
            </a>
        </li>

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

        <h1>Detail Pelatih</h1>

        <div class="detail-container">

            <!-- NAMA -->
            <div class="detail-item">

                <label>Nama Pelatih</label>

                <input
                    type="text"
                    value="<?= $data['nama_pelatih']; ?>"
                    readonly
                >

            </div>

            <!-- NIP -->
            <div class="detail-item">

                <label>NIP</label>

                <input
                    type="text"
                    value="<?= $data['nip']; ?>"
                    readonly
                >

            </div>

            <!-- ALAMAT -->
            <div class="detail-item">

                <label>Alamat</label>

                <textarea readonly><?= $data['alamat']; ?></textarea>

            </div>

            <!-- NO WA -->
            <div class="detail-item">

                <label>No WhatsApp</label>

                <input
                    type="text"
                    value="<?= $data['no_wa']; ?>"
                    readonly
                >

            </div>

            <!-- STATUS -->
            <div class="detail-item">

                <label>Status</label>

                <input
                    type="text"
                    value="<?= $data['status']; ?>"
                    readonly
                >

            </div>

            <!-- BUTTON -->
            <div class="detail-button">

                <!-- BUTTON KEMBALI -->
                <a href="index.php?page=pelatih_admin">

                    <button
                        type="button"
                        class="btn kembali"
                    >
                        ← Kembali
                    </button>

                </a>

                <!-- BUTTON EDIT -->
                <a href="index.php?page=edit_pelatih&id=<?= $data['id']; ?>">

                    <button
                        type="button"
                        class="btn detail"
                    >
                        Edit Data
                    </button>

                </a>

            </div>

        </div>

    </div>

</div>

<script src="/js/script.js"></script>

</body>
</html>