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

// Query data berdasarkan pegadaian yang login dan status_barang 'digadai'
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
        p.lokasi_pegadaian
    FROM barang_gadai b
    LEFT JOIN nasabah n ON b.nasabah_id = n.id_nasabah
    LEFT JOIN locker l ON b.locker_id = l.id_locker
    LEFT JOIN jenis_barang j ON b.jenis_id = j.id_jenis_barang 
    LEFT JOIN rahin r ON b.rahin_id = r.id_rahin
    LEFT JOIN pegadaian p ON b.pegadaian_id = p.id     
    WHERE b.pegadaian_id = ? AND b.status_barang = 'digadai'
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $pegadaian_id);
$stmt->execute();
$result = $stmt->get_result();

// Cek jika tidak ada data
if ($result->num_rows === 0) {
    die("Tidak ada data barang dengan status 'digadai' untuk pegadaian ini.");
}

// Buat spreadsheet
$spreadsheet = new Spreadsheet();

// =======================
// // SHEET 1 - Info Pegadaian
// // =======================
// $sheet1 = $spreadsheet->getActiveSheet();
// $sheet1->setTitle('Info Pegadaian');

// $headers1 = [
//     'A1' => 'Kode Pegadaian',
//     'B1' => 'Lokasi Pegadaian',
//     'C1' => 'Nama Nasabah',
//     'D1' => 'NIK Nasabah',
//     'E1' => 'Kode Locker'
// ];
// foreach ($headers1 as $cell => $title) {
//     $sheet1->setCellValue($cell, $title);
// }
// $sheet1->getStyle('A1:E1')->getFont()->setBold(true);

// $row1 = 2;
// $result->data_seek(0);
// while ($data = $result->fetch_assoc()) {
//     $sheet1->setCellValue('A' . $row1, $data['kode_pegadaian']);
//     $sheet1->setCellValue('B' . $row1, $data['lokasi_pegadaian']);
//     $sheet1->setCellValue('C' . $row1, $data['nama_nasabah']);
//     $sheet1->setCellValueExplicit('D' . $row1, $data['nik_nasabah'], DataType::TYPE_STRING);
//     $sheet1->setCellValue('E' . $row1, $data['kode_locker']);
//     $row1++;
// }
// foreach (range('A', 'E') as $col) {
//     $sheet1->getColumnDimension($col)->setAutoSize(true);
// }
// $sheet1->getStyle("A1:E" . ($row1 - 1))->applyFromArray([
//     'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]
// ]);

// =======================
// SHEET 2 - Data Barang
// =======================
$sheet2 = $spreadsheet->createSheet();
$sheet2->setTitle('Data Barang');

$headers2 = [
    'A1' => 'ID Barang',
    'B1' => 'Tanggal Digadai',
    'C1' => 'Status Barang',
    'D1' => 'Deskripsi Barang',
    'E1' => 'Nama Nasabah',
    'F1' => 'NIK Nasabah',
    'G1' => 'No WhatsApp',
    'H1' => 'Email Nasabah'
];
foreach ($headers2 as $cell => $title) {
    $sheet2->setCellValue($cell, $title);
}
$sheet2->getStyle('A1:H1')->getFont()->setBold(true);

$row2 = 2;
$result->data_seek(0);
while ($data = $result->fetch_assoc()) {
    $sheet2->setCellValue('A' . $row2, $data['id_barang']);
    $sheet2->setCellValue('B' . $row2, $data['tgl_digadai']);
    $sheet2->setCellValue('C' . $row2, $data['status_barang']);
    $sheet2->setCellValue('D' . $row2, $data['deskripsi_barang']);
    $sheet2->setCellValue('E' . $row2, $data['nama_rahin']);
    $sheet2->setCellValueExplicit('F' . $row2, $data['nik_rahin'], DataType::TYPE_STRING);
    $sheet2->setCellValue('G' . $row2, $data['no_whatsapp']);
    $sheet2->setCellValue('H' . $row2, $data['email']);
    $row2++;
}
foreach (range('A', 'H') as $col) {
    $sheet2->getColumnDimension($col)->setAutoSize(true);
}
$sheet2->getStyle("A1:H" . ($row2 - 1))->applyFromArray([
    'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]
]);

// Output file Excel ke browser
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="data_barang_digadai.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>
