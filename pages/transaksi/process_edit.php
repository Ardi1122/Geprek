<?php
session_start();
require_once '../../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $total_bayar = $_POST['total_bayar'];
    $uang_bayar = $_POST['uang_bayar'];
    $kembalian = $_POST['kembalian'];
    $items = $_POST['items'];

    // Start Transaction
    $conn->begin_transaction();

    try {
        // Update Transaksi Info
        $stmt = $conn->prepare("UPDATE transaksi SET total_bayar = ?, uang_bayar = ?, kembalian = ? WHERE id = ?");
        $stmt->bind_param("iiii", $total_bayar, $uang_bayar, $kembalian, $id);
        $stmt->execute();
        $stmt->close();

        // Delete Old Details
        $stmt = $conn->prepare("DELETE FROM detail_transaksi WHERE transaksi_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();

        // Insert New Details
        $stmt = $conn->prepare("INSERT INTO detail_transaksi (transaksi_id, menu_id, jumlah, harga_satuan, subtotal) VALUES (?, ?, ?, ?, ?)");

        foreach ($items as $item) {
            $menu_id = $item['menu_id'];
            $jumlah = $item['jumlah'];
            $harga_satuan = $item['harga_satuan'];
            $subtotal = $item['subtotal'];

            if (!empty($menu_id) && $jumlah > 0) {
                $stmt->bind_param("iiiii", $id, $menu_id, $jumlah, $harga_satuan, $subtotal);
                $stmt->execute();
            }
        }
        $stmt->close();

        // Commit
        $conn->commit();
        header("Location: index.php");
        exit();

    } catch (Exception $e) {
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }
}
$conn->close();
?>