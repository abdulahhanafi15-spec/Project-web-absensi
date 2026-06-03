<?php

session_start();

/* CEK LOGIN */
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

/* AMBIL ID */
$id_sekolah = (int) $_GET['id'];

/* HAPUS DATA MURID TERLEBIH DAHULU */
mysqli_query(

    $conn,

    "DELETE FROM murid
    WHERE id_sekolah = '$id_sekolah'"
);

/* HAPUS DATA SEKOLAH */
mysqli_query(

    $conn,

    "DELETE FROM sekolah
    WHERE id_sekolah = '$id_sekolah'"
);

/* KEMBALI */
header(
    "Location: index.php?page=sekolah_admin"
);

exit;

?>