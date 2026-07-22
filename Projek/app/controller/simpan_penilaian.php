<?php

$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "cahaya_cakra"
);

if (!$conn) {
    die("Koneksi gagal : " . mysqli_connect_error());
}


/* ======================================
   DATA DARI FORM
====================================== */

$id_murid         = intval($_POST['id_murid']);
$id_sekolah       = intval($_POST['id_sekolah']);

$hafalan_jurus    = intval($_POST['hafalan_jurus']);
$kelugesan_gerak  = intval($_POST['kelugesan_gerak']);

$bulan            = intval($_POST['bulan']);
$tahun            = intval($_POST['tahun']);


/* ======================================
   CEK DATA PENILAIAN
====================================== */

$cek = mysqli_query($conn, "

SELECT *

FROM penilaian_atlet

WHERE

id_murid='$id_murid'

AND bulan='$bulan'

AND tahun='$tahun'

");


/* ======================================
   UPDATE
====================================== */

if(mysqli_num_rows($cek) > 0){

    mysqli_query($conn, "

    UPDATE penilaian_atlet

    SET

    hafalan_jurus='$hafalan_jurus',

    kelugesan_gerak='$kelugesan_gerak'

    WHERE

    id_murid='$id_murid'

    AND bulan='$bulan'

    AND tahun='$tahun'

    ");

}


/* ======================================
   INSERT
====================================== */

else{

    mysqli_query($conn, "

    INSERT INTO penilaian_atlet

    (

        id_murid,

        bulan,

        tahun,

        hafalan_jurus,

        kelugesan_gerak

    )

    VALUES

    (

        '$id_murid',

        '$bulan',

        '$tahun',

        '$hafalan_jurus',

        '$kelugesan_gerak'

    )

    ");

}


/* ======================================
   KEMBALI
====================================== */

header("Location: index.php?page=detail_penilaian&id=".$id_sekolah."&bulan=".$bulan."&tahun=".$tahun);

exit;

?>