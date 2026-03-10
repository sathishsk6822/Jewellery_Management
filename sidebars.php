<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<style>
     body {
        font-family: 'Poppins', sans-serif;
        background-color: #f7f8f9;
    }

    /* Sidebar Styling */
    .sidebar {
        width: 250px;
        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        display: flex;
        flex-direction: column; /* Stack elements vertically */
        justify-content: space-between; /* Push content down */
        background: linear-gradient(45deg, #1e3c72, #2a5298);
        color: white;
        box-shadow: 4px 0 10px rgba(0, 0, 0, 0.2);
        overflow-y: auto;
        padding: 10px;
    }

    .sidebar::-webkit-scrollbar {
        width: 6px;
    }

    .sidebar::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.3);
        border-radius: 10px;
    }

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

    /* Logout Button */
    .logout-container {
        margin-top: auto; /* Pushes logout button to bottom */
        padding-bottom: 10px;
        text-align: center;
    }

    .logout-btn {
        background: linear-gradient(45deg, #ff416c, #ff4b2b);
        color: white;
        font-weight: bold;
        border-radius: 6px;
        padding: 10px;
        font-size: 14px;
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

    @media (max-width: 768px) {
        .sidebar {
            width: 100%;
            height: auto;
            position: relative;
        }

        .logout-container {
            position: relative;
            bottom: 0;
            width: 100%;
        }
    }
    </style>
<nav class="sidebar">
    <h4 class="text-center">User</h4>
    <a href="userdashboard.php"><i class="bi bi-bell-fill text-warning"></i> Home</a>

    <!-- Logout Button at Bottom -->
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
    window.location.href = "logout.php"; // Redirect to the logout page
}


    </script>
