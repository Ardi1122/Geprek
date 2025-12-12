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
$stmt = $conn->prepare("SELECT * FROM bahan_baku WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    header("Location: index.php");
    exit();
}

// Calculate stock status
$stock_status = 'available';
$stock_text = 'Aman';
$stock_color = '#2e7d32';

if ($row['stok'] < 5) {
    $stock_status = 'critical';
    $stock_text = 'Kritis';
    $stock_color = '#d32f2f';
} elseif ($row['stok'] < 10) {
    $stock_status = 'low';
    $stock_text = 'Menipis';
    $stock_color = '#f57c00';
}
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
                <h2 class="fw-bold mb-1" style="color: #212529;">Edit Bahan Baku</h2>
                <p class="text-muted mb-0">Perbarui informasi bahan baku <?php echo htmlspecialchars($row['nama']); ?></p>
            </div>
            <a href="index.php" class="btn-back">
                <span class="material-icons me-2" style="font-size: 18px;">arrow_back</span>
                Kembali
            </a>
        </div>

        <div class="row g-4">
            <!-- Form Section -->
            <div class="col-lg-8">
                <div class="card-modern">
                    <div class="card-modern-header">
                        <span class="material-icons text-warning me-2">edit</span>
                        <h5 class="mb-0">Edit Informasi Bahan Baku</h5>
                    </div>
                    <div class="card-modern-body">
                        <form action="process_edit.php" method="POST" id="ingredientForm">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            
                            <!-- Nama Bahan -->
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    Nama Bahan <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control-modern" 
                                       name="nama" 
                                       value="<?php echo htmlspecialchars($row['nama']); ?>"
                                       required>
                            </div>

                            <div class="row g-3">
                                <!-- Stok -->
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label class="form-label-modern">
                                            Stok <span class="text-danger">*</span>
                                        </label>
                                        <input type="number" 
                                               step="0.01" 
                                               class="form-control-modern" 
                                               name="stok" 
                                               value="<?php echo $row['stok']; ?>"
                                               required>
                                        <small class="form-text-modern">Stok saat ini: <strong><?php echo number_format($row['stok'], 2, ',', '.'); ?></strong></small>
                                    </div>
                                </div>

                                <!-- Satuan -->
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label class="form-label-modern">
                                            Satuan <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select-modern" name="satuan" required>
                                            <option value="">Pilih Satuan</option>
                                            <option value="kg" <?php echo ($row['satuan'] == 'kg') ? 'selected' : ''; ?>>Kilogram (kg)</option>
                                            <option value="gram" <?php echo ($row['satuan'] == 'gram') ? 'selected' : ''; ?>>Gram (g)</option>
                                            <option value="liter" <?php echo ($row['satuan'] == 'liter') ? 'selected' : ''; ?>>Liter (L)</option>
                                            <option value="ml" <?php echo ($row['satuan'] == 'ml') ? 'selected' : ''; ?>>Mililiter (ml)</option>
                                            <option value="pcs" <?php echo ($row['satuan'] == 'pcs') ? 'selected' : ''; ?>>Pieces (pcs)</option>
                                            <option value="pack" <?php echo ($row['satuan'] == 'pack') ? 'selected' : ''; ?>>Pack</option>
                                            <option value="box" <?php echo ($row['satuan'] == 'box') ? 'selected' : ''; ?>>Box</option>
                                            <option value="buah" <?php echo ($row['satuan'] == 'buah') ? 'selected' : ''; ?>>Buah</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Harga Beli -->
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    Harga Beli <span class="text-danger">*</span>
                                </label>
                                <div class="input-group-modern">
                                    <span class="input-prefix">Rp</span>
                                    <input type="number" 
                                           class="form-control-modern" 
                                           name="harga_beli" 
                                           value="<?php echo $row['harga_beli']; ?>"
                                           min="0"
                                           required>
                                </div>
                                <small class="form-text-modern">Harga beli per satuan</small>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex gap-2 pt-3">
                                <button type="submit" class="btn-primary-modern">
                                    <span class="material-icons me-2" style="font-size: 18px;">save</span>
                                    Update Bahan Baku
                                </button>
                                <a href="index.php" class="btn-outline-modern">
                                    Batal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Sidebar Section -->
            <div class="col-lg-4">
                <!-- Info Card -->
                <div class="card-modern mb-3">
                    <div class="card-modern-header">
                        <span class="material-icons text-warning me-2">info</span>
                        <h5 class="mb-0">Informasi</h5>
                    </div>
                    <div class="card-modern-body">
                        <div class="info-row">
                            <span class="info-label">ID Bahan:</span>
                            <span class="info-value">#<?php echo $row['id']; ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Status Stok:</span>
                            <span class="info-value" style="color: <?php echo $stock_color; ?>; font-weight: 700;">
                                <?php echo $stock_text; ?>
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Total Nilai:</span>
                            <span class="info-value">Rp <?php echo number_format($row['stok'] * $row['harga_beli'], 0, ',', '.'); ?></span>
                        </div>
                    </div>
                </div>

                <!-- Stock Status Card -->
                <div class="card-modern mb-3">
                    <div class="card-modern-header">
                        <span class="material-icons text-warning me-2">inventory_2</span>
                        <h5 class="mb-0">Status Stok</h5>
                    </div>
                    <div class="card-modern-body">
                        <div class="stock-indicator stock-<?php echo $stock_status; ?>">
                            <span class="material-icons">
                                <?php echo ($stock_status == 'critical') ? 'error' : (($stock_status == 'low') ? 'warning' : 'check_circle'); ?>
                            </span>
                            <div class="stock-info">
                                <h6><?php echo $stock_text; ?></h6>
                                <p>Stok saat ini: <strong><?php echo number_format($row['stok'], 2, ',', '.'); ?> <?php echo $row['satuan']; ?></strong></p>
                            </div>
                        </div>
                        <?php if ($stock_status !== 'available'): ?>
                        <div class="alert-warning-box mt-3">
                            <span class="material-icons">info</span>
                            <p>Segera lakukan pemesanan ulang untuk menghindari kehabisan stok!</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Tips Card -->
                <div class="card-modern">
                    <div class="card-modern-header">
                        <span class="material-icons text-warning me-2">lightbulb</span>
                        <h5 class="mb-0">Tips</h5>
                    </div>
                    <div class="card-modern-body">
                        <div class="tip-item">
                            <span class="material-icons text-success">check_circle</span>
                            <span>Perbarui stok secara berkala</span>
                        </div>
                        <div class="tip-item">
                            <span class="material-icons text-success">check_circle</span>
                            <span>Catat harga beli terbaru</span>
                        </div>
                        <div class="tip-item">
                            <span class="material-icons text-success">check_circle</span>
                            <span>Monitor stok minimal</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="<?php echo ASSETS_PATH; ?>css/bahan/edit.css">

<?php include '../../includes/footer.php'; ?>