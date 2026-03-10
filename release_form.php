<?php
include 'dbconnect.php'; // Database connection file

// Create Release Entry Logic
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $account_id = $_POST['account_id'];
    $customer_id = $_POST['customer_id'];
    $receipt_number = $_POST['receipt_number'];
    $gst_number = $_POST['gst_number'];

    // Handle Signature Upload
    $target_dir = "uploads/signatures/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $signature_name = basename($_FILES["signature"]["name"]);
    $target_file = $target_dir . time() . "_" . $signature_name; // Unique filename

    if (move_uploaded_file($_FILES["signature"]["tmp_name"], $target_file)) {
        // Insert into Database
        $sql = "INSERT INTO release_customer_gst_tbl (account_id, customer_id, receipt_number, gst_number, signature) 
                VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $account_id, $customer_id, $receipt_number, $gst_number, $target_file);

        if ($stmt->execute()) {
            // Redirect after successful insertion
            echo "<script>alert('Release Entry added successfully!'); window.location.href='release_form.php';</script>";
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "Error uploading file.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Release Entry</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>

<div class="container">
    <div class="card p-4">
        <h2 class="text-center"><i class="fa fa-user-plus"></i> Create Release Entry</h2>
        <form method="post" action="" enctype="multipart/form-data">
            
            <div class="mb-3">
                <label class="form-label"><i class="fa fa-id-card"></i> Account ID:</label>
                <input type="text" name="account_id" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label"><i class="fa fa-users"></i> Customer ID:</label>
                <input type="text" name="customer_id" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label"><i class="fa fa-receipt"></i> Receipt Number:</label>
                <input type="text" name="receipt_number" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label"><i class="fa fa-file-invoice"></i> GST Number:</label>
                <input type="text" name="gst_number" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label"><i class="fa fa-signature"></i> Signature (Upload Image):</label>
                <input type="file" name="signature" class="form-control" accept="image/*" required onchange="previewFile()">
                <img id="preview" class="file-preview d-none" alt="Signature Preview">
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary w-100"><i class="fa fa-save"></i> Save Entry</button>
            </div>
        </form>
    </div>
</div>

<script>
    function previewFile() {
        const preview = document.getElementById('preview');
        const file = document.querySelector('input[name="signature"]').files[0];
        const reader = new FileReader();

        reader.addEventListener("load", function () {
            preview.src = reader.result;
            preview.classList.remove("d-none");
        }, false);

        if (file) {
            reader.readAsDataURL(file);
        }
    }
</script>

</body>
</html>
