<?php
include '../includes/dbconnect.php';

if (isset($_GET['delete_id'])) {
    $customer_id = intval($_GET['delete_id']); // Sanitize input

    // Check if customer exists before deletion
    $checkQuery = "SELECT * FROM customer_tbl WHERE customer_id = ?";
    $stmtCheck = $conn->prepare($checkQuery);
    $stmtCheck->bind_param("i", $customer_id);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();

    if ($resultCheck->num_rows > 0) {
        // Perform deletion
        $sql = "DELETE FROM customer_tbl WHERE customer_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $customer_id);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Customer deleted successfully."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error deleting customer."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Customer not found."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request."]);
}

$conn->close();
?>
