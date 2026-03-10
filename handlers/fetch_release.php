<?php
// Include your database connection file
include '../includes/dbconnect.php';

// Set the number of records per page
$limit = 10;

// Get the page number from the URL, default to page 1
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Get the search query from the input
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Calculate the starting record for the current page
$start = ($page - 1) * $limit;

// Prepare the SQL query with pagination and search functionality
$sql = "SELECT * FROM release_customer_gst_tbl 
        WHERE customer_id LIKE '%$search%' OR 
              gst_number LIKE '%$search%' OR 
              receipt_number LIKE '%$search%' OR 
              account_id LIKE '%$search%' 
        LIMIT $start, $limit";

// Execute the query
$result = $conn->query($sql);

// Check if the query returns results
if ($result->num_rows > 0) {
    // Output the fetched rows in the table
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $row['account_id'] . "</td>";
        echo "<td>" . $row['customer_id'] . "</td>";
        echo "<td>" . $row['receipt_number'] . "</td>";
        echo "<td>" . $row['gst_number'] . "</td>";
        echo "<td><img src='" . $row['signature'] . "' alt='Signature' style='width: 100px; height: auto;'></td>";
        echo "</tr>";
    }
} else {
    // If no results are found, display a message
    echo "<tr><td colspan='6'>No records found.</td></tr>";
}
?>
