<?php

$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "cahaya_cakra"
);

$nama = $_POST['nama_pelatih'];
$nip = $_POST['nip'];
$password = md5($_POST['password']);
$status = $_POST['status'];

/* ===================================== */
/* SIMPAN KE TABEL USERS */
/* ===================================== */

mysqli_query(

    $conn,

    "INSERT INTO users
    (
        username,
        password,
        role
    )

    VALUES
    (
        '$nip',
        '$password',
        'user'
    )"
);

/* AMBIL ID USER */
$user_id = mysqli_insert_id($conn);

/* ===================================== */
/* SIMPAN KE TABEL PELATIH */
/* ===================================== */

mysqli_query(

    $conn,

    "INSERT INTO pelatih
    (
        user_id,
        nama_pelatih,
        nip,
        status
    )

    VALUES
    (
        '$user_id',
        '$nama',
        '$nip',
        '$status'
    )"
);

/* REDIRECT */
header(
    "Location: index.php?page=pelatih_admin"
);

?>