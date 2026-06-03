<?php

if (!isset($_SESSION)) {
    session_start();
}

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

/* VALIDASI KONEKSI */
if (!$conn) {
    die("Koneksi gagal : " . mysqli_connect_error());
}

/* ===================================== */
/* SEARCH DATA SEKOLAH */
/* ===================================== */

$cari = "";

if (isset($_GET['cari'])) {

    $cari = mysqli_real_escape_string(
        $conn,
        $_GET['cari']
    );

    $query = mysqli_query(
        $conn,

        "SELECT
            sekolah.*,
            COUNT(murid.id_murid) AS jumlah_murid
        FROM sekolah
        LEFT JOIN murid
            ON sekolah.id_sekolah = murid.id_sekolah
        WHERE sekolah.nama_sekolah LIKE '%$cari%'
        GROUP BY sekolah.id_sekolah
        ORDER BY sekolah.id_sekolah DESC"
    );

} else {

    $query = mysqli_query(
        $conn,

        "SELECT
            sekolah.*,
            COUNT(murid.id_murid) AS jumlah_murid
        FROM sekolah
        LEFT JOIN murid
            ON sekolah.id_sekolah = murid.id_sekolah
        GROUP BY sekolah.id_sekolah
        ORDER BY sekolah.id_sekolah DESC"
    );
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pelatih</title>

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

    <!-- CONTENT -->
    <div class="content-card">

    <!-- JUDUL -->
    <div class="card-header">
        <h1>Data Sekolah</h1>
    </div>

    <!-- SEARCH -->
    <div class="search-container">

        <input
            type="text"
            id="searchInput"
            placeholder="Cari nama sekolah..."
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
                    <th>Nama Sekolah</th>
                    <th>Telepon</th>
                    <th>Email</th>
                    <th>Jumlah Murid</th>
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

                    <td>
                        <?= $data['nama_sekolah']; ?>
                    </td>

                    <td>
                        <?= $data['telepon']; ?>
                    </td>

                    <td>
                        <?= $data['email']; ?>
                    </td>

                    <td>
                        <?= $data['jumlah_murid']; ?>
                    </td>

                    <td>

                        <!-- DETAIL -->
                        <a href="index.php?page=detail_sekolah&id=<?= $data['id_sekolah']; ?>">

                            <button
                                type="button"
                                class="btn detail"
                            >
                                Detail
                            </button>

                        </a>

                        <!-- HAPUS -->
                        <a
                            href="index.php?page=hapus_sekolah&id=<?= $data['id_sekolah']; ?>"
                            onclick="return confirm('Yakin ingin menghapus sekolah ini?')"
                        >

                            <button
                                type="button"
                                class="btn hapus"
                            >
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
            + Tambah Sekolah
        </button>

    </div>

</div>

</div>

<!-- ===================================== -->
<!-- MODAL TAMBAH SEKOLAH -->
<!-- ===================================== -->

<div id="modalSekolah" class="modal">

    <div class="modal-content">

        <!-- CLOSE -->
        <span class="close" onclick="closeModal()">
            &times;
        </span>

        <h2>Tambah Sekolah</h2>

        <!-- FORM -->
        <form action="index.php?page=simpan_sekolah" method="POST">

            <!-- NAMA -->
            <div class="form-group">

                <label>Nama Sekolah</label>

                <input
                    type="text"
                    name="nama_sekolah"
                    required
                >

            </div>

             <!-- ALAMAT -->
    <div class="form-group">

        <label>Alamat</label>

        <textarea
            name="alamat"
            rows="4"
            required
        ></textarea>

    </div>

    <!-- NO WA -->
    <div class="form-group">

        <label>No WhatsApp / Telepon</label>

        <input
            type="text"
            name="no_wa"
            required
        >

    </div>

            <!-- EMAIL -->
            <div class="form-group">

                <label>Email</label>

                <input
                    type="email"
                    name="email"
                    required
                >

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
        .getElementById("modalSekolah")
        .classList.add("show");
}

/* CLOSE MODAL */
function closeModal() {

    document
        .getElementById("modalSekolah")
        .classList.remove("show");
}

/* CLOSE SAAT KLIK LUAR */
window.onclick = function(event) {

    let modal = document.getElementById(
        "modalSekolah"
    );

    if (event.target === modal) {

        modal.classList.remove("show");
    }
}

/* ========================================= */
/* REALTIME SEARCH */
/* ========================================= */

const searchInput = document.getElementById(
    "searchInput"
);

const tableRows = document.querySelectorAll(
    "#tableData tr"
);

/* SEARCH OTOMATIS */
searchInput.addEventListener("input", function () {

    let keyword = this.value.toLowerCase();

    tableRows.forEach(row => {

        let text = row.innerText.toLowerCase();

        if (text.includes(keyword)) {

            row.style.display = "";

        } else {

            row.style.display = "none";
        }
    });
});


</script>
</body>
</html>