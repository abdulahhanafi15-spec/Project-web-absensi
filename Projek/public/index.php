<?php
session_start();

include "../app/controller/koneksi.php";
include "../routes/routes.php";

// ================= LOGIN =================
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']);

    $query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' AND password='$password'");
    $data = mysqli_fetch_assoc($query);

    if ($data) {
        $_SESSION['username'] = $data['username'];
        $_SESSION['role'] = $data['role'];

        if ($data['role'] == 'admin') {
            header("Location: index.php?page=admin_dashboard");
        } else {
            header("Location: index.php?page=user_dashboard");
        }
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}

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
    <title>Absensi Cahaya Cakra Archery</title>
    <link rel="icon" href="favicon.ico">
    <link rel="stylesheet" href="css/style.css">
    
</head>
<body>

<div class="login-box">
    <img src="/img/Cahaya.png" alt="Logo Cahaya Cakra" style="display: center; width: 128px; height: auto;">
    
    <h2>Login</h2>

    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>

    <form method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login">Login</button>
    </form>
</div>

</body>
</html>