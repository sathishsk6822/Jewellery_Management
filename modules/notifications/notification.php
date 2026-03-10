<?php
include '../../includes/dbconnect.php';
include '../../includes/header.php';
include '../../includes/sidebar.php';

// Assume customer_id is stored in session after login
$customer_id = $_SESSION['customer_id'] ?? 1; // Change as needed

$sql = "SELECT * FROM notifications_tbl WHERE customer_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .content {
            margin-left: 270px;
            padding: 40px;
            width: calc(100% - 270px);
            background-color: #f9f9f9;
        }
        .notification-item {
            padding: 10px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
            transition: background-color 0.3s ease;
        }
        .unread {
            background-color: #ffebcc;
            font-weight: bold;
        }
        .read {
            background-color: #f1f1f1;
        }
        .time {
            font-size: 12px;
            color: #777;
        }
        .mark-read {
            cursor: pointer;
            color: blue;
            text-decoration: underline;
        }
        .notification-icon {
            margin-right: 10px;
        }
    </style>
</head>
<body>

<div class="content">
    <h2>Notifications</h2>

    <div class="list-group">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="notification-item <?= $row['is_read'] == 0 ? 'unread' : 'read' ?>" id="notif_<?= $row['id'] ?>">
                    <div>
                        <?= getNotificationIcon($row['type']) ?>
                        <strong><?= $row['title'] ?></strong>
                        <p><?= $row['message'] ?></p>
                    </div>
                    <span class="time"><?= date('d M, H:i', strtotime($row['created_at'])) ?></span>
                    <?php if ($row['is_read'] == 0): ?>
                        <span class="mark-read" onclick="markAsRead(<?= $row['id'] ?>)">Mark as Read</span>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No new notifications.</p>
        <?php endif; ?>
    </div>
</div>

<script>
    function markAsRead(notifId) {
        fetch('../../handlers/mark_read.php?id=' + notifId, { method: 'GET' })
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                document.getElementById('notif_' + notifId).classList.remove('unread');
                document.getElementById('notif_' + notifId).classList.add('read');
                document.getElementById('notif_' + notifId).querySelector('.mark-read').remove();
            }
        })
        .catch(error => console.error('Error:', error));
    }
</script>

</body>
</html>

<?php
// Function to return an icon based on notification type
function getNotificationIcon($type) {
    switch ($type) {
        case 'renewal': return '<i class="bi bi-clock text-primary notification-icon"></i>';
        case 'pledge': return '<i class="bi bi-bookmark text-success notification-icon"></i>';
        case 'payment': return '<i class="bi bi-credit-card text-danger notification-icon"></i>';
        case 'security': return '<i class="bi bi-shield-lock text-warning notification-icon"></i>';
        default: return '<i class="bi bi-bell text-secondary notification-icon"></i>';
    }
}
?>
