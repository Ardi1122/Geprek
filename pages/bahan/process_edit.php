<?php
session_start();
require_once '../../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $stok = $_POST['stok'];
    $satuan = $_POST['satuan'];
    $harga_beli = $_POST['harga_beli'];

    $stmt = $conn->prepare("UPDATE bahan_baku SET nama=?, stok=?, satuan=?, harga_beli=? WHERE id=?");
    $stmt->bind_param("sdsii", $nama, $stok, $satuan, $harga_beli, $id);

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