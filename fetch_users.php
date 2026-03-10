<?php
include 'dbconnect.php';

$limit = 10; // Number of records per page
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$offset = ($page - 1) * $limit;

// Base query
$sql = "SELECT * FROM user_tbl WHERE 1";

// Apply search filter
if (!empty($search)) {
    $sql .= " AND (name LIKE '%$search%' OR username LIKE '%$search%' OR MobileNumber LIKE '%$search%' OR EmailID LIKE '%$search%')";
}

// Pagination
$sql .= " ORDER BY id ASC LIMIT $limit OFFSET $offset";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['name']}</td>
                <td>{$row['username']}</td>
                <td>{$row['MobileNumber']}</td>
                <td>{$row['EmailID']}</td>
                <td>{$row['usertype']}</td>
                <td>{$row['address']}</td>
                <td>{$row['create_date']}</td>
                <td>{$row['status']}</td>
              </tr>";
    }
} else {
    echo ""; // Returns empty if no more data (prevents infinite looping)
}
?>
