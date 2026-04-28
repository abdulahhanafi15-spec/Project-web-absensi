<?php
$conn = mysqli_connect("localhost", "root", "", "nama_database");

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>