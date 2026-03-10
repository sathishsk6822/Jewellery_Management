<?php
session_start();
include 'dbconnect.php'; // Ensure database connection

$error = ""; // Store login errors

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $user_type = $_POST['user_type']; // Check if Admin or User

    if ($user_type == "admin") {
        // Hardcoded Admin Credentials
        $admin_username = "admin";
        $admin_password = "admin123"; // Change to a secure password

        if ($username === $admin_username && $password === $admin_password) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $admin_username;
            header("Location: admindashboard.php");
            exit();
        } else {
            $error = "Invalid Admin Credentials!";
        }
    } elseif ($user_type == "user") {
        // Check user credentials from database
        $stmt = $conn->prepare("SELECT id, name, MobileNumber, EmailID, password FROM user_tbl WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($user_id, $name, $mobile, $email, $hashed_password);
            $stmt->fetch();

            // Validate password (Check if it's hashed or plaintext)
            if (password_verify($password, $hashed_password) || $password === $hashed_password) {
                $_SESSION['user_logged_in'] = true;
                $_SESSION['user_id'] = $user_id;
                $_SESSION['name'] = $name;
                $_SESSION['mobile'] = $mobile;
                $_SESSION['email'] = $email;

                header("Location: userdashboard.php"); // Redirect users to dashboard
                exit();
            } else {
                $error = "Invalid Username or Password!";
            }
        } else {
            $error = "No user found with this username!";
        }

        $stmt->close();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>

<div class="container" id="container">
    <div class="form-container sign-in-container">
        <form method="post" action="login.php">
            <h1 text="center">Login</h1>
            <?php if (!empty($error)) { echo "<p style='color: red;'>$error</p>"; } ?>
            <select name="user_type" required>
                <option value="admin">Admin</option>
                <option value="user">User</option>
            </select>
            <input type="text" name="username" placeholder="Username" required />
            <input type="password" name="password" placeholder="Password" required />
            <button type="submit">SIGN IN</button>
        </form>
    </div>

    <div class="overlay-container">
        <div class="overlay">
            <div class="overlay-panel overlay-right">
                <h2>Welcome!</h2>
                <p>Enter your credentials to continue your journey.</p>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const container = document.getElementById('container');
        const signUpButton = document.getElementById('signUp');
        const signInButton = document.getElementById('signIn');

        if (signUpButton) {
            signUpButton.addEventListener('click', () => {
                container.classList.add("right-panel-active");
            });
        }

        if (signInButton) {
            signInButton.addEventListener('click', () => {
                container.classList.remove("right-panel-active");
            });
        }
    });
</script>

</body>
</html>
