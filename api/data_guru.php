<?php
session_start();
include 'koneksi.php';

// Proteksi Admin
if(!isset($_SESSION['role']) || strtolower($_SESSION['role']) != 'admin') {
    header("location:login.php");
    exit;
}

// Ambil data semua user dengan role selain admin
$query = mysqli_query($koneksi, "SELECT * FROM users WHERE role != 'admin' ORDER BY nama ASC");
$total_guru = mysqli_num_rows($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Guru - HKBP Agape</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@600;700&family=Quicksand:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            --pink-tua: #ff0080;
            --pink-soft: #fff5f7;
            --gradient-pink: linear-gradient(135deg, #ffafbd 0%, #ffc3a0 100%);
        }
        body { font-family: 'Quicksand', sans-serif; background-color: var(--pink-soft); margin: 0; overflow-x: hidden; }
        .top-bg { background: var(--gradient-pink); height: 110px; border-radius: 0 0 35px 35px; position: absolute; top: 0; width: 100%; z-index: -1; }
        .container { padding-top: 15px; padding-bottom: 40px; }
        .header-brand { display: flex; align-items: center; gap: 12px; }
        .logo-skm { width: 45px; height: 45px; border-radius: 10px; border: 2px solid white; object-fit: cover; }
        .brand-text { color: white; font-family: 'Fredoka'; font-size: 1rem; font-weight: 700; line-height: 1.2; }
        .main-card { background: white; border-radius: 30px; padding: 25px; margin-top: 35px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: 1px solid #fff0f3; }
        .total-banner { background: var(--pink-soft); color: var(--pink-tua); border-radius: 20px; padding: 20px; text-align: center; margin-bottom: 25px; border: 2px dashed #ffafbd; }
        .guru-item { display: flex; align-items: center; padding: 15px; border-bottom: 1px solid #f8f9fa; justify-content: space-between; }
        .guru-info { display: flex; align-items: center; gap: 15px; }
        .guru-photo { width: 55px; height: 55px; border-radius: 15px; object-fit: cover; border: 2px solid white; box-shadow: 0 3px 10px rgba(0,0,0,0.1); }
        .btn-preview { color: #0d6efd; background: #e7f0ff; border-radius: 12px; padding: 10px; border:none; transition: 0.3s; }
        .btn-preview:hover { background: #0d6efd; color: white; }
        .btn-hapus { color: #dc3545; background: #ffeef0; border-radius: 12px; padding: 10px; text-decoration: none; transition: 0.3s; }
        .btn-hapus:hover { background: #dc3545; color: white; }
        .search-box { background: #f8f9fa; border-radius: 15px; border: 1px solid #eee; padding: 12px 20px; width: 100%; margin-bottom: 20px; outline: none; }
        .btn-export { background: #27ae60; color: white; border-radius: 15px; padding: 10px 20px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; }
        .credit-text { color: #aaa; font-size: 0.8rem; letter-spacing: 0.5px; }
    </style>
</head>
<body>

<div class="top-bg"></div>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3 px-1">
        <div class="header-brand">
            <img src="uploads/logo_skm.jpeg" class="logo-skm">
            <div class="brand-text">DATA GURU AGAPE</div>
        </div>
        <a href="admin.php" class="btn text-white fs-3 p-0"><i class="bi bi-house-door-fill"></i></a>
    </div>

    <div class="main-card">
        <div class="total-banner">
            <p class="mb-0 text-muted small fw-bold">JUMLAH GURU SAAT INI</p>
            <h2 class="fw-bold m-0" style="font-family: 'Fredoka';"><?php echo $total_guru; ?> Orang</h2>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold m-0">Daftar Guru</h5>
            <a href="export_guru_pdf.php" class="btn-export shadow-sm"><i class="bi bi-file-earmark-pdf-fill"></i> Cetak PDF</a>
        </div>
        
        <input type="text" id="cariGuru" class="search-box" placeholder="Cari nama guru...">

        <div id="listGuru" class="mb-2">
            <?php while($row = mysqli_fetch_assoc($query)) : 
                $alamat = !empty($row['alamat']) ? $row['alamat'] : '-';
                $nohp   = !empty($row['no_hp']) ? $row['no_hp'] : '-';
                $foto   = (!empty($row['foto'])) ? $row['foto'] : 'default_profil.png';
            ?>
            <div class="guru-item">
                <div class="guru-info">
                    <img src="uploads/<?php echo $foto; ?>" class="guru-photo">
                    <div>
                        <h6 class="fw-bold m-0"><?php echo $row['nama']; ?></h6>
                        <small class="text-muted">ID: <?php echo $row['username']; ?></small>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn-preview" onclick="showPreview(
                        '<?php echo addslashes($row['nama']); ?>', 
                        '<?php echo addslashes($alamat); ?>', 
                        '<?php echo $nohp; ?>', 
                        'uploads/<?php echo $foto; ?>'
                    )">
                        <i class="bi bi-eye-fill"></i>
                    </button>
                    <a href="hapus_guru.php?id=<?php echo $row['id']; ?>" class="btn-hapus" onclick="return confirm('Hapus akun ini?')">
                        <i class="bi bi-trash-fill"></i>
                    </a>
                </div>
            </div>
            <?php endwhile; ?>
        </div>

        <p class="text-center mt-4 credit-text">
            Developed with ❤️ by Putri Elisabeth Silalahi &copy; 2026
        </p>
    </div>
</div>

<div class="modal fade" id="previewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 30px; border:none; overflow:hidden;">
            <div class="modal-body p-0">
                <div class="text-center p-4" style="background: var(--pink-soft);">
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <img id="modalFoto" src="" class="rounded-circle shadow-sm mb-3" style="width: 110px; height: 110px; object-fit: cover; border: 5px solid white;">
                    <h4 id="modalNama" class="fw-bold text-dark mb-0" style="font-family: 'Fredoka';"></h4>
                </div>
                <div class="p-4">
                    <div class="row mb-3">
                        <div class="col-1 text-primary"><i class="bi bi-geo-alt-fill fs-5"></i></div>
                        <div class="col-11 ps-3">
                            <label class="text-muted small d-block">Alamat Lengkap</label>
                            <span id="modalAlamat" class="fw-bold"></span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-1 text-success"><i class="bi bi-whatsapp fs-5"></i></div>
                        <div class="col-11 ps-3">
                            <label class="text-muted small d-block">Nomor HP / WA</label>
                            <span id="modalNoHp" class="fw-bold"></span>
                        </div>
                    </div>
                    <button type="button" class="btn w-100 mt-2 py-2" style="background: var(--pink-tua); color:white; border-radius: 15px; font-weight: 600;" data-bs-dismiss="modal">Tutup Detail</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function showPreview(nama, alamat, nohp, foto) {
        document.getElementById('modalNama').innerText = nama;
        document.getElementById('modalAlamat').innerText = alamat;
        document.getElementById('modalNoHp').innerText = nohp;
        document.getElementById('modalFoto').src = foto;
        var myModal = new bootstrap.Modal(document.getElementById('previewModal'));
        myModal.show();
    }

    document.getElementById('cariGuru').addEventListener('keyup', function(){
        let filter = this.value.toLowerCase();
        let items = document.querySelectorAll('.guru-item');
        items.forEach(function(item){
            let nama = item.querySelector('h6').textContent.toLowerCase();
            item.style.display = (nama.indexOf(filter) > -1) ? "" : "none";
        });
    });
</script>
</body>
</html>