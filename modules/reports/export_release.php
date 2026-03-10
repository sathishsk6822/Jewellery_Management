<?php
include '../../includes/dbconnect.php';
ob_start(); // Start output buffering

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
require_once __DIR__ . '/vendor/autoload.php'; // Load PhpSpreadsheet

// Export to Excel - Release Wise
if (isset($_POST['export_excel_release_wise'])) {
    $sql = "SELECT * FROM release_customer_gst_tbl ORDER BY release_date";
    $result = $conn->query($sql);

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setCellValue('A1', 'ID')
          ->setCellValue('B1', 'Account ID')
          ->setCellValue('C1', 'Customer ID')
          ->setCellValue('D1', 'Receipt Number')
          ->setCellValue('E1', 'GST Number')
          ->setCellValue('F1', 'Signature')
          ->setCellValue('G1', 'Release Date');

    $rowNumber = 2;
    while ($row = $result->fetch_assoc()) {
        $sheet->setCellValue('A' . $rowNumber, $row['id']);
        $sheet->setCellValue('B' . $rowNumber, $row['account_id']);
        $sheet->setCellValue('C' . $rowNumber, $row['customer_id']);
        $sheet->setCellValue('D' . $rowNumber, $row['receipt_number']);
        $sheet->setCellValue('E' . $rowNumber, $row['gst_number']);
        $sheet->setCellValue('F' . $rowNumber, $row['signature']);
        $sheet->setCellValue('G' . $rowNumber, $row['release_date']);
        $rowNumber++;
    }

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="release_wise_' . date('Ymd') . '.xlsx"');
    header('Cache-Control: max-age=0');
    
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit();
}

// Export to CSV - Release Wise
if (isset($_POST['export_csv_release_wise'])) {
    $sql = "SELECT * FROM release_customer_gst_tbl ORDER BY release_date";
    $result = $conn->query($sql);

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="release_wise_' . date('Ymd') . '.csv"');

    $output = fopen('php://output', 'w');
    fputcsv($output, array('ID', 'Account ID', 'Customer ID', 'Receipt Number', 'GST Number', 'Signature', 'Release Date'));

    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }

    fclose($output);
    exit();
}

// Export to PDF - Release Wise
if (isset($_POST['export_pdf_release_wise'])) {
    $sql = "SELECT * FROM release_customer_gst_tbl ORDER BY release_date";
    $result = $conn->query($sql);

    $pdf = new TCPDF();
    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 12);
    $html = '<h1>Release Wise Records</h1><table border="1"><tr><th>ID</th><th>Account ID</th><th>Customer ID</th><th>Receipt Number</th><th>GST Number</th><th>Signature</th><th>Release Date</th></tr>';

    while ($row = $result->fetch_assoc()) {
        $html .= '<tr>';
        $html .= '<td>' . $row['id'] . '</td>';
        $html .= '<td>' . $row['account_id'] . '</td>';
        $html .= '<td>' . $row['customer_id'] . '</td>';
        $html .= '<td>' . $row['receipt_number'] . '</td>';
        $html .= '<td>' . $row['gst_number'] . '</td>';
        $html .= '<td>' . $row['signature'] . '</td>';
        $html .= '<td>' . $row['release_date'] . '</td>';
        $html .= '</tr>';
    }

    $html .= '</table>';
    $pdf->writeHTML($html, true, false, true, false, '');
    ob_end_clean(); // Clean all previous output before generating PDF
    $pdf->Output('release_wise_' . date('Ymd') . '.pdf', 'D');
    exit();
}
?>
