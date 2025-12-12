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

<?php include '../../includes/sidebar.php'; ?>

<link rel="stylesheet" href="<?php echo ASSETS_PATH; ?>css/menu/index.css">

<div class="main-content">
    <?php include '../../includes/navbar.php'; ?>
    
    <div class="container-fluid p-3 p-md-4">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-3">
            <div>
                <h2 class="fw-bold mb-1" style="color: #212529;">Manajemen Menu</h2>
                <p class="text-muted mb-0">Kelola daftar menu makanan dan minuman</p>
            </div>
            <a href="create.php" class="btn btn-primary-custom">
                <i class="bi bi-plus-lg me-2"></i>Tambah Menu
            </a>
        </div>

        <div class="content-card">
            <div class="table-responsive">
                <table class="table custom-table">
                    <thead>
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th style="width: 80px;">Gambar</th>
                            <th>Nama Menu</th>
                            <th style="width: 120px;">Kategori</th>
                            <th style="width: 150px;">Harga</th>
                            <th style="width: 100px;">Status</th>
                            <th style="width: 200px;" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $result = $conn->query("SELECT * FROM menu ORDER BY id DESC");
                        $no = 1;
                        if ($result->num_rows > 0):
                            while ($row = $result->fetch_assoc()):
                        ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td>
                                <?php if ($row['gambar']): ?>
                                    <img src="../../assets/uploads/<?php echo htmlspecialchars($row['gambar']); ?>"
                                        alt="<?php echo htmlspecialchars($row['nama']); ?>" 
                                        class="menu-image">
                                <?php else: ?>
                                    <div class="no-image">
                                        <i class="bi bi-image"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="fw-medium"><?php echo htmlspecialchars($row['nama']); ?></span>
                                <?php if ($row['deskripsi']): ?>
                                    <br><small class="text-muted"><?php echo substr(htmlspecialchars($row['deskripsi']), 0, 200); ?>...</small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge-category <?php echo $row['kategori']; ?>">
                                    <?php echo ucfirst(htmlspecialchars($row['kategori'])); ?>
                                </span>
                            </td>
                            <td>
                                <span class="fw-semibold">Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></span>
                            </td>
                            <td>
                                <?php if ($row['status'] == 'tersedia'): ?>
                                    <span class="badge-status available">
                                        <i class="bi bi-check-circle me-1"></i>Tersedia
                                    </span>
                                <?php else: ?>
                                    <span class="badge-status unavailable">
                                        <i class="bi bi-x-circle me-1"></i>Habis
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="recipe.php?id=<?php echo $row['id']; ?>" 
                                       class="btn-action btn-recipe" 
                                       title="Recipe">
                                        <i class="bi bi-list-check"></i>
                                    </a>
                                    <a href="edit.php?id=<?php echo $row['id']; ?>" 
                                       class="btn-action btn-edit" 
                                       title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="delete.php?id=<?php echo $row['id']; ?>" 
                                       class="btn-action btn-delete" 
                                       onclick="return confirm('Apakah Anda yakin ingin menghapus menu ini?')"
                                       title="Delete">
                                        <i class="bi bi-trash"></i>
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
                                <i class="bi bi-inbox" style="font-size: 3rem; color: #dee2e6;"></i>
                                <p class="text-muted mt-3 mb-0">Belum ada menu tersedia</p>
                                <a href="create.php" class="btn btn-sm btn-primary-custom mt-3">
                                    <i class="bi bi-plus-lg me-2"></i>Tambah Menu Pertama
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

<?php include '../../includes/footer.php'; ?>