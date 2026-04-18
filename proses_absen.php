<?php
session_start();
// 1. Pastikan path ke koneksi.php benar
include 'koneksi.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 2. Cek apakah variabel $koneksi ada
    if (!isset($koneksi)) {
        die("Error: Variabel koneksi tidak ditemukan. Pastikan nama variabel di koneksi.php adalah \$koneksi");
    }

    $user_id = $_POST['user_id'];
    $jenis_kegiatan = $_POST['jenis_kegiatan'];
    $status = $_POST['status'];
    $tanggal = $_POST['tanggal'];
    $keterangan = $_POST['keterangan'];
    $image = $_POST['image'];

    // Proses konversi Base64 ke File Gambar
    $image = str_replace('data:image/jpeg;base64,', '', $image);
    $image = str_replace(' ', '+', $image);
    $imageData = base64_decode($image);
    
    // Buat folder uploads jika belum ada
    if (!file_exists('uploads')) {
        mkdir('uploads', 0777, true);
    }

    $nama_file = 'img_' . uniqid() . '.jpg';
    $path = 'uploads/' . $nama_file;
    
    if (file_put_contents($path, $imageData)) {
        // 3. Gunakan mysqli_real_escape_string untuk keamanan (mencegah error karakter aneh)
        $ket = mysqli_real_escape_string($koneksi, $keterangan);
        
        $sql = "INSERT INTO absensi_gsm (user_id, jenis_kegiatan, status_kehadiran, tanggal, keterangan, foto) 
                VALUES ('$user_id', '$jenis_kegiatan', '$status', '$tanggal', '$ket', '$nama_file')";

        if (mysqli_query($koneksi, $sql)) {
            echo "success";
        } else {
            echo "Error Database: " . mysqli_error($koneksi);
        }
    } else {
        echo "Error: Gagal menyimpan gambar ke folder uploads.";
    }
}
?>