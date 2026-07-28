<?php
session_start();

include "../routes/routes.php";
include "../config/base.php";

?>

<!DOCTYPE html>
<html>
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website Absensi</title>
    <link rel="icon" href="favicon.ico">
    <link rel="stylesheet" href="css/style.css">
    
</head>
<body class="login-page">

<div style="display:flex; justify-content:center; gap:50px; flex-wrap:wrap; align-items:flex-start; padding-top:100px;">

    <div class="login-box">
        <a href="login_cahaya.php">
            <img src="/img/Cahaya.png" alt="Logo Cahaya Cakra" style="display:block; margin:auto; width:128px; height:auto;">
        <h2>Login Cahaya Cakra Archery</h2>
        </a>
    </div>

    <div class="login-box">
        <a href="login_GSIPJ.php">
            <img src="/img/GSIPJ.png" alt="Logo Gerak Silat Inti Putra Jakarta" style="display:block; margin:auto; width:128px; height:auto; padding-bottom:20px;">
        
        <h2>Login Gerak Silat Inti Putra Jakarta</h2>
        </a>
    </div>

</div>

</body>
</html>