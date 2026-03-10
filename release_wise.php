<?php
include 'dbconnect.php';
include 'header.php';
include 'sidebar.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
require_once __DIR__ . '/vendor/tecnickcom/tcpdf/tcpdf.php';
require_once __DIR__ . '/vendor/autoload.php';

ob_start();

// Export to Excel - Release Wise
if (isset($_POST['export_excel'])) {
    $sql = "SELECT * FROM release_customer_gst_tbl ORDER BY renewal_date";
    $result = $conn->query($sql);

    if (!$result) {
        die("Query failed: " . $conn->error);
    }

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setCellValue('A1', 'ID')
          ->setCellValue('B1', 'Account ID')
          ->setCellValue('C1', 'Customer ID')
          ->setCellValue('D1', 'Receipt Number')
          ->setCellValue('E1', 'GST Number')
          ->setCellValue('F1', 'Signature')
          ->setCellValue('G1', 'Renewal Date');

    $rowNumber = 2;
    while ($row = $result->fetch_assoc()) {
        $sheet->setCellValue('A' . $rowNumber, $row['id']);
        $sheet->setCellValue('B' . $rowNumber, $row['account_id']);
        $sheet->setCellValue('C' . $rowNumber, $row['customer_id']);
        $sheet->setCellValue('D' . $rowNumber, $row['receipt_number']);
        $sheet->setCellValue('E' . $rowNumber, $row['gst_number']);
        $sheet->setCellValue('F' . $rowNumber, $row['signature']);
        $sheet->setCellValue('G' . $rowNumber, $row['renewal_date'] ?? 'N/A');
        $rowNumber++;
    }

    ob_end_clean();
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="release_wise_' . date('Ymd') . '.xlsx"');
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit();
}

// Export to CSV - Release Wise
if (isset($_POST['export_csv'])) {
    $sql = "SELECT * FROM release_customer_gst_tbl ORDER BY renewal_date";
    $result = $conn->query($sql);

    if (!$result) {
        die("Query failed: " . $conn->error);
    }

    ob_end_clean();
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="release_wise_' . date('Ymd') . '.csv"');

    $output = fopen('php://output', 'w');
    fputcsv($output, ['ID', 'Account ID', 'Customer ID', 'Receipt Number', 'GST Number', 'Signature', 'Renewal Date']);

    while ($row = $result->fetch_assoc()) {
        fputcsv($output, [
            $row['id'],
            $row['account_id'],
            $row['customer_id'],
            $row['receipt_number'],
            $row['gst_number'],
            $row['signature'],
            $row['renewal_date'] ?? 'N/A'
        ]);
    }

    fclose($output);
    exit();
}

// Export to PDF - Release Wise (Optimized)
if (isset($_POST['export_pdf'])) {
    $sql = "SELECT * FROM release_customer_gst_tbl ORDER BY renewal_date";
    $result = $conn->query($sql);

    if (!$result) {
        die("Query failed: " . $conn->error);
    }

    // Initialize TCPDF
    $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Admin');
    $pdf->SetTitle('Release Wise Records');
    $pdf->SetMargins(10, 10, 10);
    $pdf->SetAutoPageBreak(TRUE, 10);
    $pdf->AddPage();

    // Set font
    $pdf->SetFont('helvetica', '', 10);

    // Header
    $pdf->Cell(0, 10, 'Release Wise Records', 0, 1, 'C');
    $pdf->Ln(5);

    // Table headers
    $headers = ['ID', 'Account ID', 'Customer ID', 'Receipt Number', 'GST Number', 'Signature', 'Renewal Date'];
    $widths = [20, 30, 30, 30, 40, 30, 30]; // Adjust widths as needed
    foreach ($headers as $i => $header) {
        $pdf->Cell($widths[$i], 7, $header, 1, 0, 'C');
    }
    $pdf->Ln();

    // Table rows
    while ($row = $result->fetch_assoc()) {
        $pdf->Cell($widths[0], 6, $row['id'], 1);
        $pdf->Cell($widths[1], 6, $row['account_id'], 1);
        $pdf->Cell($widths[2], 6, $row['customer_id'], 1);
        $pdf->Cell($widths[3], 6, $row['receipt_number'], 1);
        $pdf->Cell($widths[4], 6, $row['gst_number'], 1);
        $pdf->Cell($widths[5], 6, $row['signature'], 1);
        $pdf->Cell($widths[6], 6, $row['renewal_date'] ?? 'N/A', 1);
        $pdf->Ln();
    }

    ob_end_clean();
    $pdf->Output('release_wise_' . date('Ymd') . '.pdf', 'D');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Release Wise Records - Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* Your existing CSS unchanged */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 95%;
            padding: 20px;
            margin-left: 270px;
            width: calc(100% - 270px);
        }
        h2 {
            font-weight: 600;
            color: #333;
            margin-bottom: 20px;
        }
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
        .table-responsive {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            padding: 10px;
        }
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
        .table-responsive::-webkit-scrollbar {
            width: 8px;
        }
        .table-responsive::-webkit-scrollbar-thumb {
            background: rgb(39, 77, 143);
            border-radius: 10px;
        }
        .table-responsive::-webkit-scrollbar-track {
            background: #f8f9fa;
        }
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
    <h3>Release Wise Records</h3>
    
    <!-- Search Input -->
    <input type="text" id="searchRelease" class="form-control mb-3" placeholder="Search by Customer ID, GST Number, or Receipt Number...">

    <!-- Export Buttons -->
    <div class="mb-3">
        <button type="button" class="btn btn-info" onclick="exportData('csv')">Export as CSV</button>
        <button type="button" class="btn btn-warning" onclick="exportData('excel')">Export as Excel</button>
        <button type="button" class="btn btn-danger" onclick="exportData('pdf')">Export as PDF</button>
    </div>

    <!-- Table with Infinite Scroll -->
    <div class="table-responsive" style="height: 400px; overflow-y: auto;" id="releaseTableContainer">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Account ID</th>
                    <th>Customer ID</th>
                    <th>Receipt Number</th>
                    <th>GST Number</th>
                    <th>Signature</th>
                    <th>Renewal Date</th> <!-- Updated to match export -->
                </tr>
            </thead>
            <tbody id="releaseTableBody">
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
    fetchReleases();

    $("#releaseTableContainer").on("scroll", function() {
        var scrollTop = $(this).scrollTop();
        var innerHeight = $(this).innerHeight();
        var scrollHeight = this.scrollHeight;

        if (scrollTop + innerHeight >= scrollHeight - 10 && !isFetching) {
            fetchReleases();
        }
    });

    $("#searchRelease").on("input", function() {
        searchQuery = $(this).val().trim();
        page = 1;
        $("#releaseTableBody").html("");
        fetchReleases(true);
    });
});

function fetchReleases(isSearch = false) {
    if (isFetching) return;
    isFetching = true;
    $("#loadingMessage").show();

    $.ajax({
        url: "fetch_release.php",
        type: "GET",
        data: { page: page, search: searchQuery },
        success: function(response) {
            $("#releaseTableBody").append(response);
            page++;
            isFetching = false;
            $("#loadingMessage").hide();
        },
        error: function(xhr, status, error) {
            console.log("Fetch error: " + error);
            isFetching = false;
            $("#loadingMessage").hide();
        }
    });
}

function exportData(type) {
    let form = document.createElement("form");
    form.method = "POST";
    form.action = window.location.href;

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