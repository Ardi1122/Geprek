<?php
session_start();
require_once '../../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $role = $_POST['role'];
    $password_input = $_POST['password'];

    // Ambil password lama dulu
    $stmt_old = $conn->prepare("SELECT password FROM user WHERE id=?");
    $stmt_old->bind_param("i", $id);
    $stmt_old->execute();
    $result_old = $stmt_old->get_result();
    $row_old = $result_old->fetch_assoc();
    $old_password = $row_old['password'];

    // Jika password baru diinput → hash baru
    // Jika tidak diinput → tetap pakai password lama
    if (!empty($password_input)) {
        $password = password_hash($password_input, PASSWORD_DEFAULT);
    } else {
        $password = $old_password;
    }

    // Update
    $stmt = $conn->prepare("UPDATE user SET nama=?, password=?, role=? WHERE id=?");
    $stmt->bind_param("sssi", $nama, $password, $role, $id);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>
