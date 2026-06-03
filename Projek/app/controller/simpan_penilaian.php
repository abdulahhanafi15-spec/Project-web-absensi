<?php

if (!isset($_SESSION)) {
    session_start();
}

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

/* ===================================== */
/* AMBIL DATA FORM */
/* ===================================== */

$id_murid = $_POST['id_murid'];

$hafalan_jurus = $_POST['hafalan_jurus'];

$kelugesan_gerak = $_POST['kelugesan_gerak'];

/* ===================================== */
/* CEK DATA SUDAH ADA */
/* ===================================== */

$cek = mysqli_query(

    $conn,

    "SELECT *

    FROM penilaian_atlet

    WHERE id_murid='$id_murid'"

);

/* ===================================== */
/* UPDATE */
/* ===================================== */

if (mysqli_num_rows($cek) > 0) {

    mysqli_query(

        $conn,

        "UPDATE penilaian_atlet

        SET

        hafalan_jurus='$hafalan_jurus',

        kelugesan_gerak='$kelugesan_gerak'

        WHERE id_murid='$id_murid'"

    );

}

/* ===================================== */
/* INSERT */
/* ===================================== */

else {

    mysqli_query(

        $conn,

        "INSERT INTO penilaian_atlet (

            id_murid,

            hafalan_jurus,

            kelugesan_gerak

        )

        VALUES (

            '$id_murid',

            '$hafalan_jurus',

            '$kelugesan_gerak'

        )"

    );

}

/* ===================================== */
/* KEMBALI KE HALAMAN SEBELUMNYA */
/* ===================================== */

$id_sekolah = $_POST['id_sekolah'];

header(
    "Location: index.php?page=detail_penilaian&id=".$id_sekolah
);

exit;

exit;