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
                <h2 class="fw-bold mb-1" style="color: #212529;">Tambah Bahan Baku</h2>
                <p class="text-muted mb-0">Lengkapi form untuk menambahkan bahan baku baru</p>
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
                        <span class="material-icons text-warning me-2">inventory</span>
                        <h5 class="mb-0">Informasi Bahan Baku</h5>
                    </div>
                    <div class="card-modern-body">
                        <form action="process_add.php" method="POST" id="ingredientForm">
                            
                            <!-- Nama Bahan -->
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    Nama Bahan <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control-modern" 
                                       name="nama" 
                                       placeholder="Contoh: Tepung Terigu"
                                       required>
                            </div>

                            <div class="row g-3">
                                <!-- Stok -->
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label class="form-label-modern">
                                            Stok Awal <span class="text-danger">*</span>
                                        </label>
                                        <input type="number" 
                                               step="0.01" 
                                               class="form-control-modern" 
                                               name="stok" 
                                               placeholder="0.00"
                                               required>
                                        <small class="form-text-modern">Masukkan jumlah stok awal</small>
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
                                            <option value="kg">Kilogram (kg)</option>
                                            <option value="gram">Gram (g)</option>
                                            <option value="liter">Liter (L)</option>
                                            <option value="ml">Mililiter (ml)</option>
                                            <option value="pcs">Pieces (pcs)</option>
                                            <option value="pack">Pack</option>
                                            <option value="box">Box</option>
                                            <option value="buah">Buah</option>
                                        </select>
                                        <small class="form-text-modern">Pilih satuan yang sesuai</small>
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
                                           placeholder="0"
                                           min="0"
                                           required>
                                </div>
                                <small class="form-text-modern">Harga beli per satuan</small>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex gap-2 pt-3">
                                <button type="submit" class="btn-primary-modern">
                                    <span class="material-icons me-2" style="font-size: 18px;">check</span>
                                    Simpan Bahan Baku
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
                <div class="card-modern sticky-top" style="top: 20px;">
                    <div class="card-modern-header">
                        <span class="material-icons text-warning me-2">info</span>
                        <h5 class="mb-0">Panduan</h5>
                    </div>
                    <div class="card-modern-body">
                        <div class="guide-item">
                            <div class="guide-icon">
                                <span class="material-icons">edit</span>
                            </div>
                            <div class="guide-content">
                                <h6>Nama Bahan</h6>
                                <p>Gunakan nama yang spesifik dan mudah dikenali</p>
                            </div>
                        </div>
                        <div class="guide-item">
                            <div class="guide-icon">
                                <span class="material-icons">straighten</span>
                            </div>
                            <div class="guide-content">
                                <h6>Satuan yang Tepat</h6>
                                <p>Pilih satuan yang sesuai dengan penggunaan bahan</p>
                            </div>
                        </div>
                        <div class="guide-item">
                            <div class="guide-icon">
                                <span class="material-icons">inventory_2</span>
                            </div>
                            <div class="guide-content">
                                <h6>Stok Awal</h6>
                                <p>Masukkan jumlah stok yang ada saat ini</p>
                            </div>
                        </div>
                        <div class="guide-item mb-0">
                            <div class="guide-icon">
                                <span class="material-icons">payments</span>
                            </div>
                            <div class="guide-content">
                                <h6>Harga Beli</h6>
                                <p>Catat harga beli untuk kalkulasi biaya</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Info -->
                <div class="card-modern mt-3">
                    <div class="card-modern-header">
                        <span class="material-icons text-warning me-2">lightbulb</span>
                        <h5 class="mb-0">Tips</h5>
                    </div>
                    <div class="card-modern-body">
                        <div class="tip-item">
                            <span class="material-icons text-success">check_circle</span>
                            <span>Update stok secara berkala</span>
                        </div>
                        <div class="tip-item">
                            <span class="material-icons text-success">check_circle</span>
                            <span>Catat harga beli terbaru</span>
                        </div>
                        <div class="tip-item">
                            <span class="material-icons text-success">check_circle</span>
                            <span>Monitor stok yang menipis</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="<?php echo ASSETS_PATH; ?>css/bahan/create.css">

<?php include '../../includes/footer.php'; ?>