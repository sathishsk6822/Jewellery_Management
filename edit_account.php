<?php
include 'dbconnect.php'; // Include database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $account_holder = $_POST['account_holder'];
    $balance = $_POST['balance'];
    $mobile_number = $_POST['mobile_number'];
    $address = $_POST['address'];

    // Update the accountant record
    $sql = "UPDATE accountant_tbl 
            SET account_holder = ?, balance = ?, mobile_number = ?, address = ? 
            WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sissi", $account_holder, $balance, $mobile_number, $address, $id);

    if ($stmt->execute()) {
        // Fetch updated data to send back
        $query = "SELECT * FROM accountant_tbl WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $updatedData = $result->fetch_assoc();

        echo json_encode([
            "success" => true,
            "message" => "Account updated successfully!",
            "id" => $updatedData['id'],
            "account_holder" => $updatedData['account_holder'],
            "balance" => $updatedData['balance'],
            "mobile_number" => $updatedData['mobile_number'],
            "address" => $updatedData['address'],
            "create_date" => $updatedData['create_date']
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to update account!"]);
    }
}
?>
