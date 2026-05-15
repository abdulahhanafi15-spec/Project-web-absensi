<?php

$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "cahaya_cakra"
);

$id = $_GET['id'];

$query = mysqli_query(
    $conn,
    "SELECT * FROM pelatih WHERE id='$id'"
);

$data = mysqli_fetch_assoc($query);

/* UPDATE */
if (isset($_POST['update'])) {

    $nama = $_POST['nama_pelatih'];
    $nip = $_POST['nip'];
    $alamat = $_POST['alamat'];
    $no_wa = $_POST['no_wa'];
    $status = $_POST['status'];

    mysqli_query(

        $conn,

        "UPDATE pelatih SET

        nama_pelatih='$nama',
        nip='$nip',
        alamat='$alamat',
        no_wa='$no_wa',
        status='$status'

        WHERE id='$id'"
    );

    header(
        "Location: index.php?page=detail_pelatih&id=$id"
    );
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Edit Pelatih</title>

    <link rel="stylesheet" href="/css/style.css">

</head>

<body>

<div class="main">

    <div class="content-card">

        <h1>Edit Data Pelatih</h1>

        <form method="POST">

            <div class="form-group">

                <label>Nama Pelatih</label>

                <input
                    type="text"
                    name="nama_pelatih"
                    value="<?= $data['nama_pelatih']; ?>"
                    required
                >

            </div>

            <div class="form-group">

                <label>NIP</label>

                <input
                    type="text"
                    name="nip"
                    value="<?= $data['nip']; ?>"
                    required
                >

            </div>

            <div class="form-group">

                <label>Alamat</label>

                <textarea
                    name="alamat"
                    required
                ><?= $data['alamat']; ?></textarea>

            </div>

            <div class="form-group">

                <label>No WhatsApp</label>

                <input
                    type="text"
                    name="no_wa"
                    value="<?= $data['no_wa']; ?>"
                    required
                >

            </div>

            <div class="form-group">

                <label>Status</label>

                <select name="status">

                    <option value="Aktif"
                        <?= $data['status'] == 'Aktif' ? 'selected' : ''; ?>>
                        Aktif
                    </option>

                    <option value="Nonaktif"
                        <?= $data['status'] == 'Nonaktif' ? 'selected' : ''; ?>>
                        Nonaktif
                    </option>

                </select>

            </div>

            <button
                type="submit"
                name="update"
                class="btn tambah"
            >
                Simpan Perubahan
            </button>

        </form>

    </div>

</div>

</body>
</html>