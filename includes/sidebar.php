<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<style>
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f7f8f9;
        margin: 0;
        padding: 0;
        display: flex;
    }

    /* Sidebar Styling */
    .sidebar {
        width: 250px;
        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        display: flex;
        flex-direction: column;
        /* Stack elements */
        background: linear-gradient(45deg, #1e3c72, #2a5298);
        color: white;
        box-shadow: 4px 0 10px rgba(0, 0, 0, 0.2);
        padding: 10px;
    }

    /* Ensures menu items take up available space */
    .menu-container {
        flex-grow: 1;
        /* Allows menu to expand while keeping logout button at the bottom */
        overflow-y: auto;
        /* Enable scrolling if content overflows */
        padding-bottom: 10px;
        /* Prevents content from overlapping logout button */
    }

    /* Sidebar Scrollbar */
    .menu-container::-webkit-scrollbar {
        width: 6px;
    }

    .menu-container::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.3);
        border-radius: 10px;
    }

    .menu-container::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.5);
    }

    /* Sidebar Links */
    .sidebar a {
        display: block;
        padding: 12px 15px;
        color: white;
        text-decoration: none;
        font-size: 16px;
        transition: all 0.3s ease-in-out;
        border-radius: 5px;
    }

    .sidebar a:hover {
        background: rgba(255, 255, 255, 0.2);
    }

    /* Submenu Styling */
    .submenu {
        display: none;
        background: linear-gradient(45deg, #34495e, #1f3b4d);
        padding-left: 15px;
        border-left: 3px solid #1abc9c;
        border-radius: 5px;
    }

    /* Ensures submenu expands properly */
    .submenu.show {
        display: block;
    }

    /* Logout Button */
    .logout-container {
        margin-top: auto;
        /* Pushes logout button to the bottom */
        padding: 10px;
    }

    .logout-btn {
        background: linear-gradient(45deg, #ff416c, #ff4b2b);
        color: white;
        font-weight: bold;
        border-radius: 6px;
        padding: 10px;
        font-size: 14px;
        text-align: center;
        width: 100%;
        border: none;
        cursor: pointer;
        transition: 0.3s ease-in-out;
        box-shadow: 0 0 5px rgba(255, 65, 108, 0.6);
    }

    .logout-btn:hover {
        box-shadow: 0 0 10px rgba(255, 75, 43, 1);
        transform: scale(1.05);
    }

    /* Content Styling */
    .content {
        margin-left: 260px;
        padding: 20px;
        min-height: 100vh;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .sidebar {
            width: 100%;
            height: auto;
            position: relative;
        }

        .content {
            margin-left: 0;
        }
    }
</style>
<nav class="sidebar">
    <div class="text-center" style="display: flex; align-items: center; justify-content: center; position: relative;">
        <h4 style="margin: 0;">Admin</h4>
        <a href="../admindashboard.php" style="position: absolute; left: 0;">
            <img src="../assets/img/back.png" alt="Admin Icon" width="30" height="30">
        </a>
    </div>







    <div class="menu-container">
        <a href="#" onclick="toggleSubmenu('accountMasterSubmenu')">
            <i class="bi bi-building"></i> Accountant Master
        </a>
        <div id="accountMasterSubmenu" class="submenu">
            <a href="../modules/accountants/create_account_user.php"><i class="bi bi-person-plus"></i> Create Account
                User</a>
            <a href="../modules/accountants/list_account_user.php"><i class="bi bi-list"></i> List Accountant</a>
        </div>

        <a href="#" onclick="toggleSubmenu('customerMasterSubmenu')">
            <i class="bi bi-people"></i> Customer Master
        </a>
        <div id="customerMasterSubmenu" class="submenu">
            <a href="../modules/customers/create_customer.php"><i class="bi bi-person-plus-fill"></i> Add Customer</a>
            <a href="../modules/customers/list_customer.php"><i class="bi bi-card-list"></i> List Customers</a>
        </div>

        <a href="#" onclick="toggleSubmenu('userMasterSubmenu')">
            <i class="bi bi-person-circle"></i> User Master
        </a>
        <div id="userMasterSubmenu" class="submenu">
            <a href="../modules/users/create_user.php"><i class="bi bi-person-plus"></i> Create User</a>
            <a href="../modules/users/list_user.php"><i class="bi bi-people"></i> List Users</a>
        </div>

        <a href="#" onclick="toggleSubmenu('smsSubmenu')">
            <i class="bi bi-chat-dots"></i> SMS
        </a>
        <div id="smsSubmenu" class="submenu">
            <a href="../modules/sms/send_sms.php"><i class="bi bi-envelope"></i> Send SMS & History</a>
        </div>

        <a href="#" onclick="toggleSubmenu('findRecordsSubmenu')">
            <i class="bi bi-search text-yellow"></i> Find Records
        </a>
        <div id="findRecordsSubmenu" class="submenu">
            <a href="../modules/accountants/accountant_wise.php"><i class="bi bi-person-lines-fill text-pink"></i>
                Accountant Wise</a>
            <a href="../modules/customers/customer_wise.php"><i class="bi bi-people-fill text-teal"></i> Customer
                Wise</a>
            <a href="../modules/users/user_wise.php"><i class="bi bi-person-badge text-purple"></i> User Wise</a>
            <a href="../modules/pledges/pledge_wise.php"><i class="bi bi-gem text-orange"></i> Pledge Wise</a>
            <a href="../modules/pledges/release_wise.php"><i class="bi bi-check-circle text-green"></i> Release Wise</a>
        </div>

        <a href="../modules/reports/print.php"><i class="bi bi-printer"></i> Print</a>
        <a href="../modules/pledges/pledges.php"><i class="bi bi-gem"></i> Pledges</a>
        <a href="../modules/pledges/renewalform.php"><i class="bi bi-arrow-clockwise"></i> Renewal</a>
        <a href="../modules/reports/reports.php"><i class="bi bi-bar-chart"></i> Reports</a>
        <a href="../modules/notifications/notification.php"><i class="bi bi-bell-fill"></i> Notifications</a>
    </div>
    <div class="logout-container">
        <button class="logout-btn" onclick="logout()">Logout</button>
    </div>
</nav>

<script>
    function toggleSubmenu(id) {
        var submenu = document.getElementById(id);
        if (submenu.classList.contains("show")) {
            submenu.classList.remove("show");
        } else {
            // Close other submenus before opening the clicked one
            var allSubmenus = document.querySelectorAll(".submenu");
            allSubmenus.forEach(function (menu) {
                menu.classList.remove("show");
            });

            submenu.classList.add("show");
        }
    }
    function logout() {
        window.location.href = "../auth/logout.php"; // Redirect to the logout page
    }


</script>