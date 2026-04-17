<?php
session_start();
include 'koneksi.php';

// Proteksi Admin (Check role tanpa sensitif huruf besar/kecil)
if(!isset($_SESSION['role']) || strtolower($_SESSION['role']) != 'admin') {
    header("location:login.php");
    exit;
}

$nama_admin = $_SESSION['nama'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - HKBP Agape</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600;700&family=Quicksand:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            --pink-tua: #ff0080;
            --pink-soft: #fff5f7;
            --gradient-pink: linear-gradient(135deg, #ffafbd 0%, #ffc3a0 100%);
        }

        body { font-family: 'Quicksand', sans-serif; background-color: var(--pink-soft); margin: 0; overflow-x: hidden; }
        
        .top-bg { 
            background: var(--gradient-pink); 
            height: 120px; 
            border-radius: 0 0 35px 35px; 
            position: absolute; 
            top: 0; 
            width: 100%; 
            z-index: -1; 
        }

        .container { padding-top: 15px; }

        /* Style Header Brand: Logo & Tulisan Diperbesar */
        .header-brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo-skm {
            width: 48px; /* Ukuran diperbesar */
            height: 48px;
            object-fit: cover;
            border-radius: 10px;
            border: 2px solid white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .brand-text {
            color: white;
            font-family: 'Fredoka';
            font-size: 1rem; /* Ukuran tulisan diperbesar */
            line-height: 1.2;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .profile-section {
            text-align: center;
            margin-top: 35px;
            margin-bottom: 30px;
        }

        .menu-card {
            background: white; 
            border-radius: 30px; 
            padding: 25px;
            text-align: center; 
            border: 1px solid #fff0f3; 
            transition: 0.3s;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            text-decoration: none; 
            color: #333; 
            display: block;
            margin-bottom: 20px;
        }
        
        .menu-card:hover { 
            transform: translateY(-5px); 
            box-shadow: 0 15px 30px rgba(255, 0, 128, 0.1); 
        }

        .icon-circle {
            width: 70px;
            height: 70px;
            background: var(--pink-soft);
            color: var(--pink-tua);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 2rem;
        }

        .info-card {
            background: white;
            border-radius: 25px;
            padding: 15px;
            border: 1px solid #fff0f3;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body>

<div class="top-bg"></div>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3 px-1">
        <div class="header-brand">
            <img src="uploads/logo_skm.jpeg" alt="Logo" class="logo-skm">
            <div class="brand-text">
                SKM HKBP AGAPE
            </div>
        </div>

        <a href="logout.php" class="btn text-white fs-2 p-0">
            <i class="bi bi-box-arrow-right"></i>
        </a>
    </div>

    <div class="profile-section">
        <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($nama_admin); ?>&background=fff&color=ff0080&bold=true" 
             class="rounded-circle mb-2 shadow-sm" width="80" style="border: 3px solid white;">
        <h4 class="fw-bold m-0 text-dark">Halo, Kak <?php echo $nama_admin; ?>!</h4>
        <p class="text-muted small">Panel Administrator GSM</p>
    </div>

    <div class="row px-2">
        <div class="col-6">
            <a href="data_guru.php" class="menu-card">
                <div class="icon-circle">
                    <i class="bi bi-people-fill"></i>
                </div>
                <span class="fw-bold d-block" style="font-size: 0.9rem;">Data Guru</span>
            </a>
        </div>

        <div class="col-6">
            <a href="rekap_absen.php" class="menu-card">
                <div class="icon-circle" style="background: #eafaf1; color: #c42121;">
                    <i class="bi bi-file-earmark-pdf-fill"></i>
                </div>
                <span class="fw-bold d-block" style="font-size: 0.9rem;">Kehadiran</span>
            </a>
        </div>
    </div>

    <footer class="text-center mt-5 pb-4">
        <p class="text-muted" style="font-size: 0.8rem;">
            Developed with ❤️ by <span class="fw-bold" style="color: var(--pink-tua);">Putri Elisabeth Silalahi</span> &copy; 2026
        </p>
    </footer>
</div>

</body>
</html>