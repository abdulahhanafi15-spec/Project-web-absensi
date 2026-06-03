<?php

/* KONEKSI */
$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "cahaya_cakra"
);

/* AMBIL ID */
$id_sekolah = (int)$_GET['id'];

/* DATA SEKOLAH */
$querySekolah = mysqli_query(

    $conn,

    "SELECT *
    FROM sekolah
    WHERE id_sekolah = '$id_sekolah'"
);

$sekolah = mysqli_fetch_assoc(
    $querySekolah
);

/* DATA MURID */
$queryMurid = mysqli_query(

    $conn,

    "SELECT *
    FROM murid
    WHERE id_sekolah = '$id_sekolah'
    ORDER BY nama_murid ASC"
);

$totalMurid = mysqli_num_rows(
    $queryMurid
);

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

    <!-- ===================================== -->
    <!-- DETAIL SEKOLAH -->
    <!-- ===================================== -->

    <div class="content-card" style="margin-bottom: 20px;">

        <div class="card-header">

            <h1>Detail Sekolah</h1>

        </div>

        <div class="detail-container">

            <!-- NAMA SEKOLAH -->
            <div class="detail-item">

                <label>Nama Sekolah</label>

                <input
                    type="text"
                    value="<?= $sekolah['nama_sekolah']; ?>"
                    readonly
                >

            </div>

            <!-- ALAMAT -->
            <div class="detail-item">

                <label>Alamat</label>

                <textarea readonly><?= $sekolah['alamat']; ?></textarea>

            </div>

            <!-- TELEPON -->
            <div class="detail-item">

                <label>Telepon</label>

                <input
                    type="text"
                    value="<?= $sekolah['telepon']; ?>"
                    readonly
                >

            </div>

            <!-- EMAIL -->
            <div class="detail-item">

                <label>Email</label>

                <input
                    type="text"
                    value="<?= $sekolah['email']; ?>"
                    readonly
                >

            </div>

            <!-- JUMLAH MURID -->
            <div class="detail-item">

                <label>Jumlah Murid</label>

                <input
                    type="text"
                    value="<?= $totalMurid; ?> Murid"
                    readonly
                >

            </div>

            <div class="detail-button">

                <button
                    type="button"
                    class="btn tambah"
                    onclick="openModalEditSekolah()"
                >
                    Edit Data Sekolah
                </button>

            </div>
        </div>

    </div>

    <!-- ===================================== -->
    <!-- DATA MURID -->
    <!-- ===================================== -->

    <div class="content-card">

        <div class="card-header">

            <h1>Daftar Murid</h1>

        </div>

        

        <!-- TABLE -->
        <div class="table-container">

            <table class="karyawan-table">

                <thead>

                    <tr>

                        <th>No</th>
                        <th>Nama Murid</th>
                        <th>Jenis Kelamin</th>
                        <th>Kelas</th>
                        <th>Aksi</th>

                    </tr>

                </thead>

                <tbody>

                    <?php

                    $no = 1;

                    while ($murid = mysqli_fetch_assoc($queryMurid)) {

                    ?>

                    <tr>

                        <td>
                            <?= $no++; ?>
                        </td>

                        <td>
                            <?= $murid['nama_murid']; ?>
                        </td>

                        <td>
                        <?= ($murid['jenis_kelamin'] == 'L')
                            ? 'Laki-laki'
                            : 'Perempuan'; ?>
                        </td>

                        <td>
                            <?= $murid['kelas']; ?>
                        </td>

                        <td>

                        <a
                            href="index.php?page=hapus_murid&id=<?= $murid['id_murid']; ?>&id_sekolah=<?= $id_sekolah; ?>"
                            onclick="return confirm('Yakin ingin menghapus murid ini?')"
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

                    <?php if ($totalMurid == 0) { ?>

                    <tr>

                        <td
                            colspan="5"
                            style="
                                text-align:center;
                                padding:20px;
                            "
                        >
                            Belum ada murid terdaftar.
                        </td>

                    </tr>

                    <?php } ?>

                </tbody>

            </table>

        </div>

        <!-- BUTTON KEMBALI -->
        <div class="detail-button">

            <a href="index.php?page=sekolah_admin">

                <button
                    type="button"
                    class="btn tambah"
                >
                    Kembali
                </button>

            </a>
        
            <!-- BUTTON TAMBAH -->

                <button
                    type="button"
                    class="btn tambah"
                    onclick="openModalMurid()">
                    + Tambah Murid
                </button>

            </div>
        </div>

    </div>

</div>

<!-- ===================================== -->
<!-- MODAL EDIT SEKOLAH -->
<!-- ===================================== -->

<div id="modalEditSekolah" class="modal">

    <div class="modal-content">

        <span
            class="close"
            onclick="closeModalEditSekolah()"
        >
            &times;
        </span>

        <h2>Edit Sekolah</h2>

        <form
            action="index.php?page=update_sekolah"
            method="POST"
        >

            <input
                type="hidden"
                name="id_sekolah"
                value="<?= $sekolah['id_sekolah']; ?>"
            >

            <!-- NAMA SEKOLAH -->
            <div class="form-group">

                <label>Nama Sekolah</label>

                <input
                    type="text"
                    name="nama_sekolah"
                    value="<?= $sekolah['nama_sekolah']; ?>"
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
                ><?= $sekolah['alamat']; ?></textarea>

            </div>

            <!-- TELEPON -->
            <div class="form-group">

                <label>Telepon</label>

                <input
                    type="text"
                    name="telepon"
                    value="<?= $sekolah['telepon']; ?>"
                    required
                >

            </div>

            <!-- EMAIL -->
            <div class="form-group">

                <label>Email</label>

                <input
                    type="email"
                    name="email"
                    value="<?= $sekolah['email']; ?>"
                    required
                >

            </div>

            <button
                type="submit"
                class="btn-simpan"
            >
                Simpan Perubahan
            </button>

        </form>

    </div>

</div>

<!-- ===================================== -->
<!-- MODAL TAMBAH MURID -->
<!-- ===================================== -->

<div id="modalMurid" class="modal">

    <div class="modal-content">

        <span
            class="close"
            onclick="closeModalMurid()"
        >
            &times;
        </span>

        <h2>Tambah Murid</h2>

        <form
            action="index.php?page=simpan_murid"
            method="POST"
        >

            <!-- ID SEKOLAH -->
            <input
                type="hidden"
                name="id_sekolah"
                value="<?= $id_sekolah; ?>"
            >

            <!-- NAMA -->
            <div class="form-group">

                <label>Nama Murid</label>

                <input
                    type="text"
                    name="nama_murid"
                    required
                >

            </div>

            <!-- JENIS KELAMIN -->
            <div class="form-group">

                <label>Jenis Kelamin</label>

                <select
                    name="jenis_kelamin"
                    required
                >

                    <option value="">
                        Pilih Jenis Kelamin
                    </option>

                    <option value="L">
                    Laki-laki
                    </option>

                    <option value="P">
                    Perempuan
                    </option>

                </select>

            </div>

            <!-- KELAS -->
            <div class="form-group">

                <label>Kelas</label>

                <input
                    type="text"
                    name="kelas"
                    required
                >

            </div>

            <!-- BUTTON -->
            <button
                type="submit"
                class="btn-simpan"
            >
                Simpan Murid
            </button>

        </form>

    </div>

</div>

<script>

/* ========================= */
/* MODAL MURID */
/* ========================= */

function openModalMurid() {

    document
        .getElementById("modalMurid")
        .classList.add("show");
}

function closeModalMurid() {

    document
        .getElementById("modalMurid")
        .classList.remove("show");
}

/* ========================= */
/* MODAL EDIT SEKOLAH */
/* ========================= */

function openModalEditSekolah() {

    document
        .getElementById("modalEditSekolah")
        .classList.add("show");
}

function closeModalEditSekolah() {

    document
        .getElementById("modalEditSekolah")
        .classList.remove("show");
}

/* KLIK LUAR MODAL */

window.onclick = function(event) {

    let modalMurid =
        document.getElementById(
            "modalMurid"
        );

    let modalSekolah =
        document.getElementById(
            "modalEditSekolah"
        );

    if (event.target == modalMurid) {

        closeModalMurid();
    }

    if (event.target == modalSekolah) {

        closeModalEditSekolah();
    }
}
</script>