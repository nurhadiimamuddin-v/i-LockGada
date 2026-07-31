<?php
session_start();
include 'config.php'; // Koneksi database
require 'vendor/autoload.php'; // Autoload Composer

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Ambil pegadaian_id dari session
$pegadaian_id = $_SESSION['pegadaian_id'];

// Query data nasabah berdasarkan pegadaian_id
$query = mysqli_query($conn, "SELECT * FROM nasabah WHERE pegadaian_id = '$pegadaian_id'");

// Buat spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Header kolom
$sheet->setCellValue('A1', 'No');
$sheet->setCellValue('B1', 'Nama Pegawai');
$sheet->setCellValue('C1', 'Tempat Lahir');
$sheet->setCellValue('D1', 'Tanggal Lahir');

// Judul bold
$sheet->getStyle('A1:D1')->getFont()->setBold(true);

// Isi data
$row = 2;
$no = 1;
while ($data = mysqli_fetch_assoc($query)) {
    $sheet->setCellValue('A' . $row, $no++);
    $sheet->setCellValue('B' . $row, $data['nama']);
    $sheet->setCellValue('C' . $row, $data['tempat_lahir']);
    $sheet->setCellValue('D' . $row, date('d F Y', strtotime($data['tanggal_lahir'])));
    $row++;
}

// Auto size kolom
foreach (range('A', 'D') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Tambah border
$styleArray = [
    'borders' => [
        'allBorders' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => ['argb' => 'FF000000'],
        ],
    ],
];

$lastRow = $row - 1;
$sheet->getStyle('A1:D' . $lastRow)->applyFromArray($styleArray);

// Download file
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="data_pegawai_pegadaian.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>
