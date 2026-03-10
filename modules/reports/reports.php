<?php
include '../../includes/dbconnect.php'; // Database connection file
include '../../includes/header.php'; 
include '../../includes/sidebar.php';

$limit = 100; // Number of records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max($page, 1);
$offset = ($page - 1) * $limit;

// Fetch total records for pagination
$totalQuery = "SELECT COUNT(*) AS total FROM release_customer_gst_tbl";
$totalResult = mysqli_query($conn, $totalQuery);
$totalRow = mysqli_fetch_assoc($totalResult);
$totalRecords = $totalRow['total'];
$totalPages = ceil($totalRecords / $limit);
?>

<div class="main-content">
    <h2>Reports Dashboard</h2>

    <div class="table-row">
        <!-- Released Pledges Table -->
        <div class="table-container">
            <h3>Released Pledges</h3>
            <button onclick="printSection('releasedPledges')" class="print-btn">🖨️ Print</button>
            <div id="releasedPledges">
                <table>
                    <tr><th>Receipt No</th><th>Customer Name</th><th>GST Number</th></tr>
                    <?php
                    $query = "SELECT r.receipt_number, c.customer_name, r.gst_number FROM release_customer_gst_tbl r 
                              JOIN customer_tbl c ON r.customer_id = c.customer_id 
                              ORDER BY r.id DESC LIMIT $limit OFFSET $offset";
                    $result = mysqli_query($conn, $query);
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr><td>{$row['receipt_number']}</td><td>{$row['customer_name']}</td><td>{$row['gst_number']}</td></tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3'>No data found.</td></tr>";
                    }
                    ?>
                </table>
            </div>
        </div>
<!-- Customer-wise Release Count -->
<div class="table-container">
            <h3>Customer-wise Releases</h3>
            <button onclick="printSection('customerReleases')" class="print-btn">🖨️ Print</button>
            <div id="customerReleases">
                <table>
                    <tr><th>Customer Name</th><th>Release Count</th></tr>
                    <?php
                    $query = "SELECT c.customer_name, COUNT(r.id) AS release_count FROM release_customer_gst_tbl r 
                              JOIN customer_tbl c ON r.customer_id = c.customer_id GROUP BY r.customer_id";
                    $result = mysqli_query($conn, $query);
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr><td>{$row['customer_name']}</td><td>{$row['release_count']}</td></tr>";
                        }
                    } else {
                        echo "<tr><td colspan='2'>No data available.</td></tr>";
                    }
                    ?>
                </table>
            </div>
        </div>
        
    </div>

    <div class="table-row">
        

        <!-- Accountant-wise Transactions -->
        <div class="table-container">
            <h3>Accountant Transactions</h3>
            <button onclick="printSection('accountantTransactions')" class="print-btn">🖨️ Print</button>
            <div id="accountantTransactions">
                <table>
                    <tr><th>Accountant</th><th>Total Amount</th></tr>
                    <?php
                    $query = "SELECT a.account_holder, SUM(am.amount) AS total_amount FROM accountant_amount_tbl am 
                              JOIN accountant_tbl a ON am.accountant_id = a.id GROUP BY am.accountant_id";
                    $result = mysqli_query($conn, $query);
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr><td>{$row['account_holder']}</td><td>{$row['total_amount']}</td></tr>";
                        }
                    } else {
                        echo "<tr><td colspan='2'>No transactions found.</td></tr>";
                    }
                    ?>
                </table>
            </div>
        </div>
        <!-- Pending Renewals Table -->
        <div class="table-container">
            <h3>Pending Renewals (Next 30 Days)</h3>
            <button onclick="printSection('pendingRenewals')" class="print-btn">🖨️ Print</button>
            <div id="pendingRenewals">
                <table>
                    <tr><th>Receipt No</th><th>Release Date</th></tr>
                    <?php
                    $query = "SELECT receipt_number, release_date FROM pledge_tbl 
                              WHERE release_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY)";
                    $result = mysqli_query($conn, $query);
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr><td>{$row['receipt_number']}</td><td>{$row['release_date']}</td></tr>";
                        }
                    } else {
                        echo "<tr><td colspan='2'>No upcoming renewals.</td></tr>";
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    /* General Styles */
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
}

.main-content {
    margin-left: 270px;
    width:1000px;
    margin: 20px auto;
    padding: 20px;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
}

/* Headings */
h2 {
    text-align: center;
    color: #333;
}

h3 {
    color: #555;
    border-bottom: 2px solid #ddd;
    padding-bottom: 5px;
}

/* Tables */
/* Table Layout - Two in a Row */
.table-row {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    margin-bottom: 20px;
}

.table-container {
    width: 48%; /* Adjust width to fit two tables */
    margin-bottom: 20px;
}


table {
    width: 100%;
    border-collapse: collapse;
    background: white;
}

th, td {
    padding: 12px;
    border: 1px solid #ddd;
    text-align: center;
}

th {
    background-color: #007bff;
    color: white;
    font-weight: bold;
}

tr:nth-child(even) {
    background-color: #f9f9f9;
}

/* Buttons */
.print-btn {
    background: linear-gradient(45deg, #007bff, #6610f2);
    color: white;
    padding: 8px 15px;
    margin: 10px 0;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
}

.print-btn:hover {
    background: linear-gradient(45deg, #6610f2, #007bff);
}

/* Pagination */
.pagination {
    text-align: center;
    margin: 20px 0;
}

.pagination a {
    padding: 8px 15px;
    margin: 5px;
    text-decoration: none;
    color: white;
    background: #007bff;
    border-radius: 5px;
    font-size: 14px;
}

.pagination a:hover {
    background: #0056b3;
}

/* Widgets */
.widget {
    padding: 15px;
    background: #f1f1f1;
    border-radius: 5px;
    text-align: center;
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 20px;
}

/* Print Styles */
@media print {
    body {
        background: none;
        color: black;
    }
    
    .main-content {
        box-shadow: none;
        width: 100%;
    }
    
    .print-btn, .pagination {
        display: none; /* Hide buttons and pagination when printing */
    }
    
    table {
        border: 1px solid black;
    }
}

    </style>
<script>
function printSection(sectionId) {
    var content = document.getElementById(sectionId).innerHTML;
    var printWindow = window.open('', '', 'width=800,height=600');
    printWindow.document.write('<html><head><title>Print Report</title></head><body>');
    printWindow.document.write(content);
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.print();
}
</script>
