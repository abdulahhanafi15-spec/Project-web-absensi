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

    $username_lama = $_SESSION['username'];

    $nama_pelatih = mysqli_real_escape_string($conn, $_POST['nama_pelatih']);
    $nip          = mysqli_real_escape_string($conn, $_POST['nip']);
    $alamat       = mysqli_real_escape_string($conn, $_POST['alamat']);
    $no_wa        = mysqli_real_escape_string($conn, $_POST['no_wa']);

    // Ambil ID user yang login
    $user = mysqli_query(
        $conn,
        "SELECT id
         FROM users
         WHERE username = '$username_lama'"
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
    $user_id   = $data_user['id'];

    // Update username pada tabel users menjadi NIP baru
    $update_user = mysqli_query(
        $conn,
        "UPDATE users
         SET username = '$nip'
         WHERE id = '$user_id'"
    );

    // Update data pelatih
    $update_pelatih = mysqli_query(
        $conn,
        "UPDATE pelatih
         SET
            nama_pelatih = '$nama_pelatih',
            nip = '$nip',
            alamat = '$alamat',
            no_wa = '$no_wa'
         WHERE user_id = '$user_id'"
    );

    if ($update_user && $update_pelatih) {

        // Update session username agar tidak logout otomatis
        $_SESSION['username'] = $nip;

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