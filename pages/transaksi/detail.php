<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../auth/login.php");
    exit();
}
require_once '../../config/database.php';
$path_to_root = "../..";

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
    header("Location: index.php");
    exit();
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
<?php include '../../includes/header.php'; ?>

<!-- Google Material Icons -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<?php include '../../includes/sidebar.php'; ?>

<div class="main-content">
    <?php include '../../includes/navbar.php'; ?>

    <div class="container-fluid p-3 p-md-4">
        <!-- Header Section -->
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-3">
            <div>
                <h2 class="fw-bold mb-1" style="color: #212529;">Detail Transaksi #<?php echo $transaksi['id']; ?></h2>
                <p class="text-muted mb-0">Informasi lengkap transaksi penjualan</p>
            </div>
            <div class="d-flex gap-2">
                <a href="print.php?id=<?php echo $transaksi['id']; ?>" target="_blank" class="btn-print">
                    <span class="material-icons me-2" style="font-size: 18px;">print</span>
                    Cetak
                </a>
                <a href="index.php" class="btn-back">
                    <span class="material-icons me-2" style="font-size: 18px;">arrow_back</span>
                    Kembali
                </a>
            </div>
        </div>

        <div class="row g-4">
            <!-- Transaction Info -->
            <div class="col-lg-4">
                <div class="card-modern">
                    <div class="card-modern-header">
                        <span class="material-icons text-warning me-2">receipt</span>
                        <h5 class="mb-0">Informasi Transaksi</h5>
                    </div>
                    <div class="card-modern-body">
                        <div class="info-group">
                            <div class="info-item-detail">
                                <span class="material-icons info-icon">tag</span>
                                <div class="info-content">
                                    <label>ID Transaksi</label>
                                    <p>#<?php echo $transaksi['id']; ?></p>
                                </div>
                            </div>

                            <div class="info-item-detail">
                                <span class="material-icons info-icon">schedule</span>
                                <div class="info-content">
                                    <label>Tanggal & Waktu</label>
                                    <p><?php echo date('d M Y', strtotime($transaksi['created_at'])); ?></p>
                                    <p class="small text-muted"><?php echo date('H:i', strtotime($transaksi['created_at'])); ?> WIB</p>
                                </div>
                            </div>

                            <div class="info-item-detail">
                                <span class="material-icons info-icon">person</span>
                                <div class="info-content">
                                    <label>Kasir</label>
                                    <p><?php echo htmlspecialchars($transaksi['kasir_nama']); ?></p>
                                </div>
                            </div>
                        </div>

                        <hr class="my-3">

                        <!-- Payment Summary -->
                        <div class="payment-summary">
                            <div class="summary-row">
                                <span class="summary-label">Total Bayar:</span>
                                <span class="summary-value total">Rp <?php echo number_format($transaksi['total_bayar'], 0, ',', '.'); ?></span>
                            </div>
                            <div class="summary-row">
                                <span class="summary-label">Uang Bayar:</span>
                                <span class="summary-value">Rp <?php echo number_format($transaksi['uang_bayar'], 0, ',', '.'); ?></span>
                            </div>
                            <div class="summary-row">
                                <span class="summary-label">Kembalian:</span>
                                <span class="summary-value change">Rp <?php echo number_format($transaksi['kembalian'], 0, ',', '.'); ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card-modern mt-3">
                    <div class="card-modern-header">
                        <span class="material-icons text-warning me-2">settings</span>
                        <h5 class="mb-0">Aksi Cepat</h5>
                    </div>
                    <div class="card-modern-body">
                        <div class="d-grid gap-2">
                            <a href="edit.php?id=<?php echo $transaksi['id']; ?>" class="btn-action-full btn-edit-full">
                                <span class="material-icons me-2">edit</span>
                                Edit Transaksi
                            </a>
                            <a href="print.php?id=<?php echo $transaksi['id']; ?>" target="_blank" class="btn-action-full btn-print-full">
                                <span class="material-icons me-2">print</span>
                                Cetak Struk
                            </a>
                            <a href="delete.php?id=<?php echo $transaksi['id']; ?>" 
                               class="btn-action-full btn-delete-full"
                               onclick="return confirm('Yakin ingin menghapus transaksi ini?')">
                                <span class="material-icons me-2">delete</span>
                                Hapus Transaksi
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="col-lg-8">
                <div class="card-modern">
                    <div class="card-modern-header">
                        <span class="material-icons text-warning me-2">shopping_basket</span>
                        <h5 class="mb-0">Item Pesanan</h5>
                    </div>
                    <div class="card-modern-body p-0">
                        <div class="table-responsive">
                            <table class="table-detail">
                                <thead>
                                    <tr>
                                        <th style="width: 50px;">No</th>
                                        <th>Nama Menu</th>
                                        <th style="width: 150px;">Harga Satuan</th>
                                        <th style="width: 100px;" class="text-center">Qty</th>
                                        <th style="width: 150px;" class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $no = 1;
                                    while ($item = $details->fetch_assoc()): 
                                    ?>
                                    <tr>
                                        <td><?php echo $no++; ?></td>
                                        <td>
                                            <div class="menu-name-detail">
                                                <span class="material-icons menu-icon">restaurant</span>
                                                <span><?php echo htmlspecialchars($item['menu_nama']); ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="price-text">Rp <?php echo number_format($item['harga_satuan'], 0, ',', '.'); ?></span>
                                        </td>
                                        <td class="text-center">
                                            <span class="qty-badge"><?php echo $item['jumlah']; ?>x</span>
                                        </td>
                                        <td class="text-end">
                                            <span class="subtotal-text">Rp <?php echo number_format($item['subtotal'], 0, ',', '.'); ?></span>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                                <tfoot>
                                    <tr class="total-row">
                                        <td colspan="4" class="text-end"><strong>TOTAL:</strong></td>
                                        <td class="text-end">
                                            <strong class="total-amount">Rp <?php echo number_format($transaksi['total_bayar'], 0, ',', '.'); ?></strong>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Card Modern */
    .card-modern {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e8e8e8;
        overflow: hidden;
    }

    .card-modern-header {
        padding: 20px 24px;
        background: #fafafa;
        border-bottom: 1px solid #e8e8e8;
        display: flex;
        align-items: center;
    }

    .card-modern-header h5 {
        font-size: 18px;
        font-weight: 600;
        color: #212529;
    }

    .card-modern-body {
        padding: 24px;
    }

    /* Info Items */
    .info-group {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .info-item-detail {
        display: flex;
        gap: 16px;
        padding: 16px;
        background: #fafafa;
        border-radius: 10px;
    }

    .info-icon {
        width: 40px;
        height: 40px;
        min-width: 40px;
        background: #fff8e1;
        color: #FFCC00;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .info-content {
        flex: 1;
    }

    .info-content label {
        font-size: 12px;
        color: #6c757d;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
        display: block;
    }

    .info-content p {
        font-size: 15px;
        color: #212529;
        font-weight: 600;
        margin: 0;
    }

    .info-content p.small {
        font-size: 13px;
        font-weight: 400;
    }

    /* Payment Summary */
    .payment-summary {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 16px;
        background: #fafafa;
        border-radius: 8px;
    }

    .summary-label {
        font-size: 14px;
        color: #6c757d;
        font-weight: 500;
    }

    .summary-value {
        font-size: 16px;
        font-weight: 700;
        color: #212529;
    }

    .summary-value.total {
        color: #FFCC00;
        font-size: 18px;
    }

    .summary-value.change {
        color: #28a745;
    }

    /* Action Buttons */
    .btn-action-full {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 12px 20px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
        border: 1px solid;
    }

    .btn-edit-full {
        background: #fff8e1;
        color: #FFCC00;
        border-color: #fff8e1;
    }

    .btn-edit-full:hover {
        background: #FFCC00;
        color: #212529;
        border-color: #FFCC00;
    }

    .btn-print-full {
        background: #f5f5f5;
        color: #616161;
        border-color: #e0e0e0;
    }

    .btn-print-full:hover {
        background: #616161;
        color: #ffffff;
        border-color: #616161;
    }

    .btn-delete-full {
        background: #ffebee;
        color: #d32f2f;
        border-color: #ffcdd2;
    }

    .btn-delete-full:hover {
        background: #d32f2f;
        color: #ffffff;
        border-color: #d32f2f;
    }

    /* Table Detail */
    .table-detail {
        width: 100%;
        margin: 0;
    }

    .table-detail thead {
        background: #fafafa;
    }

    .table-detail th {
        padding: 16px 20px;
        font-size: 13px;
        font-weight: 600;
        color: #495057;
        border-bottom: 2px solid #e8e8e8;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .table-detail td {
        padding: 16px 20px;
        font-size: 14px;
        color: #212529;
        border-bottom: 1px solid #f0f0f0;
        vertical-align: middle;
    }

    .table-detail tbody tr:hover {
        background: #fafafa;
    }

    .table-detail tbody tr:last-child td {
        border-bottom: none;
    }

    .table-detail tfoot {
        background: #fff8e1;
        border-top: 2px solid #FFCC00;
    }

    .table-detail tfoot td {
        padding: 20px;
        font-size: 16px;
        border-bottom: none;
    }

    /* Menu Name Detail */
    .menu-name-detail {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .menu-icon {
        width: 32px;
        height: 32px;
        background: #fff8e1;
        color: #FFCC00;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    /* Badges & Text */
    .qty-badge {
        display: inline-block;
        padding: 6px 12px;
        background: #e3f2fd;
        color: #1976d2;
        border-radius: 6px;
        font-weight: 600;
        font-size: 13px;
    }

    .price-text {
        color: #6c757d;
        font-weight: 500;
    }

    .subtotal-text {
        color: #212529;
        font-weight: 600;
    }

    .total-amount {
        color: #FFCC00;
        font-size: 20px;
    }

    /* Header Buttons */
    .btn-print {
        background: #f5f5f5;
        color: #616161;
        border: 1px solid #e0e0e0;
        border-radius: 10px;
        padding: 10px 20px;
        font-weight: 600;
        font-size: 14px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        transition: all 0.2s;
    }

    .btn-print:hover {
        background: #616161;
        color: #ffffff;
    }

    .btn-back {
        background: #f8f9fa;
        color: #495057;
        border: 1px solid #dee2e6;
        border-radius: 10px;
        padding: 10px 20px;
        font-weight: 600;
        font-size: 14px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        transition: all 0.2s;
    }

    .btn-back:hover {
        background: #e9ecef;
        color: #212529;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .table-detail {
            font-size: 13px;
        }

        .table-detail th,
        .table-detail td {
            padding: 12px 16px;
        }

        .summary-value.total {
            font-size: 16px;
        }

        .total-amount {
            font-size: 18px;
        }
    }
</style>

<?php include '../../includes/footer.php'; ?>