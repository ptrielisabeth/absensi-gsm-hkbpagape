<?php
session_start();
include 'koneksi.php';

// Proteksi halaman
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['id'];

// Ambil filter dari URL, jika tidak ada pakai bulan & tahun sekarang sebagai default
$bulan_pilih = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');
$tahun_pilih = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');

// Query untuk mengambil data sesuai filter bulan dan tahun
$sql = "SELECT * FROM absensi_gsm 
        WHERE user_id = '$user_id' 
        AND MONTH(tanggal) = '$bulan_pilih' 
        AND YEAR(tanggal) = '$tahun_pilih' 
        ORDER BY tanggal DESC, waktu_input DESC";

$query = mysqli_query($koneksi, $sql);

// Daftar nama bulan
$nama_bulan = [
    '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
    '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
    '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
];

// Daftar tahun manual sesuai permintaan
$daftar_tahun = ['2026', '2027', '2028', '2029', '2030'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Absensi - HKBP Agape</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@600&family=Quicksand:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            --pink-tua: #ff0080;
            --soft-pink: #fff5f7;
            --pink-muda: #ffe4e9;
        }
        body { font-family: 'Quicksand', sans-serif; background-color: var(--soft-pink); }
        
        .header-pink { 
            background: linear-gradient(135deg, #ffafbd 0%, #ffc3a0 100%); 
            padding: 25px 15px; 
            border-radius: 0 0 30px 30px; 
            color: white; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .filter-card {
            background: white;
            border-radius: 20px;
            padding: 20px;
            margin-top: -25px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.05);
            border: none;
        }

        .card-riwayat {
            background: white;
            border-radius: 15px;
            border: none;
            margin-bottom: 12px;
            transition: 0.3s;
            border-left: 5px solid transparent;
        }
        .border-hadir { border-left-color: #2ecc71; }
        .border-izin { border-left-color: #f1c40f; }
        .border-sakit { border-left-color: #e74c3c; }
        .border-alpa { border-left-color: #95a5a6; }

        .img-preview {
            width: 65px;
            height: 65px;
            object-fit: cover;
            border-radius: 12px;
            border: 1px solid #eee;
        }

        .btn-filter {
            background: var(--pink-tua);
            color: white;
            border-radius: 12px;
            border: none;
            padding: 10px;
            width: 100%;
        }
        .btn-filter:hover { background: #e0115f; color: white; }
    </style>
</head>
<body>

<div class="header-pink d-flex align-items-center gap-3">
    <a href="dashboard_gsm.php" class="text-white fs-3"><i class="bi bi-arrow-left"></i></a>
    <h4 class="m-0 fw-bold" style="font-family: 'Fredoka';">Riwayat Absensi</h4>
</div>

<div class="container mt-3">
    <div class="filter-card mb-4">
        <form method="GET" action="" class="row g-2">
            <div class="col-6">
                <label class="small fw-bold text-muted">Bulan</label>
                <select name="bulan" class="form-select bg-light border-0">
                    <?php foreach ($nama_bulan as $m => $nama): ?>
                        <option value="<?= $m; ?>" <?= $bulan_pilih == $m ? 'selected' : ''; ?>><?= $nama; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-6">
                <label class="small fw-bold text-muted">Tahun</label>
                <select name="tahun" class="form-select bg-light border-0">
                    <?php foreach ($daftar_tahun as $thn): ?>
                        <option value="<?= $thn; ?>" <?= $tahun_pilih == $thn ? 'selected' : ''; ?>><?= $thn; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12 mt-3">
                <button type="submit" class="btn btn-filter fw-bold">
                    <i class="bi bi-funnel-fill me-2"></i>Tampilkan Data
                </button>
            </div>
        </form>
    </div>

    <div class="px-2 mb-3">
        <h6 class="fw-bold m-0">Periode: <?= $nama_bulan[$bulan_pilih] ?> <?= $tahun_pilih ?></h6>
        <p class="text-muted small">Ditemukan <?= mysqli_num_rows($query) ?> data kehadiran.</p>
    </div>

    <?php if (mysqli_num_rows($query) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($query)): 
            $status = $row['status_kehadiran'];
            // Logika Border & Warna Badge
            $class_border = 'border-alpa';
            $badge_color = 'bg-secondary';
            
            if($status == 'Hadir') { $class_border = 'border-hadir'; $badge_color = 'bg-success'; }
            elseif($status == 'Izin') { $class_border = 'border-izin'; $badge_color = 'bg-warning text-dark'; }
            elseif($status == 'Sakit') { $class_border = 'border-sakit'; $badge_color = 'bg-danger'; }
        ?>
            <div class="card card-riwayat p-3 <?= $class_border ?> shadow-sm">
                <div class="d-flex align-items-start gap-3">
                    <?php if(!empty($row['foto'])): ?>
                        <img src="uploads/<?= $row['foto']; ?>" class="img-preview" alt="Foto">
                    <?php else: ?>
                        <div class="img-preview bg-light d-flex align-items-center justify-content-center text-muted">
                            <i class="bi bi-camera fs-4"></i>
                        </div>
                    <?php endif; ?>
                    
                    <div class="flex-grow-1">
                        <div class="fw-bold text-dark" style="font-size: 0.95rem; line-height: 1.2;">
                            <?= $row['jenis_kegiatan']; ?>
                        </div>
                        <div class="text-muted small mb-1" style="font-size: 0.75rem;">
                            <i class="bi bi-calendar-event me-1"></i> <?= date('d M Y', strtotime($row['tanggal'])); ?>
                        </div>
                        
                        <span class="badge rounded-pill <?= $badge_color ?> mb-1" style="font-size: 0.65rem; padding: 4px 10px;">
                            <?= $status ?>
                        </span>

                        <?php if(!empty($row['keterangan'])): ?>
                            <div class="mt-1 p-2 bg-light rounded" style="font-size: 0.8rem; border-left: 2px solid #ddd;">
                                <span class="text-muted italic" style="font-style: italic;">
                                    <i class="bi bi-info-circle me-1"></i> <?= $row['keterangan']; ?>
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="text-center mt-5 py-5">
            <i class="bi bi-folder2-open display-1 text-muted opacity-25"></i>
            <p class="text-muted mt-3">Tidak ada data untuk periode ini.</p>
        </div>
    <?php endif; ?>
    <div class="footer-dev" style="margin-top: 60px; padding-bottom: 30px; text-align: center; font-size: 0.85rem; color: #aaa;">
            Developed with ❤️ by **Putri Elisabeth Silalahi** &copy; 2026
        </div>
</div>

<div class="pb-5"></div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>