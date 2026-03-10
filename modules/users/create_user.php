<?php
include '../../includes/dbconnect.php'; // Include database connection file
include '../../includes/sidebar.php';
$toastMessage = ""; // Default empty toast message

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    if (!empty($_POST['name']) && !empty($_POST['username']) && !empty($_POST['password']) && 
        !empty($_POST['MobileNumber']) && !empty($_POST['EmailID']) && 
        !empty($_POST['usertype']) && !empty($_POST['address']) && 
        isset($_POST['status'])) {

        $name = $_POST['name'];
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $mobile_number = $_POST['MobileNumber'];
        $email_id = $_POST['EmailID'];
        $usertype = $_POST['usertype'];
        $address = $_POST['address'];
        $status = $_POST['status'];
        $create_date = date('Y-m-d'); // Current date

        // Use ENUM values properly
        if (!in_array($usertype, ['user', 'admin']) || !in_array($status, ['active', 'inactive'])) {
            $toastMessage = "Invalid user type or status.";
        } else {
            $sql = "INSERT INTO user_tbl (name, username, password, MobileNumber, EmailID, usertype, address, create_date, status) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssssss", $name, $username, $password, $mobile_number, $email_id, $usertype, $address, $create_date, $status);

            if ($stmt->execute()) {
                $toastMessage = "User created successfully!";
            } else {
                $toastMessage = "Error: " . $stmt->error;
            }

            $stmt->close();
        }
        $conn->close();
    } else {
        $toastMessage = "Error: Please fill in all required fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create User</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* Form Container */
        .container {
            width: 50%;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        /* Form Title */
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        /* Form Layout - Two Fields per Row */
        .form-group {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        /* Input Fields */
        .input-box {
            width: 48%;
            display: flex;
            flex-direction: column;
        }

        /* Labels */
        label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        /* Inputs */
        input, select, textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        /* Submit Button */
        .buttons {
            width: 100%;
            background: linear-gradient(45deg, #007bff, #6610f2);
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .buttons:hover {
            background: linear-gradient(45deg, #6610f2, #007bff);
        }

        /* Toast Notification */
        .toast {
            visibility: hidden;
            min-width: 250px;
            background: linear-gradient(45deg, #6610f2, #007bff);
            color: white;
            text-align: center;
            border-radius: 8px;
            padding: 16px;
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 1000;
            font-size: 16px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            opacity: 0;
            transition: opacity 0.5s ease-in-out, bottom 0.5s ease-in-out;
        }

        .toast.show {
            visibility: visible;
            opacity: 1;
            bottom: 50px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Create User</h2>
        <form method="POST" action="">

            <!-- Row 1: Name & Username -->
            <div class="form-group">
                <div class="input-box">
                    <label>Name:</label>
                    <input type="text" name="name" required>
                </div>
                <div class="input-box">
                    <label>Username:</label>
                    <input type="text" name="username" required>
                </div>
            </div>

            <!-- Row 2: Password & Mobile Number -->
            <div class="form-group">
                <div class="input-box">
                    <label>Password:</label>
                    <input type="password" name="password" required>
                </div>
                <div class="input-box">
                    <label>Mobile Number:</label>
                    <input type="text" name="MobileNumber" required>
                </div>
            </div>

            <!-- Row 3: Email ID & User Type -->
            <div class="form-group">
                <div class="input-box">
                    <label>Email ID:</label>
                    <input type="email" name="EmailID" required>
                </div>
                <div class="input-box">
                    <label>User Type:</label>
                    <select name="usertype" required>
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
            </div>

            <!-- Row 4: Address (Full Width) -->
            <div class="form-group">
                <div class="input-box" style="width: 100%;">
                    <label>Address:</label>
                    <textarea name="address" required></textarea>
                </div>
            </div>

            <!-- Row 5: Status -->
            <div class="form-group">
                <div class="input-box">
                    <label>Status:</label>
                    <select name="status" required>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>

            <button type="submit" name="submit" class="buttons">Create User</button>
        </form>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="toast"><?php echo $toastMessage ?? ''; ?></div>

    <script>
        window.onload = function() {
            var toastMessage = "<?php echo $toastMessage ?? ''; ?>";
            if (toastMessage.trim() !== "") {
                var toast = document.getElementById("toast");
                toast.classList.add("show");
                setTimeout(() => { toast.classList.remove("show"); }, 3000);
            }
        };
    </script>
</body>
</html>
