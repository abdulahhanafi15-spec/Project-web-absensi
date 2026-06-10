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

$username = $_SESSION['username'];

$query = mysqli_query($conn,"
    SELECT p.*
    FROM pelatih p
    JOIN users u ON p.user_id = u.id
    WHERE u.username = '$username'
");

$data = mysqli_fetch_assoc($query);


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
    <div class="content-card" style="margin-bottom: 20px;">

        <div class="card-header">

            <h1>Pengaturan Akun</h1>

        </div>

        <?php if($data){ ?>

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

            <!-- NO HP -->
            <div class="detail-item">

                <label>No. WhatsApp</label>

                <input
                    type="text"
                    value="<?= $data['no_wa']; ?>"
                    readonly
                >

            </div>

            <div class="detail-button">

                <button
                    type="button"
                    class="btn tambah"
                    id="btnEditData"
                >
                    Edit Data
                </button>

                <button
                    type="button"
                    class="btn"
                    id="btnUbahPassword"
                >
                    Ubah Password
                </button>

            </div>

        </div>

        <?php } else { ?>

            <p>Data pelatih tidak ditemukan.</p>

        <?php } ?>

    </div>

</div>

<div id="modalEditData" class="modal">

    <div class="modal-content">

        <span class="close close-modal">
            &times;
        </span>

        <h2>Edit Data Pelatih</h2>

        <form action="index.php?page=edit_pelatih" method="POST">

            <div class="form-group">

                <label>Nama Pelatih</label>

                <input
                    type="text"
                    name="nama_pelatih"
                    value="<?= $data['nama_pelatih']; ?>"
                    required
                >

            </div>

            <div class="form-group">

                <label>NIP</label>

                <input
                    type="text"
                    name="nip"
                    value="<?= $data['nip']; ?>"
                    required
                >

            </div>

            <div class="form-group">

                <label>Alamat</label>

                <textarea
                    name="alamat"
                    rows="4"
                    required
                ><?= $data['alamat']; ?></textarea>

            </div>

            <div class="form-group">

                <label>No WhatsApp</label>

                <input
                    type="text"
                    name="no_wa"
                    value="<?= $data['no_wa']; ?>"
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

<!-- MODAL UBAH PASSWORD -->
<div id="modalPassword" class="modal">

    <div class="modal-content">

        <span class="close close-modal">
            &times;
        </span>

        <h2>Ubah Password</h2>

        <form action="index.php?page=ubah_password" method="POST">

            <div class="form-group">

                <label>Password Lama</label>

                <input
                    type="password"
                    name="password_lama"
                    required
                >

            </div>

            <div class="form-group">

                <label>Password Baru</label>

                <input
                    type="password"
                    name="password_baru"
                    required
                >

            </div>

            <div class="form-group">

                <label>Konfirmasi Password Baru</label>

                <input
                    type="password"
                    name="konfirmasi_password"
                    required
                >

            </div>

            <button
                type="submit"
                class="btn-simpan"
            >
                Simpan Password
            </button>

        </form>

    </div>

</div>

</div>

<script src="/js/script.js"></script>
<script src="/js/edit_data.js"></script>
</body>
</html>