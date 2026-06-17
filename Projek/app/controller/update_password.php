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
        die("Session username tidak ditemukan.");
    }

    $username = $_SESSION['username'];

    $password_lama       = md5($_POST['password_lama']);
    $password_baru       = md5($_POST['password_baru']);
    $konfirmasi_password = md5($_POST['konfirmasi_password']);

    // Cek konfirmasi password
    if ($password_baru != $konfirmasi_password) {

        echo "
        <script>
            alert('Konfirmasi password tidak sesuai');
            history.back();
        </script>";
        exit;
    }

    // Cek password lama
    $cek = mysqli_query(
        $conn,
        "SELECT * FROM users
         WHERE username='$username'
         AND password='$password_lama'"
    );

    if (!$cek) {

        echo "
        <script>
            alert('Query gagal dijalankan');
            history.back();
        </script>";
        exit;
    }

    if (mysqli_num_rows($cek) == 0) {

        echo "
        <script>
            alert('Password lama salah');
            history.back();
        </script>";
        exit;
    }

    // Update password baru
    $update = mysqli_query(
        $conn,
        "UPDATE users
         SET password='$password_baru'
         WHERE username='$username'"
    );

    if ($update) {

        echo "
        <script>
            alert('Password berhasil diubah');
            window.location.href='index.php?page=setting_admin';
        </script>";

    } else {

        echo "
        <script>
            alert('Gagal mengubah password');
            history.back();
        </script>";
    }
}
?>