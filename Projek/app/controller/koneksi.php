<?php
$conn = mysqli_connect("localhost", "root", "", "cahaya_cakra");

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>