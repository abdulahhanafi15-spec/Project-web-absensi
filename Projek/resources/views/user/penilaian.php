<?php

if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'user') {

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
                murid.id_murid,
                murid.nama_murid,
                sekolah.nama_sekolah

            FROM murid

            JOIN sekolah
            ON murid.id_sekolah = sekolah.id_sekolah

            WHERE sekolah.nama_sekolah LIKE '%$cari%'

            ORDER BY murid.nama_murid ASC

        ");

    } else {

        $query = mysqli_query($conn, "

            SELECT
                murid.id_murid,
                murid.nama_murid,
                sekolah.nama_sekolah

            FROM murid

            JOIN sekolah
            ON murid.id_sekolah = sekolah.id_sekolah

            WHERE murid.nama_murid LIKE '%$cari%' 

            ORDER BY murid.nama_murid ASC

        ");
    }

} else {

    $query = mysqli_query($conn, "

        SELECT
            murid.id_murid,
            murid.nama_murid,
            sekolah.nama_sekolah

        FROM murid

        JOIN sekolah
        ON murid.id_sekolah = sekolah.id_sekolah

        ORDER BY murid.nama_murid ASC

    ");
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penilaian Atlet</title>

    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<!-- SIDEBAR -->
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
        <h1>Penilaian Atlet Berprestasi</h1>
    </div>

    <!-- SEARCH -->
    <div class="search-container">

        <input
            type="text"
            id="searchInput"
            placeholder="Cari Sekolah..."
            class="search-input"
            autocomplete="off"
        >

    </div>

    <!-- TABLE -->
    <div class="table-container">

        <table class="karyawan-table">

            <thead>

                <tr>
                    <th width="10%">No</th>
                    <th>Nama Sekolah</th>
                    <th width="20%">Aksi</th>
                </tr>

            </thead>

            <tbody id="tableData">

                <?php

                $query = mysqli_query(

                    $conn,

                    "SELECT *
                    FROM sekolah
                    ORDER BY nama_sekolah ASC"

                );

                $no = 1;

                while ($data = mysqli_fetch_assoc($query)) {

                ?>

                <tr>

                    <td>
                        <?= $no++; ?>
                    </td>

                    <td class="nama-sekolah">

                        <?= $data['nama_sekolah']; ?>

                    </td>

                    <td>

                        <a href="index.php?page=detail_penilaian_user&id=<?= $data['id_sekolah']; ?>">

                            <button
                                type="button"
                                class="btn detail"
                            >
                                Penilaian
                            </button>

                        </a>

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

        <form action="index.php?page=user_simpan_penilaian" method="POST">

            <input
                type="hidden"
                name="id_murid"
                id="id_murid"
            >

            <div class="form-group">

                <label>Nama Atlet</label>

                <input
                    type="text"
                    id="nama_murid"
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