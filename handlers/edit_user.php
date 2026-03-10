<?php
include '../includes/dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $name = $_POST['full_name'];
    $username = $_POST['username'];
    $mobile_number = $_POST['mobile_number'];
    $email_address = $_POST['email_address'];
    $address = $_POST['address'];
    $status = $_POST['status'];

    // Check if password is provided
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $sql = "UPDATE user_tbl SET name=?, username=?, password=?, mobilenumber=?, emailid=?, address=?, status=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssi", $name, $username, $password, $mobile_number, $email_address, $address, $status, $id);
    } else {
        $sql = "UPDATE user_tbl SET name=?, username=?, mobilenumber=?, emailid=?, address=?, status=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssi", $name, $username, $mobile_number, $email_address, $address, $status, $id);
    }

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "User updated successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to update user"]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
}
?>
