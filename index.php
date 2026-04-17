<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Presensi GSM - HKBP Agape</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600;700&family=Quicksand:wght@500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --pink-muda: #ffdde1;
            --pink-cerah: #ffafbd;
            --pink-tua: #ff0080;
            --fuschia: #e0115f;
            --warna-teks: #4a4a4a;
        }

        body {
            font-family: 'Quicksand', sans-serif;
            background: linear-gradient(135deg, #fff5f7 0%, #ffe4e9 100%);
            color: var(--warna-teks);
            overflow-x: hidden;
            min-height: 100vh;
        }

        h1, h2, h5, .btn-absen, .semangat-text {
            font-family: 'Fredoka', sans-serif;
        }

        .hero-section {
            padding: 40px 0;
            position: relative;
        }

        .logo-frame {
            display: inline-flex;
            align-items: center;
            background: white;
            padding: 15px 30px;
            border-radius: 40px;
            box-shadow: 0 15px 35px rgba(224, 17, 95, 0.1);
            margin-bottom: 20px;
            border: 4px solid var(--pink-cerah);
        }

        .logo-img {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 50%;
        }

        .pink-sparkle {
            font-size: 24px;
            margin: 0 15px;
            color: var(--pink-tua);
        }

        .gsm-tag {
            color: var(--pink-tua);
            font-weight: 700;
            letter-spacing: 2px;
            font-size: 1.1rem;
            margin-bottom: 5px;
        }

        .judul-utama {
            color: var(--fuschia);
            font-size: 2.8rem;
            font-weight: 700;
            margin-bottom: 0px;
            line-height: 1.2;
        }

        .lokasi-info {
            color: #d63384;
            font-weight: 700;
            font-size: 1.2rem;
            margin-bottom: 20px;
        }

        .semangat-text {
            color: var(--pink-tua);
            font-size: 2.2rem;
            font-weight: 600;
            margin-bottom: 35px;
            background: rgba(255, 255, 255, 0.5);
            display: inline-block;
            padding: 5px 25px;
            border-radius: 30px;
        }

        .btn-absen {
            background: linear-gradient(to right, var(--pink-tua), var(--fuschia));
            color: white;
            font-size: 1.4rem;
            padding: 15px 60px;
            border-radius: 50px;
            border: none;
            box-shadow: 0 10px 20px rgba(224, 17, 95, 0.3);
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-absen:hover {
            transform: scale(1.05);
            color: white;
            box-shadow: 0 15px 30px rgba(224, 17, 95, 0.4);
        }

        .kartu-pink {
            border: none;
            border-radius: 30px;
            padding: 25px;
            background: white;
            box-shadow: 0 10px 20px rgba(224, 17, 95, 0.05);
            border-bottom: 6px solid var(--pink-cerah);
            height: 100%;
        }

        footer {
            margin-top: 50px;
            padding: 30px 0;
            background: rgba(255, 175, 189, 0.1);
            border-top: 2px dashed var(--pink-cerah);
        }
    </style>
</head>
<body>

    <section class="hero-section text-center">
        <div class="container">
            <div class="logo-frame">
                <img src="uploads/logo_hkbp.png" alt="Logo HKBP" class="logo-img">
                <span class="pink-sparkle">💕</span>
                <img src="uploads/logo_skm.jpeg" alt="Logo SKM" class="logo-img">
            </div>
            
            <div class="gsm-tag text-uppercase">GURU SEKOLAH MINGGU</div>
            <h1 class="judul-utama">HKBP AGAPE RESSORT BARELANG</h1>
            <div class="lokasi-info text-uppercase">DISTRIK XX KEPRI</div>
            
            <div class="semangat-text">Semangat Melayani! 😇</div>
            
            <br>
            <div class="mb-5">
                <a href="login.php" class="btn-absen shadow-lg">Presensi Sekarang 📸</a>
            </div>
        </div>
    </section>

    <div class="container">
        <div class="row g-4 text-center">
            <div class="col-md-4">
                <div class="kartu-pink">
                    <span style="font-size: 40px;">📖</span>
                    <h5 class="fw-bold mt-2" style="color: var(--pink-tua);">Siapkan Hati</h5>
                    <p class="text-muted small">Layani adik-adik dengan kasih Kristus.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="kartu-pink">
                    <span style="font-size: 40px;">🤳</span>
                    <h5 class="fw-bold mt-2" style="color: var(--pink-tua);">Ambil Selfie</h5>
                    <p class="text-muted small">Cukup satu foto untuk tanda kehadiran.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="kartu-pink">
                    <span style="font-size: 40px;">💒</span>
                    <h5 class="fw-bold mt-2" style="color: var(--pink-tua);">Ladang Berkat</h5>
                    <p class="text-muted small">Setiap langkahmu berharga di mata Tuhan.</p>
                </div>
            </div>
        </div>
    </div>

    <footer class="text-center">
        <p class="small text-muted mb-0">Developed with 💖 by <strong>Putri Elisabeth Silalahi</strong> &copy; 2026</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>