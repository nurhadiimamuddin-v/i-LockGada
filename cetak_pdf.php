<?php
require 'vendor/autoload.php';
use Dompdf\Dompdf;

include 'config.php';

$id_barang = isset($_GET['id_barang']) ? $_GET['id_barang'] : null;
if (!$id_barang) {
    die("ID Barang tidak ditemukan.");
}

$query = "
    SELECT 
        b.id_barang,
        b.tgl_digadai,
        b.foto_nasabah,
        b.foto_barang,
        b.status_barang,
        n.nama AS nama_nasabah,
        n.nik,
        l.kode_locker,
        r.nama_rahin,
        r.nik_rahin,
        r.no_whatsapp,
        r.email,
        m.nama AS nama_manager,
        p.kode_pegadaian,
        p.lokasi_pegadaian,
        b.deskripsi_barang
    FROM barang_gadai b
    LEFT JOIN nasabah n ON b.nasabah_id = n.id_nasabah
    LEFT JOIN locker l ON b.locker_id = l.id_locker
    LEFT JOIN jenis_barang j ON b.jenis_id = j.id_jenis_barang 
    LEFT JOIN rahin r ON b.rahin_id = r.id_rahin
    LEFT JOIN pegadaian p ON b.pegadaian_id = p.id
    LEFT JOIN manager m ON b.manager_id = m.id_manager
    WHERE b.id_barang = '$id_barang'
    LIMIT 1
";

$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);
if (!$data) {
    die("Data tidak ditemukan.");
}

// Fungsi untuk convert gambar ke base64 (jika diperlukan)
function imgToBase64($path) {
    return (file_exists($path)) ? 'data:image/png;base64,' . base64_encode(file_get_contents($path)) : '';
}
$logo = imgToBase64('ppp.png');

// Tanggal surat otomatis (format Indonesia)
function tanggalIndo($tanggal) {
    $bulan = [
        1=>'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    $tgl = date('d', strtotime($tanggal));
    $bln = $bulan[(int)date('m', strtotime($tanggal))];
    $thn = date('Y', strtotime($tanggal));
    return $tgl . ' ' . $bln . ' ' . $thn;
}

$tanggalSurat = tanggalIndo(date('Y-m-d'));

$html = '
<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 12pt;
            margin: 50px;
        }
        .kop {
            text-align: center;
            margin-bottom: 20px;
            position: relative;
        }
        .logo {
            position: absolute;
            top: 0;
            right: 0;
            width: 80px;
        }
        .judul {
            font-size: 18pt;
            font-weight: bold;
            text-decoration: underline;
        }
        .nomor {
            margin-top: 5px;
            font-size: 12pt;
        }
        .isi {
            margin-top: 30px;
            line-height: 1.6;
        }
        .ttd {
            margin-top: 50px;
            width: 100%;
        }
        .ttd .kanan {
            float: right;
            text-align: center;
            margin-right: 20px;
        }
        table {
            margin-top: 15px;
        }
        table td {
            padding: 3px 10px;
            vertical-align: top;
        }
    </style>
</head>
<body>
<div class="kop" style="display: flex; align-items: flex-start; justify-content: space-between;">
<br>
<br>
  <div style="flex:1;">
    <div class="judul" style="text-align: left; font-size: 13pt;">SURAT KETERANGAN BARANG DI GADAI</div>
    <div class="nomor" style="text-align: left;">No:  ' . htmlspecialchars($data['nik_rahin']) .'/' . htmlspecialchars($data['kode_pegadaian']) .'/' . htmlspecialchars($data['id_barang']) .'/' . htmlspecialchars($data['tgl_digadai']) .'</div>
  </div>
  
  <img src="' . $logo . '" class="logo" style="position: absolute; top: 0; right: 0; width: 260px; height: auto; margin: -40;">
</div>

<div class="isi">
    <p>Yang bertanda tangan di bawah ini menerangkan bahwa:</p>
    <table>
        <tr><td>Nama Nasabah</td><td>: ' . htmlspecialchars($data['nama_rahin']) . '</td></tr>
        <tr><td>NIK Nasabah</td><td>: ' . htmlspecialchars($data['nik_rahin']) . '</td></tr>
        <tr><td>Nomor Whatsapp</td><td>: ' . htmlspecialchars($data['no_whatsapp']) . '</td></tr>
        <tr><td>Email</td><td>: ' . htmlspecialchars($data['email']) . '</td></tr>
        <tr><td>Kode Locker</td><td>: ' . htmlspecialchars($data['kode_locker']) . '</td></tr>
        <tr><td>Status Barang</td><td>: ' . htmlspecialchars($data['status_barang']) . '</td></tr>
        <tr><td>Tanggal Digadai</td><td>: ' . tanggalIndo($data['tgl_digadai']) . '</td></tr>
        <tr><td>Deskripsi Barang</td><td>: ' . nl2br(htmlspecialchars($data['deskripsi_barang'])) . '</td></tr>
    </table>

    <p style="margin-top: 20px;">
        Dengan surat keterangan ini, kami menyatakan bahwa barang tersebut benar tercatat sebagai barang gadai di pegadaian .
    </p>

    <p>
        Demikian surat keterangan ini dibuat agar dapat dipergunakan sebagaimana mestinya.
    </p>
</div>

<div class="ttd">
    <div class="kanan" style="position: relative; width: 200px; height: 120px; text-align: center;">
        <p>' . htmlspecialchars($data['lokasi_pegadaian']) . ', ' . $tanggalSurat . '</p>
        <p>Manajer HRD</p>
        <div style="position: relative; width: 100%; height: 80px; margin-top: 20px;">
            <!-- Watermark logo di belakang tanda tangan -->
            <img src="' . $logo . '" style="position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%); width: 230px; height: auto; opacity: 0.3; z-index: 0;">
            <div style="position: relative; z-index: 1; margin-top: 100px;">
                <p><strong>( ' . htmlspecialchars($data['nama_manager']) . ' )</strong></p>
            </div>
        </div>
    </div>
</div>

</body>
</html>
';

// Buat objek Dompdf dan render PDF
$dompdf = new Dompdf();
$dompdf->set_option('isRemoteEnabled', true);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("surat_keterangan_" . $data['id_barang'] . ".pdf", ["Attachment" => false]);
exit;
