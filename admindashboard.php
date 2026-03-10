<?php
include("dbconnect.php");

// Fetch total pledges
$pledges_result = $conn->query("SELECT COUNT(*) AS total_pledges FROM pledge_tbl");
$pledges = $pledges_result->fetch_assoc()['total_pledges'];

// Fetch total releases
$releases_result = $conn->query("SELECT COUNT(*) AS total_releases FROM release_customer_gst_tbl");
$releases = $releases_result->fetch_assoc()['total_releases'];

// Fetch total customers
$customers_result = $conn->query("SELECT COUNT(*) AS total_customers FROM customer_tbl");
$customers = $customers_result->fetch_assoc()['total_customers'];

// Fetch total accountants
$accountants_result = $conn->query("SELECT COUNT(*) AS total_accountants FROM accountant_tbl");
$accountants = $accountants_result->fetch_assoc()['total_accountants'];

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jewellery Shop - Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.1.2/chart.umd.js">
    </script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
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

        @media (max-width: 768px) {
            .sidebar {
                width: 220px;
                height: 100%;
                position: fixed;
                left: -250px;
                top: 0;
                transition: left 0.3s ease-in-out;
                z-index: 1000;
            }

            .sidebar.active {
                left: 0;
            }

            .content {
                margin-left: 0;
                padding-left: 20px;
            }

            .menu-toggle {
                position: absolute;
                top: 10px;
                left: 10px;
                background: none;
                border: none;
                font-size: 20px;
                color: white;
                cursor: pointer;
            }
        }

        #dashboardChart {
            width: 600px !important;
            height: 350px !important;
            /* Change this to your desired height */
        }
    </style>
</head>

<body>

    <nav class="sidebar">
        <h4 class="text-center">Admin</h4>
        <div class="menu-container">
            <a href="#" onclick="toggleSubmenu('accountMasterSubmenu')">
                <i class="bi bi-building"></i> Accountant Master
            </a>
            <div id="accountMasterSubmenu" class="submenu">
                <a href="create_account_user.php"><i class="bi bi-person-plus"></i> Create Account User</a>
                <a href="list_account_user.php"><i class="bi bi-list"></i> List Accountant</a>
            </div>

            <a href="#" onclick="toggleSubmenu('customerMasterSubmenu')">
                <i class="bi bi-people"></i> Customer Master
            </a>
            <div id="customerMasterSubmenu" class="submenu">
                <a href="create_customer.php"><i class="bi bi-person-plus-fill"></i> Add Customer</a>
                <a href="list_customer.php"><i class="bi bi-card-list"></i> List Customers</a>
            </div>

            <a href="#" onclick="toggleSubmenu('userMasterSubmenu')">
                <i class="bi bi-person-circle"></i> User Master
            </a>
            <div id="userMasterSubmenu" class="submenu">
                <a href="create_user.php"><i class="bi bi-person-plus"></i> Create User</a>
                <a href="list_user.php"><i class="bi bi-people"></i> List Users</a>
            </div>

            <a href="#" onclick="toggleSubmenu('smsSubmenu')">
                <i class="bi bi-chat-dots"></i> SMS
            </a>
            <div id="smsSubmenu" class="submenu">
                <a href="send_sms.php"><i class="bi bi-envelope"></i> Send SMS & History</a>
            </div>

            <a href="#" onclick="toggleSubmenu('findRecordsSubmenu')">
                <i class="bi bi-search text-yellow"></i> Find Records
            </a>
            <div id="findRecordsSubmenu" class="submenu">
                <a href="accountant_wise.php"><i class="bi bi-person-lines-fill text-pink"></i> Accountant Wise</a>
                <a href="customer_wise.php"><i class="bi bi-people-fill text-teal"></i> Customer Wise</a>
                <a href="user_wise.php"><i class="bi bi-person-badge text-purple"></i> User Wise</a>
                <a href="pledge_wise.php"><i class="bi bi-gem text-orange"></i> Pledge Wise</a>
                <a href="release_wise.php"><i class="bi bi-check-circle text-green"></i> Release Wise</a>
            </div>

            <a href="print.php"><i class="bi bi-printer"></i> Print</a>
            <a href="pledges.php"><i class="bi bi-gem"></i> Pledges</a>
            <a href="renewalform.php"><i class="bi bi-arrow-clockwise"></i> Renewal</a>
            <a href="reports.php"><i class="bi bi-bar-chart"></i> Reports</a>
            <a href="notification.php"><i class="bi bi-bell-fill"></i> Notifications</a>
        </div>
        <div class="logout-container">
            <button class="logout-btn" onclick="logout()">Logout</button>
        </div>
    </nav>

    </div>
    <div class="content">
        <h2>Welcome Admin </h2>
        <div class="container mt-5">
            <h2 class="text-center mb-4"></h2>

            <div class="row">
                <!-- Total Pledges -->
                <div class="col-md-3">
                    <div class="card text-white bg-primary mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Total Pledges</h5>
                            <p class="card-text fs-3"><?php echo $pledges; ?></p>
                        </div>
                    </div>
                </div>

                <!-- Total Releases -->
                <div class="col-md-3">
                    <div class="card text-white bg-primary mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Total Releases</h5>
                            <p class="card-text fs-3"><?php echo $releases; ?></p>
                        </div>
                    </div>
                </div>

                <!-- Total Customers -->
                <div class="col-md-3">
                    <div class="card text-white bg-primary mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Total Customers</h5>
                            <p class="card-text fs-3"><?php echo $customers; ?></p>
                        </div>
                    </div>
                </div>

                <!-- Total Accountants -->
                <div class="col-md-3">
                    <div class="card text-white bg-primary mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Total Accountants</h5>
                            <p class="card-text fs-3"><?php echo $accountants; ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <!-- Bar Chart -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title text-center">Accountant Balance</h5>
                            <canvas id="barChartID" style="width: 800px !important; height: 500px !important;"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Pie Chart -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title text-center">Pledge and Release Overview</h5>
                            <canvas id="dashboardChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- New Row for Line Chart to Add Space -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title text-center">Monthly Revenue</h5>
                            <canvas id="lineChartID"
                                style="width: 500px !important; height: 300px !important;"></canvas>
                        </div>
                    </div>
                </div>
                <!-- Map Card -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title text-center">Current Location</h5>
                            <div id="map" style="height: 300px;"></div> <!-- Map Container -->
                        </div>
                    </div>
                </div>
            </div>




            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    fetchNotifications();
                });

                function fetchNotifications() {
                    fetch("fetch_notification.php")
                        .then(response => response.json())
                        .then(data => {
                            let notificationList = document.getElementById("notificationList");
                            let notificationCount = document.getElementById("notificationCount");

                            notificationList.innerHTML = "";
                            if (data.length > 0) {
                                notificationCount.innerText = data.length;
                                data.forEach(notification => {
                                    let listItem = document.createElement("li");
                                    listItem.classList.add("dropdown-item");
                                    listItem.innerHTML = `<strong>${notification.title}</strong><br>${notification.message}`;
                                    notificationList.appendChild(listItem);
                                });
                            } else {
                                notificationList.innerHTML = '<li class="dropdown-item text-center">No new notifications</li>';
                                notificationCount.innerText = 0;
                            }
                        })
                        .catch(error => console.error("Error fetching notifications:", error));
                }

                function toggleSidebar() {
                    document.getElementById('sidebar').classList.toggle('active');
                }

                function toggleSubmenu(id) {
                    var submenu = document.getElementById(id);
                    submenu.classList.toggle("show");
                }

                function logout() {
                    window.location.href = "logout.php";
                }
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
                    window.location.href = "logout.php"; // Redirect to the logout page
                }
                new Chart($("#barChartID"), {
                    type: 'bar',
                    options: {
                        legend: { display: true },
                        indexAxis: 'x',
                        title: {
                            display: true,
                            text: 'Bar Chart using ChartJS library'
                        }
                    },
                    data: {
                        labels: ["G.Vinoth Kumar", "G.Anand Kumar", "P.Surendra Kumar", "P.Dharamchand", "K.Gothamchand"],
                        datasets: [
                            {
                                label: "Accountant Wise Balance ",
                                backgroundColor: ["#2a52be", "#0000FF", "#1ca9c9", "#1e90ff", "#99badd"],
                                data: [12766206, 14024005, 17135826, 14189789, 18506417]
                            }
                        ]
                    }
                });
                var ctx = document.getElementById('dashboardChart').getContext('2d');
                var dashboardChart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: ['Pledges', 'Releases'],
                        datasets: [{
                            label: 'Dashboard Data',
                            data: [<?php echo $pledges; ?>, <?php echo $releases; ?>, <?php echo $customers; ?>, <?php echo $accountants; ?>],
                            backgroundColor: ['#007bff', ' #0f52ba']
                        }]
                    }
                });
                var ctx3 = document.getElementById('lineChartID').getContext('2d');
                new Chart(ctx3, {
                    type: 'line',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                        datasets: [{
                            label: 'Monthly Revenue (₹)',
                            data: [5000, 7000, 6000, 8000, 7500, 9000],
                            borderColor: '#007bff',
                            backgroundColor: 'rgba(0, 123, 255, 0.2)',
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true, // ✅ Prevents auto size increase
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
                document.addEventListener("DOMContentLoaded", function () {
                    var map = L.map('map').setView([12.4976, 78.5624], 12); // Default: Tirupattur, India

                    // Load OpenStreetMap Tiles
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '© OpenStreetMap contributors'
                    }).addTo(map);

                    // Add a Marker for Tirupattur
                    L.marker([12.4976, 78.5624]).addTo(map)
                        .bindPopup("<b>Admin Location</b><br>Tirupattur, India")
                        .openPopup();
                });
            </script>
</body>

</html>