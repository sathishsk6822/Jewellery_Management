<?php 
include 'dbconnect.php'; 
ob_start(); // Start output buffering
require_once __DIR__ . '/vendor/tecnickcom/tcpdf/tcpdf.php'; // Ensure the correct path for TCPDF
require_once __DIR__ . '/vendor/autoload.php'; // Load PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if (isset($_POST['export_excel'])) {
    $sql = "SELECT * FROM user_tbl";
    $result = $conn->query($sql);

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setCellValue('A1', 'ID')
          ->setCellValue('B1', 'Name')
          ->setCellValue('C1', 'Username')
          ->setCellValue('D1', 'Mobile Number')
          ->setCellValue('E1', 'Email ID')
          ->setCellValue('F1', 'User Type')
          ->setCellValue('G1', 'Address')
          ->setCellValue('H1', 'Created Date')
          ->setCellValue('I1', 'Status');

    $rowNumber = 2;
    while ($row = $result->fetch_assoc()) {
        $sheet->setCellValue('A' . $rowNumber, $row['id']);
        $sheet->setCellValue('B' . $rowNumber, $row['name']);
        $sheet->setCellValue('C' . $rowNumber, $row['username']);
        $sheet->setCellValue('D' . $rowNumber, $row['MobileNumber']);
        $sheet->setCellValue('E' . $rowNumber, $row['EmailID']);
        $sheet->setCellValue('F' . $rowNumber, $row['usertype']);
        $sheet->setCellValue('G' . $rowNumber, $row['address']);
        $sheet->setCellValue('H' . $rowNumber, $row['create_date']);
        $sheet->setCellValue('I' . $rowNumber, $row['status']);
        $rowNumber++;
    }

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="users_' . date('Ymd') . '.xlsx"');
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit();
}

if (isset($_POST['export_csv'])) {
    $sql = "SELECT * FROM user_tbl";
    $result = $conn->query($sql);

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="users_' . date('Ymd') . '.csv"');

    $output = fopen('php://output', 'w');
    fputcsv($output, array('ID', 'Name', 'Username', 'Mobile Number', 'Email ID', 'User Type', 'Address', 'Created Date', 'Status'));

    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }

    fclose($output);
    exit();
}

if (isset($_POST['export_pdf'])) {
    $sql = "SELECT * FROM user_tbl";
    $result = $conn->query($sql);

    $pdf = new TCPDF();
    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 12);
    $html = '<h1>User Records</h1><table border="1"><tr><th>ID</th><th>Name</th><th>Username</th><th>Mobile</th><th>Email</th><th>User Type</th><th>Address</th><th>Created Date</th><th>Status</th></tr>';

    while ($row = $result->fetch_assoc()) {
        $html .= '<tr>';
        $html .= '<td>' . $row['id'] . '</td>';
        $html .= '<td>' . $row['name'] . '</td>';
        $html .= '<td>' . $row['username'] . '</td>';
        $html .= '<td>' . $row['MobileNumber'] . '</td>';
        $html .= '<td>' . $row['EmailID'] . '</td>';
        $html .= '<td>' . $row['usertype'] . '</td>';
        $html .= '<td>' . $row['address'] . '</td>';
        $html .= '<td>' . $row['create_date'] . '</td>';
        $html .= '<td>' . $row['status'] . '</td>';
        $html .= '</tr>';
    }

    $html .= '</table>';
    $pdf->writeHTML($html, true, false, true, false, '');
    ob_end_clean(); // Clean all previous output before generating PDF
    $pdf->Output('users_' . date('Ymd') . '.pdf', 'D');
    exit();
}
?>
