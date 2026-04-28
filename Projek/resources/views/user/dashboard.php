<?php
if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'user') {
    header("Location: ../../public/index.php");
    exit;
}
?>

<h1>Dashboard User</h1>
<p>Selamat datang, <?php echo $_SESSION['username']; ?></p>

<a href="index.php?page=logout">Logout</a>