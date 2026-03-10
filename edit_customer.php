<?php
include 'dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_id = $_POST['customer_id'];
    $customer_name = $_POST['customer_name'];
    $mobile_number = $_POST['mobile_number'];
    $address = $_POST['address'];
    $status = $_POST['status'];

    $sql = "UPDATE customer_tbl SET 
            customer_name = '$customer_name', 
            mobile_number = '$mobile_number', 
            address = '$address', 
            status = '$status' 
            WHERE customer_id = '$customer_id'";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["status" => "success", "message" => "Customer updated successfully!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error updating customer: " . $conn->error]);
    }
}
?>
