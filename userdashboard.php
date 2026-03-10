<?php
session_start();
include 'dbconnect.php';
include 'sidebars.php';
// Redirect to login if not logged in
if (!isset($_SESSION['user_logged_in']) || !isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Fetch logged-in user's ID from session
$user_id = $_SESSION['user_id'];

// Fetch user details
$user_sql = "SELECT * FROM user_tbl WHERE id = ?";
$stmt = $conn->prepare($user_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_result = $stmt->get_result();
$user = $user_result->fetch_assoc();

if (!$user) {
    die("User not found!");
}

// Store user details in session
$_SESSION['name'] = $user['name'];
$_SESSION['mobile'] = $user['MobileNumber'];
$_SESSION['email'] = $user['EmailID'];

// Fetch notifications
$notif_sql = "SELECT title, created_at FROM notifications_tbl WHERE customer_id = ? ORDER BY created_at DESC LIMIT 5";
$stmt = $conn->prepare($notif_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$notif_result = $stmt->get_result();

// Fetch pledge details
$pledge_sql = "SELECT pledge_date, amount FROM pledge_tbl WHERE customer_id = ? ORDER BY pledge_date DESC";
$stmt = $conn->prepare($pledge_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$pledge_result = $stmt->get_result();

// Fetch release details
$release_sql = "SELECT id, receipt_number FROM release_customer_gst_tbl WHERE customer_id = ? ORDER BY id DESC";
$stmt = $conn->prepare($release_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$release_result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="user.css">
</head>
<body>

<div class="container mt-5">
    <h2>Welcome, <?= htmlspecialchars($_SESSION['name']) ?>!</h2>

    <div class="row">
        <!-- User Details -->
        <div class="col-md-4">
            <div class="card p-3">
                <h5>Your Details</h5>
                <p><strong>Name:</strong> <?= htmlspecialchars($_SESSION['name']) ?></p>
                <p><strong>Mobile:</strong> <?= htmlspecialchars($_SESSION['mobile']) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($_SESSION['email']) ?></p>
            </div>
        </div>

        <!-- Notifications -->
        <div class="col-md-8">
            <div class="card p-3">
                <h5>Notifications <span class="badge bg-danger"><?= $notif_result->num_rows ?></span></h5>
                <ul class="list-group">
                    <?php while ($notif = $notif_result->fetch_assoc()): ?>
                        <li class="list-group-item">
                            <?= htmlspecialchars($notif['title']) ?> - <?= date('d M, H:i', strtotime($notif['created_at'])) ?>
                        </li>
                    <?php endwhile; ?>
                    <?php if ($notif_result->num_rows == 0): ?>
                        <li class="list-group-item text-center">No new notifications</li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <!-- Pledge Details -->
        <div class="col-md-6">
            <div class="card p-3">
                <h5>Your Pledge Details</h5>
                <ul class="list-group">
                    <?php while ($pledge = $pledge_result->fetch_assoc()): ?>
                        <li class="list-group-item">
                            Amount: <?= htmlspecialchars($pledge['amount']) ?> | Date: <?= date('d M, Y', strtotime($pledge['pledge_date'])) ?>
                        </li>
                    <?php endwhile; ?>
                    <?php if ($pledge_result->num_rows == 0): ?>
                        <li class="list-group-item text-center">No pledges found</li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>

        <!-- Release Details -->
        <div class="col-md-6">
            <div class="card p-3">
                <h5>Your Release Details</h5>
                <ul class="list-group">
                    <?php while ($release = $release_result->fetch_assoc()): ?>
                        <li class="list-group-item">
                            Receipt Number: <?= htmlspecialchars($release['receipt_number']) ?>
                        </li>
                    <?php endwhile; ?>
                    <?php if ($release_result->num_rows == 0): ?>
                        <li class="list-group-item text-center">No releases found</li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

</body>
</html>
