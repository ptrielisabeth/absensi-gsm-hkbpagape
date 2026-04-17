<?php
session_start();
include 'koneksi.php';

require_once 'vendor/autoload.php'; 

use Dompdf\Dompdf;
use Dompdf\Options;

if(!isset($_SESSION['role']) || strtolower($_SESSION['role']) != 'admin') {
    header("location:login.php");
    exit;
}

$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true); 
$dompdf = new Dompdf($options);

$query = mysqli_query($koneksi, "SELECT * FROM users WHERE role != 'admin' ORDER BY nama ASC");

$html = '
<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h3 { margin: 0; text-transform: uppercase; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; }
        table, th, td { border: 1px solid #000; }
        th { background-color: #f2f2f2; padding: 10px; font-weight: bold; }
        td { padding: 8px; vertical-align: middle; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h3>DAFTAR GURU SEKOLAH MINGGU</h3>
        <h3>HKBP AGAPE RESSORT BARELANG</h3>
        <p>DISTRIK XX KEPULAUAN RIAU</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="30">NO</th>
                <th width="180">NAMA LENGKAP</th>
                <th width="150">TTL</th>
                <th width="100">NO HP</th>
                <th>ALAMAT</th>
            </tr>
        </thead>
        <tbody>';

$no = 1;
while($row = mysqli_fetch_assoc($query)) {
    
    // Karena di database namanya 'ttl', kita panggil langsung:
    $ttl_display = !empty($row['ttl']) ? $row['ttl'] : '-';
    $nohp        = !empty($row['no_hp']) ? $row['no_hp'] : '-';
    $alamat      = !empty($row['alamat']) ? $row['alamat'] : '-';
    
    $html .= '
            <tr>
                <td class="text-center">'.$no++.'</td>
                <td>'.htmlspecialchars($row['nama']).'</td>
                <td class="text-center">'.htmlspecialchars($ttl_display).'</td>
                <td class="text-center">'.$nohp.'</td>
                <td>'.htmlspecialchars($alamat).'</td>
            </tr>';
}

$html .= '</tbody></table></body></html>';

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape'); 
$dompdf->render();
$dompdf->stream("Daftar_GSM_HKBP_Agape.pdf", array("Attachment" => 1));
?>