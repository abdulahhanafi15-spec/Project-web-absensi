
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../../public/login_cahaya.php");
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
    "SELECT id_sekolah, nama_sekolah
     FROM sekolah
     ORDER BY nama_sekolah ASC"
);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Invoice</title>

    <link rel="stylesheet" href="/css/style.css">

</head>

<body>

<!-- =====================================
     SIDEBAR
===================================== -->

<div class="sidebar" id="sidebar">

    <button class="toggle-btn" onclick="toggleMenu()">
        ☰
    </button>

    <ul>

        <li>
            <a href="login_cahaya.php?page=admin_dashboard">
                <span>🏠</span>
                <span class="text">Dashboard</span>
            </a>
        </li>

        <li>
            <a href="login_cahaya.php?page=struktur">
                <span>👥</span>
                <span class="text">Struktur</span>
            </a>
        </li>

        <li>
            <a href="login_cahaya.php?page=pelatih_admin">
                <span>📋</span>
                <span class="text">Karyawan</span>
            </a>
        </li>

        <li>
            <a href="login_cahaya.php?page=sekolah_admin">
                <span>🏫</span>
                <span class="text">Sekolah</span>
            </a>
        </li>

        <li>
            <a href="login_cahaya.php?page=absensi">
                <span>📅</span>
                <span class="text">Absensi</span>
            </a>
        </li>

        <li>
            <a href="login_cahaya.php?page=laporan">
                <span>📊</span>
                <span class="text">Laporan</span>
            </a>
        </li>

        <li>
            <a href="login_cahaya.php?page=invoice">
                <span>💰</span>
                <span class="text">Invoice</span>
            </a>
        </li>

        <li>
            <a href="login_cahaya.php?page=setting_admin">
                <span>⚙️</span>
                <span class="text">Pengaturan</span>
            </a>
        </li>

        <li>
            <a href="login_cahaya.php?page=penilaian">
                <span>⭐</span>
                <span class="text">Penilaian</span>
            </a>
        </li>

        <li>
            <a href="login_cahaya.php?page=logout">
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

        <img src="img/Cahaya.png" alt="Logo Cahaya">

        <img src="img/Perpani.png" alt="Logo Perpani">

    </div>

    <!-- CONTENT -->

    <div class="content-card">

        <div class="card-header">

            <h1>
                Pendataan Invoice
            </h1>

        </div>

        <p style="margin-bottom:25px;">

            Kelola data tagihan dari beberapa sekolah
            dan cetak invoice berdasarkan periode pembayaran.

        </p>

        <!-- =====================================
             FORM INVOICE
        ===================================== -->

        <form action="login_cahaya.php?page=invoice_simpan" method="POST">

            <div class="form-group">

                <label>
                    Nomor Invoice
                </label>

                <input
                    type="text"
                    value="Otomatis saat disimpan"
                    readonly
                >

            </div>

            <br>

            <div class="form-group">

                <label>
                    Sekolah
                </label>

                <select name="id_sekolah" required>

                    <option value="">
                        -- Pilih Sekolah --
                    </option>

                    <?php while($sekolah = mysqli_fetch_assoc($querySekolah)) : ?>

                        <option value="<?= $sekolah['id_sekolah']; ?>">

                            <?= htmlspecialchars($sekolah['nama_sekolah']); ?>

                        </option>

                    <?php endwhile; ?>

                </select>

            </div>

            <br>

            <div class="form-group">

                <label>
                    Periode Tagihan
                </label>

                <input
                    type="month"
                    name="periode"
                    value="<?= date('Y-m'); ?>"
                    required
                >

            </div>

            <br>

            <div class="form-group">

                <label>
                    Tanggal Invoice
                </label>

                <input
                    type="date"
                    name="tanggal_invoice"
                    value="<?= date('Y-m-d'); ?>"
                    required
                >

            </div>

            <br>

            <div class="form-group">

                <label>
                    Keterangan Tagihan
                </label>

                <input
                    type="text"
                    name="keterangan"
                    placeholder="Contoh: Biaya kegiatan ekstrakurikuler"
                    required
                >

            </div>

            <br>

            <div class="form-group">

                <label>
                    Jumlah Tagihan (Rp)
                </label>

                <input
                    type="number"
                    name="jumlah_tagihan"
                    min="0"
                    placeholder="Masukkan nominal tagihan"
                    required
                >

            </div>

            <br>

            <div class="form-group">

                <label>
                    Status Pembayaran
                </label>

                <select name="status_pembayaran" required>

                    <option value="Belum Lunas">
                        Belum Lunas
                    </option>

                    <option value="Lunas">
                        Lunas
                    </option>

                </select>

            </div>

            <br><br>

            <div class="tambah-container">

                <button
                    type="submit"
                    class="btn tambah"
                >

                    💾 Simpan Invoice

                </button>

                <a
                    href="login_cahaya.php?page=invoice_data"
                    class="btn tambah"
                    style="text-decoration:none;"
                >
                    📋 Lihat Data Invoice
                </a>

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
                    Nomor invoice dibuat otomatis saat data disimpan.
                </li>

                <li>
                    Penomoran dimulai dari 0001 dan terus bertambah.
                </li>

                <li>
                    Nomor invoice berlaku untuk seluruh sekolah.
                </li>

                <li>
                    Pilih sekolah yang akan diberikan tagihan.
                </li>

                <li>
                    Tentukan periode dan tanggal invoice.
                </li>

                <li>
                    Masukkan keterangan dan nominal tagihan.
                </li>

                <li>
                    Tentukan status pembayaran.
                </li>

                <li>
                    Invoice yang tersimpan dapat dilihat melalui halaman data invoice.
                </li>

            </ul>

        </div>

    </div>

</div>

<!-- =====================================
     JAVASCRIPT
===================================== -->

<script src="/js/script.js"></script>

</body>

</html>
