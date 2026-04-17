<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['id'];
    
    $nama    = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $no_hp   = mysqli_real_escape_string($koneksi, $_POST['no_hp']);
    $ttl     = mysqli_real_escape_string($koneksi, $_POST['ttl']);
    $alamat  = mysqli_real_escape_string($koneksi, $_POST['alamat']);

    $sql = "UPDATE users SET 
            nama = '$nama', 
            no_hp = '$no_hp', 
            ttl = '$ttl', 
            alamat = '$alamat' 
            WHERE id = '$user_id'";

    if (mysqli_query($koneksi, $sql)) {
        // Menggunakan SweetAlert2 supaya muncul pop-up cantik
        echo "
        <!DOCTYPE html>
        <html>
        <head>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <link href='https://fonts.googleapis.com/css2?family=Quicksand:wght@500;700&display=swap' rel='stylesheet'>
            <style>
                body { font-family: 'Quicksand', sans-serif; }
            </style>
        </head>
        <body>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil Disimpan!',
                    text: 'Profil kamu sudah diperbarui ✨',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                    didOpen: () => {
                        Swal.showLoading()
                    }
                }).then(() => {
                    window.location.href = 'profile.php';
                });
            </script>
        </body>
        </html>";
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}
?>