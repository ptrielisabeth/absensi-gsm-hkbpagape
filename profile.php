<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['id'];
$query = mysqli_query($koneksi, "SELECT * FROM users WHERE id = '$user_id'");
$data = mysqli_fetch_assoc($query);

$foto_path = !empty($data['foto']) ? "uploads/" . $data['foto'] : "https://via.placeholder.com/150";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - HKBP Agape</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600;700&family=Quicksand:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            --pink-tua: #ff0080;
            --pink-soft: #fff5f7;
            --pink-muda: #ffe4e9;
            --gradient-dashboard: linear-gradient(135deg, #ffafbd 0%, #ffc3a0 100%);
        }

        body {
            font-family: 'Quicksand', sans-serif;
            background-color: var(--pink-soft);
            margin: 0;
        }

        .top-bg {
            background: var(--gradient-dashboard);
            height: 200px;
            border-radius: 0 0 35px 35px;
            position: absolute;
            top: 0;
            width: 100%;
            z-index: -1;
            box-shadow: 0 5px 15px rgba(255, 175, 189, 0.4);
        }

        .container { padding-top: 30px; }

        .btn-circle {
            width: 45px; height: 45px;
            background: rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: white; text-decoration: none;
            border: 1px solid rgba(255, 255, 255, 0.4);
        }

        .main-card {
            background: white;
            border-radius: 35px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.06);
            padding: 30px 20px;
            text-align: center;
            margin-top: 55px;
            border: 1px solid #fff0f3;
        }

        .profile-img-box img {
            width: 120px; height: 120px;
            object-fit: cover; border-radius: 35px; 
            margin-top: -85px; border: 5px solid white;
            box-shadow: 0 10px 20px rgba(255, 0, 128, 0.1);
        }

        .name-title {
            font-family: 'Fredoka', sans-serif;
            font-weight: 700; color: #333;
            margin-top: 15px; margin-bottom: 5px;
        }

        .role-badge {
            background: var(--pink-soft);
            color: var(--pink-tua);
            padding: 5px 15px; border-radius: 12px;
            font-weight: 700; font-size: 0.85rem;
            display: inline-block; border: 1px solid #ffe4e9;
        }

        .info-container { margin-top: 30px; text-align: left; }

        .info-row {
            background: #fdfdfd; border: 1px solid #f1f1f1;
            padding: 15px; border-radius: 20px;
            display: flex; align-items: center;
            margin-bottom: 12px; transition: 0.3s;
        }

        .info-row:hover {
            border-color: var(--pink-tua);
            transform: translateX(5px);
        }

        .icon-box {
            width: 45px; height: 45px;
            background: var(--pink-soft); color: var(--pink-tua);
            border-radius: 15px; display: flex;
            align-items: center; justify-content: center;
            font-size: 1.2rem; margin-right: 15px;
        }

        .info-content label {
            display: block; font-size: 0.75rem;
            color: #bbb; font-weight: 700;
            text-transform: uppercase;
        }

        .info-content span { font-weight: 600; color: #444; }

        .btn-edit-trigger {
            background: var(--gradient-dashboard);
            color: white; border: none; padding: 15px;
            border-radius: 20px; width: 100%;
            font-family: 'Fredoka', sans-serif;
            font-weight: 600; margin-top: 25px;
            box-shadow: 0 10px 20px rgba(255, 175, 189, 0.4);
            display: flex; align-items: center; justify-content: center; gap: 10px;
            text-decoration: none;
        }

        /* Modal Style */
        .modal-content { border-radius: 30px; border: none; }
        .modal-header { border-bottom: none; padding: 25px 25px 10px; }
        .form-control {
            border-radius: 15px; border: 1px solid var(--pink-muda);
            padding: 12px; font-size: 0.9rem;
        }
        .form-control:focus {
            box-shadow: 0 0 0 0.25rem rgba(255, 0, 128, 0.1);
            border-color: var(--pink-tua);
        }
    </style>
</head>
<body>

    <div class="top-bg"></div>

    <div class="container">
        <div class="nav-top">
            <a href="dashboard_gsm.php" class="btn-circle">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h5 class="text-white m-0 fw-bold" style="font-family: 'Fredoka';">Profil Saya</h5>
            <div style="width: 45px;"></div> 
        </div>

        <div class="main-card">
            <div class="profile-img-box">
                <img src="<?= $foto_path; ?>" alt="User Photo">
            </div>
            
            <h3 class="name-title"><?= $data['nama']; ?></h3>
            <div class="role-badge">@<?= $data['username']; ?></div>

            <div class="info-container">
                <div class="info-row">
                    <div class="icon-box"><i class="bi bi-whatsapp"></i></div>
                    <div class="info-content">
                        <label>Nomor WhatsApp</label>
                        <span><?= isset($data['no_hp']) ? $data['no_hp'] : '-'; ?></span>
                    </div>
                </div>

                <div class="info-row">
                    <div class="icon-box"><i class="bi bi-calendar-heart"></i></div>
                    <div class="info-content">
                        <label>Tempat, Tanggal Lahir</label>
                        <span><?= isset($data['ttl']) ? $data['ttl'] : '-'; ?></span>
                    </div>
                </div>

                <div class="info-row">
                    <div class="icon-box"><i class="bi bi-geo-alt-fill"></i></div>
                    <div class="info-content">
                        <label>Alamat Tinggal</label>
                        <span><?= isset($data['alamat']) ? $data['alamat'] : '-'; ?></span>
                    </div>
                </div>

                <div class="info-row">
                    <div class="icon-box"><i class="bi bi-shield-check"></i></div>
                    <div class="info-content">
                        <span><?= isset($data['role']) ? $data['role'] : 'Anggota'; ?> HKBP Agape</span>
                    </div>
                </div>
            </div>

            <button class="btn-edit-trigger" data-bs-toggle="modal" data-bs-target="#modalEdit">
                <i class="bi bi-pencil-square"></i>
                <span>Edit Profil</span>
            </button>
        </div>

        <p class="text-center mt-4 text-muted small">
            Developed with ❤️ by Putri Elisabeth Silalahi &copy; 2026
        </div>
        </p>
    </div>

    <div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="fw-bold" style="font-family: 'Fredoka'; color: var(--pink-tua);">Ubah Biodata</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="update_profile.php" method="POST">
                        <div class="mb-3 text-start">
                            <label class="small fw-bold mb-1">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" value="<?= isset($data['nama']) ? $data['nama'] : ''; ?>" required>
                        </div>
                        <div class="mb-3 text-start">
                            <label class="small fw-bold mb-1">No. WhatsApp</label>
                            <input type="text" name="no_hp" class="form-control" value="<?= isset($data['no_hp']) ? $data['no_hp'] : ''; ?>">
                        </div>
                        <div class="mb-3 text-start">
                            <label class="small fw-bold mb-1">Tempat, Tanggal Lahir</label>
                            <input type="text" name="ttl" class="form-control" value="<?= isset($data['ttl']) ? $data['ttl'] : ''; ?>" placeholder="Contoh: Batam, 12 Maret 2000">
                        </div>
                        <div class="mb-3 text-start">
                            <label class="small fw-bold mb-1">Alamat</label>
                            <textarea name="alamat" class="form-control" rows="2"><?= isset($data['alamat']) ? $data['alamat'] : ''; ?></textarea>
                        </div>
                        <button type="submit" class="btn-edit-trigger">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>