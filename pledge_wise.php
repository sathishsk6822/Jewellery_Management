<?php
// Include your database connection file
include 'dbconnect.php';
include 'header.php'; // Include header
include 'sidebar.php'; // Include sidebar
ob_start(); // Start output buffering

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
require_once __DIR__ . '/vendor/tecnickcom/tcpdf/tcpdf.php'; // Ensure the correct path for TCPDF
require_once __DIR__ . '/vendor/autoload.php'; // Load PhpSpreadsheet

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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pledge Wise Records - Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* General Styles */
body {
    font-family: 'Poppins', sans-serif;
    background-color: #f8f9fa;
}

.container {
    max-width: 95%;
    padding: 20px;
    margin-left: 270px;
            padding: 40px;
            width: calc(100% - 270px);
}

h2 {
    font-weight: 600;
    color: #333;
    margin-bottom: 20px;
}

/* Search Input */
.input-group {
    max-width: 500px;
    margin: 0 auto 20px;
}
.input-group-text {
    background-color: #28a745;
    color: white;
}
.form-control {
    border: 1px solid #28a745;
}
.form-control:focus {
    box-shadow: 0 0 5px rgba(40, 167, 69, 0.5);
    border-color: #28a745;
}

/* Export Buttons */
.mb-3 .btn {
    font-size: 16px;
    font-weight: 500;
    padding: 10px 15px;
    border-radius: 5px;
    margin-right: 10px;
    transition: all 0.3s ease-in-out;
}
.mb-3 .btn-info:hover {
    background-color: #17a2b8;
    color: white;
}
.mb-3 .btn-warning:hover {
    background-color: #ffc107;
    color: white;
}
.mb-3 .btn-danger:hover {
    background-color: #dc3545;
    color: white;
}

/* Table Container */
.table-responsive {
    background-color: white;
    border-radius: 10px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    padding: 10px;
}

/* Table Styles */
.table {
    border-collapse: collapse;
    border-radius: 10px;
    overflow: hidden;
}

.table thead {
    background-color: #343a40;
    color: white;
    font-weight: 600;
}

.table tbody tr:nth-child(even) {
    background-color: #f2f2f2;
}

.table tbody tr:hover {
    background-color: #d4edda;
    transition: all 0.3s;
}

th, td {
    text-align: center;
    padding: 12px;
    border: 1px solid #dee2e6;
}

th {
    font-size: 16px;
}

/* Scrollbar Customization */
.table-responsive::-webkit-scrollbar {
    width: 8px;
}
.table-responsive::-webkit-scrollbar-thumb {
    background:rgb(39, 77, 143);
    border-radius: 10px;
}
.table-responsive::-webkit-scrollbar-track {
    background: #f8f9fa;
}

/* Responsive Design */
@media (max-width: 768px) {
    .table-responsive {
        height: 400px;
    }
    th, td {
        font-size: 14px;
    }
    .mb-3 .btn {
        font-size: 14px;
        padding: 8px 12px;
    }
}

@media (max-width: 480px) {
    h2 {
        font-size: 20px;
    }
    .input-group {
        max-width: 100%;
    }
}

        </style>
</head>
<body>

<div class="container mt-4">
    <h3>Pledge Wise Records</h3>
    <!-- Search Input -->
    <input type="text" id="searchPledge" class="form-control mb-3" placeholder="Search by Customer ID, Father Name, or Jewel Description...">

    <!-- Export Buttons -->
    <div class="mb-3">
        <button type="button" class="btn btn-info" onclick="exportData('csv')">Export as CSV</button>
        <button type="button" class="btn btn-warning" onclick="exportData('excel')">Export as Excel</button>
        <button type="button" class="btn btn-danger" onclick="exportData('pdf')">Export as PDF</button>
    </div>

    

    <!-- Table with Infinite Scroll -->
    <div class="table-responsive" style="height: 400px; overflow-y: auto;" id="pledgeTableContainer">
    <table class="table table-bordered table-striped">
    <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Accountant ID</th>
                    <th>Receipt Number</th>
                    <th>Customer ID</th>
                    <th>Father Name</th>
                    <th>Jewel Weight</th>
                    <th>Jewel Description</th>
                    <th>Amount</th>
                    <th>Jewel Value</th>
                    <th>Pledge Date</th>
                    <th>Retailer ID</th>
                    <th>Interest 1.5%</th>
                    <th>Interest Amount (1.5%)</th>
                    <th>Interest 1.25%</th>
                    <th>Interest Amount (1.25%)</th>
                    <th>Release Date</th>
                    <th>Paid Amount</th>
                    <th>Interest Amount</th>
                    <th>Interest Amount 1.25%</th>
                </tr>
            </thead>
            <tbody id="pledgeTableBody">
                <!-- Data will be loaded dynamically -->
            </tbody>
        </table>
        <div id="loadingMessage" class="text-center mt-2" style="display: none;">Loading more records...</div>
    </div>
</div>

<script>
var page = 1;
var searchQuery = "";
var isFetching = false;

$(document).ready(function() {
    fetchPledges(); // Load initial data

    // Infinite Scroll
    $("#pledgeTableContainer").on("scroll", function() {
        var scrollTop = $(this).scrollTop();
        var innerHeight = $(this).innerHeight();
        var scrollHeight = this.scrollHeight;

        if (scrollTop + innerHeight >= scrollHeight - 10) {
            fetchPledges();
        }
    });

     // Search Functionality
     $("#searchPledge").on("input", function() {
        searchQuery = $(this).val().trim();
        page = 1; // Reset to the first page
        $("#pledgeTableBody").html(""); // Clear the current results
        fetchPledges(true); // Fetch filtered results
    });
});


// Reset Table and Fetch All Records
function resetAndFetchAll() {
    page = 1;
    $("#pledgeTableBody").html("");
    searchQuery = "";
    fetchPledges();
}

function fetchPledges(isSearch = false) {
    if (isFetching) return;
    isFetching = true;
    $("#loadingMessage").show();

    $.ajax({
        url: "fetch_pledges.php", // The PHP file that fetches the records
        type: "GET",
        data: { page: page, search: searchQuery },
        success: function(response) {
            $("#pledgeTableBody").append(response);
            page++; // Increment page for the next fetch
            isFetching = false;
            $("#loadingMessage").hide();
        }
    });
}
// Export Function
function exportData(type) {
    let form = document.createElement("form");
    form.method = "POST";
    form.action = "export_pledges.php";

    let input = document.createElement("input");
    input.type = "hidden";
    input.name = "export_" + type;
    input.value = "1";

    form.appendChild(input);
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
}
</script>

</body>
</html>
