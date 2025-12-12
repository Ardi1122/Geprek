<?php
session_start();
require_once '../../config/database.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Get image name to delete file
    $stmt = $conn->prepare("SELECT gambar FROM menu WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        $gambar = $row['gambar'];
        $target_dir = "../../assets/uploads/";

        // Delete from DB
        $stmt = $conn->prepare("DELETE FROM menu WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            // Delete file
            if ($gambar && file_exists($target_dir . $gambar)) {
                unlink($target_dir . $gambar);
            }
            header("Location: index.php");
            exit();
        } else {
            echo "Error deleting record: " . $conn->error;
        }
    } else {
        header("Location: index.php");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
?>