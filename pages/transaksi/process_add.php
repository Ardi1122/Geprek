<?php
session_start();
require_once '../../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $total_bayar = $_POST['total_bayar'];
    $uang_bayar = $_POST['uang_bayar'];
    $kembalian = $_POST['kembalian'];
    $items = $_POST['items'];

    // Start Transaction
    $conn->begin_transaction();

    try {
        // Insert into Transaksi
        $stmt = $conn->prepare("INSERT INTO transaksi (user_id, total_bayar, uang_bayar, kembalian) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiii", $user_id, $total_bayar, $uang_bayar, $kembalian);
        $stmt->execute();
        $transaksi_id = $conn->insert_id;
        $stmt->close();

        // Insert into Detail Transaksi and Update Stock
        $stmtDetail = $conn->prepare("INSERT INTO detail_transaksi (transaksi_id, menu_id, jumlah, harga_satuan, subtotal) VALUES (?, ?, ?, ?, ?)");
        $stmtRecipe = $conn->prepare("SELECT bahan_id, jumlah_pakai FROM menu_bahan WHERE menu_id = ?");
        $stmtStock = $conn->prepare("UPDATE bahan_baku SET stok = stok - ? WHERE id = ?");

        foreach ($items as $item) {
            $menu_id = $item['menu_id'];
            $jumlah = $item['jumlah'];
            $harga_satuan = $item['harga_satuan'];
            $subtotal = $item['subtotal'];

            if (!empty($menu_id) && $jumlah > 0) {
                // Insert Detail
                $stmtDetail->bind_param("iiiii", $transaksi_id, $menu_id, $jumlah, $harga_satuan, $subtotal);
                $stmtDetail->execute();

                // Update Stock
                $stmtRecipe->bind_param("i", $menu_id);
                $stmtRecipe->execute();
                $recipeResult = $stmtRecipe->get_result();

                while ($recipe = $recipeResult->fetch_assoc()) {
                    $bahan_id = $recipe['bahan_id'];
                    $qty_needed = $recipe['jumlah_pakai'] * $jumlah;

                    $stmtStock->bind_param("di", $qty_needed, $bahan_id);
                    $stmtStock->execute();
                }
            }
        }
        $stmtDetail->close();
        $stmtRecipe->close();
        $stmtStock->close();

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