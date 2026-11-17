
<?php

/* =====================================
   SESSION
===================================== */

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
    die("Koneksi database gagal: " . mysqli_connect_error());
}

/* =====================================
   CEK REQUEST
===================================== */

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login_cahaya.php?page=invoice");
    exit;
}

/* =====================================
   AMBIL DATA FORM
===================================== */

$id_sekolah = (int) ($_POST['id_sekolah'] ?? 0);
$periode = trim($_POST['periode'] ?? '');
$tanggal_invoice = trim($_POST['tanggal_invoice'] ?? '');
$keterangan = trim($_POST['keterangan'] ?? '');
$jumlah_tagihan = $_POST['jumlah_tagihan'] ?? '';
$status_pembayaran = trim($_POST['status_pembayaran'] ?? '');

/* =====================================
   VALIDASI
===================================== */

if (
    $id_sekolah <= 0 ||
    !preg_match('/^\d{4}-\d{2}$/', $periode) ||
    $tanggal_invoice == '' ||
    $keterangan == '' ||
    !is_numeric($jumlah_tagihan) ||
    (float)$jumlah_tagihan < 0 ||
    !in_array($status_pembayaran, ['Lunas', 'Belum Lunas'])
) {
    die("Data invoice tidak valid.");
}

/* Validasi tanggal */

$tanggal = DateTime::createFromFormat('Y-m-d', $tanggal_invoice);

if (!$tanggal || $tanggal->format('Y-m-d') !== $tanggal_invoice) {
    die("Tanggal invoice tidak valid.");
}

/* =====================================
   MULAI TRANSAKSI
===================================== */

mysqli_begin_transaction($conn);

try {

    /* =====================================
       VALIDASI SEKOLAH
    ===================================== */

    $stmtSekolah = mysqli_prepare(
        $conn,
        "SELECT id_sekolah FROM sekolah WHERE id_sekolah = ?"
    );

    mysqli_stmt_bind_param($stmtSekolah, "i", $id_sekolah);
    mysqli_stmt_execute($stmtSekolah);
    mysqli_stmt_store_result($stmtSekolah);

    if (mysqli_stmt_num_rows($stmtSekolah) == 0) {
        throw new Exception("Sekolah tidak ditemukan.");
    }

    mysqli_stmt_close($stmtSekolah);

    /* =====================================
       TENTUKAN PERIODE NOMOR
       BERDASARKAN TANGGAL INVOICE
    ===================================== */

    $periodeNomor = date('Ym', strtotime($tanggal_invoice));

    /* =====================================
       KUNCI COUNTER
    ===================================== */

    $resultCounter = mysqli_query(
        $conn,
        "SELECT periode, last_number
         FROM invoice_counter
         WHERE id = 1
         FOR UPDATE"
    );

    if (!$resultCounter || mysqli_num_rows($resultCounter) == 0) {
        throw new Exception("Tabel invoice_counter belum disiapkan.");
    }

    $counter = mysqli_fetch_assoc($resultCounter);

    /* =====================================
       CEK PERGANTIAN BULAN
    ===================================== */

    if ($counter['periode'] != $periodeNomor) {

        $nomorUrut = 1;

        $stmtReset = mysqli_prepare(
            $conn,
            "UPDATE invoice_counter
             SET periode = ?, last_number = 1
             WHERE id = 1"
        );

        mysqli_stmt_bind_param(
            $stmtReset,
            "s",
            $periodeNomor
        );

        if (!mysqli_stmt_execute($stmtReset)) {
            throw new Exception("Gagal mereset nomor invoice.");
        }

        mysqli_stmt_close($stmtReset);

    } else {

        $nomorUrut = (int)$counter['last_number'] + 1;

        $stmtUpdate = mysqli_prepare(
            $conn,
            "UPDATE invoice_counter
             SET last_number = ?
             WHERE id = 1"
        );

        mysqli_stmt_bind_param(
            $stmtUpdate,
            "i",
            $nomorUrut
        );

        if (!mysqli_stmt_execute($stmtUpdate)) {
            throw new Exception("Gagal memperbarui nomor invoice.");
        }

        mysqli_stmt_close($stmtUpdate);
    }

    /* =====================================
       BUAT NOMOR INVOICE
    ===================================== */

    $nomor_invoice = "INV/" .
                     $periodeNomor . "/" .
                     str_pad($nomorUrut, 4, '0', STR_PAD_LEFT);

    /* =====================================
       SIMPAN INVOICE
    ===================================== */

    $query = "
        INSERT INTO invoice (
            nomor_invoice,
            id_sekolah,
            periode,
            tanggal_invoice,
            keterangan,
            jumlah_tagihan,
            status_pembayaran
        ) VALUES (?, ?, ?, ?, ?, ?, ?)
    ";

    $stmt = mysqli_prepare($conn, $query);

    mysqli_stmt_bind_param(
        $stmt,
        "sisssds",
        $nomor_invoice,
        $id_sekolah,
        $periode,
        $tanggal_invoice,
        $keterangan,
        $jumlah_tagihan,
        $status_pembayaran
    );

    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception(
            "Gagal menyimpan invoice: " . mysqli_stmt_error($stmt)
        );
    }

    mysqli_stmt_close($stmt);

    /* =====================================
       SIMPAN TRANSAKSI
    ===================================== */

    mysqli_commit($conn);

    echo "<script>
        alert('Invoice berhasil disimpan! Nomor: $nomor_invoice');
        window.location.href = 'login_cahaya.php?page=invoice_data';
    </script>";

    exit;

} catch (Exception $e) {

    mysqli_rollback($conn);

    die("Terjadi kesalahan: " .
        htmlspecialchars($e->getMessage()));
}

?>
