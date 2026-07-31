<?php
session_start();
include 'config.php';
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

// Pastikan pegadaian login
if (!isset($_SESSION['pegadaian_id'])) {
    die("Anda harus login terlebih dahulu.");
}

$pegadaian_id = $_SESSION['pegadaian_id'];

// Query data barang yang diambil
$query = "
    SELECT 
        bd.*,
        n.nama AS nama_pegawai,
        n.nik AS nik_pegawai,
        l.kode_locker,
        j.jenis_barang,
        r.nama_rahin,
        r.nik_rahin,
        bg.id_barang
    FROM barang_diambil bd
    LEFT JOIN barang_gadai bg ON bd.barang_id = bg.id_barang
    LEFT JOIN nasabah n ON bd.nasabah_id = n.id_nasabah
    LEFT JOIN locker l ON bd.locker_id = l.id_locker
    LEFT JOIN jenis_barang j ON bd.jenis_id = j.id_jenis_barang
    LEFT JOIN rahin r ON bd.rahin_id = r.id_rahin
    WHERE bd.pegadaian_id = ?
    ORDER BY bd.tgl_diterima DESC
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $pegadaian_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Tidak ada data untuk pegadaian ini.");
}

// Buat spreadsheet baru
$spreadsheet = new Spreadsheet();

// =======================
// SHEET 1 - Data Barang Diambil
// =======================
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Barang Diambil');

$headers = [
    'A1' => 'ID Barang Diambil',
    'B1' => 'Tanggal Diterima',
    'C1' => 'NIK Nasabah',
    'D1' => 'Nama Nasabah',
    'E1' => 'Jenis Barang',
    'F1' => 'Kode Locker',
    'G1' => 'NIK Pegawai',
    'H1' => 'Nama Pegawai',
];

foreach ($headers as $cell => $title) {
    $sheet->setCellValue($cell, $title);
}
$sheet->getStyle('A1:J1')->getFont()->setBold(true);

$row = 2;
while ($data = $result->fetch_assoc()) {
    $sheet->setCellValue('A' . $row, $data['id_barang_diambil']);
    $sheet->setCellValue('B' . $row, $data['tgl_diterima']);
    $sheet->setCellValueExplicit('C' . $row, $data['nik_rahin'], DataType::TYPE_STRING);
    $sheet->setCellValue('D' . $row, $data['nama_rahin']);
    $sheet->setCellValue('E' . $row, $data['jenis_barang']);
    $sheet->setCellValue('F' . $row, $data['kode_locker']);
    $sheet->setCellValueExplicit('G' . $row, $data['nik_pegawai'], DataType::TYPE_STRING);
    $sheet->setCellValue('H' . $row, $data['nama_pegawai']);
    $row++;
}

foreach (range('A', 'H') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}
$sheet->getStyle("A1:H" . ($row - 1))->applyFromArray([
    'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]
]);

// Output file Excel ke browser
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="data_barang_diambil.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>
