<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../public/vendor/fpdf/fpdf.php';

/* ==========================
   KONEKSI DATABASE
========================== */

$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "cahaya_cakra"
);

if (!$conn) {
    die("Koneksi Gagal : " . mysqli_connect_error());
}

/* ==========================
   PARAMETER
========================== */

$id_sekolah = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$bulan = isset($_GET['bulan']) ? (int)$_GET['bulan'] : date('n');

$tahun = isset($_GET['tahun']) ? (int)$_GET['tahun'] : date('Y');

/* ==========================
   NAMA BULAN
========================== */

$namaBulan = [
    1 => "Januari",
    2 => "Februari",
    3 => "Maret",
    4 => "April",
    5 => "Mei",
    6 => "Juni",
    7 => "Juli",
    8 => "Agustus",
    9 => "September",
    10 => "Oktober",
    11 => "November",
    12 => "Desember"
];

/* ==========================
   DATA SEKOLAH
========================== */

$sekolah = mysqli_fetch_assoc(

    mysqli_query(
        $conn,
        "SELECT * FROM sekolah
        WHERE id_sekolah='$id_sekolah'"
    )

);

if (!$sekolah) {
    die("Data sekolah tidak ditemukan.");
}

/* ==========================
   DATA ABSENSI
========================== */

$query = mysqli_query($conn, "

SELECT

    murid.nama_murid,
    murid.kelas,

    absensi.tanggal,
    absensi.bulan,
    absensi.tahun,
    absensi.p1,
    absensi.p2,
    absensi.p3,
    absensi.p4,
    absensi.rata_rata

FROM murid

INNER JOIN absensi
ON murid.id_murid = absensi.id_murid

WHERE

    murid.id_sekolah='$id_sekolah'

    AND absensi.bulan='$bulan'

    AND absensi.tahun='$tahun'

ORDER BY murid.nama_murid ASC

");

/* ==========================
   PDF
========================== */

$pdf = new FPDF("P", "mm", "A4");

$pdf->AddPage();

$root = dirname(__DIR__, 2);

/* ==========================
   LOGO
========================== */

$pdf->Image($root . "/public/img/Cahaya.png", 10, 8, 22);

$pdf->Image($root . "/public/img/Perpani.png", 178, 8, 20);

/* ==========================
   HEADER
========================== */

$pdf->SetFont("Arial", "B", 15);
$pdf->Cell(0, 7, "CAHAYA CAKRA ARCHERY CLUB", 0, 1, "C");

$pdf->SetFont("Arial", "B", 12);
$pdf->Cell(0, 6, "LAPORAN ABSENSI ATLET", 0, 1, "C");

$pdf->SetFont("Arial", "", 11);
$pdf->Cell(0, 6, "Tajurhalang, Tajur Halang, Bogor", 0, 1, "C");

$pdf->Line(10, 35, 200, 35);

$pdf->Ln(6);

/* ==========================
   IDENTITAS SEKOLAH
========================== */

$pdf->SetFont("Arial", "", 10);

$pdf->Cell(35, 7, "Nama Sekolah");
$pdf->Cell(5, 7, ":");
$pdf->Cell(120, 7, $sekolah['nama_sekolah']);
$pdf->Ln();

$pdf->Cell(35, 7, "Alamat");
$pdf->Cell(5, 7, ":");
$pdf->MultiCell(145, 7, $sekolah['alamat']);

$pdf->Cell(35, 7, "Telepon");
$pdf->Cell(5, 7, ":");
$pdf->Cell(120, 7, $sekolah['telepon']);
$pdf->Ln();

$pdf->Cell(35, 7, "Periode");
$pdf->Cell(5, 7, ":");
$pdf->Cell(120, 7, $namaBulan[$bulan] . " " . $tahun);
$pdf->Ln();

$pdf->Cell(35, 7, "Tanggal Cetak");
$pdf->Cell(5, 7, ":");
$pdf->Cell(120, 7, date("d-m-Y"));

$pdf->Ln(10);

/* ==========================
   HEADER TABEL
========================== */

$pdf->SetFillColor(220, 220, 220);

$pdf->SetFont("Arial", "B", 9);

$pdf->Cell(10, 8, "No", 1, 0, "C", true);
$pdf->Cell(55, 8, "Nama Murid", 1, 0, "C", true);
$pdf->Cell(18, 8, "Kelas", 1, 0, "C", true);
$pdf->Cell(15, 8, "P1", 1, 0, "C", true);
$pdf->Cell(15, 8, "P2", 1, 0, "C", true);
$pdf->Cell(15, 8, "P3", 1, 0, "C", true);
$pdf->Cell(15, 8, "P4", 1, 0, "C", true);
$pdf->Cell(25, 8, "Nilai", 1, 1, "C", true);

/* ==========================
   DATA
========================== */

$pdf->SetFont("Arial", "", 9);

$no = 1;

while ($row = mysqli_fetch_assoc($query)) {

    $p1 = ($row['p1'] == 25) ? "H" : "TH";
    $p2 = ($row['p2'] == 25) ? "H" : "TH";
    $p3 = ($row['p3'] == 25) ? "H" : "TH";
    $p4 = ($row['p4'] == 25) ? "H" : "TH";

    $pdf->Cell(10, 8, $no++, 1, 0, "C");
    $pdf->Cell(55, 8, $row['nama_murid'], 1);
    $pdf->Cell(18, 8, $row['kelas'], 1, 0, "C");
    $pdf->Cell(15, 8, $p1, 1, 0, "C");
    $pdf->Cell(15, 8, $p2, 1, 0, "C");
    $pdf->Cell(15, 8, $p3, 1, 0, "C");
    $pdf->Cell(15, 8, $p4, 1, 0, "C");
    $pdf->Cell(25, 8, $row['rata_rata'], 1, 1, "C");
}

/* ==========================
   JIKA DATA KOSONG
========================== */

if ($no == 1) {

    $pdf->Cell(
        168,
        10,
        "Tidak ada data absensi pada periode ini.",
        1,
        1,
        "C"
    );
}

/* ==========================
   TANDA TANGAN
========================== */

$pdf->Ln(15);

$pdf->Cell(120);

$pdf->Cell(
    60,
    6,
    "Bogor, " . date("d F Y"),
    0,
    1,
    "C"
);

$pdf->Cell(120);

$pdf->Cell(
    60,
    6,
    "Pelatih",
    0,
    1,
    "C"
);

$pdf->Ln(25);

$pdf->Cell(120);

$pdf->Cell(
    60,
    6,
    "(__________________)",
    0,
    1,
    "C"
);

/* ==========================
   OUTPUT
========================== */

$pdf->Output(
    "I",
    "Laporan_Absensi_" .
    $sekolah['nama_sekolah'] .
    "_" .
    $namaBulan[$bulan] .
    "_" .
    $tahun .
    ".pdf"
);

?>