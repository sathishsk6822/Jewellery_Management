<?php
include '../../includes/dbconnect.php';
include '../../includes/header.php';
include '../../includes/sidebar.php'; // Include sidebar 

// Fetch Users Data
$sql = "SELECT id, name, username, password, mobilenumber, emailid, usertype, address, create_date, status FROM user_tbl ORDER BY create_date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User List</title>
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
    <h2>User List</h2>
    
    <!-- Search Field -->
    <input type="text" id="searchInput" class="form-control mb-3" placeholder="Search users...">
    
    <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
        <?php if ($result->num_rows > 0): ?>
            <table class="table table-bordered" id="userTable">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Full Name</th>
                        <th>Username</th>
                        <th>Mobile Number</th>
                        <th>Email</th>
                        <th>User Type</th>
                        <th>Address</th>
                        <th>Created Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['username']; ?></td>
                            <td><?php echo $row['mobilenumber']; ?></td>
                            <td><?php echo $row['emailid']; ?></td>
                            <td><?php echo ($row['usertype'] == 1) ? "Admin" : "User"; ?></td>
                            <td><?php echo $row['address']; ?></td>
                            <td><?php echo $row['create_date']; ?></td>
                            <td><?php echo ($row['status'] == 1) ? "Active" : "Inactive"; ?></td>
                            <td>
                                <button class="btn btn-success btn-sm" onclick="showEditPopup(<?php echo htmlspecialchars(json_encode($row)); ?>)">Edit</button>
                                <button class="btn btn-danger btn-sm" onclick="confirmDelete(<?php echo $row['id']; ?>)">Delete</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No users found.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editUserForm">
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit_user_id">
                    
                    <div class="mb-3">
                        <label>Name</label>
                        <input type="text" class="form-control" id="edit_name" name="full_name" required>
                    </div>
                    <div class="mb-3">
                        <label>Username</label>
                        <input type="text" class="form-control" id="edit_username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" class="form-control" id="edit_password" name="password">
                        <small>Leave blank if you don't want to change</small>
                    </div>
                    <div class="mb-3">
                        <label>Mobile Number</label>
                        <input type="text" class="form-control" id="edit_mobilenumber" name="mobile_number">
                    </div>
                    <div class="mb-3">
                        <label>Email ID</label>
                        <input type="email" class="form-control" id="edit_emailid" name="email_address">
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

<script>
    function showEditPopup(data) {
        document.getElementById('edit_user_id').value = data.id;
        document.getElementById('edit_name').value = data.full_name;
        document.getElementById('edit_username').value = data.username;
        document.getElementById('edit_mobilenumber').value = data.mobile_number;
        document.getElementById('edit_emailid').value = data.email_address;
        document.getElementById('edit_address').value = data.address;
        document.getElementById('edit_status').value = data.status;
        new bootstrap.Modal(document.getElementById('editUserModal')).show();
    }

    document.getElementById('editUserForm').addEventListener('submit', function(event) {
        event.preventDefault();
        let formData = new FormData(this);

        fetch('../../handlers/edit_user.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                alert(data.message);
                setTimeout(() => location.reload(), 1000);
            } else {
                alert(data.message);
            }
        })
        .catch(error => alert("Error updating user!"));
    });

    function confirmDelete(userId) {
        if (confirm("Are you sure you want to delete this user?")) {
            fetch(`delete_user.php?delete_id=${userId}`, { method: 'GET' })
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    alert(data.message);
                    setTimeout(() => location.reload(), 1000);
                } else {
                    alert(data.message);
                }
            })
            .catch(error => alert("Error deleting user!"));
        }
    }
    // Search Functionality
    document.getElementById('searchInput').addEventListener('keyup', function () {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll('#userTable tbody tr');
        
        rows.forEach(row => {
            let text = row.textContent.toLowerCase();
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
