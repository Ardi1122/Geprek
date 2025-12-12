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
                <h2 class="fw-bold mb-1" style="color: #212529;">Manajemen Bahan Baku</h2>
                <p class="text-muted mb-0">Kelola stok dan harga bahan baku dapur</p>
            </div>
            <a href="create.php" class="btn-primary-modern">
                <span class="material-icons me-2" style="font-size: 18px;">add_circle</span>
                Tambah Bahan Baku
            </a>
        </div>

        <!-- Stats Cards -->
        <div class="row g-3 mb-4">
            <?php
            // Get stats
            $total_items = $conn->query("SELECT COUNT(*) as total FROM bahan_baku")->fetch_assoc()['total'];
            $low_stock = $conn->query("SELECT COUNT(*) as total FROM bahan_baku WHERE stok < 10")->fetch_assoc()['total'];
            $total_value = $conn->query("SELECT SUM(stok * harga_beli) as total FROM bahan_baku")->fetch_assoc()['total'];
            ?>

            <div class="col-md-6 col-xl-4">
                <div class="stat-card stat-card-blue">
                    <div class="stat-icon">
                        <span class="material-icons">inventory_2</span>
                    </div>
                    <div class="stat-content">
                        <p class="stat-label">Total Bahan Baku</p>
                        <h3 class="stat-value"><?php echo $total_items; ?> Item</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-4">
                <div class="stat-card stat-card-red">
                    <div class="stat-icon">
                        <span class="material-icons">warning</span>
                    </div>
                    <div class="stat-content">
                        <p class="stat-label">Stok Menipis</p>
                        <h3 class="stat-value"><?php echo $low_stock; ?> Item</h3>
                    </div>
                </div>
            </div>

            <!-- <div class="col-md-6 col-xl-4">
                <div class="stat-card stat-card-green">
                    <div class="stat-icon">
                        <span class="material-icons">account_balance_wallet</span>
                    </div>
                    <div class="stat-content">
                        <p class="stat-label">Total Nilai Stok</p>
                        <h3 class="stat-value">Rp <?php echo number_format($total_value ?? 0, 0, ',', '.'); ?></h3>
                    </div>
                </div>
            </div> -->
        </div>

        <!-- Table Section -->
        <div class="card-modern">
            <div class="card-modern-header">
                <span class="material-icons text-warning me-2">list_alt</span>
                <h5 class="mb-0">Daftar Bahan Baku</h5>
            </div>
            <div class="card-modern-body p-0">
                <div class="table-responsive">
                    <table class="table-modern">
                        <thead>
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th>Nama Bahan</th>
                                <th style="width: 150px;">Stok</th>
                                <th style="width: 100px;">Satuan</th>
                                <th style="width: 150px;">Harga Beli</th>
                                <th style="width: 150px;">Status Stok</th>
                                <th style="width: 150px;" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $result = $conn->query("SELECT * FROM bahan_baku ORDER BY nama ASC");
                            $no = 1;
                            if ($result->num_rows > 0):
                                while ($row = $result->fetch_assoc()):
                                    // Determine stock status
                                    $stock_status = 'available';
                                    $stock_icon = 'check_circle';
                                    $stock_text = 'Aman';
                                    
                                    if ($row['stok'] < 5) {
                                        $stock_status = 'critical';
                                        $stock_icon = 'error';
                                        $stock_text = 'Kritis';
                                    } elseif ($row['stok'] < 10) {
                                        $stock_status = 'low';
                                        $stock_icon = 'warning';
                                        $stock_text = 'Menipis';
                                    }
                            ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td>
                                    <div class="ingredient-name">
                                        <span class="material-icons ingredient-icon">inventory</span>
                                        <span class="fw-semibold"><?php echo htmlspecialchars($row['nama']); ?></span>
                                    </div>
                                </td>
                                <td>
                                    <span class="stock-display"><?php echo number_format($row['stok'], 2, ',', '.'); ?></span>
                                </td>
                                <td>
                                    <span class="unit-badge"><?php echo htmlspecialchars($row['satuan']); ?></span>
                                </td>
                                <td>
                                    <span class="price-display">Rp <?php echo number_format($row['harga_beli'], 0, ',', '.'); ?></span>
                                </td>
                                <td>
                                    <span class="stock-badge stock-<?php echo $stock_status; ?>">
                                        <span class="material-icons"><?php echo $stock_icon; ?></span>
                                        <?php echo $stock_text; ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="edit.php?id=<?php echo $row['id']; ?>" 
                                           class="btn-action btn-action-edit" 
                                           title="Edit">
                                            <span class="material-icons">edit</span>
                                        </a>
                                        <a href="delete.php?id=<?php echo $row['id']; ?>" 
                                           class="btn-action btn-action-delete" 
                                           onclick="return confirm('Yakin ingin menghapus bahan baku ini?')"
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
                                    <span class="material-icons empty-icon">inventory_2</span>
                                    <p class="text-muted mt-3 mb-0">Belum ada bahan baku</p>
                                    <a href="create.php" class="btn btn-sm btn-primary-modern mt-3">
                                        <span class="material-icons me-2" style="font-size: 16px;">add</span>
                                        Tambah Bahan Baku Pertama
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

<link rel="stylesheet" href="<?php echo ASSETS_PATH; ?>css/bahan/index.css">

<?php include '../../includes/footer.php'; ?>