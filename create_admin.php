<?php
require_once 'config/database.php';

$username = 'admin'; // Change this if you want a different default username
$password = 'admin123'; // Change this if you want a different default password
$role = 'pemilik';

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Check if user already exists
$check = $conn->prepare("SELECT id FROM user WHERE nama = ?");
$check->bind_param("s", $username);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
    echo "User '$username' already exists.<br>";
} else {
    // Insert new admin
    $stmt = $conn->prepare("INSERT INTO user (nama, password, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $hashed_password, $role);

    if ($stmt->execute()) {
        echo "Success! Admin account created.<br>";
        echo "Username: $username<br>";
        echo "Password: $password (Hashed)<br>";
        echo "<a href='auth/login.php'>Go to Login</a>";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

$check->close();
$conn->close();
?>