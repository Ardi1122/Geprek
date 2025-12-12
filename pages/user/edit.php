<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'pemilik') {
    header("Location: ../../index.php");
    exit();
}
require_once '../../config/database.php';
$path_to_root = "../..";

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM user WHERE id = ?");
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
                <h2 class="fw-bold mb-1" style="color: #212529;">Edit User</h2>
                <p class="text-muted mb-0">Perbarui informasi user <?php echo htmlspecialchars($row['nama']); ?></p>
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
                        <h5 class="mb-0">Edit Informasi User</h5>
                    </div>
                    <div class="card-modern-body">
                        <form action="process_edit.php" method="POST">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

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
                                           value="<?php echo htmlspecialchars($row['nama']); ?>"
                                           required>
                                </div>
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
                                           value="<?php echo htmlspecialchars($row['password']); ?>"
                                           required>
                                    <button type="button" 
                                            class="toggle-password-btn" 
                                            onclick="togglePassword()">
                                        <span class="material-icons" id="toggleIcon">visibility</span>
                                    </button>
                                </div>
                                
                                <!-- Warning Alert -->
                                <div class="alert-warning-box mt-3">
                                    <span class="material-icons">warning</span>
                                    <div>
                                        <strong>Perhatian:</strong>
                                        <p class="mb-0">Password saat ini tersimpan dalam plain text. Disarankan untuk menggunakan enkripsi password di production.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Role Selection -->
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    Role <span class="text-danger">*</span>
                                </label>
                                <div class="role-selection-grid">
                                    <label class="role-card-option">
                                        <input type="radio" name="role" value="kasir" <?php echo ($row['role'] == 'kasir') ? 'checked' : ''; ?>>
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
                                        <input type="radio" name="role" value="pemilik" <?php echo ($row['role'] == 'pemilik') ? 'checked' : ''; ?>>
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
                                    <span class="material-icons me-2" style="font-size: 18px;">save</span>
                                    Update User
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
                <!-- User Info Card -->
                <div class="card-modern mb-3">
                    <div class="card-modern-header">
                        <span class="material-icons text-warning me-2">info</span>
                        <h5 class="mb-0">Informasi User</h5>
                    </div>
                    <div class="card-modern-body">
                        <div class="user-profile-card">
                            <div class="user-avatar-large">
                                <span class="material-icons">person</span>
                            </div>
                            <div class="user-profile-info">
                                <h6><?php echo htmlspecialchars($row['nama']); ?></h6>
                                <span class="role-badge-modern role-<?php echo $row['role']; ?>">
                                    <?php if ($row['role'] == 'pemilik'): ?>
                                        <span class="material-icons">admin_panel_settings</span>
                                        Pemilik
                                    <?php else: ?>
                                        <span class="material-icons">badge</span>
                                        Kasir
                                    <?php endif; ?>
                                </span>
                            </div>
                        </div>

                        <div class="info-divider"></div>

                        <div class="info-item-detail">
                            <div class="info-item-icon">
                                <span class="material-icons">tag</span>
                            </div>
                            <div class="info-item-content">
                                <label>ID User</label>
                                <p>#<?php echo $row['id']; ?></p>
                            </div>
                        </div>

                        <div class="info-item-detail">
                            <div class="info-item-icon">
                                <span class="material-icons">calendar_today</span>
                            </div>
                            <div class="info-item-content">
                                <label>Bergabung</label>
                                <p><?php echo isset($row['created_at']) ? date('d M Y', strtotime($row['created_at'])) : 'N/A'; ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Security Note -->
                <div class="card-modern mb-3">
                    <div class="card-modern-header">
                        <span class="material-icons text-warning me-2">security</span>
                        <h5 class="mb-0">Keamanan</h5>
                    </div>
                    <div class="card-modern-body">
                        <div class="security-note-box">
                            <span class="material-icons">info</span>
                            <div>
                                <strong>Catatan Penting:</strong>
                                <p class="mb-0">Pastikan password yang digunakan aman dan tidak mudah ditebak. Jangan bagikan password kepada siapapun.</p>
                            </div>
                        </div>

                        <?php if ($row['id'] == $_SESSION['user_id']): ?>
                        <div class="alert-info-box mt-3">
                            <span class="material-icons">person_check</span>
                            <span>Anda sedang mengedit akun Anda sendiri</span>
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
                        <div class="tip-item-modern">
                            <span class="material-icons">check_circle</span>
                            <span>Update password secara berkala</span>
                        </div>
                        <div class="tip-item-modern">
                            <span class="material-icons">check_circle</span>
                            <span>Verifikasi role sebelum menyimpan</span>
                        </div>
                        <div class="tip-item-modern">
                            <span class="material-icons">check_circle</span>
                            <span>Gunakan password yang kuat</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="<?php echo ASSETS_PATH; ?>css/user/edit.css">

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
</script>

<?php include '../../includes/footer.php'; ?>