<?php
include '../../includes/dbconnect.php'; // Include database connection file
include '../../includes/sidebar.php';

function generateReceiptNumber($conn, $prefix = "B-") {
    // Fetch the last receipt number based on the prefix
    $sql = "SELECT receipt_number FROM pledge_tbl 
            WHERE receipt_number LIKE '$prefix%' 
            ORDER BY CAST(SUBSTRING(receipt_number, 3) AS UNSIGNED) DESC 
            LIMIT 1";
    
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $lastNumber = intval(substr($row['receipt_number'], 2)); // Extract number part
        $newNumber = $lastNumber + 1;
    } else {
        $newNumber = 1000; // Start from 1000 if no previous record
    }

    return $prefix . $newNumber; // Return formatted receipt number
}



// Fetch the accountant IDs dynamically
$accountant_ids = [];
$sql_accountant = "SELECT accountant_id FROM accountant_amount_tbl LIMIT 5"; // Modify as needed
$result_accountant = $conn->query($sql_accountant);

if ($result_accountant->num_rows > 0) {
    while ($row = $result_accountant->fetch_assoc()) {
        $accountant_ids[] = $row['accountant_id']; // Store all accountant IDs in an array
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $accountant_id = $_POST['accountant_id']; // FIXED: Retrieve accountant_id
    $customer_id = $_POST['customer_id'];
    $father_name = $_POST['father_name'];
    $jewel_weight = $_POST['jewel_weight'];
    $jewel_description = $_POST['jewel_description'];
    $amount = $_POST['amount'];
    $jewel_value = $_POST['jewel_value'];
    $pledge_date = $_POST['pledge_date'];
    $retailer_id = $_POST['retailer_id'];
    $release_date = $_POST['release_date'];
    $paid_amount = $_POST['paid_amount'];
    $status = $_POST['status'];
    $usernameNew = $_POST['usernameNew'];
    $interest = $_POST['interest'];
    $interests_amount = $_POST['interests_amount'];
    
    $receipt_number = generateReceiptNumber($conn); // Auto-generate receipt number

    $sql = "INSERT INTO pledge_tbl (accountant_id, receipt_number, customer_id, father_name, jewel_weight, 
            jewel_description, amount, jewel_value, pledge_date, retailer_id, release_date, paid_amount, 
            status, usernameNew, interest, interests_amount) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issssdsssssdssis", $accountant_id, $receipt_number, $customer_id, $father_name, $jewel_weight, 
                      $jewel_description, $amount, $jewel_value, $pledge_date, $retailer_id, $release_date, 
                      $paid_amount, $status, $usernameNew, $interest, $interests_amount);

                      if ($stmt->execute()) {
                        echo "<script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    var toastEl = new bootstrap.Toast(document.getElementById('successToast'));
                                    toastEl.show();
                                });
                              </script>";
                    }
                    

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pledge Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 20px;
        }
        .form-container {
            background: #fff;
            padding: 20px;
            padding-top: 80px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 1000px;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }
        .form-group {
            display: flex;
            flex-direction: column;
        }
        label {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #555;
        }
        input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }
        .full-width {
            grid-column: span 2;
        }
        button {
            width: 100%;
            padding: 10px;
            background: linear-gradient(45deg, #007bff, #6610f2);
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }
        button:hover {
            background: linear-gradient(45deg, #6610f2, #007bff);
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Pledge Form</h2>
        <form id="pledgeForm" method="POST" action="">

            <div class="form-grid">
            <div class="form-group">
    <label for="accountant_id">Select Accountant:</label>
    <select name="accountant_id" id="accountant_id" class="form-control" required>
        <option value="">-- Select Accountant --</option>
        <?php
        foreach ($accountant_ids as $id) {
            echo "<option value='$id'>$id</option>";
        }
        ?>
    </select>
</div>

                <div class="form-group">
    <label>Receipt Number:</label>
    <input type="text" name="receipt_number" value="<?php echo generateReceiptNumber($conn); ?>" readonly>
</div>

                <div class="form-group">
                    <label>Customer ID:</label>
                    <input type="text" name="customer_id" required>
                </div>
                <div class="form-group">
                    <label>Father Name:</label>
                    <input type="text" name="father_name" required>
                </div>
                <div class="form-group">
                    <label>Jewel Weight:</label>
                    <input type="text" name="jewel_weight" required>
                </div>
                <div class="form-group">
                    <label>Jewel Description:</label>
                    <input type="text" name="jewel_description" required>
                </div>
                <div class="form-group">
                    <label>Amount:</label>
                    <input type="number" step="0.01" name="amount" required>
                </div>
                <div class="form-group">
                    <label>Jewel Value:</label>
                    <input type="number" step="0.01" name="jewel_value" required>
                </div>
                <div class="form-group">
                    <label>Pledge Date:</label>
                    <input type="date" name="pledge_date" required>
                </div>
                <div class="form-group">
                    <label>Retailer ID:</label>
                    <input type="text" name="retailer_id" required>
                </div>
                <div class="form-group">
                    <label>Release Date:</label>
                    <input type="date" name="release_date">
                </div>
                <div class="form-group">
                    <label>Paid Amount:</label>
                    <input type="number" step="0.01" name="paid_amount">
                </div>
                <div class="form-group">
                    <label>Status:</label>
                    <input type="text" name="status" required>
                </div>
                <div class="form-group">
                    <label>Username:</label>
                    <input type="text" name="usernameNew" required>
                </div>
                <div class="form-group">
                    <label>Interest:</label>
                    <input type="text" name="interest" required>
                </div>
                <div class="form-group">
                    <label>Interest Amount:</label>
                    <input type="number" step="0.01" name="interests_amount" required>
                </div>
                <div class="form-group full-width">
                    <button type="submit">Submit</button>
                </div>
            </div>
        </form>
    </div>

    <div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="successToast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                Form submitted successfully!
            </div>
            <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<script>
    function showToast() {
        var toastEl = new bootstrap.Toast(document.getElementById('successToast'));
        toastEl.show();
    }
    document.getElementById("pledgeForm").addEventListener("submit", function(event){
            event.preventDefault();

            fetch("", {
                method: "POST",
                body: new FormData(this)
            })
            .then(response => response.text())
            .then(data => {
                alert("Form submitted successfully!");
                document.getElementById("pledgeForm").reset(); // Reset form fields
                location.reload(); // Reload page to reset receipt number
            })
            .catch(error => console.error("Error:", error));
        });

</script>
</body>
</html>
