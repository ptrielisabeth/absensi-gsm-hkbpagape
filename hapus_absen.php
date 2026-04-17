<?php
session_start();
include 'koneksi.php';

// Cek apakah yang akses adalah admin
if(!isset($_SESSION['role']) || strtolower($_SESSION['role']) != 'admin') {
    header("location:login.php");
    exit;
}

// Cek apakah ada ID yang dikirim untuk dihapus
if(isset($_GET['id'])) {
    $id = $_GET['id'];
    $tahun = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');

    // Query hapus data berdasarkan ID
    $query = mysqli_query($koneksi, "DELETE FROM absensi_gsm WHERE id = '$id'");

    if($query) {
        // Jika berhasil, balik lagi ke halaman rekap dengan tahun yang sama
        echo "<script>
                alert('Data absensi berhasil dihapus! ✨');
                window.location='rekap_absen.php?tahun=$tahun';
              </script>";
    } else {
        // Jika gagal
        echo "<script>
                alert('Aduh, gagal menghapus data nih.');
                window.location='rekap_absen.php?tahun=$tahun';
              </script>";
    }
} else {
    // Jika tidak ada ID, balikkan ke halaman utama
    header("location:rekap_absen.php");
}
?>