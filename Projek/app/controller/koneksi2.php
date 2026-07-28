<?php
$conn = mysqli_connect("localhost", "root", "", "gsipj");

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>