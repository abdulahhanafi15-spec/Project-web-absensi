<?php

if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../../public/index.php");
    exit;
}

$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "cahaya_cakra"
);

$id_sekolah = intval($_GET['id']);

/* =====================================
   FILTER BULAN & TAHUN
===================================== */

$bulan = isset($_GET['bulan'])
    ? intval($_GET['bulan'])
    : date('n');

$tahun = isset($_GET['tahun'])
    ? intval($_GET['tahun'])
    : date('Y');


$sekolah = mysqli_fetch_assoc(

    mysqli_query(

        $conn,

        "SELECT *

        FROM sekolah

        WHERE id_sekolah='$id_sekolah'"

    )

);

/* ===================================== */
/* DATA TOPSIS */
/* ===================================== */

$data_topsis = [];

$query_topsis = mysqli_query(

    $conn,

    "SELECT

        m.id_murid,
        m.nama_murid,
        p.hafalan_jurus,
        p.kelugesan_gerak,

        IFNULL(a.rata_rata,0) AS absensi
        FROM murid m
        LEFT JOIN penilaian_atlet p
        ON

        m.id_murid=p.id_murid

        AND p.bulan='$bulan'
        AND p.tahun='$tahun'
        LEFT JOIN absensi a
        ON

        m.id_murid=a.id_murid

        AND a.bulan='$bulan'
        AND a.tahun='$tahun'
        WHERE

        m.id_sekolah='$id_sekolah'

        ORDER BY

        m.nama_murid ASC"

);

while($row = mysqli_fetch_assoc($query_topsis)){

    $hafalan = floatval($row['hafalan_jurus'] ?? 0);

    $gerak = floatval($row['kelugesan_gerak'] ?? 0);

    $absensi = floatval($row['absensi'] ?? 0);

    $data_topsis[] = [

        'id_murid'      => $row['id_murid'],

        'nama_murid'    => $row['nama_murid'],

        'c1'            => $hafalan,

        'c2'            => $gerak,

        'c3'            => $absensi

    ];

}

/* ===================================== */
/* PEMBAGI NORMALISASI */
/* ===================================== */

$pembagi_c1 = 0;
$pembagi_c2 = 0;
$pembagi_c3 = 0;

foreach($data_topsis as $d){

    $pembagi_c1 += pow(
        $d['c1'],
        2
    );

    $pembagi_c2 += pow(
        $d['c2'],
        2
    );

    $pembagi_c3 += pow(
        $d['c3'],
        2
    );

}

$pembagi_c1 = sqrt($pembagi_c1);
$pembagi_c2 = sqrt($pembagi_c2);
$pembagi_c3 = sqrt($pembagi_c3);

if($pembagi_c1 == 0) $pembagi_c1 = 1;
if($pembagi_c2 == 0) $pembagi_c2 = 1;
if($pembagi_c3 == 0) $pembagi_c3 = 1;

/* ===================================== */
/* BOBOT */
/* ===================================== */

$bobot_c1 = 0.50;
$bobot_c2 = 0.30;
$bobot_c3 = 0.20;

/* ===================================== */
/* NORMALISASI TERBOBOT */
/* ===================================== */

$terbobot = [];

foreach($data_topsis as $d){

    $r1 = $d['c1'] / $pembagi_c1;
    $r2 = $d['c2'] / $pembagi_c2;
    $r3 = $d['c3'] / $pembagi_c3;

    $y1 = $r1 * $bobot_c1;
    $y2 = $r2 * $bobot_c2;
    $y3 = $r3 * $bobot_c3;

    $terbobot[] = [

        'id_murid' => $d['id_murid'],
        'nama_murid' => $d['nama_murid'],

        'c1' => $d['c1'],
        'c2' => $d['c2'],
        'c3' => $d['c3'],

        'r1' => $r1,
        'r2' => $r2,
        'r3' => $r3,

        'y1' => $y1,
        'y2' => $y2,
        'y3' => $y3

    ];

}

/* ===================================== */
/* SOLUSI IDEAL */
/* ===================================== */

$a_plus_c1 = max(
    array_column(
        $terbobot,
        'y1'
    )
);

$a_plus_c2 = max(
    array_column(
        $terbobot,
        'y2'
    )
);

$a_plus_c3 = max(
    array_column(
        $terbobot,
        'y3'
    )
);

$a_min_c1 = min(
    array_column(
        $terbobot,
        'y1'
    )
);

$a_min_c2 = min(
    array_column(
        $terbobot,
        'y2'
    )
);

$a_min_c3 = min(
    array_column(
        $terbobot,
        'y3'
    )
);

/* ===================================== */
/* D+ D- */
/* ===================================== */

foreach($terbobot as $key => $d){

    $dplus = sqrt(

        pow(
            $d['y1'] - $a_plus_c1,
            2
        )

        +

        pow(
            $d['y2'] - $a_plus_c2,
            2
        )

        +

        pow(
            $d['y3'] - $a_plus_c3,
            2
        )

    );

    $dmin = sqrt(

        pow(
            $d['y1'] - $a_min_c1,
            2
        )

        +

        pow(
            $d['y2'] - $a_min_c2,
            2
        )

        +

        pow(
            $d['y3'] - $a_min_c3,
            2
        )

    );

    $nilai_preferensi = 0;

    if(($dplus + $dmin) > 0){

        $nilai_preferensi =

        $dmin /

        (
            $dplus + $dmin
        );

    }

    $terbobot[$key]['dplus'] =
    $dplus;

    $terbobot[$key]['dmin'] =
    $dmin;

    $terbobot[$key]['preferensi'] =
    $nilai_preferensi;

}

/* ===================================== */
/* RANKING */
/* ===================================== */

$ranking_topsis = $terbobot;

foreach($ranking_topsis as &$r){

    $r['nilai_akhir'] = round(

        $r['preferensi'],

        4

    );

}

unset($r);

?>

<!DOCTYPE html>
    <html lang="id">
    <head>
            <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Pelatih</title>
        <link rel="stylesheet" href="/css/style.css">
    </head>
<body>

        
<!-- SIDEBAR -->
<div class="sidebar" id="sidebar">

    <button class="toggle-btn" onclick="toggleMenu()">☰</button>

    <ul>
        <li><a href="index.php?page=admin_dashboard"><span>🏠</span><span class="text">Dashboard</span></a></li>
        <li><a href="index.php?page=struktur"><span>👥</span><span class="text">Struktur</span></a></li>
        <li><a href="index.php?page=pelatih_admin"><span>📋</span><span class="text">Karyawan</span></a></li>
        <li><a href="index.php?page=sekolah_admin"><span>🏫</span><span class="text">Sekolah</span></a></li>
        <li><a href="index.php?page=absensi"><span>📅</span><span class="text">Absensi</span></a></li>
        <li><a href="index.php?page=laporan"><span>📊</span><span class="text">Laporan</span></a></li>
        <li><a href="index.php?page=setting_admin"><span>⚙️</span><span class="text">Pengaturan</span></a></li>
        <li><a href="index.php?page=penilaian"><span>⭐</span><span class="text">Penilaian</span></a></li>
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

    <!-- ===================================== -->
    <!-- DETAIL SEKOLAH -->
    <!-- ===================================== -->
<div class="content-card">

    <!-- ===================================== -->
    <!-- HEADER -->
    <!-- ===================================== -->

    <div class="card-header">

        <h1>

            Penilaian Atlet Berprestasi

        </h1>

        <p>

            Perguruan :

            <strong>

                <?= $sekolah['nama_sekolah']; ?>

            </strong>

            <hr>

        <form method="GET">

        <input
        type="hidden"
        name="page"
        value="detail_penilaian">

        <input
        type="hidden"
        name="id"
        value="<?= $id_sekolah; ?>">


        <label>Bulan</label>

        <select
        name="bulan">

        <?php

        for($i=1;$i<=12;$i++){

        ?>

        <option
        value="<?= $i; ?>"

        <?= $bulan==$i ? 'selected' : ''; ?>

        >

        <?= $i; ?>

        </option>

        <?php } ?>

        </select>


        <label>Tahun</label>

        <select
        name="tahun">

        <?php

        for($t=date('Y')-2;$t<=date('Y')+2;$t++){

        ?>

        <option

        value="<?= $t; ?>"

        <?= $tahun==$t ? 'selected' : ''; ?>

        >

        <?= $t; ?>

        </option>

        <?php } ?>

        </select>


        <button
        type="submit"
        class="btn tambah">

        Tampilkan

        </button>

        </form>

        <br>

        </p>

    </div>

    <!-- ===================================== -->
    <!-- TAB MENU -->
    <!-- ===================================== -->

    <div class="tab-container">

        <button
            type="button"
            class="tab-btn active"
            onclick="showTab('tab_penilaian', this)"
        >
            Data Penilaian
        </button>

        <button
            type="button"
            class="tab-btn"
            onclick="showTab('tab_topsis', this)"
        >
            Hasil TOPSIS
        </button>

    </div>

    <!-- ===================================== -->
    <!-- TAB DATA PENILAIAN -->
    <!-- ===================================== -->

    <div
        id="tab_penilaian"
        class="tab-content"
        style="display:block;"
    >

        <div class="table-container">

            <table class="karyawan-table">

                <thead>

                    <tr>

                        <th>No</th>

                        <th>Nama Atlet</th>

                        <th>Hafalan Jurus</th>

                        <th>Kelugesan Gerak</th>

                        <th>Absensi</th>

                        <th>Rata-rata</th>

                        <th>Status</th>

                        <th>Aksi</th>

                    </tr>

                </thead>

                <tbody>

                    <?php

                    $query = mysqli_query(

                        $conn,

                        "SELECT m.*,

                        p.hafalan_jurus,

                        p.kelugesan_gerak,

                        IFNULL(a.rata_rata,0) AS absensi
                        FROM murid m
                        LEFT JOIN penilaian_atlet p
                        ON

                        m.id_murid=p.id_murid

                        AND p.bulan='$bulan'
                        AND p.tahun='$tahun'
                        LEFT JOIN absensi a
                        ON

                        m.id_murid=a.id_murid

                        AND a.bulan='$bulan'
                        AND a.tahun='$tahun'
                        WHERE

                        m.id_sekolah='$id_sekolah'

                        ORDER BY

                        m.nama_murid ASC"
                    );

                    $no = 1;

                    while($data = mysqli_fetch_assoc($query)){

                    $hafalan = intval($data['hafalan_jurus'] ?? 0);

                    $gerak = intval($data['kelugesan_gerak'] ?? 0);

                    $absensi = intval($data['absensi'] ?? 0);

                    $rata = round(
                        ($hafalan + $gerak + $absensi) / 3,
                        2
                    );

                    ?>

                    <tr>

                        <td>

                            <?= $no++; ?>

                        </td>

                        <td>

                            <?= $data['nama_murid']; ?>

                        </td>

                        <td>

                            <?= $data['hafalan_jurus'] ?? '-'; ?>

                        </td>

                        <td>

                            <?= $data['kelugesan_gerak'] ?? '-'; ?>

                        </td>

                        <td>

                            <?= $data['absensi'] ?? '-'; ?>

                        </td>

                        <td>

                            <?= number_format(
                                $rata,
                                2
                            ); ?>

                        </td>

                        <td>

                            <?php

                            if(
                            $data['hafalan_jurus'] !== null &&
                            $data['kelugesan_gerak'] !== null
                            ){

                                echo '

                                <span class="status aktif">

                                    Sudah Dinilai

                                </span>

                                ';

                            }else{

                                echo '

                                <span class="status nonaktif">

                                    Belum Dinilai

                                </span>

                                ';

                            }

                            ?>

                        </td>

                        <td>

                            <button

                                type="button"

                                class="btn detail"

                                onclick="openModal(
                                '<?= $data['id_murid']; ?>',
                                '<?= htmlspecialchars($data['nama_murid'], ENT_QUOTES); ?>',
                                '<?= $data['hafalan_jurus'] ?? 0; ?>',
                                '<?= $data['kelugesan_gerak'] ?? 0; ?>'
                                )"

                            >

                                Edit Nilai

                            </button>

                        </td>

                    </tr>

                    <?php } ?>

                </tbody>

            </table>

        </div>

        <br>
        <br>

        <h2>

            Ranking Sementara

        </h2>

        <h3>

        Periode Penilaian

        <?= $bulan; ?>/<?= $tahun; ?>

        </h3>

        <br>

        <div class="table-container">

            <table class="karyawan-table">

                <thead>

                    <tr>

                        <th>Ranking</th>

                        <th>Nama Atlet</th>

                        <th>Nilai Rata-rata</th>

                    </tr>

                </thead>

                <tbody>

                    <?php

                    $rank = 1;

                    foreach($ranking_topsis as $r){

                    ?>

                    <tr>

                        <td>

                            <?= $rank++; ?>

                        </td>

                        <td>

                            <?= $r['nama_murid']; ?>

                        </td>

                        <td>

                            <?= number_format(

                                $r['nilai_akhir'],

                                4

                                ); ?>

                        </td>

                    </tr>

                    <?php } ?>

                </tbody>

            </table>

        <!-- BUTTON KEMBALI -->
        <div class="detail-button">

            <a href="index.php?page=penilaian">

                <button
                    type="button"
                    class="btn tambah"
                >
                    Kembali
                </button>

            </a>
        </div>

        </div>

    </div>

<!-- ===================================== -->
<!-- TAB HASIL TOPSIS -->
<!-- ===================================== -->

<div
    id="tab_topsis"
    class="tab-content"
    style="display:none;"
>

    <h2>

        Hasil Perhitungan TOPSIS

    </h2>

    <br>

    <!-- ===================================== -->
    <!-- BOBOT -->
    <!-- ===================================== -->

    <div class="table-container">

        <table class="karyawan-table">

            <thead>

                <tr>

                    <th>Kriteria</th>
                    <th>Bobot</th>
                    <th>Tipe</th>

                </tr>

            </thead>

            <tbody>

                <tr>

                    <td>Hafalan Jurus (C1)</td>
                    <td>50%</td>
                    <td>Benefit</td>

                </tr>

                <tr>

                    <td>Kelugesan Gerak (C2)</td>
                    <td>30%</td>
                    <td>Benefit</td>

                </tr>

                <tr>

                    <td>Absensi (C3)</td>
                    <td>20%</td>
                    <td>Benefit</td>

                </tr>

            </tbody>

        </table>

    </div>

    <br>

    <!-- ===================================== -->
    <!-- MATRIKS KEPUTUSAN -->
    <!-- ===================================== -->

    <h3>

        Matriks Keputusan (X)

    </h3>

    <div class="table-container">

        <table class="karyawan-table">

            <thead>

                <tr>

                    <th>Atlet</th>
                    <th>C1</th>
                    <th>C2</th>
                    <th>C3</th>

                </tr>

            </thead>

            <tbody>

                <?php foreach($terbobot as $d){ ?>

                <tr>

                    <td>

                        <?= $d['nama_murid']; ?>

                    </td>

                    <td>

                        <?= $d['c1']; ?>

                    </td>

                    <td>

                        <?= $d['c2']; ?>

                    </td>

                    <td>

                        <?= $d['c3']; ?>

                    </td>

                </tr>

                <?php } ?>

            </tbody>

        </table>

    </div>

    <br>

    <!-- ===================================== -->
    <!-- PEMBAGI -->
    <!-- ===================================== -->

    <h3>

        Nilai Pembagi

    </h3>

    <div class="table-container">

        <table class="karyawan-table">

            <thead>

                <tr>

                    <th>C1</th>
                    <th>C2</th>
                    <th>C3</th>

                </tr>

            </thead>

            <tbody>

                <tr>

                    <td>

                        <?= number_format(
                            $pembagi_c1,
                            4
                        ); ?>

                    </td>

                    <td>

                        <?= number_format(
                            $pembagi_c2,
                            4
                        ); ?>

                    </td>

                    <td>

                        <?= number_format(
                            $pembagi_c3,
                            4
                        ); ?>

                    </td>

                </tr>

            </tbody>

        </table>

    </div>

    <br>

    <!-- ===================================== -->
    <!-- NORMALISASI -->
    <!-- ===================================== -->

    <h3>

        Matriks Normalisasi (R)

    </h3>

    <!-- ===================================== -->
<!-- NORMALISASI TERBOBOT -->
<!-- ===================================== -->

<h3>

    Matriks Normalisasi Terbobot (Y)

</h3>

<div class="table-container">

    <table class="karyawan-table">

        <thead>

            <tr>

                <th>Atlet</th>

                <th>Y1</th>

                <th>Y2</th>

                <th>Y3</th>

            </tr>

        </thead>

        <tbody>

            <?php foreach($terbobot as $d){ ?>

            <tr>

                <td>

                    <?= $d['nama_murid']; ?>

                </td>

                <td>

                    <?= number_format(
                        $d['y1'],
                        4
                    ); ?>

                </td>

                <td>

                    <?= number_format(
                        $d['y2'],
                        4
                    ); ?>

                </td>

                <td>

                    <?= number_format(
                        $d['y3'],
                        4
                    ); ?>

                </td>

            </tr>

            <?php } ?>

        </tbody>

    </table>

</div>

<br>

<!-- ===================================== -->
<!-- SOLUSI IDEAL POSITIF -->
<!-- ===================================== -->

<h3>

    Solusi Ideal Positif (A+)

</h3>

<div class="table-container">

    <table class="karyawan-table">

        <thead>

            <tr>

                <th>A+ C1</th>

                <th>A+ C2</th>

                <th>A+ C3</th>

            </tr>

        </thead>

        <tbody>

            <tr>

                <td>

                    <?= number_format(
                        $a_plus_c1,
                        4
                    ); ?>

                </td>

                <td>

                    <?= number_format(
                        $a_plus_c2,
                        4
                    ); ?>

                </td>

                <td>

                    <?= number_format(
                        $a_plus_c3,
                        4
                    ); ?>

                </td>

            </tr>

        </tbody>

    </table>

</div>

<br>

<!-- ===================================== -->
<!-- SOLUSI IDEAL NEGATIF -->
<!-- ===================================== -->

<h3>

    Solusi Ideal Negatif (A-)

</h3>

<div class="table-container">

    <table class="karyawan-table">

        <thead>

            <tr>

                <th>A- C1</th>

                <th>A- C2</th>

                <th>A- C3</th>

            </tr>

        </thead>

        <tbody>

            <tr>

                <td>

                    <?= number_format(
                        $a_min_c1,
                        4
                    ); ?>

                </td>

                <td>

                    <?= number_format(
                        $a_min_c2,
                        4
                    ); ?>

                </td>

                <td>

                    <?= number_format(
                        $a_min_c3,
                        4
                    ); ?>

                </td>

            </tr>

        </tbody>

    </table>

</div>

<br>

    <div class="table-container">

        <table class="karyawan-table">

            <thead>

                <tr>

                    <th>Atlet</th>
                    <th>R1</th>
                    <th>R2</th>
                    <th>R3</th>

                </tr>

            </thead>

            <tbody>

                <?php foreach($terbobot as $d){ ?>

                <tr>

                    <td>

                        <?= $d['nama_murid']; ?>

                    </td>

                    <td>

                        <?= number_format(
                            $d['r1'],
                            4
                        ); ?>

                    </td>

                    <td>

                        <?= number_format(
                            $d['r2'],
                            4
                        ); ?>

                    </td>

                    <td>

                        <?= number_format(
                            $d['r3'],
                            4
                        ); ?>

                    </td>

                </tr>

                <?php } ?>

            </tbody>

        </table>

    </div>

        <!-- BUTTON KEMBALI -->
        <div class="detail-button">

            <a href="index.php?page=user_penilaian">

                <button
                    type="button"
                    class="btn tambah"
                >
                    Kembali
                </button>

            </a>
        </div>

    </div>

</div>

<div id="modalNilai" class="modal">

    <div class="modal-content">

        <span
            class="close"
            onclick="closeModal()"
        >
            &times;
        </span>

        <h2>

            Input Nilai Atlet

        </h2>

        <form
            action="index.php?page=simpan_penilaian"
            method="POST"
        >

            <input
                type="hidden"
                id="id_murid"
                name="id_murid"
            >

            <input
                type="hidden"
                name="id_sekolah"
                value="<?= $id_sekolah; ?>"
            >

            <input
                type="hidden"
                name="bulan"
                value="<?= $bulan; ?>">

            <input
                type="hidden"
                name="tahun"
                value="<?= $tahun; ?>">

            <div class="form-group">

                <label>

                    Nama Atlet

                </label>

                <input
                    type="text"
                    id="nama_murid"
                    readonly
                >

            </div>

            <div class="form-group">

                <label>

                    Hafalan Jurus

                </label>

                <input
                    type="number"
                    id="hafalan_jurus"
                    name="hafalan_jurus"
                    min="0"
                    max="100"
                    required
                >

            </div>

            <div class="form-group">

                <label>

                    Kelugesan Gerak

                </label>

                <input
                    type="number"
                    id="kelugesan_gerak"
                    name="kelugesan_gerak"
                    min="0"
                    max="100"
                    required
                >

            </div>

            <button
                type="submit"
                class="btn tambah"
            >

                Simpan Nilai

            </button>

        </form>

    </div>

</div>

<script src="js/detail_penilaian.js"></script>
<script src="js/script.js"></script>