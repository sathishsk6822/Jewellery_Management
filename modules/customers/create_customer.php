<?php
include '../../includes/dbconnect.php';
include '../../includes/header.php'; // Include header
include '../../includes/sidebar.php'; // Include sidebar

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_name = trim(htmlspecialchars($_POST['customer_name']));
    $father_name = trim(htmlspecialchars($_POST['father_name']));
    $mobile_number = trim($_POST['mobile_number']);
    $interest = trim(htmlspecialchars($_POST['interest']));
    $address = trim(htmlspecialchars($_POST['address']));
    $status = ($_POST['status'] == '1') ? 1 : 0;

    if (!preg_match('/^[0-9]{10}$/', $mobile_number)) {
        $message = "<div class='alert alert-danger'>Invalid mobile number format!</div>";
    } else {
        $target_dir = "uploads/";
        $signature = $_FILES['signature']['name'];
        $signature_tmp = $_FILES['signature']['tmp_name'];
        $signature_size = $_FILES['signature']['size'];
        $signature_type = strtolower(pathinfo($signature, PATHINFO_EXTENSION));

        if ($signature_type !== "png") {
            $message = "<div class='alert alert-danger'>Only PNG files are allowed!</div>";
        } elseif ($signature_size > 50000000) {
            $message = "<div class='alert alert-danger'>File is too large. Max size is 500KB.</div>";
        } else {
            $new_filename = uniqid('signature_', true) . '.' . $signature_type;
            $target_file = $target_dir . $new_filename;

            if (move_uploaded_file($signature_tmp, $target_file)) {
                $stmt = $conn->prepare("INSERT INTO customer_tbl (customer_name, father_name, mobile_number, interest, address, status, signature) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssssis", $customer_name, $father_name, $mobile_number, $interest, $address, $status, $new_filename);

                if ($stmt->execute()) {
                    echo "<script>setTimeout(function() { showToast('Customer added successfully!', 'success'); }, 500);</script>";
                } else {
                    echo "<script>setTimeout(function() { showToast('Error: " . $stmt->error . "', 'danger'); }, 500);</script>";
                }
                
                $stmt->close();
            } else {
                $message = "<div class='alert alert-danger'>Error uploading the file.</div>";
            }
        }
    }
}
?>
<style>
    /* General Styles */
body {
    font-family: 'Poppins', sans-serif;
    background-color:rgb(245, 246, 250);
    color: #333;
    margin: 0;
    padding: 0;
    display: flex;
    min-height: 100vh;
}

/* Content Styles */
.content {
    margin-left: 270px;
    padding: 40px;
    width: calc(100% - 270px);
}

h2 {
    text-align: center;
    font-size: 28px;
    font-weight: bold;
    color: #2c3e50;
    margin-bottom: 20px;
}

/* Form Styles */
form {
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    max-width: 600px;
    margin: auto;
}
label {
    font-weight: 600;
    color: #34495e;
}
input, textarea, select {
    width: 100%;
    padding: 10px;
    margin-top: 5px;
    margin-bottom: 15px;
    border: 1px solid #bdc3c7;
    border-radius: 5px;
    transition: border 0.3s;
}
input:focus, textarea:focus, select:focus {
    border-color: #3498db;
    outline: none;
}

/* Button Styling */
.btn-primary {
    background: linear-gradient(45deg, #007bff, #6610f2);
    border: none;
    font-size: 16px;
    font-weight: bold;
    padding: 12px;
    border-radius: 8px;
    transition: all 0.3s ease-in-out;
}

/* Button Hover Effect */
.btn-primary:hover {
    background: linear-gradient(45deg, #6610f2, #007bff);
    transform: translateY(-2px);
    box-shadow: 0px 4px 12px rgba(0, 123, 255, 0.3);
}

/* Alert Messages */
.alert {
    padding: 15px;
    border-radius: 5px;
    margin-bottom: 15px;
    text-align: center;
}
.alert-success {
    background: #2ecc71;
    color: white;
}
.alert-danger {
    background: #e74c3c;
    color: white;
}
/* Toast Notification Styles */
.toast-container {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 1050;
}

.toast {
    min-width: 280px;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
    font-family: 'Poppins', sans-serif;
    animation: slideIn 0.3s ease-in-out;
}

.toast-body {
    font-size: 16px;
    font-weight: 500;
    padding: 15px;
}

.toast .btn-close {
    margin-right: 10px;
}

.toast.bg-success {
    background-color: #28a745 !important;
    color: #fff;
}

.toast.bg-danger {
    background-color: #dc3545 !important;
    color: #fff;
}

/* Animation for Toast */
@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes slideOut {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(100%);
        opacity: 0;
    }
}

/* Automatically hide toast */
.toast.fade.hide {
    animation: slideOut 0.3s ease-in-out;
}


/* Responsive Design */
@media (max-width: 768px) {
    .sidebar {
        width: 200px;
    }
    .content {
        margin-left: 210px;
        padding: 20px;
    }
}

@media (max-width: 576px) {
    .sidebar {
        display: none;
    }
    .content {
        margin-left: 0;
        width: 100%;
    }
    form {
        padding: 15px;
    }
}
</style>


<!-- Toast Container -->
<!-- Toast Container -->
<div class="toast-container position-fixed top-0 end-0 p-3">
    <div id="toastMessage" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true" >
        <div class="d-flex">
            <div class="toast-body" id="toastBody">
                <!-- Toast Message will be inserted dynamically -->
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>


<div class="content">
    

    <?php if ($message) echo $message; ?>

    <form method="POST" action="" enctype="multipart/form-data">
    <h2 class="text-center">Add Customer</h2>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="customer_name" class="form-label">Customer Name</label>
                <input type="text" class="form-control" id="customer_name" name="customer_name" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="father_name" class="form-label">Father's Name</label>
                <input type="text" class="form-control" id="father_name" name="father_name" required>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="mobile_number" class="form-label">Mobile Number</label>
                <input type="text" class="form-control" id="mobile_number" name="mobile_number" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="interest" class="form-label">Interest</label>
                <input type="text" class="form-control" id="interest" name="interest" required>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-3">
                <label for="address" class="form-label">Address</label>
                <textarea class="form-control" id="address" name="address" required></textarea>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label for="signature" class="form-label">Signature (PNG Only)</label>
                <input type="file" class="form-control" id="signature" name="signature" accept="image/png" required>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Add Customer</button>
    </form>
</div>
<script>
   function showToast(message, type) {
    let toast = document.getElementById("toastMessage");
    let toastBody = document.getElementById("toastBody");

    // Set message
    toastBody.innerText = message;

    // Change background color based on type
    toast.classList.remove("bg-success", "bg-danger"); // Reset classes
    if (type === "success") {
        toast.classList.add("bg-success");
    } else {
        toast.classList.add("bg-danger");
    }

    // Initialize and show toast
    let bsToast = new bootstrap.Toast(toast);
    bsToast.show();
}
function logout() {
    window.location.href = "logout.php"; // Redirect to the logout page
}

    </script>
<script src="path-to-bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
