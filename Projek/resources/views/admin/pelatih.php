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

if (isset($_GET['cari'])) {

    $cari = $_GET['cari'];

    $query = mysqli_query(
        $conn,

        "SELECT * FROM pelatih
        WHERE nama_pelatih LIKE '%$cari%'
        ORDER BY id DESC"
    );

} else {

    $query = mysqli_query(
        $conn,

        "SELECT * FROM pelatih
        ORDER BY id DESC"
    );
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pelatih</title>

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

    <!-- CONTENT -->
    <div class="content-card">

        <!-- JUDUL -->
        <div class="card-header">
            <h1>Data Karyawan / Pelatih</h1>
        </div>

        <!-- SEARCH -->
        <form method="GET" action="index.php">

            <input type="hidden" name="page" value="pelatih_admin">

            <div class="search-container">

                <input
                    type="text"
                    name="cari"
                    placeholder="Cari nama pelatih..."
                    class="search-input"
                    value="<?= $cari; ?>"
                >

                <button type="submit" class="search-btn">
                    Cari
                </button>

            </div>

        </form>

        <!-- TABLE -->
        <div class="table-container">

            <table class="karyawan-table">

                <thead>

                    <tr>
                        <th>No</th>
                        <th>Nama Pelatih</th>
                        <th>NIP</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>

                </thead>

                <tbody>

                    <?php

                    $no = 1;

                    while ($data = mysqli_fetch_assoc($query)) {

                    ?>

                    <tr>

                        <td><?= $no++; ?></td>

                        <td>
                            <?= $data['nama_pelatih']; ?>
                        </td>

                        <td>
                            <?= $data['nip']; ?>
                        </td>

                        <td>

                            <?php if ($data['status'] == 'Aktif') { ?>

                                <span class="status aktif">
                                    Aktif
                                </span>

                            <?php } else { ?>

                                <span class="status nonaktif">
                                    Nonaktif
                                </span>

                            <?php } ?>

                        </td>

                        <td>

                            <!-- DETAIL -->
                            <a href="index.php?page=detail_pelatih&id=<?= $data['id']; ?>">

                                <button type="button" class="btn detail">
                                    Detail
                                </button>

                            </a>

                            <!-- HAPUS -->
                            <a href="index.php?page=hapus_pelatih&id=<?= $data['id']; ?>"
                               onclick="return confirm('Yakin ingin menghapus data ini?')">

                                <button type="button" class="btn hapus">
                                    Hapus
                                </button>

                            </a>

                        </td>

                    </tr>

                    <?php } ?>

                </tbody>

            </table>

        </div>

        <!-- BUTTON TAMBAH -->
        <div class="tambah-container">

            <button
                type="button"
                class="btn tambah"
                onclick="openModal()"
            >
                + Tambah Pelatih
            </button>

        </div>

    </div>

</div>

<!-- ===================================== -->
<!-- MODAL TAMBAH PELATIH -->
<!-- ===================================== -->

<div id="modalPelatih" class="modal">

    <div class="modal-content">

        <!-- CLOSE -->
        <span class="close" onclick="closeModal()">
            &times;
        </span>

        <h2>Tambah Pelatih</h2>

        <!-- FORM -->
        <form action="index.php?page=simpan_pelatih" method="POST">

            <!-- NAMA -->
            <div class="form-group">

                <label>Nama Pelatih</label>

                <input
                    type="text"
                    name="nama_pelatih"
                    required
                >

            </div>

            <!-- NIP -->
            <div class="form-group">

                <label>NIP</label>

                <input
                    type="text"
                    name="nip"
                    required
                >

            </div>

            <!-- PASSWORD -->
            <div class="form-group">

                <label>Password</label>

                <input
                    type="password"
                    name="password"
                    required
                >

            </div>

            <!-- STATUS -->
            <div class="form-group">

                <label>Status</label>

                <select name="status" required>

                    <option value="Aktif">
                        Aktif
                    </option>

                    <option value="Nonaktif">
                        Nonaktif
                    </option>

                </select>

            </div>

            <!-- BUTTON -->
            <button type="submit" class="btn-simpan">
                Simpan Data
            </button>

        </form>

    </div>

</div>

<script src="/js/script.js"></script>

<!-- ===================================== -->
<!-- JAVASCRIPT MODAL -->
<!-- ===================================== -->

<script>

/* OPEN MODAL */
function openModal() {

    document
        .getElementById("modalPelatih")
        .classList.add("show");
}

/* CLOSE MODAL */
function closeModal() {

    document
        .getElementById("modalPelatih")
        .classList.remove("show");
}

/* CLOSE SAAT KLIK LUAR */
window.onclick = function(event) {

    let modal = document.getElementById(
        "modalPelatih"
    );

    if (event.target === modal) {

        modal.classList.remove("show");
    }
}

</script>
</body>
</html>