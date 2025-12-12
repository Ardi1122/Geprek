<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../auth/login.php");
    exit();
}
require_once '../../config/database.php';
$path_to_root = "../..";
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
                <h2 class="fw-bold mb-1" style="color: #212529;">Riwayat Transaksi</h2>
                <p class="text-muted mb-0">Kelola dan pantau semua transaksi penjualan</p>
            </div>
            <a href="create.php" class="btn-primary-modern">
                <span class="material-icons me-2" style="font-size: 18px;">add_circle</span>
                Transaksi Baru
            </a>
        </div>

        <!-- Stats Cards -->
        <div class="row g-3 mb-4">
            <?php
            // Get today's stats
            $today = date('Y-m-d');
            $stats_query = "SELECT 
                COUNT(*) as total_transactions,
                COALESCE(SUM(total_bayar), 0) as total_sales
                FROM transaksi 
                WHERE DATE(created_at) = '$today'";
            $stats_result = $conn->query($stats_query);
            $stats = $stats_result->fetch_assoc();

            // Get this month's stats
            $this_month = date('Y-m');
            $month_query = "SELECT 
                COUNT(*) as total_transactions,
                COALESCE(SUM(total_bayar), 0) as total_sales
                FROM transaksi 
                WHERE DATE_FORMAT(created_at, '%Y-%m') = '$this_month'";
            $month_result = $conn->query($month_query);
            $month_stats = $month_result->fetch_assoc();
            ?>

            <div class="col-md-6 col-xl-3">
                <div class="stat-card stat-card-yellow">
                    <div class="stat-icon">
                        <span class="material-icons">today</span>
                    </div>
                    <div class="stat-content">
                        <p class="stat-label">Transaksi Hari Ini</p>
                        <h3 class="stat-value"><?php echo $stats['total_transactions']; ?></h3>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="stat-card stat-card-green">
                    <div class="stat-icon">
                        <span class="material-icons">payments</span>
                    </div>
                    <div class="stat-content">
                        <p class="stat-label">Penjualan Hari Ini</p>
                        <h3 class="stat-value">Rp <?php echo number_format($stats['total_sales'], 0, ',', '.'); ?></h3>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="stat-card stat-card-blue">
                    <div class="stat-icon">
                        <span class="material-icons">calendar_month</span>
                    </div>
                    <div class="stat-content">
                        <p class="stat-label">Transaksi Bulan Ini</p>
                        <h3 class="stat-value"><?php echo $month_stats['total_transactions']; ?></h3>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="stat-card stat-card-purple">
                    <div class="stat-icon">
                        <span class="material-icons">trending_up</span>
                    </div>
                    <div class="stat-content">
                        <p class="stat-label">Penjualan Bulan Ini</p>
                        <h3 class="stat-value">Rp <?php echo number_format($month_stats['total_sales'], 0, ',', '.'); ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transaction Table -->
        <div class="card-modern">
            <div class="card-modern-header">
                <span class="material-icons text-warning me-2">receipt_long</span>
                <h5 class="mb-0">Daftar Transaksi</h5>
            </div>
            <div class="card-modern-body p-0">
                <div class="table-responsive">
                    <table class="table-modern">
                        <thead>
                            <tr>
                                <th style="width: 80px;">ID</th>
                                <th style="width: 180px;">Tanggal & Waktu</th>
                                <th style="width: 150px;">Kasir</th>
                                <th style="width: 150px;">Total Bayar</th>
                                <th style="width: 150px;">Uang Bayar</th>
                                <th style="width: 150px;">Kembalian</th>
                                <th style="width: 200px;" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT t.*, u.nama as kasir_nama 
                                      FROM transaksi t 
                                      JOIN user u ON t.user_id = u.id 
                                      ORDER BY t.created_at DESC";
                            $result = $conn->query($query);

                            if ($result->num_rows > 0):
                                while ($row = $result->fetch_assoc()):
                            ?>
                            <tr>
                                <td>
                                    <span class="transaction-id">#<?php echo $row['id']; ?></span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="material-icons text-muted" style="font-size: 18px;">schedule</span>
                                        <span><?php echo date('d M Y, H:i', strtotime($row['created_at'])); ?></span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="material-icons text-muted" style="font-size: 18px;">person</span>
                                        <span><?php echo htmlspecialchars($row['kasir_nama']); ?></span>
                                    </div>
                                </td>
                                <td>
                                    <span class="price-display">Rp <?php echo number_format($row['total_bayar'], 0, ',', '.'); ?></span>
                                </td>
                                <td>
                                    <span class="text-muted">Rp <?php echo number_format($row['uang_bayar'], 0, ',', '.'); ?></span>
                                </td>
                                <td>
                                    <span class="change-display">Rp <?php echo number_format($row['kembalian'], 0, ',', '.'); ?></span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="detail.php?id=<?php echo $row['id']; ?>" 
                                           class="btn-action btn-action-view" 
                                           title="Lihat Detail">
                                            <span class="material-icons">visibility</span>
                                        </a>
                                        <a href="print.php?id=<?php echo $row['id']; ?>" 
                                           target="_blank"
                                           class="btn-action btn-action-print" 
                                           title="Cetak">
                                            <span class="material-icons">print</span>
                                        </a>
                                        <a href="edit.php?id=<?php echo $row['id']; ?>" 
                                           class="btn-action btn-action-edit" 
                                           title="Edit">
                                            <span class="material-icons">edit</span>
                                        </a>
                                        <a href="delete.php?id=<?php echo $row['id']; ?>" 
                                           class="btn-action btn-action-delete" 
                                           onclick="return confirm('Yakin ingin menghapus transaksi ini?')"
                                           title="Hapus">
                                            <span class="material-icons">delete</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php 
                                endwhile;
                            else:
                            ?>
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <span class="material-icons empty-icon">receipt</span>
                                    <p class="text-muted mt-3 mb-0">Belum ada transaksi</p>
                                    <a href="create.php" class="btn btn-sm btn-primary-modern mt-3">
                                        <span class="material-icons me-2" style="font-size: 16px;">add</span>
                                        Buat Transaksi Pertama
                                    </a>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="<?php echo ASSETS_PATH; ?>css/transaksi/index.css">

<?php include '../../includes/footer.php'; ?>