<?php
include 'koneksi.php';

$tahun = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');

// Query Ambil Data
$sql = "SELECT absensi_gsm.*, users.nama 
        FROM absensi_gsm 
        LEFT JOIN users ON absensi_gsm.user_id = users.id 
        WHERE YEAR(absensi_gsm.tanggal) = '$tahun'
        ORDER BY absensi_gsm.tanggal DESC";

$query = mysqli_query($koneksi, $sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Rekap Absensi GSM HKPB Agape <?php echo $tahun; ?></title>
    <style>
        body { font-family: 'Arial', sans-serif; padding: 20px; color: #333; }
        .header { text-align: center; border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 20px; }
        .header h2, .header h3, .header p { margin: 5px 0; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table th, table td { border: 1px solid #000; padding: 8px; text-align: center; font-size: 11pt; }
        table th { background-color: #f2f2f2; text-transform: uppercase; }
        
        .footer { margin-top: 30px; text-align: right; font-size: 11pt; }
        .ttd { margin-top: 70px; font-weight: bold; text-decoration: underline; }

        /* Style khusus saat dicetak */
        @media print {
            .no-print { display: none; }
            body { padding: 0; }
        }
    </style>
</head>
<body>

    <div class="no-print" style="margin-bottom: 20px; text-align: right;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #ff0080; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;">
            Klik Disini untuk Simpan sebagai PDF
        </button>
        <button onclick="window.history.back()" style="padding: 10px 20px; background: #666; color: white; border: none; border-radius: 5px; cursor: pointer;">
            Kembali
        </button>
    </div>

    <div class="header">
        <h2>DAFTAR KEHADIRAN GURU SEKOLAH MINGGU TAHUN <?php echo $tahun; ?></h2>
        <h3>HKBP AGAPE RESSORT BARELANG</h3>
        <p>DISTRIK XX KEPULAUAN RIAU</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="20%">TANGGAL</th>
                <th width="35%">NAMA GURU</th>
                <th width="15%">STATUS</th>
                <th width="25%">KEGIATAN</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            if(mysqli_num_rows($query) > 0) {
                while($row = mysqli_fetch_assoc($query)) {
                    echo "<tr>";
                    echo "<td>".$no++."</td>";
                    echo "<td>".date('d/m/Y', strtotime($row['tanggal']))."</td>";
                    echo "<td style='text-align: left;'>".($row['nama'] ?? 'ID: '.$row['user_id'])."</td>";
                    echo "<td>".strtoupper($row['status_kehadiran'])."</td>";
                    // Jika ada kolom kegiatan di tabel kamu, pakai $row['kegiatan']. Jika belum ada, kita kasih strip (-) dulu
                    echo "<td>".(isset($row['jenis_kegiatan']) ? $row['jenis_kegiatan'] : '-')."</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>Tidak ada data pada tahun $tahun</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <div class="footer">
        <p>Batam, <?php echo date('d F Y'); ?></p>
        <p>Mengetahui,</p>
        <br><br>
        <p class="ttd">Pengurus GSM HKBP Agape</p>
    </div>

</body>
</html>