<?php
include 'dbconnect.php';

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 20;
$offset = ($page - 1) * $limit;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// SQL Query with Search
if ($search !== '') {
    $stmt = $conn->prepare("SELECT customer_id, customer_name, father_name, mobile_number, address, interest, create_date, status FROM customer_tbl 
                            WHERE customer_name LIKE ? OR mobile_number LIKE ? OR address LIKE ? 
                            LIMIT ?, ?");
    $searchTerm = "%$search%";
    $stmt->bind_param("sssii", $searchTerm, $searchTerm, $searchTerm, $offset, $limit);
} else {
    $stmt = $conn->prepare("SELECT customer_id, customer_name, father_name, mobile_number, address, interest, create_date, status FROM customer_tbl 
                            LIMIT ?, ?");
    $stmt->bind_param("ii", $offset, $limit);
}

$stmt->execute();
$result = $stmt->get_result();

// Generate Table Rows
while ($row = $result->fetch_assoc()) {
    echo "<tr>
            <td>{$row['customer_id']}</td>
            <td>".htmlspecialchars($row['customer_name'])."</td>
            <td>".htmlspecialchars($row['father_name'])."</td>
            <td>".htmlspecialchars($row['mobile_number'])."</td>
            <td>".htmlspecialchars($row['address'])."</td>
            <td>".htmlspecialchars($row['interest'])."</td>
            <td>".htmlspecialchars($row['create_date'])."</td>
            <td>".htmlspecialchars($row['status'])."</td>
        </tr>";
}

$stmt->close();
$conn->close();
?>
