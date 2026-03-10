<?php
include '../../includes/header.php'; // Include header
include '../../includes/sidebar.php'; // Include sidebar
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Renewal Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        /* General Styles */
body {
    background: #f8f9fa;
    font-family: 'Poppins', sans-serif;
    margin-left: 260px;
            padding: 5px;
            width: calc(100% - 270px);
}

/* Form Container */
.form-container {
    max-width: 500px;
    background: white;
    padding: 30px;
    margin: auto;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: 0.3s ease-in-out;
}

.form-container:hover {
    transform: translateY(-5px);
}

/* Form Title */
.form-title {
    text-align: center;
    color: #333;
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 20px;
}

/* Input Group */
.input-group {
    margin-bottom: 15px;
}

.input-group label {
    display: block;
    font-size: 16px;
    font-weight: 500;
    color: #333;
    margin-bottom: 5px;
}

.input-group input {
    width: 100%;
    padding: 10px;
    font-size: 14px;
    border: 2px solid #ddd;
    border-radius: 5px;
    outline: none;
    transition: 0.3s ease-in-out;
}

.input-group input:focus {
    border-color: #28a745;
    box-shadow: 0 0 5px rgba(40, 167, 69, 0.5);
}

/* Submit Button */
.btn-submit {
    width: 100%;
    padding: 10px;
    background: linear-gradient(45deg, #007bff, #0056b3);
    border: none;
    color: white;
    font-size: 16px;
    font-weight: bold;
    border-radius: 5px;
    cursor: pointer;
    transition: 0.3s ease-in-out;
}

.btn-submit:hover {
    background: linear-gradient(45deg, #6610f2, #007bff);
}

        </style>
</head>
<body>
    <div class="container mt-5">
        <h2>Renewal Form</h2>
        <form id="renewalForm" action='../../handlers/process_renewal.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="customer_id" class="form-label">Customer ID</label>
                <input type="text" class="form-control" id="customer_id" name="customer_id" required>
            </div>

            <div class="mb-3">
                <label for="account_id" class="form-label">Account ID</label>
                <input type="text" class="form-control" id="account_id" name="account_id">
            </div>

            <div class="mb-3">
                <label for="receipt_number" class="form-label">Receipt Number</label>
                <input type="text" class="form-control" id="receipt_number" name="receipt_number">
            </div>

            <div class="mb-3">
                <label for="gst_number" class="form-label">GST Number</label>
                <input type="text" class="form-control" id="gst_number" name="gst_number">
            </div>

            <div class="mb-3">
                <label for="signature" class="form-label">Upload Signature</label>
                <input type="file" class="form-control" id="signature" name="signature" accept="image/*">
            </div>

            <div class="mb-3">
                <label for="renewal_date" class="form-label">Renewal Date</label>
                <input type="date" class="form-control" id="renewal_date" name="renewal_date" required>
            </div>

            <button type="submit" class="btn btn-success">Submit Renewal</button>
        </form>
    </div>

    <script>
        document.getElementById('customer_id').addEventListener('input', function() {
            var customerId = this.value;
            var today = new Date();
            var formattedDate = today.getFullYear() + ('0' + (today.getMonth() + 1)).slice(-2) + ('0' + today.getDate()).slice(-2);
            document.getElementById('receipt_number').value = formattedDate + customerId;
        });
    </script>
</body>
</html>
