<?php
include 'dbconnect.php';

$result = $conn->query("SELECT id, password FROM user_tbl");

while ($row = $result->fetch_assoc()) {
    if (!password_needs_rehash($row['password'], PASSWORD_DEFAULT)) {
        continue; // Skip if already hashed properly
    }

    $hashed_password = password_hash($row['password'], PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE user_tbl SET password = ? WHERE id = ?");
    $stmt->bind_param("si", $hashed_password, $row['id']);
    $stmt->execute();
}

echo "✅ Passwords updated successfully!";
?>
