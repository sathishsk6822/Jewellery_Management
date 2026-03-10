<?php
include 'dbconnect.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "UPDATE notificationstbl SET is_read = 1 WHERE id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to update"]);
    }
}
?>
