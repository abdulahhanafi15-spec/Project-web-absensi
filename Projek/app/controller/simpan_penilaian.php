<?php

if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {

    header("Location: ../../public/index.php");
    exit;
}

/* ===================================== */
/* KONEKSI DATABASE */
/* ===================================== */

$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "cahaya_cakra"
);

/* ===================================== */
/* VALIDASI KONEKSI */
/* ===================================== */

if (!$conn) {

    die(
        "Koneksi gagal : " .
        mysqli_connect_error()
    );
}

/* ===================================== */
/* AMBIL DATA FORM */
/* ===================================== */

$id_siswa      = $_POST['id_siswa'];

$kedisiplinan  = $_POST['kedisiplinan'];

$kerjasama     = $_POST['kerjasama'];

$keaktifan     = $_POST['keaktifan'];

/* ===================================== */
/* CEK APAKAH SUDAH ADA NILAI */
/* ===================================== */

$cek = mysqli_query(

    $conn,

    "SELECT * FROM penilaian
    WHERE id_siswa = '$id_siswa'"
);

if (mysqli_num_rows($cek) > 0) {

    /* ===================================== */
    /* UPDATE DATA */
    /* ===================================== */

    $update = mysqli_query(

        $conn,

        "UPDATE penilaian SET

            kedisiplinan = '$kedisiplinan',
            kerjasama    = '$kerjasama',
            keaktifan    = '$keaktifan'

        WHERE id_siswa = '$id_siswa'"
    );

} else {

    /* ===================================== */
    /* SIMPAN DATA BARU */
    /* ===================================== */

    $simpan = mysqli_query(

        $conn,

        "INSERT INTO penilaian (

            id_siswa,
            kedisiplinan,
            kerjasama,
            keaktifan

        ) VALUES (

            '$id_siswa',
            '$kedisiplinan',
            '$kerjasama',
            '$keaktifan'

        )"
    );
}

/* ===================================== */
/* REDIRECT */
/* ===================================== */

header(
    "Location: index.php?page=penilaian"
);

exit;

?>