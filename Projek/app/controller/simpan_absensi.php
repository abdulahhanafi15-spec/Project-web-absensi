<?php

session_start();

$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "cahaya_cakra"
);

$id_murid = $_POST['id_murid'];
$tanggal  = $_POST['tanggal'];

$p1 = $_POST['p1'];
$p2 = $_POST['p2'];
$p3 = $_POST['p3'];
$p4 = $_POST['p4'];

for($i = 0; $i < count($id_murid); $i++){

    $nilai_absensi =
        $p1[$i] +
        $p2[$i] +
        $p3[$i] +
        $p4[$i];

    mysqli_query($conn,"
        INSERT INTO absensi
        (
            id_murid,
            tanggal,
            p1,
            p2,
            p3,
            p4,
            rata_rata
        )
        VALUES
        (
            '{$id_murid[$i]}',
            '{$tanggal[$i]}',
            '{$p1[$i]}',
            '{$p2[$i]}',
            '{$p3[$i]}',
            '{$p4[$i]}',
            '$nilai_absensi'
        )
    ");

    $cek = mysqli_query(
        $conn,
        "SELECT * FROM penilaian_atlet
         WHERE id_murid='{$id_murid[$i]}'"
    );

    if(mysqli_num_rows($cek) > 0){

        mysqli_query($conn,"
            UPDATE penilaian_atlet
            SET absensi='$nilai_absensi'
            WHERE id_murid='{$id_murid[$i]}'
        ");

    }else{

        mysqli_query($conn,"
            INSERT INTO penilaian_atlet
            (
                id_murid,
                hafalan_jurus,
                kelugesan_gerak,
                absensi
            )
            VALUES
            (
                '{$id_murid[$i]}',
                0,
                0,
                '$nilai_absensi'
            )
        ");

    }

}

echo "
<script>

alert('Absensi berhasil disimpan');

window.location='index.php?page=absensi';

</script>
";