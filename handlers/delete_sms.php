<?php
include '../includes/dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];

    $stmt = $conn->prepare("DELETE FROM sms_history WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "SMS record deleted successfully"]);
    } else {
        echo json_encode(["success" => false, "error" => "Failed to delete SMS record"]);
    }

    $stmt->close();
    $conn->close();
}
?>
