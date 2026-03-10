<?php
ob_start(); // Start output buffering to prevent unwanted output

include '../../includes/dbconnect.php'; 
require_once __DIR__ . '/vendor/tecnickcom/tcpdf/tcpdf.php'; // Ensure the correct path for TCPDF
require_once __DIR__ . '/vendor/autoload.php'; // Load PhpSpreadsheet

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// ✅ Export to Excel
if (isset($_POST['export_excel'])) {
    $sql = "SELECT * FROM accountant_tbl";
    $result = $conn->query($sql);

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setCellValue('A1', 'ID')
          ->setCellValue('B1', 'Account Holder')
          ->setCellValue('C1', 'Balance')
          ->setCellValue('D1', 'Mobile Number')
          ->setCellValue('E1', 'Address')
          ->setCellValue('F1', 'Created Date');

    $rowNumber = 2;
    while ($row = $result->fetch_assoc()) {
        $sheet->setCellValue('A' . $rowNumber, $row['id']);
        $sheet->setCellValue('B' . $rowNumber, $row['account_holder']);
        $sheet->setCellValue('C' . $rowNumber, $row['balance']);
        $sheet->setCellValue('D' . $rowNumber, $row['mobile_number']);
        $sheet->setCellValue('E' . $rowNumber, $row['address']);
        $sheet->setCellValue('F' . $rowNumber, $row['create_date']);
        $rowNumber++;
    }

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="accountants_' . date('Ymd') . '.xlsx"');
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit();
}

// ✅ Export to CSV
if (isset($_POST['export_csv'])) {
    $sql = "SELECT * FROM accountant_tbl";
    $result = $conn->query($sql);

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="accountants_' . date('Ymd') . '.csv"');

    $output = fopen('php://output', 'w');
    fputcsv($output, ['ID', 'Account Holder', 'Balance', 'Mobile Number', 'Address', 'Created Date']);

    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }

    fclose($output);
    exit();
}

// ✅ Export to PDF
if (isset($_POST['export_pdf'])) {
    $sql = "SELECT * FROM accountant_tbl";
    $result = $conn->query($sql);

    $pdf = new TCPDF();
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Jewellery Shop');
    $pdf->SetTitle('Accountant Data');
    $pdf->SetMargins(10, 10, 10);
    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 12);

    $html = '<h1>Accountant Records</h1>
    <table border="1" cellpadding="5">
    <tr>
        <th>ID</th>
        <th>Account Holder</th>
        <th>Balance</th>
        <th>Mobile Number</th>
        <th>Address</th>
        <th>Created Date</th>
    </tr>';

    while ($row = $result->fetch_assoc()) {
        $html .= '<tr>';
        $html .= '<td>' . htmlspecialchars($row['id']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['account_holder']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['balance']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['mobile_number']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['address']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['create_date']) . '</td>';
        $html .= '</tr>';
    }

    $html .= '</table>';
    $pdf->writeHTML($html, true, false, true, false, '');

    ob_end_clean(); // Prevent "Some data has already been output" error
    $pdf->Output('accountants_' . date('Ymd') . '.pdf', 'D');
    exit();
}
?>
