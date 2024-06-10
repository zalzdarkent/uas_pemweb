<?php
require 'vendor/autoload.php';
include 'koneksi.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->setCellValue('A1', 'No.');
$sheet->setCellValue('B1', 'Nama Mahasiswa');
$sheet->setCellValue('C1', 'NPM Mahasiswa');
$sheet->setCellValue('D1', 'File');

$query = "SELECT * FROM mhs";
$result = mysqli_query($koneksi, $query);

$rowNum = 2; 
$no = 1; 
while ($row = mysqli_fetch_assoc($result)) {
    $sheet->setCellValue('A' . $rowNum, $no);
    $sheet->setCellValue('B' . $rowNum, $row['nama_mhs']);
    $sheet->setCellValue('C' . $rowNum, $row['npm']);
    $sheet->setCellValue('D' . $rowNum, $row['file']);
    $rowNum++;
    $no++;
}

$writer = new Xlsx($spreadsheet);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="data_mahasiswa.xlsx"');
header('Cache-Control: max-age=0');

$writer->save('php://output');
exit();
