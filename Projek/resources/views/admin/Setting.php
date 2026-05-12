<?php

if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../../public/index.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="id"
<head>
    <meta charset="UTF-8">
    <title>Pengaturan</title>
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>
    <h1>testing</h1>
</body>