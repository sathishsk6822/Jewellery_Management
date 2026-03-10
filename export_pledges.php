<?php
// Include database connection
include 'dbconnect.php';
ob_start(); // Start output buffering

// Load PhpSpreadsheet & TCPDF
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
require_once __DIR__ . '/vendor/autoload.php';


// Export to Excel
if (isset($_POST['export_excel'])) {
    $sql = "SELECT * FROM pledge_tbl";
    $result = $conn->query($sql);

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setCellValue('A1', 'ID')
        ->setCellValue('B1', 'Accountant ID')
        ->setCellValue('C1', 'Receipt Number')
        ->setCellValue('D1', 'Customer ID')
        ->setCellValue('E1', 'Father Name')
        ->setCellValue('F1', 'Jewel Weight')
        ->setCellValue('G1', 'Jewel Description')
        ->setCellValue('H1', 'Amount')
        ->setCellValue('I1', 'Jewel Value')
        ->setCellValue('J1', 'Pledge Date')
        ->setCellValue('K1', 'Retailer ID')
        ->setCellValue('L1', 'Interest 1.5%')
        ->setCellValue('M1', 'Interest Amount (1.5%)')
        ->setCellValue('N1', 'Interest 1.25%')
        ->setCellValue('O1', 'Interest Amount (1.25%)')
        ->setCellValue('P1', 'Release Date')
        ->setCellValue('Q1', 'Paid Amount')
        ->setCellValue('R1', 'Interest Amount')
        ->setCellValue('S1', 'Interest Amount 1.25%');

    $rowNumber = 2;
    while ($row = $result->fetch_assoc()) {
        $sheet->setCellValue('A' . $rowNumber, $row['id']);
        $sheet->setCellValue('B' . $rowNumber, $row['accountant_id']);
        $sheet->setCellValue('C' . $rowNumber, $row['receipt_number']);
        $sheet->setCellValue('D' . $rowNumber, $row['customer_id']);
        $sheet->setCellValue('E' . $rowNumber, $row['father_name']);
        $sheet->setCellValue('F' . $rowNumber, $row['jewel_weight']);
        $sheet->setCellValue('G' . $rowNumber, $row['jewel_description']);
        $sheet->setCellValue('H' . $rowNumber, $row['amount']);
        $sheet->setCellValue('I' . $rowNumber, $row['jewel_value']);
        $sheet->setCellValue('J' . $rowNumber, $row['pledge_date']);
        $sheet->setCellValue('K' . $rowNumber, $row['retailer_id']);
        $sheet->setCellValue('L' . $rowNumber, $row['interest_1.5']);
        $sheet->setCellValue('M' . $rowNumber, $row['interest_amount_1.5']);
        $sheet->setCellValue('N' . $rowNumber, $row['interest_1.25']);
        $sheet->setCellValue('O' . $rowNumber, $row['interest_amount_1.25']);
        $sheet->setCellValue('P' . $rowNumber, $row['release_date']);
        $sheet->setCellValue('Q' . $rowNumber, $row['paid_amount']);
        $sheet->setCellValue('R' . $rowNumber, $row['interest_amount']);
        $sheet->setCellValue('S' . $rowNumber, $row['interest_amount125']);
        $rowNumber++;
    }

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="pledges_' . date('Ymd') . '.xlsx"');
    header('Cache-Control: max-age=0');
    
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit();
}


// Export to CSV
if (isset($_POST['export_csv'])) {
    $sql = "SELECT * FROM pledge_tbl";
    $result = $conn->query($sql);

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="pledges_' . date('Ymd') . '.csv"');

    $output = fopen('php://output', 'w');
    fputcsv($output, array('ID', 'Accountant ID', 'Receipt Number', 'Customer ID', 'Father Name', 'Jewel Weight', 'Jewel Description', 'Amount', 'Jewel Value', 'Pledge Date', 'Retailer ID', 'Interest 1.5%', 'Interest Amount (1.5%)', 'Interest 1.25%', 'Interest Amount (1.25%)', 'Release Date', 'Paid Amount', 'Interest Amount', 'Interest Amount 1.25%'));

    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }

    fclose($output);
    exit();
}

// Export to PDF
if (isset($_POST['export_pdf'])) {
    $sql = "SELECT * FROM pledge_tbl";
    $result = $conn->query($sql);

    $pdf = new TCPDF();
    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 12);
    $html = '<h1>Pledge Records</h1><table border="1"><tr><th>ID</th><th>Accountant ID</th><th>Receipt Number</th><th>Customer ID</th><th>Father Name</th><th>Jewel Weight</th><th>Jewel Description</th><th>Amount</th><th>Jewel Value</th><th>Pledge Date</th><th>Retailer ID</th><th>Interest 1.5%</th><th>Interest Amount (1.5%)</th><th>Interest 1.25%</th><th>Interest Amount (1.25%)</th><th>Release Date</th><th>Paid Amount</th><th>Interest Amount</th><th>Interest Amount 1.25%</th></tr>';

    while ($row = $result->fetch_assoc()) {
        $html .= '<tr>';
        $html .= '<td>' . $row['id'] . '</td>';
        $html .= '<td>' . $row['accountant_id'] . '</td>';
        $html .= '<td>' . $row['receipt_number'] . '</td>';
        $html .= '<td>' . $row['customer_id'] . '</td>';
        $html .= '<td>' . $row['father_name'] . '</td>';
        $html .= '<td>' . $row['jewel_weight'] . '</td>';
        $html .= '<td>' . $row['jewel_description'] . '</td>';
        $html .= '<td>' . $row['amount'] . '</td>';
        $html .= '<td>' . $row['jewel_value'] . '</td>';
        $html .= '<td>' . $row['pledge_date'] . '</td>';
        $html .= '<td>' . $row['retailer_id'] . '</td>';
        $html .= '<td>' . $row['interest_1.5'] . '</td>';
        $html .= '<td>' . $row['interest_amount_1.5'] . '</td>';
        $html .= '<td>' . $row['interest_1.25'] . '</td>';
        $html .= '<td>' . $row['interest_amount_1.25'] . '</td>';
        $html .= '<td>' . $row['release_date'] . '</td>';
        $html .= '<td>' . $row['paid_amount'] . '</td>';
        $html .= '<td>' . $row['interest_amount'] . '</td>';
        $html .= '<td>' . $row['interest_amount125'] . '</td>';
        $html .= '</tr>';
    }

    $html .= '</table>';
    $pdf->writeHTML($html, true, false, true, false, '');
    ob_end_clean(); // Clean all previous output before generating PDF
    $pdf->Output('pledges_' . date('Ymd') . '.pdf', 'D');
    exit();
}
?>