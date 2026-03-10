<?php
include 'dbconnect.php';

$result = $conn->query("SELECT * FROM sms_history ORDER BY sent_at DESC");

while ($row = $result->fetch_assoc()) {
    echo "<tr id='sms_{$row['id']}'>
            <td>{$row['sent_at']}</td>
            <td>{$row['recipient']}</td>
            <td>{$row['message']}</td>
            <td>
                <button class='btn btn-danger btn-sm' onclick='deleteSMS({$row['id']})'>Delete</button>
            </td>
          </tr>";
}
?>
