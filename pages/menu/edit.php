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
$stmt = $conn->prepare("SELECT * FROM menu WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    header("Location: index.php");
    exit();
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
                <h2 class="fw-bold mb-1" style="color: #212529;">Edit Menu</h2>
                <p class="text-muted mb-0">Perbarui informasi menu <?php echo htmlspecialchars($row['nama']); ?></p>
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
                        <h5 class="mb-0">Edit Informasi Menu</h5>
                    </div>
                    <div class="card-modern-body">
                        <form action="process_edit.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <input type="hidden" name="old_gambar" value="<?php echo $row['gambar']; ?>">

                            <!-- Nama Menu -->
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    Nama Menu <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control-modern" 
                                       name="nama" 
                                       value="<?php echo htmlspecialchars($row['nama']); ?>"
                                       required>
                            </div>

                            <div class="row g-3">
                                <!-- Kategori -->
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label class="form-label-modern">
                                            Kategori <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select-modern" name="kategori" required>
                                            <option value="makanan" <?php echo ($row['kategori'] == 'makanan') ? 'selected' : ''; ?>>Makanan</option>
                                            <option value="minuman" <?php echo ($row['kategori'] == 'minuman') ? 'selected' : ''; ?>>Minuman</option>
                                            <option value="cemilan" <?php echo ($row['kategori'] == 'cemilan') ? 'selected' : ''; ?>>Cemilan</option>
                                        </select>
                                        <div class="category-icons mt-2">
                                            <div class="category-icon-item <?php echo ($row['kategori'] == 'makanan') ? 'active' : ''; ?>" data-category="makanan">
                                                <span class="material-icons">restaurant</span>
                                                <small>Makanan</small>
                                            </div>
                                            <div class="category-icon-item <?php echo ($row['kategori'] == 'minuman') ? 'active' : ''; ?>" data-category="minuman">
                                                <span class="material-icons">local_cafe</span>
                                                <small>Minuman</small>
                                            </div>
                                            <div class="category-icon-item <?php echo ($row['kategori'] == 'cemilan') ? 'active' : ''; ?>" data-category="cemilan">
                                                <span class="material-icons">cookie</span>
                                                <small>Cemilan</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Harga -->
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label class="form-label-modern">
                                            Harga <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group-modern">
                                            <span class="input-prefix">Rp</span>
                                            <input type="number" 
                                                   class="form-control-modern" 
                                                   name="harga" 
                                                   value="<?php echo $row['harga']; ?>"
                                                   min="0"
                                                   required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Deskripsi -->
                            <div class="form-group-modern">
                                <label class="form-label-modern">Deskripsi</label>
                                <textarea class="form-control-modern" 
                                          name="deskripsi" 
                                          rows="4"><?php echo htmlspecialchars($row['deskripsi']); ?></textarea>
                                <small class="form-text-modern">Jelaskan detail menu, bahan, atau keunikan produk</small>
                            </div>

                            <!-- Current & Upload Gambar -->
                            <div class="form-group-modern">
                                <label class="form-label-modern">Gambar Menu</label>
                                
                                <?php if ($row['gambar']): ?>
                                <div class="current-image-box">
                                    <p class="small text-muted mb-2 fw-semibold">Gambar saat ini:</p>
                                    <div class="current-image-wrapper">
                                        <img src="../../assets/uploads/<?php echo htmlspecialchars($row['gambar']); ?>" 
                                             alt="Current" 
                                             id="currentImg">
                                    </div>
                                </div>
                                <?php endif; ?>

                                <div class="upload-box mt-3" id="uploadBox">
                                    <input type="file" 
                                           id="imageInput" 
                                           name="gambar" 
                                           accept="image/*"
                                           style="display: none;"
                                           onchange="previewImage(event)">
                                    
                                    <div class="upload-content" id="uploadContent">
                                        <span class="material-icons upload-icon">cloud_upload</span>
                                        <h6 class="mt-2 mb-1">Klik untuk upload gambar baru</h6>
                                        <p class="text-muted small mb-0">PNG, JPG atau JPEG (Max. 2MB)</p>
                                    </div>

                                    <div class="image-preview" id="imagePreview" style="display: none;">
                                        <img id="previewImg" src="" alt="Preview">
                                        <button type="button" class="btn-remove-preview" onclick="removeImage()">
                                            <span class="material-icons">close</span>
                                        </button>
                                    </div>
                                </div>
                                <small class="form-text-modern">Kosongkan jika tidak ingin mengubah gambar</small>
                            </div>

                            <!-- Status -->
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    Status <span class="text-danger">*</span>
                                </label>
                                <div class="status-options">
                                    <label class="status-option">
                                        <input type="radio" name="status" value="tersedia" <?php echo ($row['status'] == 'tersedia') ? 'checked' : ''; ?>>
                                        <div class="status-card status-available">
                                            <span class="material-icons">check_circle</span>
                                            <span>Tersedia</span>
                                        </div>
                                    </label>
                                    <label class="status-option">
                                        <input type="radio" name="status" value="habis" <?php echo ($row['status'] == 'habis') ? 'checked' : ''; ?>>
                                        <div class="status-card status-unavailable">
                                            <span class="material-icons">cancel</span>
                                            <span>Habis</span>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex gap-2 pt-3">
                                <button type="submit" class="btn-primary-modern">
                                    <span class="material-icons me-2" style="font-size: 18px;">check</span>
                                    Update Menu
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
                            <span class="info-label">ID Menu:</span>
                            <span class="info-value">#<?php echo $row['id']; ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Dibuat:</span>
                            <span class="info-value"><?php echo date('d M Y', strtotime($row['created_at'] ?? 'now')); ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Terakhir Update:</span>
                            <span class="info-value"><?php echo date('d M Y', strtotime($row['updated_at'] ?? 'now')); ?></span>
                        </div>
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
                            <span>Pastikan gambar jelas dan menarik</span>
                        </div>
                        <div class="tip-item">
                            <span class="material-icons text-success">check_circle</span>
                            <span>Update harga secara berkala</span>
                        </div>
                        <div class="tip-item">
                            <span class="material-icons text-success">check_circle</span>
                            <span>Ubah status jika stok habis</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<link rel="stylesheet" href="<?php echo ASSETS_PATH; ?>css/menu/edit.css">
<script>
    // Upload box click handler
    document.getElementById('uploadBox').addEventListener('click', function(e) {
        if (e.target.closest('.btn-remove-preview')) return;
        document.getElementById('imageInput').click();
    });

    // Preview image
    function previewImage(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImg').src = e.target.result;
                document.getElementById('uploadContent').style.display = 'none';
                document.getElementById('imagePreview').style.display = 'block';
                
                // Dim current image
                const currentImg = document.getElementById('currentImg');
                if (currentImg) {
                    currentImg.style.opacity = '0.5';
                }
            };
            reader.readAsDataURL(file);
        }
    }

    // Remove image
    function removeImage() {
        document.getElementById('imageInput').value = '';
        document.getElementById('uploadContent').style.display = 'block';
        document.getElementById('imagePreview').style.display = 'none';
        
        // Restore current image
        const currentImg = document.getElementById('currentImg');
        if (currentImg) {
            currentImg.style.opacity = '1';
        }
    }

    // Category icon selection
    document.querySelectorAll('.category-icon-item').forEach(item => {
        item.addEventListener('click', function() {
            document.querySelectorAll('.category-icon-item').forEach(i => i.classList.remove('active'));
            this.classList.add('active');
            
            const category = this.dataset.category;
            document.querySelector('select[name="kategori"]').value = category;
        });
    });

    // Sync select with icon selection
    document.querySelector('select[name="kategori"]').addEventListener('change', function() {
        document.querySelectorAll('.category-icon-item').forEach(item => {
            item.classList.remove('active');
            if (item.dataset.category === this.value) {
                item.classList.add('active');
            }
        });
    });
</script>

<?php include '../../includes/footer.php'; ?>