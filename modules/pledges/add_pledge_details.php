<?php
include '../../includes/dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $accountant_id = $_POST['accountant_id'];
    $receipt_number = $_POST['receipt_number'];
    $customer_id = $_POST['customer_id'];
    $father_name = $_POST['father_name'];
    $jewel_weight = $_POST['jewel_weight'];
    $jewel_description = $_POST['jewel_description'];
    $amount = $_POST['amount'];
    $jewel_value = $_POST['jewel_value'];
    $pledge_date = $_POST['pledge_date'];
    $retailer_id = $_POST['retailer_id'];
    $status = $_POST['status'];

    // Handle missing optional fields
    $release_date = isset($_POST['release_date']) && !empty($_POST['release_date']) ? $_POST['release_date'] : date("Y-m-d"); 
    $paid_amount = isset($_POST['paid_amount']) && !empty($_POST['paid_amount']) ? $_POST['paid_amount'] : 0;
    $usernameNew = isset($_POST['usernameNew']) && !empty($_POST['usernameNew']) ? $_POST['usernameNew'] : 'Unknown';

    // Interest Calculation
    $interest = $_POST['interest'];
    $interests_amount = ($amount * $interest) / 100; 

    // Prepared statement
    $sql = "INSERT INTO pledge_tbl (accountant_id, receipt_number, customer_id, father_name, jewel_weight, jewel_description, amount, jewel_value, pledge_date, retailer_id, release_date, paid_amount, status, usernameNew, interest, interests_amount) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iisssdidsdsssdds", $accountant_id, $receipt_number, $customer_id, $father_name, $jewel_weight, $jewel_description, $amount, $jewel_value, $pledge_date, $retailer_id, $release_date, $paid_amount, $status, $usernameNew, $interest, $interests_amount);

    if ($stmt->execute()) {
        echo "<script>alert('Pledge details added successfully!'); window.location.href='?page=list_pledge_details';</script>";
    } else {
        echo "<script>alert('Error adding pledge details!');</script>";
    }
}
?>



<h2>Add Pledge Details</h2>
<form method="post"style="height: 530px; overflow-y: auto; scrollbar-width:none;">
    <div class="row">
        <div class="col-md-6">
            <label>Accountant ID:</label>
            <input type="text" name="accountant_id" required class="form-control">
        </div>
        <div class="col-md-6">
            <label>Receipt Number:</label>
            <input type="text" name="receipt_number" required class="form-control">
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <label>Customer ID:</label>
            <input type="text" name="customer_id" required class="form-control">
        </div>
        <div class="col-md-6">
            <label>Father's Name:</label>
            <input type="text" name="father_name" required class="form-control">
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <label>Jewel Weight:</label>
            <input type="text" name="jewel_weight" required class="form-control">
        </div>
        <div class="col-md-6">
            <label>Jewel Description:</label>
            <input type="text" name="jewel_description" required class="form-control">
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <label>Amount:</label>
            <input type="text" name="amount" required class="form-control">
        </div>
        <div class="col-md-6">
            <label>Jewel Value:</label>
            <input type="text" name="jewel_value" required class="form-control">
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <label>Pledge Date:</label>
            <input type="date" name="pledge_date" required class="form-control">
        </div>
        <div class="col-md-6">
            <label>Retailer ID:</label>
            <input type="text" name="retailer_id" required class="form-control">
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <label>Release Date:</label>
            <input type="date" name="release_date" class="form-control">
        </div>
        <div class="col-md-6">
            <label>Paid Amount:</label>
            <input type="text" name="paid_amount" class="form-control">
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <label>Interest Rate:</label>
            <select name="interest" required class="form-control" onchange="calculateInterest()">
                <option value="1.5">1.5%</option>
                <option value="1.25">1.25%</option>
            </select>
        </div>
        <div class="col-md-6">
            <label>Interest Amount:</label>
            <input type="text" name="interests_amount" id="interests_amount" readonly class="form-control">
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <label>Status:</label>
            <select name="status" class="form-control">
                <option value="Active">Active</option>
                <option value="Released">Released</option>
            </select>
        </div>
        <div class="col-md-6">
            <label>Username:</label>
            <input type="text" name="usernameNew" required class="form-control">
        </div>
    </div>

    <br>
    <button type="submit" class="btn btn-primary">Add Pledge</button>
</form>

<script>
function calculateInterest() {
    let amount = document.querySelector('input[name="amount"]').value;
    let interestRate = document.querySelector('select[name="interest"]').value;
    if (amount) {
        let interestAmount = (parseFloat(amount) * parseFloat(interestRate)) / 100;
        document.getElementById('interests_amount').value = interestAmount.toFixed(2);
    }
}
</script>
