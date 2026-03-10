<?php
include 'dbconnect.php';

if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];

    // Prepare DELETE query
    $sql = "DELETE FROM user_tbl WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "User deleted successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to delete user"]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
}
?>
