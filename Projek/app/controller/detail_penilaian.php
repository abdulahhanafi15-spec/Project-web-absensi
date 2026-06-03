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

$id_sekolah = $_GET['id'];

$sekolah = mysqli_fetch_assoc(

    mysqli_query(

        $conn,

        "SELECT *
        FROM sekolah
        WHERE id_sekolah='$id_sekolah'"

    )

);

$query = mysqli_query(

    $conn,

    "SELECT *

    FROM murid

    WHERE id_sekolah='$id_sekolah'

    ORDER BY nama_murid ASC"

);

?>

<div class="content-card">

    <div class="card-header">

        <h1>

            Penilaian Atlet

            <br>

            <small>

                <?= $sekolah['nama_sekolah']; ?>

            </small>

        </h1>

    </div>

    <!-- TAB -->

    <div class="tab-container">

        <button
            class="tab-btn active"
            onclick="showTab('penilaian')"
        >
            Data Penilaian
        </button>

        <button
            class="tab-btn"
            onclick="showTab('topsis')"
        >
            Hasil TOPSIS
        </button>

    </div>

    <!-- TAB PENILAIAN -->

    <div
        id="penilaian"
        class="tab-content active"
    >

        <div class="table-container">

            <table class="karyawan-table">

                <thead>

                    <tr>

                        <th>No</th>

                        <th>Nama Atlet</th>

                        <th>Aksi</th>

                    </tr>

                </thead>

                <tbody>

                    <?php

                    $no = 1;

                    while($data = mysqli_fetch_assoc($query)){

                    ?>

                    <tr>

                        <td>
                            <?= $no++; ?>
                        </td>

                        <td>
                            <?= $data['nama_murid']; ?>
                        </td>

                        <td>

                            <button

                                type="button"

                                class="btn detail"

                                onclick="openModal(

                                    '<?= $data['id_murid']; ?>',

                                    '<?= $data['nama_murid']; ?>'

                                )"

                            >

                                Input Nilai

                            </button>

                        </td>

                    </tr>

                    <?php } ?>

                </tbody>

            </table>

        </div>

    </div>

    <!-- TAB TOPSIS -->

    <div
        id="topsis"
        class="tab-content"
    >

        <div class="table-container">

            <table class="karyawan-table">

                <thead>

                    <tr>

                        <th>Ranking</th>

                        <th>Nama Atlet</th>

                        <th>Nilai Preferensi</th>

                    </tr>

                </thead>

                <tbody>

                    <tr>

                        <td colspan="3">

                            Belum ada proses TOPSIS

                        </td>

                    </tr>

                </tbody>

            </table>

        </div>

    </div>

</div>

<!-- MODAL -->

<div
    id="modalNilai"
    class="modal"
>

    <div class="modal-content">

        <span
            class="close"
            onclick="closeModal()"
        >
            &times;
        </span>

        <h2>

            Input Penilaian Atlet

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

                    name="hafalan_jurus"

                    required

                >

            </div>

            <div class="form-group">

                <label>

                    Kelugesan Gerak

                </label>

                <input

                    type="number"

                    name="kelugesan_gerak"

                    required

                >

            </div>

            <button

                type="submit"

                class="btn tambah"

            >

                Simpan

            </button>

            <input
                type="hidden"
                name="id_sekolah"
                value="<?= $id_sekolah; ?>"
            >

        </form>

    </div>

</div>

<script src="/js/detail_penilaian.js"></script>