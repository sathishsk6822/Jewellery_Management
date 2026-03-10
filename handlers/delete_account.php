<?php
include '../includes/dbconnect.php'; // Include database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];

    // Delete the accountant record
    $sql = "DELETE FROM accountant_tbl WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Account deleted successfully!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to delete account!"]);
    }
}
?>
