<?php

$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "cahaya_cakra"
);

/* AMBIL DATA FORM */
$nama     = $_POST['nama_pelatih'];
$nip      = $_POST['nip'];
$alamat   = $_POST['alamat'];
$no_wa    = $_POST['no_wa'];
$password = md5($_POST['password']);
$status   = $_POST['status'];
$role     = $_POST['role']; // admin atau user

/* CEK NIP SUDAH ADA ATAU BELUM */
$cek = mysqli_query(
    $conn,
    "SELECT id FROM users WHERE username='$nip'"
);

if (mysqli_num_rows($cek) > 0) {

    echo "
        <script>
            alert('NIP sudah digunakan!');
            window.location='index.php?page=pelatih_admin';
        </script>
    ";
    exit;
}

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
        '$role'
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
        alamat,
        no_wa,
        status
    )

    VALUES
    (
        '$user_id',
        '$nama',
        '$nip',
        '$alamat',
        '$no_wa',
        '$status'
    )"
);

/* REDIRECT */
header(
    "Location: index.php?page=pelatih_admin"
);
exit;