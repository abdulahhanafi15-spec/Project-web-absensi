
<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* =====================================
   CEK ROLE ADMIN
===================================== */

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
    die("Koneksi gagal: " . mysqli_connect_error());
}

/* =====================================
   UPDATE STATUS PEMBAYARAN
===================================== */

if (
    $_SERVER['REQUEST_METHOD'] == 'POST' &&
    isset($_POST['update_status'])
) {

    $id_invoice = (int) ($_POST['id_invoice'] ?? 0);
    $status = $_POST['status_pembayaran'] ?? '';

    if (
        $id_invoice > 0 &&
        in_array($status, ['Lunas', 'Belum Lunas'])
    ) {

        $stmtUpdate = mysqli_prepare(
            $conn,
            "UPDATE invoice
             SET status_pembayaran = ?
             WHERE id_invoice = ?"
        );

        mysqli_stmt_bind_param(
            $stmtUpdate,
            "si",
            $status,
            $id_invoice
        );

        if (mysqli_stmt_execute($stmtUpdate)) {

            header("Location: login_cahaya.php?page=invoice_data");
            exit;

        } else {
            die("Gagal memperbarui status.");
        }

    } else {
        die("Data status tidak valid.");
    }
}

/* =====================================
   AMBIL DATA SEKOLAH
===================================== */

$dataSekolah = mysqli_query(
    $conn,
    "SELECT id_sekolah, nama_sekolah
     FROM sekolah
     ORDER BY nama_sekolah ASC"
);

/* =====================================
   AMBIL FILTER
===================================== */

$idSekolah = (int) ($_GET['sekolah'] ?? 0);
$bulan = trim($_GET['bulan'] ?? '');

/* Validasi format bulan YYYY-MM */

if ($bulan !== '' && !preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $bulan)) {
    $bulan = '';
}

/* =====================================
   QUERY DATA INVOICE
===================================== */

$sql = "
    SELECT
        invoice.*,
        sekolah.nama_sekolah

    FROM invoice

    INNER JOIN sekolah
    ON invoice.id_sekolah = sekolah.id_sekolah

    WHERE
        (? = 0 OR invoice.id_sekolah = ?)

        AND

        (? = '' OR DATE_FORMAT(invoice.tanggal_invoice, '%Y-%m') = ?)

    ORDER BY invoice.id_invoice DESC
";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "iiss",
    $idSekolah,
    $idSekolah,
    $bulan,
    $bulan
);

mysqli_stmt_execute($stmt);

$dataInvoice = mysqli_stmt_get_result($stmt);

/* =====================================
   FUNGSI FORMAT RUPIAH
===================================== */

function rupiah($angka) {
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

/* =====================================
   FUNGSI TAMPILKAN TABEL
===================================== */

function tampilkanTabelInvoice($dataInvoice) {

    $no = 1;

    if ($dataInvoice && mysqli_num_rows($dataInvoice) > 0):

        while ($row = mysqli_fetch_assoc($dataInvoice)):

?>

<tr>

    <td><?= $no++; ?></td>

    <td>
        <?= htmlspecialchars($row['nomor_invoice']); ?>
    </td>

    <td>
        <?= htmlspecialchars($row['nama_sekolah']); ?>
    </td>

    <td>
        <?= date('F Y', strtotime($row['periode'] . '-01')); ?>
    </td>

    <td>
        <?= date('d-m-Y', strtotime($row['tanggal_invoice'])); ?>
    </td>

    <td>
        <?= htmlspecialchars($row['keterangan']); ?>
    </td>

    <td>
        <?= rupiah($row['jumlah_tagihan']); ?>
    </td>

    <td>

        <form method="POST" action="login_cahaya.php?page=invoice_data">

            <input
                type="hidden"
                name="id_invoice"
                value="<?= $row['id_invoice']; ?>"
            >

            <select
                name="status_pembayaran"
                onchange="this.form.submit()"
                style="
                    padding:7px;
                    border-radius:5px;
                    cursor:pointer;
                    background:<?= $row['status_pembayaran'] == 'Lunas' ? '#d4edda' : '#fff3cd'; ?>;
                "
            >

                <option
                    value="Belum Lunas"
                    <?= $row['status_pembayaran'] == 'Belum Lunas' ? 'selected' : ''; ?>
                >
                    Belum Lunas
                </option>

                <option
                    value="Lunas"
                    <?= $row['status_pembayaran'] == 'Lunas' ? 'selected' : ''; ?>
                >
                    Lunas
                </option>

            </select>

            <input
                type="hidden"
                name="update_status"
                value="1"
            >

        </form>

    </td>

</tr>

<?php

        endwhile;

    else:

?>

<tr>
    <td colspan="8" style="text-align:center;">
        Tidak ada invoice yang sesuai dengan pencarian.
    </td>
</tr>

<?php

    endif;
}

/* =====================================
   JIKA REQUEST AJAX
===================================== */

if (isset($_GET['ajax']) && $_GET['ajax'] == '1') {

    tampilkanTabelInvoice($dataInvoice);
    exit;
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Data Invoice</title>

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

            <h1>Data Invoice</h1>

        </div>

        <p style="margin-bottom:25px;">
            Berikut merupakan daftar seluruh invoice
            dari sekolah yang telah terdaftar.
        </p>

        <!-- =====================================
             TOMBOL TAMBAH
        ===================================== -->

        <div class="tambah-container" style="margin-bottom:20px;">

            <a
                href="login_cahaya.php?page=invoice"
                class="btn tambah"
                style="text-decoration:none;"
            >
                + Tambah Invoice
            </a>

        </div>

        <!-- =====================================
             FILTER REALTIME
        ===================================== -->

        <div style="
            display:flex;
            gap:15px;
            flex-wrap:wrap;
            align-items:end;
            margin-bottom:20px;
        ">

            <!-- FILTER SEKOLAH -->

            <div class="form-group" style="flex:1; min-width:220px;">

                <label for="filterSekolah">
                    Pilih Sekolah
                </label>

                <select
                    id="filterSekolah"
                    style="
                        width:100%;
                        padding:10px;
                        border-radius:5px;
                    "
                >

                    <option value="">
                        Semua Sekolah
                    </option>

                    <?php while ($sekolah = mysqli_fetch_assoc($dataSekolah)): ?>

                        <option value="<?= $sekolah['id_sekolah']; ?>">

                            <?= htmlspecialchars($sekolah['nama_sekolah']); ?>

                        </option>

                    <?php endwhile; ?>

                </select>

            </div>

            <!-- FILTER BULAN -->

            <div class="form-group" style="flex:1; min-width:220px;">

                <label for="filterBulan">
                    Pilih Bulan Invoice
                </label>

                <input
                    type="month"
                    id="filterBulan"
                    style="
                        width:100%;
                        padding:10px;
                        border-radius:5px;
                    "
                >

            </div>

            <!-- RESET -->

            <div>

                <button
                    type="button"
                    class="btn tambah"
                    onclick="resetFilterInvoice()"
                >
                    Reset Filter
                </button>

            </div>

        </div>

        <p
            id="statusPencarian"
            style="margin-bottom:15px; color:#666;"
        >
            Menampilkan seluruh invoice.
        </p>

        <!-- =====================================
             TABEL DATA INVOICE
        ===================================== -->

        <div style="overflow-x:auto;">

            <table
                border="1"
                cellpadding="10"
                cellspacing="0"
                width="100%"
                style="border-collapse:collapse; min-width:1000px;"
            >

                <thead>

                    <tr style="background:#f5f5f5;">

                        <th>No</th>

                        <th>Nomor Invoice</th>

                        <th>Nama Sekolah</th>

                        <th>Periode</th>

                        <th>Tanggal Invoice</th>

                        <th>Keterangan</th>

                        <th>Jumlah Tagihan</th>

                        <th>Status Pembayaran</th>

                    </tr>

                </thead>

                <tbody id="tabelInvoice">

                    <?php tampilkanTabelInvoice($dataInvoice); ?>

                </tbody>

            </table>

        </div>

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
                    Semua invoice dari berbagai sekolah ditampilkan dalam satu tabel.
                </li>

                <li>
                    Gunakan dropdown sekolah untuk mencari invoice berdasarkan sekolah.
                </li>

                <li>
                    Gunakan filter bulan untuk menampilkan invoice berdasarkan bulan dan tahun.
                </li>

                <li>
                    Kedua filter dapat digunakan secara bersamaan.
                </li>

                <li>
                    Status pembayaran dapat diubah langsung melalui pilihan status.
                </li>

                <li>
                    Pencarian berjalan otomatis tanpa perlu menekan tombol Cari.
                </li>

            </ul>

        </div>

    </div>

</div>

<!-- =====================================
     JAVASCRIPT
===================================== -->

<script src="/js/script.js"></script>

<script>

/* =====================================
   PENCARIAN REALTIME INVOICE
===================================== */

const filterSekolah = document.getElementById('filterSekolah');
const filterBulan = document.getElementById('filterBulan');
const tabelInvoice = document.getElementById('tabelInvoice');
const statusPencarian = document.getElementById('statusPencarian');

let timerPencarian = null;

/* =====================================
   FUNGSI PENCARIAN
===================================== */

function cariInvoice() {

    clearTimeout(timerPencarian);

    timerPencarian = setTimeout(function() {

        const sekolah = filterSekolah.value;
        const bulan = filterBulan.value;

        statusPencarian.textContent = 'Memuat data invoice...';

        const params = new URLSearchParams();

        params.append('page', 'invoice_data');
        params.append('ajax', '1');
        params.append('sekolah', sekolah);
        params.append('bulan', bulan);

        fetch('login_cahaya.php?' + params.toString())

            .then(response => {

                if (!response.ok) {
                    throw new Error('Gagal mengambil data invoice.');
                }

                return response.text();

            })

            .then(data => {

                tabelInvoice.innerHTML = data;

                if (sekolah === '' && bulan === '') {

                    statusPencarian.textContent =
                        'Menampilkan seluruh invoice.';

                } else {

                    statusPencarian.textContent =
                        'Hasil pencarian berhasil diperbarui.';

                }

            })

            .catch(error => {

                statusPencarian.textContent =
                    'Terjadi kesalahan saat mencari invoice.';

                console.error(error);

            });

    }, 200);
}

/* =====================================
   EVENT FILTER SEKOLAH
===================================== */

filterSekolah.addEventListener('change', cariInvoice);

/* =====================================
   EVENT FILTER BULAN
===================================== */

filterBulan.addEventListener('change', cariInvoice);

/* =====================================
   RESET FILTER
===================================== */

function resetFilterInvoice() {

    filterSekolah.value = '';
    filterBulan.value = '';

    cariInvoice();
}

</script>

</body>
</html>
