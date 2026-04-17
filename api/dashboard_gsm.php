<?php
session_start();
include 'koneksi.php';

// Proteksi halaman: Hanya GSM yang bisa masuk
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'GSM') {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['id'];
$tahun_ini = date('Y');

// Ambil data user yang sedang login
$query_user = mysqli_query($koneksi, "SELECT * FROM users WHERE id = '$user_id'");
$data = mysqli_fetch_assoc($query_user);

// Ambil Nama Depan saja untuk sapaan
$nama_lengkap = trim($data['nama']);
$nama_depan = explode(' ', $nama_lengkap)[0];

// Path foto profil & Logo SKM
$foto_profil = !empty($data['foto']) ? "uploads/" . $data['foto'] : "https://via.placeholder.com/150";
$logo_skm = "uploads/logo_skm.jpeg";

// --- HITUNG STATISTIK ---
$q_hadir = mysqli_query($koneksi, "SELECT id FROM absensi_gsm WHERE user_id = '$user_id' AND status_kehadiran = 'Hadir' AND YEAR(tanggal) = '$tahun_ini'");
$hadir   = mysqli_num_rows($q_hadir);

$q_izin  = mysqli_query($koneksi, "SELECT id FROM absensi_gsm WHERE user_id = '$user_id' AND status_kehadiran = 'Izin' AND YEAR(tanggal) = '$tahun_ini'");
$izin    = mysqli_num_rows($q_izin);

$q_sakit = mysqli_query($koneksi, "SELECT id FROM absensi_gsm WHERE user_id = '$user_id' AND status_kehadiran = 'Sakit' AND YEAR(tanggal) = '$tahun_ini'");
$sakit   = mysqli_num_rows($q_sakit);

$q_alpa  = mysqli_query($koneksi, "SELECT id FROM absensi_gsm WHERE user_id = '$user_id' AND status_kehadiran = 'Tanpa Kabar' AND YEAR(tanggal) = '$tahun_ini'");
$alpa    = mysqli_num_rows($q_alpa);

// --- AMBIL 5 RIWAYAT TERAKHIR (FIXED) ---
$riwayat_limit = mysqli_query($koneksi, "SELECT * FROM absensi_gsm WHERE user_id = '$user_id' ORDER BY tanggal DESC, waktu_input DESC LIMIT 5");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard GSM - HKBP Agape</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600;700&family=Quicksand:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            --pink-tua: #ff0080;
            --fuschia: #e0115f;
            --soft-pink: #fff5f7;
            --pink-muda: #ffe4e9;
        }

        body {
            font-family: 'Quicksand', sans-serif;
            background-color: var(--soft-pink);
            color: #333;
        }

        .navbar-custom {
            background: white;
            padding: 12px 20px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
            border-bottom: 2px solid var(--pink-muda);
        }

        .navbar-brand-custom {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .logo-navbar {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid var(--pink-muda);
        }

        .brand-text {
            font-family: 'Fredoka', sans-serif;
            font-weight: 700;
            color: var(--fuschia);
            font-size: 1.3rem;
            line-height: 1.1;
        }

        .brand-subtext {
            display: block;
            font-size: 0.85rem;
            color: #555;
            font-weight: 600;
        }

        .nav-profile-img {
            width: 45px;
            height: 45px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid var(--pink-tua);
        }

        .hero-section {
            background: linear-gradient(135deg, #ffafbd 0%, #ffc3a0 100%);
            border-radius: 35px;
            padding: 30px 20px;
            color: white;
            margin-top: 20px;
            text-align: center;
            box-shadow: 0 10px 25px rgba(255, 175, 189, 0.4);
        }

        .sapaan-text {
            font-family: 'Fredoka', sans-serif;
            font-weight: 700;
            font-size: 1.8rem;
            margin-bottom: 5px;
        }

        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 15px 5px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.02);
            border-bottom: 5px solid #eee;
            height: 100%;
        }
        .stat-hadir { border-color: #2ecc71; }
        .stat-izin { border-color: #f1c40f; }
        .stat-sakit { border-color: #e74c3c; }
        .stat-alpa { border-color: #95a5a6; }

        .stat-value {
            font-family: 'Fredoka', sans-serif;
            font-size: 1.6rem;
            font-weight: 700;
            display: block;
        }
        .stat-label {
            font-size: 0.7rem;
            color: #888;
            font-weight: 700;
            text-transform: uppercase;
        }

        .btn-absen-utama {
            background: linear-gradient(to right, #ff3385, #ff0066);
            border-radius: 25px;
            padding: 22px;
            font-family: 'Fredoka', sans-serif;
            font-size: 1.4rem;
            border: none;
            color: white;
            width: 100%;
            margin-top: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            text-decoration: none;
            box-shadow: 0 8px 20px rgba(255, 0, 102, 0.2);
            transition: 0.3s;
        }
        .btn-absen-utama:hover { transform: translateY(-3px); color: white; }

        .history-section {
            background: white;
            border-radius: 25px;
            padding: 20px;
            margin-top: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.03);
        }

        .riwayat-item {
            border-bottom: 1px solid #f8f9fa;
            padding: 12px 0;
        }
        .riwayat-item:last-child { border-bottom: none; }

        .footer-dev {
            margin-top: 60px;
            padding-bottom: 30px;
            text-align: center;
            font-size: 0.85rem;
            color: #aaa;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-custom sticky-top">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <a href="#" class="navbar-brand-custom">
                <img src="<?= $logo_skm; ?>" alt="Logo SKM" class="logo-navbar">
                <div class="brand-text">
                    GSM HKBP AGAPE
                    <span class="brand-subtext">Barelang - Distrik XX Kepri</span>
                </div>
            </a>

            <div class="d-flex align-items-center gap-3">
                <a href="profile.php">
                    <img src="<?= $foto_profil; ?>" alt="Profile" class="nav-profile-img shadow-sm">
                </a>
                <a href="logout.php" style="color: var(--pink-tua); font-size: 1.6rem;">
                    <i class="bi bi-box-arrow-right"></i>
                </a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="hero-section">
            <h4 class="sapaan-text">Shalom, Kak <?= $nama_depan; ?>! 😇</h4>
            <p class="mb-3" style="opacity: 0.9; font-weight: 500;">Rekap kehadiran tahun <?= $tahun_ini; ?>:</p>
            
            <div class="row g-2 mb-2">
                <div class="col-6">
                    <div class="stat-card stat-hadir">
                        <span class="stat-value text-success"><?= $hadir; ?></span>
                        <span class="stat-label">Hadir</span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="stat-card stat-izin">
                        <span class="stat-value text-warning"><?= $izin; ?></span>
                        <span class="stat-label">Izin</span>
                    </div>
                </div>
            </div>
            <div class="row g-2">
                <div class="col-6">
                    <div class="stat-card stat-sakit">
                        <span class="stat-value text-danger"><?= $sakit; ?></span>
                        <span class="stat-label">Sakit</span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="stat-card stat-alpa">
                        <span class="stat-value text-secondary"><?= $alpa; ?></span>
                        <span class="stat-label">Alpa</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-5">
                <a href="isi_absen.php" class="btn-absen-utama">
                    <i class="bi bi-pencil-square fs-2"></i>
                    <span>ISI ABSEN</span>
                </a>

                <div class="history-section">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold m-0"><i class="bi bi-clock-history me-2"></i> Riwayat Terakhir</h6>
                        <a href="riwayat_absen.php" class="text-decoration-none small fw-bold" style="color: var(--pink-tua);">Lihat Semua</a>
                    </div>

                    <?php if(mysqli_num_rows($riwayat_limit) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($riwayat_limit)): ?>
                            <div class="riwayat-item d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-bold" style="font-size: 0.9rem;"><?= $row['jenis_kegiatan']; ?></div>
                                    <div class="text-muted" style="font-size: 0.75rem;"><?= date('d M Y', strtotime($row['tanggal'])); ?></div>
                                </div>
                                <span class="badge rounded-pill <?= $row['status_kehadiran'] == 'Hadir' ? 'bg-success' : ($row['status_kehadiran'] == 'Izin' ? 'bg-warning' : 'bg-danger'); ?>" style="font-size: 0.7rem;">
                                    <?= $row['status_kehadiran']; ?>
                                </span>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="text-center py-3 text-muted small">Belum ada riwayat absensi.</div>
                    <?php endif; ?>
                </div>
                
                <div class="text-center mt-4 p-3" style="background: white; border-radius: 20px; border: 1px dashed var(--pink-tua);">
                    <small class="text-muted d-block">Status Hari Ini:</small>
                    <span class="fw-bold" style="color: var(--fuschia);">Tuhan Yesus Memberkati 🌸</span>
                </div>
            </div>
        </div>

        <div class="footer-dev">
            Developed with ❤️ by Putri Elisabeth Silalahi &copy; 2026
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>