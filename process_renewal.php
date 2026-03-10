<?php
include 'dbconnect.php'; // Database connectio
$sql = "SELECT customer_id FROM release_customer_gst_tbl WHERE renewal_date = DATE_ADD(CURDATE(), INTERVAL 7 DAY)";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    $message = "Customer " . $row['customer_name'] . " has a pledge due for renewal soon.";
    addNotification($row['customer_id'], "Renewal Reminder", $message, "renewal");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_id = mysqli_real_escape_string($conn, $_POST['customer_id']);
    $account_id = mysqli_real_escape_string($conn, $_POST['account_id']);
    $receipt_number = mysqli_real_escape_string($conn, $_POST['receipt_number']);
    $gst_number = mysqli_real_escape_string($conn, $_POST['gst_number']);
    $renewal_date = mysqli_real_escape_string($conn, $_POST['renewal_date']);


    // Handle File Upload (Signature)
    $signature = "";
    if (!empty($_FILES['signature']['name'])) {
        $target_dir = "uploads/signatures/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $file_name = basename($_FILES["signature"]["name"]);
        $target_file = $target_dir . time() . "_" . $file_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check file type
        $allowed_types = array("jpg", "jpeg", "png", "gif");
        if (!in_array($imageFileType, $allowed_types)) {
            echo "<script>alert('Only JPG, JPEG, PNG, and GIF files are allowed!'); window.history.back();</script>";
            exit();
        }

        if (move_uploaded_file($_FILES["signature"]["tmp_name"], $target_file)) {
            $signature = $target_file;
        } else {
            echo "<script>alert('Error uploading signature!'); window.history.back();</script>";
            exit();
        }
    }

    // Insert data into release_customer_gst_tbl
    $query = "INSERT INTO release_customer_gst_tbl (account_id, customer_id, receipt_number, gst_number, signature, renewal_date)
              VALUES ('$account_id', '$customer_id', '$receipt_number', '$gst_number', '$signature', '$renewal_date')";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Renewal Successful!'); window.location.href='renewalform.php';</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($conn) . "'); window.history.back();</script>";
    }
}

$conn->close();
?>
