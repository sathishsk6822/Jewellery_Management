<?php
include 'db_connection.php'; // Ensure this file contains your database connection

header('Content-Type: application/json');

$data = [];

// Fetch total pledges by category
$query1 = "SELECT category, COUNT(*) AS total FROM pledge_tbl GROUP BY category";
$result1 = mysqli_query($conn, $query1);
$pledges = [];
while ($row = mysqli_fetch_assoc($result1)) {
    $pledges[] = $row;
}
$data['pledges'] = $pledges;

// Fetch total releases by month
$query2 = "SELECT DATE_FORMAT(release_date, '%Y-%m') AS month, COUNT(*) AS total FROM release_customer_gst_tbl GROUP BY month";
$result2 = mysqli_query($conn, $query2);
$releases = [];
while ($row = mysqli_fetch_assoc($result2)) {
    $releases[] = $row;
}
$data['releases'] = $releases;

// Fetch total pledges by region
$query3 = "SELECT district, COUNT(*) AS total FROM customer_tbl GROUP BY district";
$result3 = mysqli_query($conn, $query3);
$regions = [];
while ($row = mysqli_fetch_assoc($result3)) {
    $regions[] = $row;
}
$data['regions'] = $regions;

// Return data as JSON
echo json_encode($data);
?>
