<?php
include '../../includes/dbconnect.php';
include '../../includes/header.php'; 
include '../../includes/sidebar.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $recordType = $_POST['recordType'];

    if ($recordType == "customer") {
        $query = "SELECT * FROM customer_tbl";
    } elseif ($recordType == "pledge") {
        $query = "SELECT * FROM pledge_tbl";
    } elseif ($recordType == "release") {
        $query = "SELECT * FROM release_customer_gst_tbl";
    } else {
        die("Invalid Record Type Selected.");
    }

    $result = mysqli_query($conn, $query);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Records</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        /* General Styles */
body {
    font-family: 'Poppins', sans-serif;
    background-color: #f4f7f9;
    color: #333;
    overflow: auto;
    scrollbar-width: none;
    -ms-overflow-style: none;
    margin-left: 230px;
            padding: 5px;
            width: calc(100% - 270px);
}
body::-webkit-scrollbar {
    display: none;
}

/* Container */
.container {
    max-width: 90%;
    margin: 40px auto;
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
    overflow: auto;
    scrollbar-width: none;
    -ms-overflow-style: none;
}

/* Headings */
h2 {
    text-align: center;
    color: #2c3e50;
    font-size: 28px;
    font-weight: bold;
}

/* Table Styling */
.table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
}

.table thead {
    background-color: #3498db;
    color: white;
    text-transform: uppercase;
}

.table th, .table td {
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

.table tbody tr:hover {
    background-color: #f1f1f1;
}

.table tbody tr:nth-child(even) {
    background-color: #f9f9f9;
}

/* Buttons */
.btn {
    display: inline-block;
    padding: 10px 20px;
    font-size: 16px;
    font-weight: bold;
    text-decoration: none;
    border-radius: 6px;
    transition: 0.3s;
    cursor: pointer;
}

.btn-primary {
    background: linear-gradient(45deg, #007bff, #6610f2);
    color: white;
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(45deg, #6610f2, #007bff);
}

.btn-success {
    background: #e74c3c;
    color: white;
    border: none;
}

.btn-success:hover {
    background: #c0392b;
}

/* Responsive Design */
@media screen and (max-width: 768px) {
    .container {
        width: 95%;
        padding: 15px;
    }

    .table th, .table td {
        font-size: 14px;
        padding: 8px;
    }

    .btn {
        width: 100%;
        text-align: center;
    }
}

/* Print Styling */
@media print {
    body {
        background: white;
        color: black;
    }

    .container {
        width: 100%;
        box-shadow: none;
        padding: 0;
    }

    .no-print {
        display: none !important;
    }

    .table {
        border: 2px solid black;
    }

    .table th {
        background: #000;
        color: white;
        border: 1px solid black;
    }

    .table td {
        border: 1px solid black;
    }
}

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2>Print Records</h2>
        <button class="btn btn-success no-print" onclick="window.print()">Print</button>
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <?php
                    if ($recordType == "customer") {
                        echo "<th>ID</th><th>Name</th><th>Father Name</th><th>Mobile</th><th>Address</th><th>Interest</th><th>Created At</th><th>Status</th><th>Signature</th>";
                    } elseif ($recordType == "pledge") {
                        echo "<th>ID</th><th>Customer ID</th><th>Amount</th><th>Date</th><th>Status</th>";
                    } elseif ($recordType == "release") {
                        echo "<th>ID</th><th>Account ID</th><th>Customer ID</th><th>Receipt Number</th><th>GST Number</th>";
                    }
                    ?>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <?php
                        foreach ($row as $value) {
                            echo "<td>{$value}</td>";
                        }
                        ?>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
