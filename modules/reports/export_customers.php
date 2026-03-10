<?php
include '../../includes/dbconnect.php'; // Database connection file
ob_start(); // Start output buffering

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
require_once __DIR__ . '/vendor/autoload.php';



// CSV Export
if (isset($_POST['export_csv'])) {
    $sql = "SELECT * FROM customer_tbl";
    $result = $conn->query($sql);

    $filename = "customer_data_" . date('Ymd') . ".csv";
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');

    $output = fopen('php://output', 'w');
    fputcsv($output, array('Customer Name', 'Father Name', 'Mobile Number', 'Address', 'Interest', 'Create Date'));

    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }

    fclose($output);
    exit();
}

// Excel Export
if (isset($_POST['export_excel'])) {
    $sql = "SELECT * FROM customer_tbl";
    $result = $conn->query($sql);

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setCellValue('A1', 'Customer Name')
          ->setCellValue('B1', 'Father Name')
          ->setCellValue('C1', 'Mobile Number')
          ->setCellValue('D1', 'Address')
          ->setCellValue('E1', 'Interest')
          ->setCellValue('F1', 'Create Date');

    $rowNumber = 2;
    while ($row = $result->fetch_assoc()) {
        $sheet->setCellValue('A' . $rowNumber, $row['customer_name']);
        $sheet->setCellValue('B' . $rowNumber, $row['father_name']);
        $sheet->setCellValue('C' . $rowNumber, $row['mobile_number']);
        $sheet->setCellValue('D' . $rowNumber, $row['address']);
        $sheet->setCellValue('E' . $rowNumber, $row['interest']);
        $sheet->setCellValue('F' . $rowNumber, $row['create_date']);
        $rowNumber++;
    }

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="customer_data_' . date('Ymd') . '.xlsx"');
    header('Cache-Control: max-age=0');
    
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit();
}

// PDF Export
if (isset($_POST['export_pdf'])) {
    $sql = "SELECT * FROM customer_tbl";
    $result = $conn->query($sql);

    $pdf = new TCPDF();
    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 12);

    $html = '<h1>Customer Data</h1><table border="1"><tr><th>Customer Name</th><th>Father Name</th><th>Mobile Number</th><th>Address</th><th>Interest</th><th>Create Date</th></tr>';
    
    while ($row = $result->fetch_assoc()) {
        $html .= '<tr>';
        $html .= '<td>' . $row['customer_name'] . '</td>';
        $html .= '<td>' . $row['father_name'] . '</td>';
        $html .= '<td>' . $row['mobile_number'] . '</td>';
        $html .= '<td>' . $row['address'] . '</td>';
        $html .= '<td>' . $row['interest'] . '</td>';
        $html .= '<td>' . $row['create_date'] . '</td>';
        $html .= '</tr>';
    }

    $html .= '</table>';
    $pdf->writeHTML($html, true, false, true, false, '');
    ob_end_clean(); // Clean all previous output before generating the PDF

    $pdf->Output('customer_data_' . date('Ymd') . '.pdf', 'D');
    exit();
}
?>
