<?php include '../../includes/dbconnect.php'; 
include 'header.php'; // Include header
include '../../includes/sidebar.php'; // Include sidebar
ob_start(); // Start output buffering
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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
    header('Content-Disposition: attachment;filename="accountants_' . date('Ymd') . '.xlsx"');
    header('Cache-Control: max-age=0');
    
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit();
}

if (isset($_POST['export_csv'])) {
    $sql = "SELECT * FROM accountant_tbl";
    $result = $conn->query($sql);

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="accountants_' . date('Ymd') . '.csv"' );

    $output = fopen('php://output', 'w');
    fputcsv($output, array('ID', 'Account Holder', 'Balance', 'Mobile Number', 'Address', 'Created Date'));

    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }

    fclose($output);
    exit();
}

if (isset($_POST['export_pdf'])) {
    $sql = "SELECT * FROM accountant_tbl";
    $result = $conn->query($sql);

    $pdf = new TCPDF();
    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 12);
    $html = '<h1>Accountant Records</h1><table border="1"><tr><th>ID</th><th>Account Holder</th><th>Balance</th><th>Mobile</th><th>Address</th><th>Created Date</th></tr>';

    while ($row = $result->fetch_assoc()) {
        $html .= '<tr>';
        $html .= '<td>' . $row['id'] . '</td>';
        $html .= '<td>' . $row['account_holder'] . '</td>';
        $html .= '<td>' . $row['balance'] . '</td>';
        $html .= '<td>' . $row['mobile_number'] . '</td>';
        $html .= '<td>' . $row['address'] . '</td>';
        $html .= '<td>' . $row['create_date'] . '</td>';
        $html .= '</tr>';
    }

    $html .= '</table>';
    $pdf->writeHTML($html, true, false, true, false, '');
    ob_end_clean(); // Clean all previous output before generating PDF
    $pdf->Output('accountants_' . date('Ymd') . '.pdf', 'D');
    exit();
}?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accountant Wise Records</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
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
    <h2 class="text-center"> Accountant Wise Records</h2>

    <!-- Search Input -->
    <div class="input-group mb-3">
        <input type="text" id="searchAccountant" class="form-control" placeholder="Search by Account Holder, Mobile Number, or Address...">
    </div>

    <!-- Export Buttons -->
    <div class="mb-3 text-left">
        <button type="button" class="btn btn-info" onclick="exportData('csv')">
            <i class="bi bi-file-earmark-spreadsheet text-white"></i> Export as CSV
        </button>
        <button type="button" class="btn btn-warning" onclick="exportData('excel')">
            <i class="bi bi-file-earmark-excel text-white"></i> Export as Excel
        </button>
        <button type="button" class="btn btn-danger" onclick="exportData('pdf')">
            <i class="bi bi-file-earmark-pdf text-white"></i> Export as PDF
        </button>
    </div>

    <!-- Table with Infinite Scroll -->
    <div class="table-responsive" style="height: 300px; overflow-y: auto;" id="accountantTableContainer">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th> ID</th>
                    <th> Account Holder</th>
                    <th> Balance</th>
                    <th> Mobile Number</th>
                    <th> Address</th>
                    <th>Created Date</th>
                </tr>
            </thead>
            <tbody id="accountantTableBody">
                <!-- Data will be loaded dynamically -->
            </tbody>
        </table>
        
    </div>
</div>

<script>
var page = 1;
var searchQuery = "";
var isFetching = false;

$(document).ready(function() {
    fetchAccountants(); // Load initial data

    $("#accountantTableContainer").on("scroll", function() {
        var scrollTop = $(this).scrollTop();
        var innerHeight = $(this).innerHeight();
        var scrollHeight = this.scrollHeight;

        if (scrollTop + innerHeight >= scrollHeight - 10) {
            fetchAccountants();
        }
    });

    // Search Functionality
    $("#searchAccountant").on("input", function() {
        searchQuery = $(this).val().trim();
        if (searchQuery === "") {
            resetAndFetchAll();
        } else {
            page = 1;
            $("#accountantTableBody").html(""); // Clear table for new results
            fetchAccountants(true);
        }
    });
});

// Reset Table and Fetch All Records
function resetAndFetchAll() {
    page = 1;
    $("#accountantTableBody").html("");
    searchQuery = "";
    fetchAccountants();
}

// Fetch Data from Server
function fetchAccountants(isSearch = false) {
    if (isFetching) return;
    isFetching = true;
    $("#loadingMessage").show();

    $.ajax({
        url: '../../handlers/fetch_accountants.php",
        type: "GET",
        data: { page: page, search: searchQuery },
        success: function(response) {
            if (page === 1) {
                $("#accountantTableBody").html(response); // Reset table on new search
            } else {
                $("#accountantTableBody").append(response); // Append data on scroll
            }
            page++;
            isFetching = false;
            $("#loadingMessage").hide();
        },
        error: function() {
            console.log("Error loading data");
            isFetching = false;
            $("#loadingMessage").hide();
        }
    });
}
// Export Function
function exportData(type) {
    let form = document.createElement("form");
    form.method = "POST";
    form.action = "export_accountants.php";

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
