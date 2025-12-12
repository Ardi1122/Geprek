<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../auth/login.php");
    exit();
}
require_once '../../config/database.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];

// Get Transaction Info
$stmt = $conn->prepare("SELECT t.*, u.nama as kasir_nama 
                        FROM transaksi t 
                        JOIN user u ON t.user_id = u.id 
                        WHERE t.id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$transaksi = $stmt->get_result()->fetch_assoc();

if (!$transaksi) {
    die("Transaction not found.");
}

// Get Details
$stmt = $conn->prepare("SELECT dt.*, m.nama as menu_nama 
                        FROM detail_transaksi dt 
                        JOIN menu m ON dt.menu_id = m.id 
                        WHERE dt.transaksi_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$details = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Transaksi #<?php echo $transaksi['id']; ?> - Geprek Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            padding: 30px 20px;
            max-width: 80mm;
            margin: 0 auto;
            background: #f5f5f5;
            color: #212529;
        }

        .receipt-container {
            background: #ffffff;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        /* Header */
        .header {
            text-align: center;
            margin-bottom: 24px;
            padding-bottom: 20px;
            border-bottom: 2px solid #FFCC00;
        }

        .logo-text {
            font-size: 24px;
            font-weight: 700;
            color: #212529;
            margin-bottom: 4px;
            letter-spacing: 1px;
        }

        .logo-highlight {
            color: #FFCC00;
        }

        .header-subtitle {
            font-size: 11px;
            color: #6c757d;
            margin-bottom: 8px;
            font-weight: 500;
        }

        .header-address {
            font-size: 10px;
            color: #6c757d;
            line-height: 1.4;
        }

        /* Transaction Info */
        .transaction-info {
            margin-bottom: 20px;
            padding: 12px;
            background: #fafafa;
            border-radius: 8px;
            font-size: 11px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 6px;
            line-height: 1.5;
        }

        .info-row:last-child {
            margin-bottom: 0;
        }

        .info-label {
            color: #6c757d;
            font-weight: 500;
        }

        .info-value {
            color: #212529;
            font-weight: 600;
        }

        .transaction-id {
            color: #FFCC00;
            font-weight: 700;
        }

        /* Divider */
        .divider {
            border: none;
            border-top: 1px dashed #dee2e6;
            margin: 16px 0;
        }

        /* Items Table */
        .items-section {
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 12px;
            font-weight: 700;
            color: #212529;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .item {
            margin-bottom: 12px;
            font-size: 11px;
        }

        .item-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 4px;
        }

        .item-name {
            font-weight: 600;
            color: #212529;
            flex: 1;
        }

        .item-total {
            font-weight: 700;
            color: #212529;
            margin-left: 8px;
        }

        .item-details {
            display: flex;
            justify-content: space-between;
            color: #6c757d;
            font-size: 10px;
        }

        .item-price {
            font-weight: 500;
        }

        .item-qty {
            font-weight: 600;
        }

        /* Payment Summary */
        .payment-summary {
            background: #fafafa;
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 12px;
        }

        .summary-row:last-child {
            margin-bottom: 0;
        }

        .summary-label {
            color: #6c757d;
            font-weight: 500;
        }

        .summary-value {
            font-weight: 600;
            color: #212529;
        }

        /* Total Row */
        .total-row {
            padding-top: 12px;
            margin-top: 12px;
            border-top: 2px solid #FFCC00;
        }

        .total-row .summary-label {
            font-size: 14px;
            font-weight: 700;
            color: #212529;
        }

        .total-row .summary-value {
            font-size: 16px;
            font-weight: 700;
            color: #FFCC00;
        }

        /* Change Row */
        .change-row .summary-value {
            color: #28a745;
            font-weight: 700;
        }

        /* Footer */
        .footer {
            text-align: center;
            padding-top: 20px;
            border-top: 1px dashed #dee2e6;
        }

        .footer-message {
            font-size: 12px;
            font-weight: 600;
            color: #212529;
            margin-bottom: 8px;
        }

        .footer-tagline {
            font-size: 10px;
            color: #6c757d;
            margin-bottom: 12px;
        }

        .footer-website {
            font-size: 9px;
            color: #FFCC00;
            font-weight: 600;
        }

        /* Print Buttons */
        .print-actions {
            margin-bottom: 20px;
            text-align: center;
            display: flex;
            gap: 12px;
            justify-content: center;
        }

        .btn-print {
            background: #FFCC00;
            color: #212529;
            border: none;
            border-radius: 8px;
            padding: 12px 24px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s;
            font-family: 'Inter', sans-serif;
        }

        .btn-print:hover {
            background: #e6b800;
            transform: translateY(-1px);
        }

        .btn-close {
            background: #f8f9fa;
            color: #495057;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 12px 24px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s;
            font-family: 'Inter', sans-serif;
        }

        .btn-close:hover {
            background: #e9ecef;
        }

        /* Print Styles */
        @media print {
            body {
                background: #ffffff;
                padding: 0;
            }

            .receipt-container {
                box-shadow: none;
                border-radius: 0;
                padding: 20px;
            }

            .print-actions {
                display: none;
            }

            .header {
                border-bottom: 2px solid #000;
            }

            .total-row {
                border-top: 2px solid #000;
            }

            .footer {
                border-top: 1px dashed #000;
            }
        }

        @page {
            size: 80mm auto;
            margin: 0;
        }
    </style>
</head>

<body>
    <!-- Print Actions (Hidden on Print) -->
    <div class="print-actions">
        <button class="btn-print" onclick="window.print()">
            🖨️ Cetak Struk
        </button>
        <button class="btn-close" onclick="window.close()">
            ✖️ Tutup
        </button>
    </div>

    <!-- Receipt Container -->
    <div class="receipt-container">
        <!-- Header -->
        <div class="header">
            <div class="logo-text">
                GEPREK <span class="logo-highlight">DASHBOARD</span>
            </div>
            <div class="header-subtitle">RESTO & CAFE</div>
            <div class="header-address">
                Jl. Example No. 123<br>
                Makassar, Sulawesi Selatan<br>
                Telp: (0411) 123-4567
            </div>
        </div>

        <!-- Transaction Info -->
        <div class="transaction-info">
            <div class="info-row">
                <span class="info-label">No. Transaksi:</span>
                <span class="info-value transaction-id">#<?php echo str_pad($transaksi['id'], 6, '0', STR_PAD_LEFT); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Tanggal:</span>
                <span class="info-value"><?php echo date('d/m/Y', strtotime($transaksi['created_at'])); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Waktu:</span>
                <span class="info-value"><?php echo date('H:i', strtotime($transaksi['created_at'])); ?> WIB</span>
            </div>
            <div class="info-row">
                <span class="info-label">Kasir:</span>
                <span class="info-value"><?php echo htmlspecialchars($transaksi['kasir_nama']); ?></span>
            </div>
        </div>

        <hr class="divider">

        <!-- Items Section -->
        <div class="items-section">
            <div class="section-title">Pesanan</div>
            
            <?php while ($item = $details->fetch_assoc()): ?>
            <div class="item">
                <div class="item-header">
                    <span class="item-name"><?php echo htmlspecialchars($item['menu_nama']); ?></span>
                    <span class="item-total">Rp <?php echo number_format($item['subtotal'], 0, ',', '.'); ?></span>
                </div>
                <div class="item-details">
                    <span class="item-price">@ Rp <?php echo number_format($item['harga_satuan'], 0, ',', '.'); ?></span>
                    <span class="item-qty">x <?php echo $item['jumlah']; ?></span>
                </div>
            </div>
            <?php endwhile; ?>
        </div>

        <hr class="divider">

        <!-- Payment Summary -->
        <div class="payment-summary">
            <div class="summary-row">
                <span class="summary-label">Subtotal:</span>
                <span class="summary-value">Rp <?php echo number_format($transaksi['total_bayar'], 0, ',', '.'); ?></span>
            </div>
            <div class="summary-row total-row">
                <span class="summary-label">TOTAL:</span>
                <span class="summary-value">Rp <?php echo number_format($transaksi['total_bayar'], 0, ',', '.'); ?></span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Tunai:</span>
                <span class="summary-value">Rp <?php echo number_format($transaksi['uang_bayar'], 0, ',', '.'); ?></span>
            </div>
            <div class="summary-row change-row">
                <span class="summary-label">Kembali:</span>
                <span class="summary-value">Rp <?php echo number_format($transaksi['kembalian'], 0, ',', '.'); ?></span>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="footer-message">Terima Kasih Atas Kunjungan Anda!</div>
            <div class="footer-tagline">Semoga Hari Anda Menyenangkan 😊</div>
            <div class="footer-website">www.geprekdashboard.com</div>
        </div>
    </div>

    <script>
        // Auto print on load (optional)
        // window.onload = function() {
        //     window.print();
        // };
    </script>
</body>

</html>