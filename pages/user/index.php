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
                <h2 class="fw-bold mb-1" style="color: #212529;">Manajemen User</h2>
                <p class="text-muted mb-0">Kelola akses pengguna sistem</p>
            </div>
            <a href="create.php" class="btn-primary-modern">
                <span class="material-icons me-2" style="font-size: 18px;">person_add</span>
                Tambah User
            </a>
        </div>

        <!-- Stats Cards -->
        <div class="row g-3 mb-4">
            <?php
            $total_users = $conn->query("SELECT COUNT(*) as count FROM user")->fetch_assoc()['count'];
            $total_pemilik = $conn->query("SELECT COUNT(*) as count FROM user WHERE role='pemilik'")->fetch_assoc()['count'];
            $total_kasir = $conn->query("SELECT COUNT(*) as count FROM user WHERE role='kasir'")->fetch_assoc()['count'];
            ?>
            <div class="col-md-6 col-xl-4">
                <div class="stat-card stat-card-yellow">
                    <div class="stat-icon">
                        <span class="material-icons">group</span>
                    </div>
                    <div class="stat-content">
                        <p class="stat-label">Total User</p>
                        <h3 class="stat-value"><?php echo $total_users; ?></h3>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-4">
                <div class="stat-card stat-card-blue">
                    <div class="stat-icon">
                        <span class="material-icons">admin_panel_settings</span>
                    </div>
                    <div class="stat-content">
                        <p class="stat-label">Pemilik</p>
                        <h3 class="stat-value"><?php echo $total_pemilik; ?></h3>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-4">
                <div class="stat-card stat-card-purple">
                    <div class="stat-icon">
                        <span class="material-icons">badge</span>
                    </div>
                    <div class="stat-content">
                        <p class="stat-label">Kasir</p>
                        <h3 class="stat-value"><?php echo $total_kasir; ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Table -->
        <div class="card-modern">
            <div class="card-modern-header">
                <span class="material-icons text-warning me-2">people</span>
                <h5 class="mb-0">Daftar User</h5>
            </div>
            <div class="card-modern-body p-0">
                <div class="table-responsive">
                    <table class="table-modern">
                        <thead>
                            <tr>
                                <th style="width: 60px;">No</th>
                                <th>User Info</th>
                                <th style="width: 150px;">Role</th>
                                <th style="width: 150px;" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $result = $conn->query("SELECT * FROM user ORDER BY id DESC");
                            $no = 1;
                            if ($result->num_rows > 0):
                                while ($row = $result->fetch_assoc()):
                            ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td>
                                    <div class="user-info-row">
                                        <div class="user-avatar">
                                            <span class="material-icons">person</span>
                                        </div>
                                        <div>
                                            <div class="user-name"><?php echo htmlspecialchars($row['nama']); ?></div>
                                            <small class="user-id">ID: #<?php echo $row['id']; ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <?php if ($row['role'] == 'pemilik'): ?>
                                        <span class="role-badge role-pemilik">
                                            <span class="material-icons">admin_panel_settings</span>
                                            Pemilik
                                        </span>
                                    <?php else: ?>
                                        <span class="role-badge role-kasir">
                                            <span class="material-icons">badge</span>
                                            Kasir
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="edit.php?id=<?php echo $row['id']; ?>" 
                                           class="btn-action btn-action-edit" 
                                           title="Edit">
                                            <span class="material-icons">edit</span>
                                        </a>
                                        <?php if ($row['id'] != $_SESSION['user_id']): ?>
                                        <a href="delete.php?id=<?php echo $row['id']; ?>" 
                                           class="btn-action btn-action-delete" 
                                           onclick="return confirm('Yakin ingin menghapus user ini?')"
                                           title="Hapus">
                                            <span class="material-icons">delete</span>
                                        </a>
                                        <?php else: ?>
                                        <button class="btn-action btn-action-delete" 
                                                disabled
                                                title="Tidak dapat menghapus diri sendiri">
                                            <span class="material-icons">delete</span>
                                        </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php 
                                endwhile;
                            else:
                            ?>
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <span class="material-icons empty-icon">group</span>
                                    <p class="text-muted mt-3 mb-0">Belum ada user terdaftar</p>
                                    <a href="create.php" class="btn btn-sm btn-primary-modern mt-3">
                                        <span class="material-icons me-2" style="font-size: 16px;">add</span>
                                        Tambah User Pertama
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

<link rel="stylesheet" href="<?php echo ASSETS_PATH; ?>css/user/index.css">

<?php include '../../includes/footer.php'; ?>