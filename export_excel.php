<?php
include 'config.php'; // Koneksi database
require 'vendor/autoload.php'; // Path ke autoload Composer

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Koneksi database

// Query data pegadaian
$query = "SELECT Manager.*, Pegadaian.kode_pegadaian, Pegadaian.lokasi_pegadaian 
                                          FROM Manager 
                                          JOIN Pegadaian ON Manager.pegadaian_id = Pegadaian.id"; // Sesuaikan nama tabel
$result = $conn->query($query);

// Buat spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Header kolom
$sheet->setCellValue('A1', 'ID');
$sheet->setCellValue('B1', 'KODE PEGADAIAN');
$sheet->setCellValue('C1', 'LOKASI PEGADAIAN');
$sheet->setCellValue('D1', 'NAMA MANAGER');
$sheet->setCellValue('E1', 'USERNAME MANAGER');

// Membuat judul kolom menjadi bold
$sheet->getStyle('A1:E1')->getFont()->setBold(true);

// Isi data
$row = 2;
while ($data = $result->fetch_assoc()) {
    $sheet->setCellValue('A' . $row, $data['id_manager']);
    $sheet->setCellValue('B' . $row, $data['kode_pegadaian']);
    $sheet->setCellValue('C' . $row, $data['lokasi_pegadaian']);
    $sheet->setCellValue('D' . $row, $data['nama']); // Sesuaikan dengan nama kolom di database
    $sheet->setCellValue('E' . $row, $data['username']); // Sesuaikan dengan nama kolom di database
    $row++;
}

foreach (range('A', 'E') as $col) {
  $sheet->getColumnDimension($col)->setAutoSize(true);
}
// Header untuk download
$lastRow = $row - 1;
$styleArray = [
  'borders' => [
    'allBorders' => [
      'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
      'color' => ['argb' => 'FF000000'],
    ],
  ],
];

// Tambahkan border pada header (baris judul)
$sheet->getStyle('A1:E1')->applyFromArray($styleArray);
// Tambahkan border pada seluruh data
$sheet->getStyle('A1:E' . $lastRow)->applyFromArray($styleArray);

// Header untuk download
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="data_pegadaian.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>
