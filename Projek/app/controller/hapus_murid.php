<?php

$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "cahaya_cakra"
);

if (!$conn) {

    die(
        "Koneksi gagal : "
        . mysqli_connect_error()
    );
}

/* AMBIL DATA */
$id_murid   = $_GET['id'];
$id_sekolah = $_GET['id_sekolah'];

/* HAPUS */
$query = mysqli_query(

    $conn,

    "DELETE FROM murid
    WHERE id_murid = '$id_murid'"
);

/* REDIRECT */
if ($query) {

    header(
        "Location: index.php?page=detail_sekolah&id=$id_sekolah"
    );

    exit;

} else {

    echo "Gagal menghapus data : "
         . mysqli_error($conn);
}