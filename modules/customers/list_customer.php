<?php
include '../../includes/dbconnect.php';
include '../../includes/header.php'; // Include header
include '../../includes/sidebar.php'; // Include sidebar

// Fetch Customers Data
$sql = "SELECT * FROM customer_tbl";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer List</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .content {
            margin-left: 270px;
            padding: 40px;
            width: calc(100% - 270px);
        }
        body {
            background-color: rgb(245, 246, 250);
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body>

<div class="content">
    <h2>Customer List</h2>
<!-- Search Bar -->
<input type="text" id="searchInput" class="form-control mb-3" placeholder="Search by name, mobile, or address..." onkeyup="searchTable()">
    
    <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
        <?php if ($result->num_rows > 0): ?>
            <table class="table table-bordered" id="customerTable">
                <thead class="table-dark">
                    <tr>
                        <th>Customer Name</th>
                        <th>Father's Name</th>
                        <th>Mobile Number</th>
                        <th>Interest</th>
                        <th>Address</th>
                        <th>Status</th>
                        <th>Signature</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['customer_name']; ?></td>
                            <td><?php echo $row['father_name']; ?></td>
                            <td><?php echo $row['mobile_number']; ?></td>
                            <td><?php echo $row['interest']; ?></td>
                            <td><?php echo $row['address']; ?></td>
                            <td><?php echo ($row['status'] == 1) ? "Active" : "Inactive"; ?></td>
                            <td>
                                <?php if (!empty($row['signature'])): ?>
                                    <img src="uploads/<?php echo $row['signature']; ?>" alt="Signature" width="100">
                                <?php else: ?>
                                    No signature uploaded
                                <?php endif; ?>
                            </td>
                            <td>
                                <button class="btn btn-success btn-sm" onclick="showEditPopup(<?php echo htmlspecialchars(json_encode($row)); ?>)">Edit</button>
                                <button class="btn btn-danger btn-sm" onclick="confirmDelete(<?php echo $row['customer_id']; ?>)">Delete</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No customers found.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Edit Customer Modal -->
<div class="modal fade" id="editCustomerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editCustomerForm">
                <div class="modal-body">
                    <input type="hidden" name="customer_id" id="edit_customer_id">
                    
                    <div class="mb-3">
                        <label>Customer Name</label>
                        <input type="text" class="form-control" id="edit_customer_name" name="customer_name" required>
                    </div>
                    <div class="mb-3">
                        <label>Mobile Number</label>
                        <input type="text" class="form-control" id="edit_mobile_number" name="mobile_number">
                    </div>
                    <div class="mb-3">
                        <label>Address</label>
                        <input type="text" class="form-control" id="edit_address" name="address">
                    </div>
                    <div class="mb-3">
                        <label>Status</label>
                        <select class="form-control" id="edit_status" name="status">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Toast Notification Container -->
<div id="toastContainer" style="position: fixed; top: 20px; right: 20px; z-index: 10000;"></div>

<script>
    function showEditPopup(data) {
        document.getElementById('edit_customer_id').value = data.customer_id;
        document.getElementById('edit_customer_name').value = data.customer_name;
        document.getElementById('edit_mobile_number').value = data.mobile_number;
        document.getElementById('edit_address').value = data.address;
        document.getElementById('edit_status').value = data.status;
        new bootstrap.Modal(document.getElementById('editCustomerModal')).show();
    }

    document.getElementById('editCustomerForm').addEventListener('submit', function(event) {
        event.preventDefault();

        let formData = new FormData(this);

        fetch('../../handlers/edit_customer.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                showToast(data.message, 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast(data.message, 'error');
            }
        })
        .catch(error => showToast("Error updating customer!", 'error'));
    });

    function confirmDelete(customerId) {
        if (confirm("Are you sure you want to delete this customer?")) {
            fetch(`delete_customer.php?delete_id=${customerId}`, { method: 'GET' })
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    showToast(data.message, 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showToast(data.message, 'error');
                }
            })
            .catch(error => showToast("Error deleting customer!", 'error'));
        }
    }

    function showToast(message, type) {
        let bgColor = type === 'success' ? 'green' : 'red';

        let toast = document.createElement('div');
        toast.innerHTML = message;
        toast.style.cssText = `
            background-color: ${bgColor};
            color: white;
            padding: 12px 20px;
            margin-top: 10px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            opacity: 0.9;
            transition: opacity 0.5s;
        `;

        document.getElementById('toastContainer').appendChild(toast);

        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 500);
        }, 2000);
    }
    function searchTable() {
        let input = document.getElementById('searchInput').value.toLowerCase();
        let table = document.getElementById('customerTable');
        let rows = table.getElementsByTagName('tr');

        for (let i = 1; i < rows.length; i++) {
            let cells = rows[i].getElementsByTagName('td');
            let match = false;

            for (let j = 0; j < cells.length - 2; j++) { // Exclude last columns with buttons
                if (cells[j].innerText.toLowerCase().includes(input)) {
                    match = true;
                    break;
                }
            }

            rows[i].style.display = match ? '' : 'none';
        }
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
