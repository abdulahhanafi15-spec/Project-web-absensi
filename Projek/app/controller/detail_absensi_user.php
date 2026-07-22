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


/* =====================================
   ID SEKOLAH
===================================== */

$id_sekolah = (int)$_GET['id'];

/* =====================================
   BULAN YANG DIBUKA
===================================== */

if (isset($_GET['bulan']) && $_GET['bulan'] != "") {

    $bulanDipilih = $_GET['bulan'];

} else {

    $bulanDipilih = date("Y-m");

}

$tanggalFilter = $bulanDipilih . "-01";

$bulan = date("m", strtotime($tanggalFilter));
$tahun = date("Y", strtotime($tanggalFilter));

/* =====================================
   DATA SEKOLAH
===================================== */

$sekolah = mysqli_fetch_assoc(

    mysqli_query(

        $conn,

        "SELECT *
        FROM sekolah
        WHERE id_sekolah='$id_sekolah'"

    )

);

/* =====================================
   DATA MURID
===================================== */

$murid = mysqli_query(

    $conn,

    "SELECT *
    FROM murid
    WHERE id_sekolah='$id_sekolah'
    ORDER BY nama_murid ASC"

);

/* =====================================
   DATA ABSENSI BULAN INI
===================================== */

$dataAbsensi = [];

$queryAbsensi = mysqli_query($conn, "

SELECT
    absensi.*

FROM absensi

INNER JOIN murid
ON murid.id_murid = absensi.id_murid

WHERE

    murid.id_sekolah='$id_sekolah'

    AND absensi.bulan='$bulan'

    AND absensi.tahun='$tahun'

");

while ($row = mysqli_fetch_assoc($queryAbsensi)) {

    $dataAbsensi[$row['id_murid']] = $row;

}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Detail Pelatih</title>

    <link rel="stylesheet" href="/css/style.css">

</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar" id="sidebar">

    <button class="toggle-btn" onclick="toggleMenu()">☰</button>

    <ul>
        <li><a href="index.php?page=user_dashboard"><span>🏠</span><span class="text">Dashboard</span></a></li>
        <li><a href="index.php?page=user_struktur"><span>👥</span><span class="text">Struktur</span></a></li>
        <li><a href="index.php?page=user_pelatih"><span>📋</span><span class="text">Karyawan</span></a></li>
        <li><a href="index.php?page=user_sekolah"><span>🏫</span><span class="text">Sekolah</span></a></li>
        <li><a href="index.php?page=user_absensi"><span>📅</span><span class="text">Absensi</span></a></li>
        <li><a href="index.php?page=user_setting"><span>⚙️</span><span class="text">Pengaturan</span></a></li>
        <li><a href="index.php?page=user_penilaian"><span>⭐</span><span class="text">Penilaian</span></a></li>
        <li><a href="index.php?page=logout"><span>🚪</span><span class="text">Logout</span></a></li>
    </ul>

</div>

<!-- MAIN -->
<div class="main">

    <!-- HEADER -->
    <div class="header">

        <img src="img/Cahaya.png" alt="Logo 1">

        <img src="img/Perpani.png" alt="Logo 2">

    </div>

    <!-- CARD -->
    <div class="content-card">

        <h2>
            Absensi Murid - <?= $sekolah['nama_sekolah']; ?>
        </h2>

        <div style="margin:20px 0;">

            <form method="GET">

                <input
                    type="hidden"
                    name="page"
                    value="detail_absensi"
                >

                <input
                    type="hidden"
                    name="id"
                    value="<?= $id_sekolah; ?>"
                >

                <label><b>Bulan Absensi :</b></label>

                <input
                    type="month"
                    name="bulan"
                    value="<?= $bulanDipilih; ?>"
                    onchange="this.form.submit();"
                >

            </form>

        </div>

        <form
            action="index.php?page=simpan_absensi_user"
            method="POST"
        >

        <input
            type="hidden"
            name="bulan"
            value="<?= $bulanDipilih; ?>"
        >

        <input
            type="hidden"
            name="id_sekolah"
            value="<?= $id_sekolah; ?>"
        >

    <div class="table-container">
    
        <table class="karyawan-table">

        <thead>
            <tr>
                <th>No</th>
                <th>Nama Murid</th>
                <th>Kelas</th>
                <th>P1</th>
                <th>P2</th>
                <th>P3</th>
                <th>P4</th>
                <th>Nilai Absensi</th>
            </tr>
        </thead>

        <tbody>

        <?php

        $no = 1;

            while($row = mysqli_fetch_assoc($murid)):

            $idMurid = $row['id_murid'];

            $absensi = $dataAbsensi[$idMurid] ?? [];

            $p1 = $absensi['p1'] ?? 0;
            $p2 = $absensi['p2'] ?? 0;
            $p3 = $absensi['p3'] ?? 0;
            $p4 = $absensi['p4'] ?? 0;

            $rata = $p1 + $p2 + $p3 + $p4;

            ?>

        <tr>

            <td><?= $no++; ?></td>

            <td>

                <?= $row['nama_murid']; ?>

                <input
                    type="hidden"
                    name="id_murid[]"
                    value="<?= $idMurid; ?>"
                >

            </td>

            <td><?= $row['kelas']; ?></td>

            <!-- P1 -->

            <td>

                <select
                    class="kehadiran"
                    name="p1[]"
                >

                    <option
                        value="0"
                        <?= $p1==0 ? "selected" : "" ?>
                    >
                        Tidak Hadir
                    </option>

                    <option
                        value="25"
                        <?= $p1==25 ? "selected" : "" ?>
                    >
                        Hadir
                    </option>

                </select>

            </td>

            <!-- P2 -->

            <td>

                <select
                    class="kehadiran"
                    name="p2[]"
                >

                    <option
                        value="0"
                        <?= $p2==0 ? "selected" : "" ?>
                    >
                        Tidak Hadir
                    </option>

                    <option
                        value="25"
                        <?= $p2==25 ? "selected" : "" ?>
                    >
                        Hadir
                    </option>

                </select>

            </td>

            <!-- P3 -->

            <td>

                <select
                    class="kehadiran"
                    name="p3[]"
                >

                    <option
                        value="0"
                        <?= $p3==0 ? "selected" : "" ?>
                    >
                        Tidak Hadir
                    </option>

                    <option
                        value="25"
                        <?= $p3==25 ? "selected" : "" ?>
                    >
                        Hadir
                    </option>

                </select>

            </td>

            <!-- P4 -->

            <td>

                <select
                    class="kehadiran"
                    name="p4[]"
                >

                    <option
                        value="0"
                        <?= $p4==0 ? "selected" : "" ?>
                    >
                        Tidak Hadir
                    </option>

                    <option
                        value="25"
                        <?= $p4==25 ? "selected" : "" ?>
                    >
                        Hadir
                    </option>

                </select>

            </td>

            <td class="nilai-absensi">

                <?= $rata; ?>

            </td>

        </tr>

        <?php endwhile; ?>

        </tbody>

                </table>
    </div>
                <br>

                <!-- BUTTON KEMBALI -->
                <a href="index.php?page=user_absensi">

                    <button
                        type="button"
                        style="margin-right: 1px;"
                    >
                        ← Kembali
                    </button>

                </a>

                <button type="submit">
                Simpan Absensi
                </button>

                </form>
    </div>

</div>

<script src="/js/script.js"></script>
<script src="/js/hitung_absensi.js"></script>

</body>
</html>