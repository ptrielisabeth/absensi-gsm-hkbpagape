<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "absensi_hkbp"; // Ganti dengan nama database kamu

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>