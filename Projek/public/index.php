<?php
session_start();

include "../app/controller/koneksi.php";
include "../routes/routes.php";
include "../config/base.php";



// ================= ROUTING =================
$page = $_GET['page'] ?? 'login';

if ($page != 'login') {

    // logout boleh tanpa login
    if ($page != 'logout' && !isset($_SESSION['role'])) {
        header("Location: index.php");
        exit;
    }

    // proteksi role
    if ($page == 'admin_dashboard' && $_SESSION['role'] != 'admin') {
        die("Akses ditolak!");
    }

    if ($page == 'user_dashboard' && $_SESSION['role'] != 'user') {
        die("Akses ditolak!");
    }

    if (isset($routes[$page])) {
        include $routes[$page];
        exit;
    } else {
        echo "404 - Halaman tidak ditemukan";
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi Cahaya Cakra Archery</title>
    <link rel="icon" href="favicon.ico">
    <link rel="stylesheet" href="css/style.css">
    
</head>
<body class="login-page">

<div style="display:flex; justify-content:center; gap:50px; flex-wrap:wrap; align-items:flex-start; padding-top:100px;">

    <div class="login-box">
        <a href="index.php?page=login_cahaya">
            <img src="/img/Cahaya.png" alt="Logo Cahaya Cakra" style="display:block; margin:auto; width:128px; height:auto;">
        </a>
        <h2>Login Cahaya Cakra Archery</h2>
    </div>

    <div class="login-box">
        <a href="index.php?page=login_cahaya">
            <img src="/img/Cahaya.png" alt="Logo Cahaya Cakra" style="display:block; margin:auto; width:128px; height:auto;">
        </a>
        <h2>Login Cahaya Cakra Archery</h2>
    </div>

</div>

</body>
</html>