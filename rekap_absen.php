<?php
session_start();
include 'koneksi.php';

// 1. Proteksi Admin
if(!isset($_SESSION['role']) || strtolower($_SESSION['role']) != 'admin') {
    header("location:login.php");
    exit;
}

// 2. Ambil Filter Tahun
$tahun_pilih = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');

// 3. Query Data
$sql = "SELECT absensi_gsm.*, users.nama 
        FROM absensi_gsm 
        LEFT JOIN users ON absensi_gsm.user_id = users.id 
        WHERE YEAR(absensi_gsm.tanggal) = '$tahun_pilih'
        ORDER BY absensi_gsm.tanggal DESC";

$query = mysqli_query($koneksi, $sql);
$total_data = mysqli_num_rows($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Kehadiran - HKBP Agape</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@600;700&family=Quicksand:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            --pink-tua: #ff0080;
            --pink-soft: #fff5f7;
            --gradient-pink: linear-gradient(135deg, #ffafbd 0%, #ffc3a0 100%);
        }
        body { font-family: 'Quicksand', sans-serif; background-color: var(--pink-soft); margin: 0; min-height: 100vh; }
        .top-bg { background: var(--gradient-pink); height: 120px; border-radius: 0 0 40px 40px; position: absolute; top: 0; width: 100%; z-index: -1; }
        .container { padding-top: 25px; padding-bottom: 50px; }
        .header-title { font-family: 'Fredoka'; font-size: 1.3rem; color: white; font-weight: 700; }
        .main-card { background: white; border-radius: 30px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        
        .filter-box { background: var(--pink-soft); border-radius: 20px; padding: 15px; margin-bottom: 25px; border: 1px dashed #ffafbd; }
        .btn-cari { background: var(--pink-tua); color: white; border-radius: 12px; font-weight: 700; border: none; padding: 10px; transition: 0.3s; }
        .btn-cari:hover { background: #e60073; transform: scale(1.02); }

        .badge-status { padding: 8px 12px; border-radius: 12px; font-weight: 700; font-size: 0.7rem; }
        .status-hadir { background: #e8fff3; color: #10b981; }
        .status-izin { background: #fff8e6; color: #f59e0b; }
        .status-alfa { background: #fff0f0; color: #ef4444; }
        
        .badge-kegiatan-tabel { background: #f0f2f5; color: #4b5563; padding: 6px 10px; border-radius: 10px; font-size: 0.7rem; font-weight: 600; border: 1px solid #e5e7eb; }

        .btn-delete-absen { color: #dc3545; background: #ffeef0; border-radius: 10px; padding: 6px 10px; border: none; transition: 0.3s; text-decoration: none; }
        .btn-delete-absen:hover { background: #dc3545; color: white; }

        .footer-dev { text-align: center; margin-top: 30px; color: #888; font-size: 0.85rem; font-weight: 600; }
        .footer-dev span { color: var(--pink-tua); }
    </style>
</head>
<body>

<div class="top-bg"></div>

<div class="container">
    <div class="header-section d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-white p-2 rounded-3 shadow-sm">
                <i class="bi bi-calendar-check-fill fs-3" style="color: var(--pink-tua);"></i>
            </div>
            <div class="header-title">REKAP TAHUNAN GSM AGAPE</div>
        </div>
        <a href="admin.php" class="btn text-white fs-1 p-0"><i class="bi bi-house-door-fill"></i></a>
    </div>

    <div class="main-card">
        <div class="filter-box">
            <form method="GET" class="row g-2 align-items-end justify-content-center">
                <div class="col-8">
                    <label class="small fw-bold text-muted mb-1">Pilih Tahun</label>
                    <select name="tahun" class="form-select rounded-pill shadow-sm">
                        <?php 
                        for($x = 2025; $x <= 2030; $x++) {
                            echo "<option value='$x' ".($x == $tahun_pilih ? 'selected' : '').">Tahun $x</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-4">
                    <button type="submit" class="btn-cari w-100 shadow-sm">Cari</button>
                </div>
            </form>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h6 class="fw-bold m-0 text-muted small">Menampilkan Tahun: <?php echo $tahun_pilih; ?></h6>
            <?php if($total_data > 0) : ?>
                <a href="download_rekap.php?tahun=<?php echo $tahun_pilih; ?>" class="btn btn-danger btn-sm rounded-pill px-3 fw-bold shadow-sm">
                    <i class="bi bi-file-earmark-pdf-fill me-1"></i> PDF
                </a>
            <?php endif; ?>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="text-center text-uppercase">
                    <tr>
                        <th style="font-size: 0.7rem;">Tanggal</th>
                        <th style="font-size: 0.7rem;">Nama Lengkap</th>
                        <th style="font-size: 0.7rem;">Status</th>
                        <th style="font-size: 0.7rem;">Kegiatan</th>
                        <th style="font-size: 0.7rem;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($total_data > 0) : ?>
                        <?php while($row = mysqli_fetch_assoc($query)) : 
                            $st = strtolower($row['status_kehadiran']);
                            $badge = ($st == 'hadir') ? 'status-hadir' : (($st == 'izin' || $st == 'sakit') ? 'status-izin' : 'status-alfa');
                            // Sesuaikan nama kolom database 'jenis_kegiatan' atau 'kegiatan'
                            $kegiatan = !empty($row['jenis_kegiatan']) ? $row['jenis_kegiatan'] : '-';
                        ?>
                        <tr>
                            <td class="text-center fw-bold" style="color: var(--pink-tua); font-size: 0.85rem;">
                                <?php echo date('d/m/Y', strtotime($row['tanggal'])); ?>
                            </td>
                            <td>
                                <span class="fw-bold text-dark" style="font-size: 0.9rem;"><?php echo $row['nama']; ?></span>
                            </td>
                            <td class="text-center">
                                <span class="badge-status <?php echo $badge; ?> text-capitalize">
                                    <?php echo $st; ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge-kegiatan-tabel">
                                    <?php echo $kegiatan; ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="hapus_absen.php?id=<?php echo $row['id']; ?>&tahun=<?php echo $tahun_pilih; ?>" 
                                   class="btn-delete-absen" 
                                   onclick="return confirm('Yakin ingin menghapus data ini?')">
                                    <i class="bi bi-trash3-fill"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="5" class="text-center p-5 text-muted">
                                <i class="bi bi-emoji-frown fs-2 d-block mb-2"></i>
                                Belum ada data untuk tahun <?php echo $tahun_pilih; ?>.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="footer-dev">
        Developed with ❤️ by <span>Putri Elisabeth Silalahi</span> &copy; 2026
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>