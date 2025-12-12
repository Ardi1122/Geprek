<?php
session_start();
require_once '../../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $stok = $_POST['stok'];
    $satuan = $_POST['satuan'];
    $harga_beli = $_POST['harga_beli'];

    $stmt = $conn->prepare("INSERT INTO bahan_baku (nama, stok, satuan, harga_beli) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sdsi", $nama, $stok, $satuan, $harga_beli);

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