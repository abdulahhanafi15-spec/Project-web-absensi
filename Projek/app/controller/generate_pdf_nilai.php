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

if(!$conn){
    die("Koneksi gagal");
}

/* ==========================
   ID SEKOLAH
========================== */

$id_sekolah = intval($_GET['id']);

$bulan = isset($_GET['bulan'])
    ? intval($_GET['bulan'])
    : date('n');

$tahun = isset($_GET['tahun'])
    ? intval($_GET['tahun'])
    : date('Y');

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

$sekolah = mysqli_fetch_assoc(mysqli_query($conn,"

SELECT *

FROM sekolah

WHERE id_sekolah='$id_sekolah'

"));

/* ==========================
   DATA PENILAIAN
========================== */

$query = mysqli_query($conn, "

SELECT

    murid.nama_murid,
    murid.kelas,

    penilaian_atlet.hafalan_jurus,
    penilaian_atlet.kelugesan_gerak,

    IFNULL(absensi.rata_rata,0) AS absensi

FROM murid

LEFT JOIN penilaian_atlet

ON murid.id_murid = penilaian_atlet.id_murid

AND penilaian_atlet.bulan='$bulan'

AND penilaian_atlet.tahun='$tahun'

LEFT JOIN absensi

ON murid.id_murid = absensi.id_murid

AND absensi.bulan='$bulan'

AND absensi.tahun='$tahun'

WHERE murid.id_sekolah='$id_sekolah'

ORDER BY murid.nama_murid ASC

");

/* ==========================
   PDF
========================== */

$pdf = new FPDF("P","mm","A4");

$pdf->AddPage();

/* ==========================
   LOGO
========================== */

$root = dirname(__DIR__,2);

$pdf->Image($root."/public/img/Cahaya.png",10,8,24);

$pdf->Image($root."/public/img/Perpani.png",176,8,24);
/* ==========================
   HEADER
========================== */

$pdf->SetFont("Arial","B",16);
$pdf->Cell(0,8,"YAYASAN CAHAYA CAKRA",0,1,"C");

$pdf->SetFont("Arial","B",13);
$pdf->Cell(0,7,"PERSATUAN PANAHAN INDONESIA",0,1,"C");

$pdf->SetFont("Arial","",11);
$pdf->Cell(0,6,"LAPORAN PENILAIAN ATLET",0,1,"C");

$pdf->Ln(2);

$pdf->Line(10,35,200,35);

$pdf->Ln(5);

/* ==========================
   IDENTITAS SEKOLAH
========================== */

$pdf->SetFont("Arial","",11);

$pdf->Cell(35,7,"Nama Sekolah");
$pdf->Cell(5,7,":");
$pdf->Cell(120,7,$sekolah['nama_sekolah']);
$pdf->Ln();

$pdf->Cell(35,7,"Alamat");
$pdf->Cell(5,7,":");
$pdf->MultiCell(145,7,$sekolah['alamat']);

$pdf->Cell(35,7,"Telepon");
$pdf->Cell(5,7,":");
$pdf->Cell(120,7,$sekolah['telepon']);
$pdf->Ln();

$pdf->Cell(35,7,"Periode");
$pdf->Cell(5,7,":");
$pdf->Cell(120,7,$namaBulan[$bulan]." ".$tahun);
$pdf->Ln();

$pdf->Cell(35,7,"Tanggal Cetak");
$pdf->Cell(5,7,":");
$pdf->Cell(120,7,date("d-m-Y"));

$pdf->Ln(10);

/* ==========================
   HEADER TABEL
========================== */

$pdf->SetFont("Arial","B",10);
$pdf->SetFillColor(220,220,220);

$pdf->Cell(10,8,"No",1,0,"C",true);
$pdf->Cell(60,8,"Nama Murid",1,0,"C",true);
$pdf->Cell(20,8,"Kelas",1,0,"C",true);
$pdf->Cell(35,8,"Hafalan",1,0,"C",true);
$pdf->Cell(35,8,"Kelugesan",1,0,"C",true);
$pdf->Cell(30,8,"Absensi",1,1,"C",true);

/* ==========================
   DATA
========================== */

$pdf->SetFont("Arial","",10);

$no = 1;

while($row = mysqli_fetch_assoc($query)){

    $hafalan = $row['hafalan_jurus'] ?? "-";
    $kelugesan = $row['kelugesan_gerak'] ?? "-";
    $absensi = $row['absensi'] ?? "-";

    $pdf->Cell(10,8,$no++,1,0,"C");

    $pdf->Cell(60,8,$row['nama_murid'],1);

    $pdf->Cell(20,8,$row['kelas'],1,0,"C");

    $pdf->Cell(35,8,$hafalan,1,0,"C");

    $pdf->Cell(35,8,$kelugesan,1,0,"C");

    $pdf->Cell(30,8,$absensi,1,1,"C");

}

/* ==========================
   FOOTER
========================== */

$pdf->Ln(15);

$pdf->Cell(120);

$pdf->Cell(
    60,
    6,
    "Bogor, ".date("d F Y"),
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
    "Laporan_Nilai_".$sekolah['nama_sekolah'].".pdf"
);

?>