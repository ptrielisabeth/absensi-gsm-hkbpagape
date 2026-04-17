<?php
session_start();
include 'koneksi.php';

// --- LOGIKA LOGIN ---
if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($koneksi, trim($_POST['username']));
    $password = trim($_POST['password']);

    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($koneksi, $query);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row['password'])) {
            $_SESSION['id']   = $row['id'];
            $_SESSION['nama'] = $row['nama'];
            $_SESSION['role'] = $row['role'];

            if ($row['role'] == "ADMIN") {
                header("Location: admin.php");
            } else {
                header("Location: dashboard_gsm.php");
            }
            exit;
        } else {
            $error_msg = "Aduh, passwordnya salah! Coba cek lagi ya 💖";
        }
    } else {
        $error_msg = "Username tidak ditemukan! ✨";
    }
}

// --- LOGIKA DAFTAR + UPLOAD FOTO ---
if (isset($_POST['daftar'])) {
    $username   = mysqli_real_escape_string($koneksi, $_POST['username']);
    $nama       = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']);
    $password   = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $no_hp      = mysqli_real_escape_string($koneksi, $_POST['no_hp']);
    $alamat     = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $ttl        = mysqli_real_escape_string($koneksi, $_POST['ttl']);
    $role       = "GSM"; 

    $foto_name = $_FILES['foto']['name'];
    $foto_tmp  = $_FILES['foto']['tmp_name'];
    $foto_ext  = strtolower(pathinfo($foto_name, PATHINFO_EXTENSION));
    $new_name  = "gsm_" . time() . "_" . $username . "." . $foto_ext;
    $target    = "uploads/" . $new_name;

    $cek_user = mysqli_query($koneksi, "SELECT username FROM users WHERE username = '$username'");
    if(mysqli_num_rows($cek_user) > 0) {
        $error_msg = "Username sudah ada! 🎀";
    } else {
        if(move_uploaded_file($foto_tmp, $target)) {
            $ins = "INSERT INTO users (username, nama, password, no_hp, alamat, ttl, role, foto) 
                    VALUES ('$username', '$nama', '$password', '$no_hp', '$alamat', '$ttl', '$role', '$new_name')";
            if(mysqli_query($koneksi, $ins)) {
                $success_msg = "Yeay! Akun berhasil dibuat. Silakan login ya 🌸";
            }
        } else {
            $error_msg = "Gagal upload foto!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login GSM - HKBP Agape</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600;700&family=Quicksand:wght@500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --pink-tua: #ff0080;
            --fuschia: #e0115f;
            --pink-bg: #ffe4e9;
        }

        body {
            font-family: 'Quicksand', sans-serif;
            background: linear-gradient(135deg, #fff5f7 0%, var(--pink-bg) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-wrapper {
            position: relative;
            width: 100%;
            max-width: 450px;
            margin-top: 50px;
            text-align: center;
        }

        .logo-login {
            width: 110px;
            height: 110px;
            object-fit: cover;
            border-radius: 50%;
            border: 5px solid white;
            box-shadow: 0 10px 25px rgba(224, 17, 95, 0.2);
            position: absolute;
            top: -55px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 2;
        }

        .login-card {
            border: none;
            border-radius: 35px;
            background: white;
            box-shadow: 0 15px 40px rgba(0,0,0,0.05);
            padding: 70px 40px 40px 40px;
            border: 4px solid white;
            outline: 3px dashed var(--pink-tua);
            outline-offset: -18px;
            position: relative;
        }

        .gsm-tag {
            background: #e3f2fd;
            color: #0d6efd;
            font-weight: 700;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.75rem;
            display: inline-block;
            margin-bottom: 10px;
        }

        h2 { font-family: 'Fredoka', sans-serif; color: #000; font-weight: 700; margin-bottom: 5px; font-size: 1.8rem; }
        .info-header { font-weight: 700; color: #333; font-size: 0.95rem; margin-bottom: 5px; }
        .semangat-text { color: #666; font-size: 0.9rem; font-weight: 600; margin-bottom: 25px; }

        .form-control { border-radius: 50px; border: 2px solid #eee; padding: 12px 25px; margin-bottom: 15px; }
        .form-control:focus { border-color: var(--pink-tua); box-shadow: none; }

        .btn-masuk {
            background: linear-gradient(to right, #ff3385, #ff0066);
            color: white; border: none; padding: 12px; border-radius: 50px; width: 100%;
            font-weight: 700; font-size: 1.1rem; box-shadow: 0 8px 15px rgba(255, 0, 102, 0.2);
            transition: 0.3s;
        }
        .btn-masuk:hover { transform: scale(1.02); color: white; }

        .links-area { margin-top: 20px; }
        .daftar-link { display: block; color: #333; text-decoration: none; font-weight: 700; font-size: 0.85rem; margin-bottom: 10px; }
        .back-home { color: var(--fuschia); text-decoration: none; font-weight: 700; font-size: 0.85rem; }
        .back-home:hover { text-decoration: underline; }

        .footer-credit { margin-top: 25px; font-size: 0.85rem; color: #666; }

        .modal-content { border-radius: 30px; border: none; }
        .form-label-custom { font-weight: 700; color: var(--fuschia); font-size: 0.8rem; margin-left: 15px; display: block; text-align: left; margin-bottom: 5px; }
    </style>
</head>
<body>

    <div class="login-wrapper">
        <img src="uploads/logo_skm.jpeg" alt="Logo SKM" class="logo-login">
        
        <div class="login-card">
            <div class="gsm-tag text-uppercase">Guru Sekolah Minggu</div>
            <h2 class="text-uppercase">Presensi HKBP Agape</h2>
            <div class="info-header text-uppercase">Distrik XX Kepri</div>
            <div class="semangat-text">Semangat Melayani! 😇</div>

            <?php if(isset($error_msg)) : ?>
                <div class="alert alert-danger py-2" style="border-radius:20px; font-size:0.8rem;"><?= $error_msg; ?></div>
            <?php endif; ?>
            <?php if(isset($success_msg)) : ?>
                <div class="alert alert-success py-2" style="border-radius:20px; font-size:0.8rem;"><?= $success_msg; ?></div>
            <?php endif; ?>

            <form method="POST">
                <input type="text" name="username" class="form-control" placeholder="Username" required>
                <input type="password" name="password" class="form-control" placeholder="Password" required>
                <button type="submit" name="login" class="btn btn-masuk">Masuk Sekarang ✨</button>
            </form>

            <div class="links-area">
                <a href="#" class="daftar-link" data-bs-toggle="modal" data-bs-target="#modalDaftar">
                    Belum punya akun? Daftar Akun Baru Yuk! 🎀
                </a>
                <a href="index.php" class="back-home">🏠 Kembali ke Beranda</a>
            </div>
        </div>

        <div class="footer-credit">
            Developed with <span style="color:red;">❤️</span> by Putri Elisabeth Silalahi &copy; 2026
        </div>
    </div>

    <div class="modal fade" id="modalDaftar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content p-4">
                <h4 class="text-center mb-4 fw-bold" style="color: var(--fuschia)">Daftar Akun Baru</h4>
                <form method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6"><input type="text" name="username" class="form-control" placeholder="Username" required></div>
                        <div class="col-md-6"><input type="text" name="nama_lengkap" class="form-control" placeholder="Nama Lengkap" required></div>
                        <div class="col-md-6"><input type="password" name="password" class="form-control" placeholder="Password" required></div>
                        <div class="col-md-6"><input type="text" name="no_hp" class="form-control" placeholder="No WhatsApp" required></div>
                        <div class="col-md-6"><input type="text" name="ttl" class="form-control" placeholder="Tempat, Tgl Lahir" required></div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Foto Profil</label>
                            <input type="file" name="foto" class="form-control" accept="image/*" required>
                        </div>
                        <div class="col-12">
                            <textarea name="alamat" class="form-control" placeholder="Alamat Lengkap" style="border-radius:20px" rows="2" required></textarea>
                        </div>
                    </div>
                    <button type="submit" name="daftar" class="btn btn-masuk mt-3">Buat Akun 🌸</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>