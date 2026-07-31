<?php
require 'vendor/autoload.php';
use Dompdf\Dompdf;

include 'config.php';

$id = isset($_GET['id_barang_diambil']) ? $_GET['id_barang_diambil'] : null;
if (!$id) {
    die("ID tidak ditemukan.");
}

// Ambil data dari tabel barang_diambil dan relasi
$query = "
    SELECT 
        bd.*,
        r.nama_rahin,
        r.nik_rahin,
        r.no_whatsapp,
        r.email,
        n.nama AS nama_pegawai,
        n.nik AS nik_pegawai,
        l.kode_locker,
        p.kode_pegadaian,
        p.lokasi_pegadaian,
        m.nama AS nama_manager,
        bg.deskripsi_barang
    FROM barang_diambil bd
    LEFT JOIN barang_gadai bg ON bd.barang_id = bg.id_barang
    LEFT JOIN rahin r ON bd.rahin_id = r.id_rahin
    LEFT JOIN nasabah n ON bd.nasabah_id = n.id_nasabah
    LEFT JOIN locker l ON bd.locker_id = l.id_locker
    LEFT JOIN pegadaian p ON bd.pegadaian_id = p.id
    LEFT JOIN manager m ON bd.manager_id = m.id_manager
    WHERE bd.id_barang_diambil = '$id'
    LIMIT 1
";

$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);
if (!$data) {
    die("Data tidak ditemukan.");
}

// Fungsi base64 logo
function imgToBase64($path) {
    return (file_exists($path)) ? 'data:image/png;base64,' . base64_encode(file_get_contents($path)) : '';
}
$logo = imgToBase64('ppp.png');

// Fungsi tanggal Indonesia
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
        body { font-family: "Times New Roman", Times, serif; font-size: 12pt; margin: 50px; }
        .kop { text-align: center; margin-bottom: 20px; position: relative; }
        .logo { position: absolute; top: 0; right: 0; width: 80px; }
        .judul { font-size: 18pt; font-weight: bold; text-decoration: underline; }
        .nomor { margin-top: 5px; font-size: 12pt; }
        .isi { margin-top: 30px; line-height: 1.6; }
        .ttd { margin-top: 50px; width: 100%; }
        .ttd .kanan { float: right; text-align: center; margin-right: 20px; }
        table { margin-top: 15px; }
        table td { padding: 3px 10px; vertical-align: top; }
    </style>
</head>
<body>
<div class="kop" style="display: flex; align-items: flex-start; justify-content: space-between;">
    <br><br>
    <div style="flex:1;">
        <div class="judul" style="text-align: left; font-size: 13pt;">SURAT KETERANGAN BARANG DIAMBIL</div>
        <div class="nomor" style="text-align: left;">No: ' . htmlspecialchars($data['nik_rahin']) .'/' . htmlspecialchars($data['kode_pegadaian']) .'/' . htmlspecialchars($data['id_barang_diambil']) .'/' . date('d/m/Y', strtotime($data['tgl_diterima'])) .'</div>
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
        <tr><td>Tanggal Diambil</td><td>: ' . tanggalIndo($data['tgl_diterima']) . '</td></tr>
        <tr><td>Deskripsi Barang</td><td>: ' . nl2br(htmlspecialchars($data['deskripsi_barang'])) . '</td></tr>
    </table>

    <p style="margin-top: 20px;">
        Dengan surat keterangan ini, kami menyatakan bahwa barang tersebut telah diambil oleh pihak terkait dengan ketentuan yang berlaku di Pegadaian.
    </p>

    <p>
        Demikian surat keterangan ini dibuat agar dapat dipergunakan sebagaimana mestinya.
    </p>
</div>

<div class="ttd">
    <div class="kanan" style="position: relative; width: 200px; height: 120px; text-align: center;">
        <p>' . htmlspecialchars($data['lokasi_pegadaian']) . ', ' . $tanggalSurat . '</p>
        <p>Pihak Terkait</p>
        <div style="position: relative; width: 100%; height: 80px; margin-top: 20px;">
            <img src="' . $logo . '" style="position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%); width: 230px; height: auto; opacity: 0.3; z-index: 0;">
            <div style="position: relative; z-index: 1; margin-top: 100px;">
                <p><strong>( ' . htmlspecialchars($data['nama_rahin']) . ' )</strong></p>
            </div>
        </div>
    </div>
</div>

</body>
</html>
';

$dompdf = new Dompdf();
$dompdf->set_option('isRemoteEnabled', true);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("surat_pengambilan_" . $data['id_barang_diambil'] . ".pdf", ["Attachment" => false]);
exit;
