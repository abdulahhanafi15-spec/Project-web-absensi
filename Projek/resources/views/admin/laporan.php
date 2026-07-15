<?php

if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../../public/index.php");
    exit;
}

/* =====================================
   KONEKSI DATABASE
===================================== */

$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "cahaya_cakra"
);

if (!$conn) {
    die("Koneksi gagal : " . mysqli_connect_error());
}

/* =====================================
   DATA SEKOLAH
===================================== */

$querySekolah = mysqli_query(

    $conn,

    "SELECT
        id_sekolah,
        nama_sekolah
    FROM sekolah
    ORDER BY nama_sekolah ASC"

);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Laporan</title>

    <link
        rel="stylesheet"
        href="/css/style.css"
    >

</head>

<body>

<!-- =====================================
     SIDEBAR
===================================== -->

<div class="sidebar" id="sidebar">

    <button
        class="toggle-btn"
        onclick="toggleMenu()"
    >
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
            <a href="index.php?page=struktur">
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
            <a href="index.php?page=sekolah_admin">
                <span>🏫</span>
                <span class="text">Sekolah</span>
            </a>
        </li>

        <li>
            <a href="index.php?page=absensi">
                <span>📅</span>
                <span class="text">Absensi</span>
            </a>
        </li>

        <li>
            <a href="index.php?page=laporan">
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
            <a href="index.php?page=penilaian">
                <span>⭐</span>
                <span class="text">Penilaian</span>
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

<!-- =====================================
     MAIN
===================================== -->

<div class="main">

    <!-- HEADER -->

    <div class="header">

        <img
            src="img/Cahaya.png"
            alt="Logo Cahaya"
        >

        <img
            src="img/Perpani.png"
            alt="Logo Perpani"
        >

    </div>

    <!-- CONTENT -->

    <div class="content-card">

        <div class="card-header">

            <h1>
                Laporan
            </h1>

        </div>

        <p style="margin-bottom:25px;">

            Pilih sekolah, jenis laporan dan periode
            untuk mencetak laporan dalam format PDF.

        </p>

<!-- =====================================
     FORM CETAK LAPORAN
===================================== -->

<form id="formLaporan" onsubmit="return false;">

    <div class="form-group">

        <label>
            Sekolah
        </label>

        <select
            id="idSekolah"
            required
        >

            <option value="">
                -- Pilih Sekolah --
            </option>

            <?php while($sekolah = mysqli_fetch_assoc($querySekolah)) : ?>

                <option
                    value="<?= $sekolah['id_sekolah']; ?>"
                >

                    <?= $sekolah['nama_sekolah']; ?>

                </option>

            <?php endwhile; ?>

        </select>

    </div>

    <br>

    <div class="form-group">

        <label>
            Jenis Laporan
        </label>

        <select
            id="jenisLaporan"
            required
        >

            <option value="">
                -- Pilih Jenis Laporan --
            </option>

            <option value="absensi">
                Laporan Absensi
            </option>

            <option value="nilai">
                Laporan Nilai
            </option>

        </select>

    </div>

    <br>

    <div class="form-group">

        <label>
            Periode
        </label>

        <input
            type="month"
            id="periode"
            value="<?= date('Y-m'); ?>"
            required
        >

    </div>

    <br><br>

    <div class="tambah-container">

        <button
            type="button"
            class="btn tambah"
            id="btnCetak"
        >

            🖨 Cetak PDF

        </button>

    </div>

</form>

        <!-- =====================================
             INFORMASI
        ===================================== -->

        <br>

        <div
            style="
                margin-top:20px;
                padding:15px;
                background:#f5f5f5;
                border-left:5px solid #2c7be5;
                border-radius:5px;
            "
        >

            <b>Informasi :</b>

            <ul style="margin-top:10px; margin-left:20px;">

                <li>
                    Pilih sekolah yang akan dicetak laporannya.
                </li>

                <li>
                    Pilih jenis laporan (Absensi atau Nilai).
                </li>

                <li>
                    Pilih periode (bulan dan tahun).
                </li>

                <li>
                    Klik tombol <b>Cetak PDF</b>.
                </li>

            </ul>

        </div>

    </div>

</div>

<!-- =====================================
     JAVASCRIPT
===================================== -->

<script src="/js/script.js"></script>

<script src="/js/laporan.js"></script>

</body>
</html>