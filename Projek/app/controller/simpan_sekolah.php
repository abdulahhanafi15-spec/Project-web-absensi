<?php

session_start();

/* CEK LOGIN ADMIN */
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {

    header("Location: ../../public/index.php");
    exit;
}

/* KONEKSI */
$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "cahaya_cakra"
);

/* VALIDASI */
if (!$conn) {

    die(
        "Koneksi gagal : "
        . mysqli_connect_error()
    );
}

/* AMBIL DATA */
$nama_sekolah = mysqli_real_escape_string(
    $conn,
    $_POST['nama_sekolah']
);

$alamat = mysqli_real_escape_string(
    $conn,
    $_POST['alamat']
);

$telepon = mysqli_real_escape_string(
    $conn,
    $_POST['telepon']
);

$email = mysqli_real_escape_string(
    $conn,
    $_POST['email']
);

/* SIMPAN */
mysqli_query(

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

/* KEMBALI */
header(
    "Location: index.php?page=sekolah_admin"
);

exit;

?>