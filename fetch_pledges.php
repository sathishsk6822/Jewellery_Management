<?php
// Include your database connection file
include 'dbconnect.php';

$limit = 10; // Number of records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Determine the starting record for the current page
$start = ($page - 1) * $limit;

// Modify the query to implement pagination and search
$sql = "SELECT * FROM pledge_tbl WHERE 
        customer_id LIKE '%$search%' OR 
        father_name LIKE '%$search%' OR 
        jewel_description LIKE '%$search%' 
        LIMIT $start, $limit";

$result = $conn->query($sql);

// Check if there are results
if ($result->num_rows > 0) {
    // Loop through the results and output each record as a table row
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $row['accountant_id'] . "</td>";
        echo "<td>" . $row['receipt_number'] . "</td>";
        echo "<td>" . $row['customer_id'] . "</td>";
        echo "<td>" . $row['father_name'] . "</td>";
        echo "<td>" . $row['jewel_weight'] . "</td>";
        echo "<td>" . $row['jewel_description'] . "</td>";
        echo "<td>" . $row['amount'] . "</td>";
        echo "<td>" . $row['jewel_value'] . "</td>";
        echo "<td>" . $row['pledge_date'] . "</td>";
        echo "<td>" . $row['retailer_id'] . "</td>";
        echo "<td>" . $row['interest_1.5'] . "</td>";
        echo "<td>" . $row['interest_amount_1.5'] . "</td>";
        echo "<td>" . $row['interest_1.25'] . "</td>";
        echo "<td>" . $row['interest_amount_1.25'] . "</td>";
        echo "<td>" . $row['release_date'] . "</td>";
        echo "<td>" . $row['paid_amount'] . "</td>";
        echo "<td>" . $row['interest_amount'] . "</td>";
        echo "<td>" . $row['interest_amount125'] . "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='18'>No records found.</td></tr>";
}
?>
