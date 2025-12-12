<?php
session_start();
require_once '../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, nama, password, role FROM user WHERE nama = ?");
    $stmt->bind_param("s", $nama);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {

        $row = $result->fetch_assoc(); // <-- cukup sekali

        if (password_verify($password, $row['password'])) {

            $_SESSION['user_id'] = $row['id'];
            $_SESSION['nama'] = $row['nama'];
            $_SESSION['role'] = $row['role'];

            header("Location: ../index.php");
            exit();

        } else {
            header("Location: login.php?error=Invalid password");
            exit();
        }

    } else {
        header("Location: login.php?error=User not found");
        exit();
    }
}

$conn->close();
?>
