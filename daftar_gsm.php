<?php
session_start();
include 'koneksi.php';

// Keamanan: Cek apakah yang akses adalah ADMIN
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'ADMIN') {
    header("Location: login.php");
    exit;
}

$pesan = "";
$tipe_pesan = "";

if (isset($_POST['tambah_akun'])) {
    $nama_lengkap = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $username     = mysqli_real_escape_string($conn, trim($_POST['username']));
    $password     = $_POST['password'];
    $role         = "GSM";

    // 1. Validasi: Apakah username sudah dipakai?
    $cek = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");
    if (mysqli_num_rows($cek) > 0) {
        $pesan = "Gagal! Username <strong>$username</strong> sudah terdaftar.";
        $tipe_pesan = "danger";
    } else {
        // 2. Hash Password (agar aman dan bisa dibaca oleh login.php)
        $password_fix = password_hash($password, PASSWORD_DEFAULT);

        // 3. Simpan ke database
        $sql = "INSERT INTO users (nama, username, password, role) VALUES ('$nama_lengkap', '$username', '$password_fix', '$role')";
        
        if (mysqli_query($conn, $sql)) {
            $pesan = "Berhasil! Akun GSM untuk <strong>$nama_lengkap</strong> telah aktif.";
            $tipe_pesan = "success";
        } else {
            $pesan = "Terjadi kesalahan database.";
            $tipe_pesan = "danger";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Akun GSM - HKBP Apage</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .register-card { max-width: 500px; margin: 50px auto; border: none; border-radius: 12px; }
        .btn-success { background-color: #198754; }
    </style>
</head>
<body>

<div class="container">
    <div class="card register-card shadow">
        <div class="card-header bg-success text-white text-center py-3" style="border-radius: 12px 12px 0 0;">
            <h5 class="mb-0 fw-bold">TAMBAH AKUN GURU BARU</h5>
        </div>
        <div class="card-body p-4">
            
            <?php if($pesan !== "") : ?>
                <div class="alert alert-<?php echo $tipe_pesan; ?> alert-dismissible fade show" role="alert">
                    <?php echo $pesan; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label fw-bold">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" class="form-control" placeholder="Masukkan nama asli guru" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Username</label>
                    <input type="text" name="username" class="form-control" placeholder="Contoh: putri_gsm" required>
                    <div class="form-text">Username digunakan untuk login.</div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Buat password minimal 5 karakter" required>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" name="tambah_akun" class="btn btn-success py-2 fw-bold">SIMPAN AKUN</button>
                    <a href="admin.php" class="btn btn-light text-muted">Kembali ke Dashboard</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>