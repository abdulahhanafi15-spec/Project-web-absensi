<?php

if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../../public/index.php");
    exit;
}

/* KONEKSI DATABASE */
$conn = mysqli_connect("localhost", "root", "", "cahaya_cakra");

/* VALIDASI KONEKSI */
if (!$conn) {
    die("Koneksi gagal : " . mysqli_connect_error());
}

/* ===================================== */
/* SEARCH */
/* ===================================== */

$cari = "";
$filter = "nama";

if (isset($_GET['cari'])) {

    $cari = $_GET['cari'];
    $filter = $_GET['filter'];

    if ($filter == "sekolah") {

        $query = mysqli_query($conn, "

            SELECT
                siswa.id_siswa,
                siswa.nama_siswa,
                sekolah.nama_sekolah

            FROM siswa

            JOIN sekolah
            ON siswa.id_sekolah = sekolah.id_sekolah

            WHERE sekolah.nama_sekolah LIKE '%$cari%'

            ORDER BY siswa.nama_siswa ASC

        ");

    } else {

        $query = mysqli_query($conn, "

            SELECT
                siswa.id_siswa,
                siswa.nama_siswa,
                sekolah.nama_sekolah

            FROM siswa

            JOIN sekolah
            ON siswa.id_sekolah = sekolah.id_sekolah

            WHERE siswa.nama_siswa LIKE '%$cari%' 

            ORDER BY siswa.nama_siswa ASC

        ");
    }

} else {

    $query = mysqli_query($conn, "

        SELECT
            siswa.id_siswa,
            siswa.nama_siswa,
            sekolah.nama_sekolah

        FROM siswa

        JOIN sekolah
        ON siswa.id_sekolah = sekolah.id_sekolah

        ORDER BY siswa.nama_siswa ASC

    ");
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Penilaian Siswa</title>

    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar" id="sidebar">

    <button class="toggle-btn" onclick="toggleMenu()">☰</button>

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
                <span>📝</span>
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

<!-- MAIN -->
<div class="main">

    <!-- HEADER -->
    <div class="header">

        <img src="img/Cahaya.png" alt="Logo 1">

        <img src="img/Perpani.png" alt="Logo 2">

    </div>

    <!-- CONTENT -->
    <div class="content-card">

        <!-- JUDUL -->
        <div class="card-header">
            <h1>Penilaian Siswa</h1>
        </div>

        <!-- SEARCH -->
        <div class="search-container">

            <select id="filterSearch" class="search-input">

                <option value="nama">
                    Nama Siswa
                </option>

                <option value="sekolah">
                    Asal Sekolah
                </option>

            </select>

            <input
                type="text"
                id="searchInput"
                placeholder="Cari data..."
                class="search-input"
                autocomplete="off"
            >

        </div>
        <!-- TABLE -->
        <div class="table-container">

            <table class="karyawan-table">

                <thead>

                    <tr>
                        <th>No</th>
                        <th>Nama Siswa</th>
                        <th>Asal Sekolah</th>
                        <th>Aksi</th>
                    </tr>

                </thead>

                <tbody id="tableData">

                    <?php

                    $no = 1;

                    while ($data = mysqli_fetch_assoc($query)) {

                    ?>

                    <tr>

                        <td><?= $no++; ?></td>

                        <td class="nama-siswa">
                            <?= $data['nama_siswa']; ?>
                        </td>

                        <td class="nama-sekolah">
                            <?= $data['nama_sekolah']; ?>
                        </td>

                        <td>

                            <button
                                type="button"
                                class="btn detail"
                                onclick="openModal(
                                    '<?= $data['id_siswa']; ?>',
                                    '<?= $data['nama_siswa']; ?>'
                                )"
                            >
                                Edit Nilai
                            </button>

                        </td>

                    </tr>

                    <?php } ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<!-- ===================================== -->
<!-- MODAL PENILAIAN -->
<!-- ===================================== -->

<div id="modalNilai" class="modal">

    <div class="modal-content">

        <span class="close" onclick="closeModal()">
            &times;
        </span>

        <h2>Input Penilaian</h2>

        <form action="index.php?page=simpan_penilaian" method="POST">

            <input
                type="hidden"
                name="id_siswa"
                id="id_siswa"
            >

            <div class="form-group">

                <label>Nama Siswa</label>

                <input
                    type="text"
                    id="nama_siswa"
                    readonly
                >

            </div>

            <div class="form-group">

                <label>Kedisiplinan</label>

                <input
                    type="number"
                    name="kedisiplinan"
                    min="0"
                    max="100"
                    required
                >

            </div>

            <div class="form-group">

                <label>Kerjasama</label>

                <input
                    type="number"
                    name="kerjasama"
                    min="0"
                    max="100"
                    required
                >

            </div>

            <div class="form-group">

                <label>Keaktifan</label>

                <input
                    type="number"
                    name="keaktifan"
                    min="0"
                    max="100"
                    required
                >

            </div>

            <button type="submit" class="btn-simpan">
                Simpan Nilai
            </button>

        </form>

    </div>

</div>

<script src="../../js/penilaian.js"></script>
<script src="/js/script.js"></script>

</body>
</html>