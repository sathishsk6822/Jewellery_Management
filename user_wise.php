<?php
include 'dbconnect.php';
include 'header.php'; // Include header
include 'sidebar.php'; // Include sidebar
ob_start(); // Start output buffering
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Wise Records</title>
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
    <h2 class="text-center">User Wise Records</h2>

    <input type="text" id="searchUser" class="form-control mb-3" placeholder="Search by Name, Username, Email, or Mobile Number...">

    <div class="mb-3">
        <button type="button" class="btn btn-info" onclick="exportData('csv')">Export as CSV</button>
        <button type="button" class="btn btn-warning" onclick="exportData('excel')">Export as Excel</button>
        <button type="button" class="btn btn-danger" onclick="exportData('pdf')">Export as PDF</button>
    </div>

    <div class="table-responsive" style="height: 400px; overflow-y: auto;" id="userTableContainer">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Mobile Number</th>
                    <th>Email</th>
                    <th>User Type</th>
                    <th>Address</th>
                    <th>Create Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="userTableBody">
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
    fetchUsers();

    $("#userTableContainer").on("scroll", function() {
        if ($(this).scrollTop() + $(this).innerHeight() >= this.scrollHeight - 10) {
            fetchUsers();
        }
    });

    $("#searchUser").on("input", function() {
        searchQuery = $(this).val().trim();
        page = 1;
        $("#userTableBody").html("");
        fetchUsers(true);
    });
});

function fetchUsers(isSearch = false) {
    if (isFetching) return;
    isFetching = true;
    $("#loadingMessage").show();

    $.ajax({
        url: "fetch_users.php",
        type: "GET",
        data: { page: page, search: searchQuery },
        success: function(response) {
            $("#userTableBody").append(response);
            page++;
            isFetching = false;
            $("#loadingMessage").hide();
        }
    });
}
// Export Function
function exportData(type) {
    let form = document.createElement("form");
    form.method = "POST";
    form.action = "export_users.php";

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
