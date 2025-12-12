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
                <h2 class="fw-bold mb-1" style="color: #212529;">Tambah Menu Baru</h2>
                <p class="text-muted mb-0">Lengkapi form untuk menambahkan menu baru</p>
            </div>
            <a href="index.php" class="btn btn-back">
                <span class="material-icons me-2" style="font-size: 18px;">arrow_back</span>
                Kembali
            </a>
        </div>

        <div class="row g-4">
            <!-- Form Section -->
            <div class="col-lg-8">
                <div class="card-modern">
                    <div class="card-modern-header">
                        <span class="material-icons text-warning me-2">restaurant_menu</span>
                        <h5 class="mb-0">Informasi Menu</h5>
                    </div>
                    <div class="card-modern-body">
                        <form action="process_add.php" method="POST" enctype="multipart/form-data" id="menuForm">
                            
                            <!-- Nama Menu -->
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    Nama Menu <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control-modern" 
                                       name="nama" 
                                       placeholder="Contoh: Ayam Geprek Original"
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
                                            <option value="">Pilih Kategori</option>
                                            <option value="makanan">
                                                <span class="material-icons">restaurant</span> Makanan
                                            </option>
                                            <option value="minuman">Minuman</option>
                                            <option value="cemilan">Cemilan</option>
                                        </select>
                                        <div class="category-icons mt-2">
                                            <div class="category-icon-item" data-category="makanan">
                                                <span class="material-icons">restaurant</span>
                                                <small>Makanan</small>
                                            </div>
                                            <div class="category-icon-item" data-category="minuman">
                                                <span class="material-icons">local_cafe</span>
                                                <small>Minuman</small>
                                            </div>
                                            <div class="category-icon-item" data-category="cemilan">
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
                                                   placeholder="15000"
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
                                          rows="4"
                                          placeholder="Masukkan deskripsi menu (opsional)"></textarea>
                                <small class="form-text-modern">Jelaskan detail menu, bahan, atau keunikan produk</small>
                            </div>

                            <!-- Upload Gambar -->
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    Gambar Menu <span class="text-danger">*</span>
                                </label>
                                <div class="upload-box" id="uploadBox">
                                    <input type="file" 
                                           id="imageInput" 
                                           name="gambar" 
                                           accept="image/*" 
                                           required
                                           style="display: none;"
                                           onchange="previewImage(event)">
                                    
                                    <div class="upload-content" id="uploadContent">
                                        <span class="material-icons upload-icon">cloud_upload</span>
                                        <h6 class="mt-2 mb-1">Klik untuk upload gambar</h6>
                                        <p class="text-muted small mb-0">PNG, JPG atau JPEG (Max. 2MB)</p>
                                    </div>

                                    <div class="image-preview" id="imagePreview" style="display: none;">
                                        <img id="previewImg" src="" alt="Preview">
                                        <button type="button" class="btn-remove-preview" onclick="removeImage()">
                                            <span class="material-icons">close</span>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    Status <span class="text-danger">*</span>
                                </label>
                                <div class="status-options">
                                    <label class="status-option">
                                        <input type="radio" name="status" value="tersedia" checked>
                                        <div class="status-card status-available">
                                            <span class="material-icons">check_circle</span>
                                            <span>Tersedia</span>
                                        </div>
                                    </label>
                                    <label class="status-option">
                                        <input type="radio" name="status" value="habis">
                                        <div class="status-card status-unavailable">
                                            <span class="material-icons">cancel</span>
                                            <span>Habis</span>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex gap-2 pt-3">
                                <button type="submit" class="btn btn-primary-modern">
                                    <span class="material-icons me-2" style="font-size: 18px;">check</span>
                                    Simpan Menu
                                </button>
                                <a href="index.php" class="btn btn-outline-modern">
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
                                <h6>Nama Menu</h6>
                                <p>Gunakan nama yang jelas dan mudah diingat</p>
                            </div>
                        </div>
                        <div class="guide-item">
                            <div class="guide-icon">
                                <span class="material-icons">image</span>
                            </div>
                            <div class="guide-content">
                                <h6>Gambar Berkualitas</h6>
                                <p>Upload foto dengan pencahayaan yang baik</p>
                            </div>
                        </div>
                        <div class="guide-item">
                            <div class="guide-icon">
                                <span class="material-icons">payments</span>
                            </div>
                            <div class="guide-content">
                                <h6>Harga Kompetitif</h6>
                                <p>Sesuaikan harga dengan target pasar</p>
                            </div>
                        </div>
                        <div class="guide-item mb-0">
                            <div class="guide-icon">
                                <span class="material-icons">update</span>
                            </div>
                            <div class="guide-content">
                                <h6>Update Status</h6>
                                <p>Ubah status jika stok habis</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="<?php echo ASSETS_PATH; ?>css/menu/create.css">

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
            };
            reader.readAsDataURL(file);
        }
    }

    // Remove image
    function removeImage() {
        document.getElementById('imageInput').value = '';
        document.getElementById('uploadContent').style.display = 'block';
        document.getElementById('imagePreview').style.display = 'none';
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