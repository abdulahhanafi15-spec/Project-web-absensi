<?php

$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "cahaya_cakra"
);

if (!$conn) {

    die(
        "Koneksi gagal : " .
        mysqli_connect_error()
    );
}

/* DATA FORM */
$id_sekolah     = $_POST['id_sekolah'];
$nama_murid     = $_POST['nama_murid'];
$jenis_kelamin  = $_POST['jenis_kelamin'];
$kelas          = $_POST['kelas'];

/* SIMPAN */
$query = mysqli_query(

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

if ($query) {

    header(
        "Location: index.php?page=detail_sekolah&id=$id_sekolah"
    );

    exit;

} else {

    echo mysqli_error($conn);
}