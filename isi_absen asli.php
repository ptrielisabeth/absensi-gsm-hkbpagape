<?php
session_start();
include 'koneksi.php';

// Pastikan user sudah login
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['id'];
$nama_user = $_SESSION['nama']; 
$hari_ini = date('Y-m-d');
$hari_angka = date('w'); // 0 = Minggu, 3 = Rabu

// Logika penentuan jadwal
$is_jadwal = ($hari_angka == 0 || $hari_angka == 3);
$nama_hari = ($hari_angka == 0) ? "Minggu (Ibadah & Ceria)" : (($hari_angka == 3) ? "Rabu (Sermon)" : "Bukan Jadwal");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Isi Absensi - HKBP Agape</title>
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

        body { font-family: 'Quicksand', sans-serif; background-color: var(--pink-soft); margin: 0; overflow-x: hidden; }
        .top-bg { background: var(--gradient-dashboard); height: 110px; border-radius: 0 0 30px 30px; position: absolute; top: 0; width: 100%; z-index: -1; }
        .container { padding-top: 20px; }
        .form-card { background: white; border-radius: 30px; padding: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: 1px solid #fff0f3; }
        
        .camera-wrapper {
            width: 100%; max-width: 320px; height: 240px; background: #000;
            border-radius: 20px; overflow: hidden; border: 4px solid var(--pink-muda);
            margin: 0 auto 10px auto; position: relative; display: flex; align-items: center; justify-content: center;
        }

        #video { width: 100% !important; height: 100% !important; object-fit: contain !important; transform: scaleX(-1); }
        #photo-preview { width: 100% !important; height: 100% !important; object-fit: contain !important; }

        .nb-text {
            background: #fff0f3; color: var(--pink-tua); padding: 10px;
            border-radius: 12px; font-size: 0.75rem; font-weight: 700;
            margin: 0 auto 15px auto; border-left: 4px solid var(--pink-tua); max-width: 320px;
        }

        .btn-capture {
            background: white; color: var(--pink-tua); border: 2px solid var(--pink-tua);
            padding: 10px; border-radius: 15px; width: 100%; max-width: 320px;
            font-weight: 700; font-size: 0.9rem; margin: 0 auto 20px auto; display: block;
        }

        .form-label { font-weight: 700; color: #555; font-size: 0.8rem; text-transform: uppercase; display: flex; align-items: center; gap: 8px; margin-top: 10px; }
        .form-control { border-radius: 15px; border: 1px solid var(--pink-muda); padding: 12px; font-size: 0.95rem; }
        .readonly-input { background-color: #f8f9fa !important; color: #888; cursor: not-allowed; }
        
        .status-options { display: flex; gap: 8px; margin-bottom: 10px; flex-wrap: wrap; }
        .status-options input { display: none; }
        .status-options label { 
            flex: 1; min-width: 80px; text-align: center; padding: 12px 5px; border-radius: 15px; 
            border: 1px solid var(--pink-muda); cursor: pointer; font-weight: 700; font-size: 0.75rem; transition: 0.3s;
            background: white; color: #777;
        }

        #status_hadir:checked + label { background: #2ecc71; color: white; border-color: #2ecc71; }
        #status_izin:checked + label { background: #f1c40f; color: white; border-color: #f1c40f; }
        #status_sakit:checked + label { background: #e74c3c; color: white; border-color: #e74c3c; }

        /* Warna untuk jenis kegiatan */
        #jenis_ibadah:checked + label { background: #3498db; color: white; border-color: #3498db; }
        #jenis_ceria:checked + label { background: #9b59b6; color: white; border-color: #9b59b6; }
        #jenis_sermon:checked + label { background: #e67e22; color: white; border-color: #e67e22; }

        .btn-kirim { 
            background: var(--gradient-dashboard); color: white; border: none; padding: 18px; 
            border-radius: 20px; width: 100%; font-family: 'Fredoka', sans-serif; 
            font-weight: 600; font-size: 1.1rem; margin-top: 20px;
            box-shadow: 0 10px 20px rgba(255, 175, 189, 0.4);
        }
    </style>
</head>
<body>

<div class="top-bg"></div>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="dashboard_gsm.php" class="btn text-white fs-4"><i class="bi bi-arrow-left"></i></a>
        <h5 class="text-white m-0 fw-bold" style="font-family: 'Fredoka';">Presensi Guru</h5>
        <div style="width: 40px;"></div>
    </div>

    <?php if (!$is_jadwal): ?>
        <div class="form-card text-center py-5">
            <i class="bi bi-calendar-x-fill display-1 text-danger opacity-25"></i>
            <h5 class="fw-bold mt-3">Maaf, Presensi Ditutup</h5>
            <p class="text-muted small px-3">Hanya tersedia pada hari <b>Minggu</b> (Ibadah & Minggu Ceria) dan <b>Rabu</b> (Sermon).</p>
            <a href="dashboard_gsm.php" class="btn btn-outline-secondary mt-2 px-4" style="border-radius: 12px;">Kembali</a>
        </div>
    <?php else: ?>
        <div class="form-card">
            <div class="camera-wrapper">
                <video id="video" autoplay playsinline></video>
                <img id="photo-preview" style="display:none;">
                <canvas id="canvas" style="display:none;"></canvas>
            </div>

            <div class="nb-text">
                <i class="bi bi-info-circle-fill me-1"></i> 
                Hari ini: <b><?= $nama_hari ?></b>. <br>
                Jika tidak absen, sistem mencatat <b>ALPA</b> secara otomatis.
            </div>

            <button type="button" id="btn-capture" class="btn-capture">
                <i class="bi bi-camera-fill me-2"></i> AMBIL FOTO
            </button>
            
            <form id="absenForm">
                <input type="hidden" name="user_id" value="<?= $user_id; ?>">
                <input type="hidden" name="image" id="image_data">

                <label class="form-label mb-2"><i class="bi bi-bookmark-star-fill"></i> Jenis Kegiatan</label>
                <div class="status-options">
                    <?php if ($hari_angka == 0): // Khusus Minggu ?>
                        <input type="radio" name="jenis_kegiatan" id="jenis_ibadah" value="Ibadah Minggu" checked>
                        <label for="jenis_ibadah">IBADAH MINGGU</label>
                        <input type="radio" name="jenis_kegiatan" id="jenis_ceria" value="Minggu Ceria">
                        <label for="jenis_ceria">MINGGU CERIA</label>
                    <?php elseif ($hari_angka == 3): // Khusus Rabu ?>
                        <input type="radio" name="jenis_kegiatan" id="jenis_sermon" value="Sermon" checked>
                        <label for="jenis_sermon">SERMON</label>
                    <?php endif; ?>
                </div>

                <label class="form-label mb-2"><i class="bi bi-person-check-fill"></i> Status Kehadiran</label>
                <div class="status-options">
                    <input type="radio" name="status" id="status_hadir" value="Hadir" checked>
                    <label for="status_hadir">HADIR</label>
                    <input type="radio" name="status" id="status_izin" value="Izin">
                    <label for="status_izin">IZIN</label>
                    <input type="radio" name="status" id="status_sakit" value="Sakit">
                    <label for="status_sakit">SAKIT</label>
                </div>

                <div class="mb-3">
                    <label class="form-label"><i class="bi bi-calendar3"></i> Tanggal Absensi (Locked)</label>
                    <input type="date" name="tanggal" class="form-control readonly-input" 
                           value="<?= $hari_ini; ?>" min="<?= $hari_ini; ?>" max="<?= $hari_ini; ?>" readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label"><i class="bi bi-chat-left-text"></i> Keterangan</label>
                    <textarea name="keterangan" class="form-control" rows="3" placeholder="Tuliskan keterangan ..."></textarea>
                </div>

                <button type="submit" id="btn-submit" class="btn-kirim">
                    <i class="bi bi-send-fill me-2"></i> KIRIM ABSENSI
                </button>
            </form>
        </div>
    <?php endif; ?>

    <footer class="text-center mt-5 pb-4">
        <p class="text-muted" style="font-size: 0.8rem; font-family: 'Quicksand', sans-serif;">
            Developed with ❤️ by <br> 
            <span class="fw-bold" style="color: var(--pink-tua);">Putri Elisabeth Silalahi</span> &copy; 2026
        </p>
    </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // JS Script untuk Kamera & Submit (tetap sama)
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const preview = document.getElementById('photo-preview');
    const btnCapture = document.getElementById('btn-capture');
    const imageDataInput = document.getElementById('image_data');

    <?php if ($is_jadwal): ?>
    navigator.mediaDevices.getUserMedia({ video: { facingMode: "user" }, audio: false })
        .then(stream => { video.srcObject = stream; })
        .catch(err => { alert("Harap izinkan akses kamera!"); });

    btnCapture.addEventListener('click', () => {
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        const context = canvas.getContext('2d');
        context.translate(canvas.width, 0);
        context.scale(-1, 1);
        context.drawImage(video, 0, 0, canvas.width, canvas.height);
        const data_uri = canvas.toDataURL('image/jpeg', 0.9);
        preview.src = data_uri;
        preview.style.display = 'block';
        video.style.display = 'none';
        imageDataInput.value = data_uri;
        btnCapture.innerHTML = '<i class="bi bi-arrow-clockwise me-2"></i> ULANG FOTO';
    });

    document.getElementById('absenForm').addEventListener('submit', function(e) {
        e.preventDefault();
        if (!imageDataInput.value) {
            Swal.fire('Opps!', 'Ambil foto selfie dulu ya!', 'warning');
            return;
        }

        Swal.fire({ title: 'Memproses Absen...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });

        const formData = new FormData(this);
        fetch('proses_absen.php', { method: 'POST', body: formData })
        .then(response => response.text())
        .then(result => {
            if(result.trim() === "success") {
                Swal.fire({ icon: 'success', title: 'Berhasil Terkirim!', timer: 2000, showConfirmButton: false })
                .then(() => { window.location.href = 'dashboard_gsm.php'; });
            } else {
                Swal.fire('Gagal', result, 'error');
            }
        })
        .catch(error => { Swal.fire('Error', 'Gagal kirim data', 'error'); });
    });
    <?php endif; ?>
</script>

</body>
</html>