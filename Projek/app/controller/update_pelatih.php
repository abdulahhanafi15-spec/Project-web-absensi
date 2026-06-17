<?php

session_start();

$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "cahaya_cakra"
);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (!isset($_SESSION['username'])) {

        echo "
        <script>
            alert('Session login tidak ditemukan');
            history.back();
        </script>";
        exit;
    }

    $username      = $_SESSION['username'];
    $nama_pelatih  = mysqli_real_escape_string($conn, $_POST['nama_pelatih']);
    $nip           = mysqli_real_escape_string($conn, $_POST['nip']);
    $alamat        = mysqli_real_escape_string($conn, $_POST['alamat']);
    $no_wa         = mysqli_real_escape_string($conn, $_POST['no_wa']);

    // Ambil ID user yang sedang login
    $user = mysqli_query(
        $conn,
        "SELECT id
         FROM users
         WHERE username = '$username'"
    );

    if (mysqli_num_rows($user) == 0) {

        echo "
        <script>
            alert('User tidak ditemukan');
            history.back();
        </script>";
        exit;
    }

    $data_user = mysqli_fetch_assoc($user);
    $user_id = $data_user['id'];

    // Update data pelatih berdasarkan foreign key user_id
    $update = mysqli_query(
        $conn,
        "UPDATE pelatih
         SET
            nama_pelatih = '$nama_pelatih',
            nip = '$nip',
            alamat = '$alamat',
            no_wa = '$no_wa'
         WHERE user_id = '$user_id'"
    );

    if ($update) {

        echo "
        <script>
            alert('Data berhasil diperbarui');
            window.location.href='index.php?page=setting_admin';
        </script>";

    } else {

        echo "
        <script>
            alert('Gagal memperbarui data');
            history.back();
        </script>";
    }
}
?>