<?php

$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "cahaya_cakra"
);

$id_sekolah  = $_POST['id_sekolah'];
$nama        = $_POST['nama_sekolah'];
$alamat      = $_POST['alamat'];
$telepon     = $_POST['telepon'];
$email       = $_POST['email'];

mysqli_query(

    $conn,

    "UPDATE sekolah SET

        nama_sekolah = '$nama',
        alamat       = '$alamat',
        telepon      = '$telepon',
        email        = '$email'

    WHERE id_sekolah = '$id_sekolah'"
);

header(
    "Location: index.php?page=detail_sekolah&id=$id_sekolah"
);

exit;