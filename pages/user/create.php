<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'pemilik') {
    header("Location: ../../index.php");
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
                <h2 class="fw-bold mb-1" style="color: #212529;">Tambah User Baru</h2>
                <p class="text-muted mb-0">Buat akun pengguna baru untuk sistem</p>
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
                        <span class="material-icons text-warning me-2">person_add</span>
                        <h5 class="mb-0">Informasi User</h5>
                    </div>
                    <div class="card-modern-body">
                        <form action="process_add.php" method="POST" id="userForm">
                            
                            <!-- Username -->
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    Username <span class="text-danger">*</span>
                                </label>
                                <div class="input-with-icon">
                                    <span class="material-icons input-icon-left">person</span>
                                    <input type="text" 
                                           class="form-control-modern with-icon-left" 
                                           name="nama" 
                                           placeholder="Masukkan username"
                                           required>
                                </div>
                                <small class="form-text-modern">Username harus unik dan mudah diingat</small>
                            </div>

                            <!-- Password -->
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    Password <span class="text-danger">*</span>
                                </label>
                                <div class="input-with-icon">
                                    <span class="material-icons input-icon-left">lock</span>
                                    <input type="password" 
                                           class="form-control-modern with-icon-left with-icon-right" 
                                           id="password"
                                           name="password" 
                                           placeholder="Masukkan password"
                                           required>
                                    <button type="button" 
                                            class="toggle-password-btn" 
                                            onclick="togglePassword()">
                                        <span class="material-icons" id="toggleIcon">visibility</span>
                                    </button>
                                </div>
                                
                                <!-- Password Strength Indicator -->
                                <div class="password-strength-container" id="strengthContainer" style="display: none;">
                                    <div class="strength-bar">
                                        <div class="strength-fill" id="strengthFill"></div>
                                    </div>
                                    <small class="strength-text" id="strengthText"></small>
                                </div>
                                
                                <small class="form-text-modern">Gunakan kombinasi huruf, angka, dan karakter khusus</small>
                            </div>

                            <!-- Role Selection -->
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    Role <span class="text-danger">*</span>
                                </label>
                                <div class="role-selection-grid">
                                    <label class="role-card-option">
                                        <input type="radio" name="role" value="kasir" checked>
                                        <div class="role-card-modern">
                                            <div class="role-icon-modern role-kasir-icon">
                                                <span class="material-icons">badge</span>
                                            </div>
                                            <div class="role-content">
                                                <h6>Kasir</h6>
                                                <p>Mengelola transaksi penjualan</p>
                                            </div>
                                            <div class="role-check-icon">
                                                <span class="material-icons">check_circle</span>
                                            </div>
                                        </div>
                                    </label>

                                    <label class="role-card-option">
                                        <input type="radio" name="role" value="pemilik">
                                        <div class="role-card-modern">
                                            <div class="role-icon-modern role-pemilik-icon">
                                                <span class="material-icons">admin_panel_settings</span>
                                            </div>
                                            <div class="role-content">
                                                <h6>Pemilik</h6>
                                                <p>Akses penuh ke seluruh sistem</p>
                                            </div>
                                            <div class="role-check-icon">
                                                <span class="material-icons">check_circle</span>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex gap-2 pt-3">
                                <button type="submit" class="btn-primary-modern">
                                    <span class="material-icons me-2" style="font-size: 18px;">check</span>
                                    Simpan User
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
                <!-- Role Comparison -->
                <div class="card-modern mb-3">
                    <div class="card-modern-header">
                        <span class="material-icons text-warning me-2">compare_arrows</span>
                        <h5 class="mb-0">Perbedaan Role</h5>
                    </div>
                    <div class="card-modern-body">
                        <div class="role-comparison-modern">
                            <div class="comparison-section">
                                <div class="comparison-title">
                                    <span class="material-icons">admin_panel_settings</span>
                                    <strong>Pemilik</strong>
                                </div>
                                <ul class="permission-list">
                                    <li><span class="material-icons">check</span> Kelola Menu</li>
                                    <li><span class="material-icons">check</span> Kelola Bahan Baku</li>
                                    <li><span class="material-icons">check</span> Kelola Transaksi</li>
                                    <li><span class="material-icons">check</span> Kelola User</li>
                                    <li><span class="material-icons">check</span> Lihat Dashboard</li>
                                    <li><span class="material-icons">check</span> Chat Assistant</li>
                                </ul>
                            </div>

                            <div class="comparison-divider"></div>

                            <div class="comparison-section">
                                <div class="comparison-title">
                                    <span class="material-icons">badge</span>
                                    <strong>Kasir</strong>
                                </div>
                                <ul class="permission-list">
                                    <li><span class="material-icons">check</span> Kelola Menu</li>
                                    <li><span class="material-icons">check</span> Kelola Bahan Baku</li>
                                    <li><span class="material-icons">check</span> Kelola Transaksi</li>
                                    <li class="disabled"><span class="material-icons">close</span> Kelola User</li>
                                    <li><span class="material-icons">check</span> Lihat Dashboard</li>
                                    <li class="disabled"><span class="material-icons">close</span> Chat Assistant</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Security Tips -->
                <div class="card-modern">
                    <div class="card-modern-header">
                        <span class="material-icons text-warning me-2">security</span>
                        <h5 class="mb-0">Tips Keamanan</h5>
                    </div>
                    <div class="card-modern-body">
                        <div class="security-tip-item">
                            <span class="material-icons">verified_user</span>
                            <span>Gunakan password minimal 8 karakter</span>
                        </div>
                        <div class="security-tip-item">
                            <span class="material-icons">verified_user</span>
                            <span>Kombinasikan huruf besar & kecil</span>
                        </div>
                        <div class="security-tip-item">
                            <span class="material-icons">verified_user</span>
                            <span>Tambahkan angka dan simbol</span>
                        </div>
                        <div class="security-tip-item">
                            <span class="material-icons">verified_user</span>
                            <span>Jangan gunakan info pribadi</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="<?php echo ASSETS_PATH; ?>css/user/create.css">

<script>
    // Toggle Password Visibility
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.textContent = 'visibility_off';
        } else {
            passwordInput.type = 'password';
            toggleIcon.textContent = 'visibility';
        }
    }

    // Password Strength Checker
    document.getElementById('password').addEventListener('input', function(e) {
        const password = e.target.value;
        const container = document.getElementById('strengthContainer');
        const fill = document.getElementById('strengthFill');
        const text = document.getElementById('strengthText');
        
        if (password.length === 0) {
            container.style.display = 'none';
            return;
        }
        
        container.style.display = 'block';
        
        let strength = 0;
        let label = '';
        let color = '';
        
        // Calculate strength
        if (password.length >= 8) strength += 25;
        if (password.match(/[a-z]/)) strength += 25;
        if (password.match(/[A-Z]/)) strength += 25;
        if (password.match(/[0-9]/)) strength += 12.5;
        if (password.match(/[^a-zA-Z0-9]/)) strength += 12.5;
        
        // Set label and color
        if (strength < 40) {
            label = 'Lemah';
            color = '#ef5350';
        } else if (strength < 70) {
            label = 'Sedang';
            color = '#ff9800';
        } else {
            label = 'Kuat';
            color = '#4caf50';
        }
        
        fill.style.width = strength + '%';
        fill.style.backgroundColor = color;
        text.textContent = label;
        text.style.color = color;
    });
</script>

<?php include '../../includes/footer.php'; ?>