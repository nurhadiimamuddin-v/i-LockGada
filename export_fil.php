<?php
session_start(); // WAJIB di baris paling atas, sebelum output apa pun
include 'config.php'; // Koneksi database
require 'vendor/autoload.php'; // Autoload Composer

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

// Pastikan pegadaian login
if (!isset($_SESSION['pegadaian_id'])) {
    die("Anda harus login terlebih dahulu.");
}

$pegadaian_id = $_SESSION['pegadaian_id'];

// Query data berdasarkan pegadaian yang login
$query = "
    SELECT 
        b.id_barang,
        b.tgl_digadai,
        b.status_barang,
        b.deskripsi_barang,
        n.nama AS nama_nasabah,
        n.nik AS nik_nasabah,
        l.kode_locker,
        r.nama_rahin,
        r.nik_rahin,
        r.no_whatsapp,
        r.email,
        p.kode_pegadaian,
        p.lokasi_pegadaian,
        bd.tgl_diterima
    FROM barang_gadai b
    LEFT JOIN barang_diambil bd ON b.id_barang = bd.barang_id
    LEFT JOIN nasabah n ON b.nasabah_id = n.id_nasabah
    LEFT JOIN locker l ON b.locker_id = l.id_locker
    LEFT JOIN jenis_barang j ON b.jenis_id = j.id_jenis_barang 
    LEFT JOIN rahin r ON b.rahin_id = r.id_rahin
    LEFT JOIN pegadaian p ON b.pegadaian_id = p.id     
    WHERE b.pegadaian_id = ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $pegadaian_id);
$stmt->execute();
$result = $stmt->get_result();

// Cek jika tidak ada data
if ($result->num_rows === 0) {
    die("Tidak ada data untuk pegadaian ini.");
}

// Buat spreadsheet
$spreadsheet = new Spreadsheet();

// =======================
// SHEET 1 - Info Pegadaian
// =======================
$sheet1 = $spreadsheet->getActiveSheet();
$sheet1->setTitle('Info Pegadaian');

$headers1 = [
    'A1' => 'Kode Pegadaian',
    'B1' => 'Lokasi Pegadaian',
    'C1' => 'Nama Nasabah',
    'D1' => 'NIK Nasabah',
    'E1' => 'Kode Locker',
    'F1' => 'Barang ID'
];
foreach ($headers1 as $cell => $title) {
    $sheet1->setCellValue($cell, $title);
}
$sheet1->getStyle('A1:F1')->getFont()->setBold(true);

$row1 = 2;
$result->data_seek(0);
while ($data = $result->fetch_assoc()) {
    $sheet1->setCellValue('A' . $row1, $data['kode_pegadaian']);
    $sheet1->setCellValue('B' . $row1, $data['lokasi_pegadaian']);
    $sheet1->setCellValue('C' . $row1, $data['nama_nasabah']);
    $sheet1->setCellValueExplicit('D' . $row1, $data['nik_nasabah'], DataType::TYPE_STRING);
    $sheet1->setCellValue('E' . $row1, $data['kode_locker']);
    $sheet1->setCellValue('F' . $row1, $data['id_barang']);
    $row1++;
}
foreach (range('A', 'F') as $col) {
    $sheet1->getColumnDimension($col)->setAutoSize(true);
}
$sheet1->getStyle("A1:F" . ($row1 - 1))->applyFromArray([
    'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]
]);

// =======================
// SHEET 2 - Data Barang
// =======================
$sheet2 = $spreadsheet->createSheet();
$sheet2->setTitle('Data Barang');

$headers2 = [
    'A1' => 'ID Barang',
    'B1' => 'Tanggal Digadai',
    'C1' => 'Tanggal Diterima',
    'D1' => 'Status Barang',
    'E1' => 'Deskripsi Barang',
    'F1' => 'Nama Nasabah',
    'G1' => 'NIK Nasabah',
    'H1' => 'No WhatsApp',
    'I1' => 'Email Nasabah'
];
foreach ($headers2 as $cell => $title) {
    $sheet2->setCellValue($cell, $title);
}
$sheet2->getStyle('A1:I1')->getFont()->setBold(true);

$row2 = 2;
$result->data_seek(0);
while ($data = $result->fetch_assoc()) {
    $sheet2->setCellValue('A' . $row2, $data['id_barang']);
    $sheet2->setCellValue('B' . $row2, $data['tgl_digadai']);
    $sheet2->setCellValue('C' . $row2, $data['tgl_diterima']); // ← yang ditambahkan
    $sheet2->setCellValue('D' . $row2, $data['status_barang']);
    $sheet2->setCellValue('E' . $row2, $data['deskripsi_barang']);
    $sheet2->setCellValue('F' . $row2, $data['nama_rahin']);
    $sheet2->setCellValueExplicit('G' . $row2, $data['nik_rahin'], DataType::TYPE_STRING);
    $sheet2->setCellValue('H' . $row2, $data['no_whatsapp']);
    $sheet2->setCellValue('I' . $row2, $data['email']);
    $row2++;
}
foreach (range('A', 'I') as $col) {
    $sheet2->getColumnDimension($col)->setAutoSize(true);
}
$sheet2->getStyle("A1:I" . ($row2 - 1))->applyFromArray([
    'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]
]);


// Output file Excel ke browser
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="data_barang_yg_digadaikan.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>
