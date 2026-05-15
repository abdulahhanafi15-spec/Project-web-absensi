<?php

if (!isset($_SESSION)) {
    session_start();
}

/* VALIDASI ADMIN */
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {

    header("Location: ../../public/index.php");
    exit;
}

/* KONEKSI DATABASE */
$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "cahaya_cakra"
);

/* AMBIL ID */
$id = $_GET['id'];

/* ===================================== */
/* AMBIL DATA PELATIH */
/* ===================================== */

$query = mysqli_query(

    $conn,

    "SELECT * FROM pelatih
    WHERE id = '$id'"
);

$data = mysqli_fetch_assoc($query);

/* ===================================== */
/* AMBIL USER ID */
/* ===================================== */

$user_id = $data['user_id'];

/* ===================================== */
/* HAPUS DATA PELATIH */
/* ===================================== */

mysqli_query(

    $conn,

    "DELETE FROM pelatih
    WHERE id = '$id'"
);

/* ===================================== */
/* HAPUS USER LOGIN */
/* ===================================== */

mysqli_query(

    $conn,

    "DELETE FROM users
    WHERE id = '$user_id'"
);

/* ===================================== */
/* REDIRECT */
/* ===================================== */

header(
    "Location: index.php?page=pelatih_admin"
);

exit;

?>