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

/* ==========================================
   DATA DARI FORM
========================================== */

$id_murid = $_POST['id_murid'];

$p1 = $_POST['p1'];
$p2 = $_POST['p2'];
$p3 = $_POST['p3'];
$p4 = $_POST['p4'];

$bulanDipilih = $_POST['bulan']; // contoh : 2026-07
$id_sekolah = (int)$_POST['id_sekolah'];
$tanggal = $bulanDipilih . "-01";

$bulan = (int)date("n", strtotime($tanggal));
$tahun = date("Y", strtotime($tanggal));

/* ==========================================
   SIMPAN DATA
========================================== */

for ($i = 0; $i < count($id_murid); $i++) {

    $id = (int)$id_murid[$i];

    $nilaiP1 = (int)$p1[$i];
    $nilaiP2 = (int)$p2[$i];
    $nilaiP3 = (int)$p3[$i];
    $nilaiP4 = (int)$p4[$i];

    $rata = $nilaiP1 + $nilaiP2 + $nilaiP3 + $nilaiP4;

    /*
    ==========================================
    CEK DATA BULAN TERSEBUT
    ==========================================
    */

        $cek = mysqli_query($conn, "

            SELECT id_absensi

            FROM absensi

            WHERE id_murid='$id'

            AND bulan='$bulan'

            AND tahun='$tahun'

            LIMIT 1

        ");

    if (mysqli_num_rows($cek) > 0) {

        /*
        ==========================================
        UPDATE
        ==========================================
        */

        $data = mysqli_fetch_assoc($cek);

        mysqli_query($conn, "

        UPDATE absensi SET

            tanggal='$tanggal',
            bulan='$bulan',
            tahun='$tahun',
            p1='$nilaiP1',
            p2='$nilaiP2',
            p3='$nilaiP3',
            p4='$nilaiP4',
            rata_rata='$rata'

        WHERE id_absensi='".$data['id_absensi']."'

        ");

    } else {

        /*
        ==========================================
        INSERT
        ==========================================
        */

        mysqli_query($conn, "

        INSERT INTO absensi
        (

            id_murid,
            tanggal,
            bulan,
            tahun,
            p1,
            p2,
            p3,
            p4,
            rata_rata

        )

        VALUES
        (

            '$id',
            '$tanggal',
            '$bulan',
            '$tahun',
            '$nilaiP1',
            '$nilaiP2',
            '$nilaiP3',
            '$nilaiP4',
            '$rata'

        )

        ");

    }

}

echo "

<script>

alert('Absensi berhasil disimpan.');

window.location='index.php?page=detail_absensi&id=".$id_sekolah."&bulan=".$bulanDipilih."';

</script>

";