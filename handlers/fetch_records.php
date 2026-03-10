<?php
include '../includes/dbconnect.php';

if (isset($_POST['recordType'])) {
    $recordType = $_POST['recordType'];

    if ($recordType == "customer") {
        $query = "SELECT * FROM customer_tbl";
        $columns = "<th>ID</th><th>Name</th><th>Father Name</th><th>Mobile</th><th>Address</th><th>Interest</th><th>Created At</th><th>Status</th>";
    } elseif ($recordType == "pledge") {
        $query = "SELECT * FROM pledge_tbl";
        $columns = "<th>ID</th><th>Customer ID</th><th>Amount</th><th>Date</th><th>Status</th>";
    } elseif ($recordType == "release") {
        $query = "SELECT * FROM release_customer_gst_tbl";
        $columns = "<th>ID</th><th>Account ID</th><th>Customer ID</th><th>Receipt Number</th><th>GST Number</th>";
    } else {
        echo "Invalid Record Type";
        exit;
    }

    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        echo "<table class='table table-bordered'>";
        echo "<thead><tr>$columns</tr></thead><tbody>";

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            foreach ($row as $value) {
                echo "<td>{$value}</td>";
            }
            echo "</tr>";
        }

        echo "</tbody></table>";
    } else {
        echo "<p>No records found.</p>";
    }
} else {
    echo "Invalid Request";
}
?>
