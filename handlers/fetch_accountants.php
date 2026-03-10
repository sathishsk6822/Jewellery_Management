<?php
include '../includes/dbconnect.php';

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 20;
$offset = ($page - 1) * $limit;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// SQL Query with Search
if ($search !== '') {
    $stmt = $conn->prepare("SELECT id, account_holder, balance, mobile_number, address, create_date FROM accountant_tbl 
                            WHERE account_holder LIKE ? OR mobile_number LIKE ? OR address LIKE ? 
                            LIMIT ?, ?");
    $searchTerm = "%$search%";
    $stmt->bind_param("sssii", $searchTerm, $searchTerm, $searchTerm, $offset, $limit);
} else {
    $stmt = $conn->prepare("SELECT id, account_holder, balance, mobile_number, address, create_date FROM accountant_tbl 
                            LIMIT ?, ?");
    $stmt->bind_param("ii", $offset, $limit);
}

$stmt->execute();
$result = $stmt->get_result();

// Generate Table Rows
while ($row = $result->fetch_assoc()) {
    echo "<tr>
            <td>{$row['id']}</td>
            <td>".htmlspecialchars($row['account_holder'])."</td>
            <td>".number_format($row['balance'], 2)."</td>
            <td>".htmlspecialchars($row['mobile_number'])."</td>
            <td>".htmlspecialchars($row['address'])."</td>
            <td>".htmlspecialchars($row['create_date'])."</td>
        </tr>";
}

$stmt->close();
$conn->close();
?>
