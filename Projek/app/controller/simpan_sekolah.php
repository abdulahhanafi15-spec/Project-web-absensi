<?php

$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "cahaya_cakra"
);

/* DATA SEKOLAH */
$nama_sekolah = $_POST['nama_sekolah'];
$alamat       = $_POST['alamat'];
$telepon      = $_POST['telepon'];
$email        = $_POST['email'];

/* DATA MURID */
$nama_murid     = $_POST['nama_murid'];
$jenis_kelamin = $_POST['jenis_kelamin'];
$kelas          = $_POST['kelas'];

/* ==========================
   SIMPAN SEKOLAH
========================== */

$querySekolah = mysqli_query(
    $conn,
    "INSERT INTO sekolah
    (
        nama_sekolah,
        alamat,
        telepon,
        email
    )
    VALUES
    (
        '$nama_sekolah',
        '$alamat',
        '$telepon',
        '$email'
    )"
);

/* AMBIL ID SEKOLAH */
$id_sekolah = mysqli_insert_id($conn);

/* ==========================
   SIMPAN MURID
========================== */

$queryMurid = mysqli_query(
    $conn,
    "INSERT INTO murid
    (
        id_sekolah,
        nama_murid,
        jenis_kelamin,
        kelas
    )
    VALUES
    (
        '$id_sekolah',
        '$nama_murid',
        '$jenis_kelamin',
        '$kelas'
    )"
);

/* REDIRECT */
header("Location: index.php?page=sekolah");
exit;
?>