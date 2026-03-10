<?php
include '../../includes/dbconnect.php'; 
?>

<style>
/* General Styling */
body {
    font-family: 'Arial', sans-serif;
    background:rgb(169, 233, 249);
    margin: 0;
    padding: 0;
}

/* Main Content */
.main-content {
    margin-left: 250px; /* Adjusted for sidebar */
    padding: 20px;
    transition: all 0.3s ease-in-out;
}

/* Sidebar */
.sidebar {
    width: 250px;
    height: 100vh;
    background: #2c3e50;
    color: white;
    position: fixed;
    top: 0;
    left: 0;
    padding: 20px;
    transition: all 0.3s;
}

.sidebar h2 {
    text-align: center;
    font-size: 22px;
    margin-bottom: 20px;
}

.sidebar ul {
    list-style-type: none;
    padding: 0;
}

.sidebar ul li {
    padding: 10px;
    margin: 5px 0;
    transition: 0.3s;
}

.sidebar ul li a {
    color: white;
    text-decoration: none;
    display: block;
}

.sidebar ul li:hover {
    background: #34495e;
    border-radius: 5px;
}

/* Table Styling */
.table-responsive {
    background: white;
    padding: 20px;
    width:1200px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    max-height: 500px; /* Adjust height as needed */
    overflow-y: auto;
    overflow-x: auto;
    position: relative;
}


.table {
    width: 100%;
    border-collapse: collapse;
}

/* Fix table header */
.table thead {
    position: relative;
    top: 0;
    background: #1abc9c;
    color: white;
    z-index: 100;
}

.table tbody tr {
    transition: all 0.3s;
}

.table tbody tr:hover {
    background: #ecf0f1;
}

.table td, .table th {
    padding: 12px;
    text-align: center;
}

/* Button Styling */
.btn {
    padding: 8px 12px;
    border-radius: 5px;
    font-size: 14px;
    transition: all 0.3s;
}

.btn-warning {
    background: #f39c12;
    border: none;
    color: white;
}

.btn-warning:hover {
    background: #e67e22;
}

.btn-danger {
    background: #e74c3c;
    border: none;
    color: white;
}

.btn-danger:hover {
    background: #c0392b;
}
</style>

<div class="main-content">
<?php include '../../includes/sidebar.php'; ?>

<div class="container">
    <h2 class="text-center mt-3">List of Accountant Users</h2>
    <div class="input-group mb-3">
        <span class="input-group-text"><i class="bi bi-search text-success"></i></span>
        <input type="text" id="searchAccountant" class="form-control" placeholder="Search by Account Holder, Mobile Number, or Address...">
    </div>
    <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Account Holder</th>
                    <th>Balance</th>
                    <th>Mobile Number</th>
                    <th>Address</th>
                    <th>Create Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM accountant_tbl";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) { ?>
                    <tr id="row-<?= $row['id']; ?>">
                        <td><?= $row['id']; ?></td>
                        <td><?= $row['account_holder']; ?></td>
                        <td><?= $row['balance']; ?></td>
                        <td><?= $row['mobile_number']; ?></td>
                        <td><?= $row['address']; ?></td>
                        <td><?= $row['create_date']; ?></td>
                        <td>
                            <button class="btn btn-warning btn-sm" onclick="openEditModal(<?= $row['id']; ?>, '<?= $row['account_holder']; ?>', '<?= $row['balance']; ?>', '<?= $row['mobile_number']; ?>', '<?= $row['address']; ?>')">Edit</button>
                            <button class="btn btn-danger btn-sm" onclick="openDeleteModal(<?= $row['id']; ?>)">Delete</button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Accountant</h5>
                <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    <input type="hidden" id="edit_id" name="id">
                    <label>Account Holder</label>
                    <input type="text" id="edit_account_holder" name="account_holder" class="form-control">
                    <label>Balance</label>
                    <input type="text" id="edit_balance" name="balance" class="form-control">
                    <label>Mobile Number</label>
                    <input type="text" id="edit_mobile_number" name="mobile_number" class="form-control">
                    <label>Address</label>
                    <input type="text" id="edit_address" name="address" class="form-control">
                    <button type="submit" class="btn btn-success mt-2">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this accountant?</p>
                <button class="btn btn-danger" id="confirmDelete">Delete</button>
            </div>
        </div>
    </div>
</div>
<!-- Toast Notification Container -->
<div class="position-fixed top-0 end-0 p-3" style="z-index: 1050;">
    <div id="successToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="polite" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                <span id="toastMessage"></span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
let deleteId = null;

function showToast(message) {
    $('#toastMessage').text(message);
    let toast = new bootstrap.Toast(document.getElementById('successToast'));
    toast.show();
}

function openEditModal(id, accountHolder, balance, mobileNumber, address) {
    $('#edit_id').val(id);
    $('#edit_account_holder').val(accountHolder);
    $('#edit_balance').val(balance);
    $('#edit_mobile_number').val(mobileNumber);
    $('#edit_address').val(address);
    $('#editModal').modal('show');
}

function openDeleteModal(id) {
    deleteId = id;
    $('#deleteModal').modal('show');
}

$('#editForm').submit(function(e) {
    e.preventDefault();
    $.post('edit_account.php', $(this).serialize(), function(response) {
        $('#editModal').modal('hide');
        showToast('Account updated successfully!');
        setTimeout(() => location.reload(), 1500);
    });
});

$('#confirmDelete').click(function() {
    $.post('delete_account.php', { id: deleteId }, function(response) {
        $('#deleteModal').modal('hide');
        $('#row-' + deleteId).fadeOut(500, function() { $(this).remove(); });
        showToast('Account deleted successfully!');
    });
});
$(document).ready(function() {
    $("#searchAccountant").on("keyup", function() {
        let value = $(this).val().toLowerCase();
        
        $("tbody tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });

        // If the search input is cleared, reset table view
        if (value === "") {
            $("tbody tr").show();
        }
    });
});
</script>
