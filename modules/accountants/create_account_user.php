<?php
include '../../includes/dbconnect.php'; // Database connection file

// Create Account Logic
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['account_holder'];
    $balance = $_POST['balance'];
    $mobile_number = $_POST['mobile_number'];
    $address = $_POST['address'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $create_date = date('Y-m-d');

    $sql = "INSERT INTO accountant_tbl (account_holder, balance, mobile_number, address, create_date, password) 
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdssss", $name, $balance, $mobile_number, $address, $create_date, $password);

    if ($stmt->execute()) {
        echo "<script>
    document.addEventListener('DOMContentLoaded', function() {
        showToast('Account created successfully!', 'success');
    });
</script>";

    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<?php
include 'header.php'; 
?>
<style>
/* Main Content Area */
/* Main Content Area */
html, body {
    width: 100%;
    height: 100%;
    margin: 0;
    padding: 0;
    overflow-x: hidden; /* Prevent horizontal scroll */
}
.content {
    margin-left: 260px;
    padding: 20px;
    min-height: 100vh;
    overflow-y: auto; /* Allow vertical scrolling only if necessary */
    overflow-x: hidden; /* Prevent horizontal scroll */
}

/* Glassmorphic Card Styling */
.card {
    border-radius: 15px;
    border: bold;
    width: 100%;
    max-width: 800px;
    margin: auto;
    backdrop-filter: blur(10px);
    justify-content:space-between;
    background: rgba(255, 255, 255, 0.2);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    padding: 10px;
    animation: fadeIn 0.8s ease-in-out;
}

/* Card Header - Mild Gradient */
.container-fluid {
    width: 100%;
    max-width: 100%;
    padding-right: 15px;
    padding-left: 15px;
    overflow: hidden;
}

/* Form Labels */
.form-label {
    font-weight: bold;
    color: #333;
}

/* Form Inputs */
.form-control {
    border-radius: 10px;
    border: 20px solid rgba(0, 0, 0, 0.2);
    background: rgba(255, 255, 255, 0.6);
    padding: 12px;
    color: #333;
    font-size: 16px;
    transition: all 0.3s ease-in-out;
}

/* Input Placeholder & Text Color */
.form-control::placeholder {
    color: rgba(0, 0, 0, 0.5);
}

/* Input Focus Effect */
.form-control:focus {
    border-color: #2a5298;
    box-shadow: 0px 0px 10px rgba(42, 82, 152, 0.5);
    background: rgba(255, 255, 255, 0.8);
}

/* Textarea Styling */
textarea.form-control {
    min-height: 80px;
    resize: none;
    
}

/* Mild Gradient Button */
.btn-primary {
    background: linear-gradient(45deg, #007bff, #6610f2);
    border: none;
    font-size: 18px;
    font-weight: bold;
    padding: 14px;
    border-radius: 8px;
    color: white;
    transition: all 0.3s ease-in-out;
    box-shadow: 0 4px 12px rgba(42, 82, 152, 0.4);
}

/* Button Hover Effect */
.btn-primary:hover {
    background: linear-gradient(45deg, #6610f2, #007bff);
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(28, 99, 221, 0.6);
}

/* Fade-in Animation */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-15px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsive Design */
@media (max-width: 768px) {
    .card {
        max-width: 90%;
    }
    .btn-primary {
        font-size: 16px;
        padding: 12px;
    }
}
@keyframes shrink {
    from {
        width: 100%;
    }
    to {
        width: 0%;
    }
}

#toastProgressBar {
    animation: shrink 3s linear forwards;
}



    </style>
<?php include '../../includes/sidebar.php'; ?>

<!-- Toast Container -->
<div aria-live="polite" aria-atomic="true" class="position-fixed top-0 end-0 p-3" style="z-index: 1050;">
    <div id="toastMessage" class="toast align-items-center text-white bg-success border-0 fade" role="alert" aria-live="assertive" aria-atomic="true"
        data-bs-autohide="true" data-bs-delay="3000"> <!-- Auto-hide in 3s -->
        <div class="d-flex">
            <div class="toast-body">
                <!-- Toast Message Content -->
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <!-- Closing Pipeline Animation -->
        <div class="progress" style="height: 3px;">
            <div id="toastProgressBar" class="progress-bar bg-light" style="width: 100%;"></div>
        </div>
    </div>
</div>



<div class="container-fluid">
    <div class="row">
        <main class="col-md-9 col-lg-10 content">
            <div class="container mt-4">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="card shadow-sm">
                        
                                <h4>Create Accountant</h4>
                        
                            <div class="card-body">
    <form method="post" action="">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Accountant Holder Name:</label>
                <input type="text" name="account_holder" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Balance:</label>
                <input type="number" step="0.01" name="balance" class="form-control" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Phone Number:</label>
                <input type="text" name="mobile_number" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Address:</label>
                <textarea name="address" class="form-control" required></textarea>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Password:</label>
                <input type="password" name="password" class="form-control" required>
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100">Create Account</button>
    </form>
</div>

                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
<script>
    function logout() {
    window.location.href = "logout.php"; // Redirect to the logout page
}
function showToast(message, type = 'success') {
    let toastElement = document.getElementById('toastMessage');
    let toastBody = toastElement.querySelector('.toast-body');
    let progressBar = document.getElementById('toastProgressBar');

    // Set message
    toastBody.textContent = message;

    // Set toast background color
    toastElement.classList.remove('bg-success', 'bg-danger');
    toastElement.classList.add(type === 'success' ? 'bg-success' : 'bg-danger');

    // Reset and start progress bar animation
    progressBar.style.width = '100%';
    progressBar.style.animation = 'none';
    void progressBar.offsetWidth; // Trigger reflow
    progressBar.style.animation = 'shrink 3s linear forwards';

    // Show the toast
    let toast = new bootstrap.Toast(toastElement);
    toast.show();
}
    </script>
