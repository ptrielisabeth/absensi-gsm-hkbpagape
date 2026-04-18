<?php
session_start();
include 'koneksi.php';

// 1. Keamanan: Pastikan hanya Admin yang bisa akses file ini
if (!isset($_SESSION['role']) || strtolower($_SESSION['role']) != 'admin') {
    header("location:login.php");
    exit;
}

// 2. Cek apakah ada ID guru yang dikirim melalui URL
if (isset($_GET['id'])) {
    // Escape string untuk keamanan dari SQL Injection
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);

    // A. Cari tahu dulu nama kolom ID di tabel absensi (biasanya id_user atau id_guru)
    $cek_kolom = mysqli_query($koneksi, "SHOW COLUMNS FROM absensi");
    $col_id_absensi = "id_user"; // Default tebakan awal
    
    while($row = mysqli_fetch_assoc($cek_kolom)) {
        $field = strtolower($row['Field']);
        if (strpos($field, 'id_') !== false || strpos($field, 'user') !== false || strpos($field, 'guru') !== false) {
            $col_id_absensi = $row['Field'];
            break;
        }
    }

    // B. Hapus data di tabel ABSENSI dulu (agar tidak error relasi database)
    // Jika guru tersebut sudah pernah absen, kita hapus riwayatnya dulu
    mysqli_query($koneksi, "DELETE FROM absensi WHERE $col_id_absensi = '$id'");

    // C. Hapus data di tabel USERS
    $query_hapus_user = mysqli_query($koneksi, "DELETE FROM users WHERE id = '$id'");

    if ($query_hapus_user) {
        // Jika berhasil, kirim status sukses ke halaman data_guru.php
        header("location:data_guru.php?status=deleted");
    } else {
        // Jika gagal karena masalah database
        header("location:data_guru.php?status=error");
    }

} else {
    // Jika mencoba akses langsung tanpa ID, lempar balik ke data guru
    header("location:data_guru.php");
}
?>