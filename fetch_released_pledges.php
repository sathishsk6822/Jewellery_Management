<?php
include 'db_connection.php'; // Adjust as needed

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$offset = ($page - 1) * $limit;

// Fetch records with pagination
$query = "SELECT r.receipt_number, c.customer_name, r.gst_number 
          FROM release_customer_gst_tbl r 
          JOIN customer_tbl c ON r.customer_id = c.customer_id 
          ORDER BY r.id DESC 
          LIMIT $limit OFFSET $offset";

$result = mysqli_query($conn, $query);

echo '<table>';
echo '<tr><th>Receipt No</th><th>Customer Name</th><th>GST Number</th></tr>';

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr><td>{$row['receipt_number']}</td><td>{$row['customer_name']}</td><td>{$row['gst_number']}</td></tr>";
    }
} else {
    echo "<tr><td colspan='3'>No data found.</td></tr>";
}

echo '</table>';
?>
