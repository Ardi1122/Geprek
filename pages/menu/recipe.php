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

$menu_id = $_GET['id'];

// Get Menu Info
$stmt = $conn->prepare("SELECT * FROM menu WHERE id = ?");
$stmt->bind_param("i", $menu_id);
$stmt->execute();
$menu = $stmt->get_result()->fetch_assoc();

if (!$menu) {
    header("Location: index.php");
    exit();
}

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $bahan_id = $_POST['bahan_id'];
    $jumlah_pakai = $_POST['jumlah_pakai'];

    $stmt = $conn->prepare("INSERT INTO menu_bahan (menu_id, bahan_id, jumlah_pakai, satuan) VALUES (?, ?, ?, 1)");
    $stmt->bind_param("iid", $menu_id, $bahan_id, $jumlah_pakai);
    if ($stmt->execute()) {
        $success = "Bahan berhasil ditambahkan!";
    } else {
        $error = "Error: " . $stmt->error;
    }
}

// Handle Delete
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $stmt = $conn->prepare("DELETE FROM menu_bahan WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    header("Location: recipe.php?id=" . $menu_id);
    exit();
}

// Get Existing Recipe
$query = "SELECT mb.*, bb.nama as bahan_nama, bb.satuan as bahan_satuan, bb.stok as bahan_stok
          FROM menu_bahan mb 
          JOIN bahan_baku bb ON mb.bahan_id = bb.id 
          WHERE mb.menu_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $menu_id);
$stmt->execute();
$recipe = $stmt->get_result();

// Get All Ingredients for Dropdown
$ingredients = $conn->query("SELECT * FROM bahan_baku ORDER BY nama ASC");
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
                <h2 class="fw-bold mb-1" style="color: #212529;">Resep Menu</h2>
                <p class="text-muted mb-0">Kelola bahan untuk: <strong><?php echo htmlspecialchars($menu['nama']); ?></strong></p>
            </div>
            <a href="index.php" class="btn-back">
                <span class="material-icons me-2" style="font-size: 18px;">arrow_back</span>
                Kembali
            </a>
        </div>

        <?php if (isset($success)): ?>
        <div class="alert alert-success alert-modern">
            <span class="material-icons">check_circle</span>
            <span><?php echo $success; ?></span>
        </div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
        <div class="alert alert-danger alert-modern">
            <span class="material-icons">error</span>
            <span><?php echo $error; ?></span>
        </div>
        <?php endif; ?>

        <div class="row g-4">
            <!-- Menu Info Card -->
            <div class="col-12">
                <div class="card-modern menu-info-card">
                    <div class="menu-info-content">
                        <?php if ($menu['gambar']): ?>
                        <div class="menu-info-image">
                            <img src="../../assets/uploads/<?php echo htmlspecialchars($menu['gambar']); ?>" 
                                 alt="<?php echo htmlspecialchars($menu['nama']); ?>">
                        </div>
                        <?php endif; ?>
                        <div class="menu-info-details">
                            <h4 class="mb-2"><?php echo htmlspecialchars($menu['nama']); ?></h4>
                            <div class="menu-info-meta">
                                <span class="badge-category-modern <?php echo $menu['kategori']; ?>">
                                    <?php 
                                    $icons = [
                                        'makanan' => 'restaurant',
                                        'minuman' => 'local_cafe',
                                        'cemilan' => 'cookie'
                                    ];
                                    ?>
                                    <span class="material-icons"><?php echo $icons[$menu['kategori']]; ?></span>
                                    <?php echo ucfirst($menu['kategori']); ?>
                                </span>
                                <span class="menu-price">Rp <?php echo number_format($menu['harga'], 0, ',', '.'); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add Ingredient Form -->
            <div class="col-lg-4">
                <div class="card-modern sticky-top" style="top: 20px;">
                    <div class="card-modern-header">
                        <span class="material-icons text-warning me-2">add_circle</span>
                        <h5 class="mb-0">Tambah Bahan</h5>
                    </div>
                    <div class="card-modern-body">
                        <form method="POST">
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    Pilih Bahan <span class="text-danger">*</span>
                                </label>
                                <select name="bahan_id" class="form-select-modern" required>
                                    <option value="">-- Pilih Bahan --</option>
                                    <?php while ($ing = $ingredients->fetch_assoc()): ?>
                                        <option value="<?php echo $ing['id']; ?>">
                                            <?php echo htmlspecialchars($ing['nama']); ?> 
                                            (Stok: <?php echo $ing['stok']; ?> <?php echo $ing['satuan']; ?>)
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    Jumlah Digunakan <span class="text-danger">*</span>
                                </label>
                                <input type="number" 
                                       step="0.01" 
                                       name="jumlah_pakai" 
                                       class="form-control-modern" 
                                       placeholder="Contoh: 0.25"
                                       required>
                                <small class="form-text-modern">Masukkan jumlah bahan yang dibutuhkan</small>
                            </div>

                            <button type="submit" class="btn-primary-modern w-100">
                                <span class="material-icons me-2" style="font-size: 18px;">add</span>
                                Tambah ke Resep
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Recipe List -->
            <div class="col-lg-8">
                <div class="card-modern">
                    <div class="card-modern-header">
                        <span class="material-icons text-warning me-2">list_alt</span>
                        <h5 class="mb-0">Daftar Bahan Resep</h5>
                    </div>
                    <div class="card-modern-body p-0">
                        <div class="table-responsive">
                            <table class="table-modern">
                                <thead>
                                    <tr>
                                        <th style="width: 50px;">No</th>
                                        <th>Nama Bahan</th>
                                        <th style="width: 150px;">Jumlah Digunakan</th>
                                        <th style="width: 100px;">Satuan</th>
                                        <th style="width: 120px;">Stok Tersedia</th>
                                        <th style="width: 100px;" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $no = 1;
                                    if ($recipe->num_rows > 0):
                                        while ($row = $recipe->fetch_assoc()): 
                                    ?>
                                    <tr>
                                        <td><?php echo $no++; ?></td>
                                        <td>
                                            <span class="fw-semibold"><?php echo htmlspecialchars($row['bahan_nama']); ?></span>
                                        </td>
                                        <td>
                                            <span class="badge-qty"><?php echo $row['jumlah_pakai']; ?></span>
                                        </td>
                                        <td><?php echo htmlspecialchars($row['bahan_satuan']); ?></td>
                                        <td>
                                            <?php if ($row['bahan_stok'] < $row['jumlah_pakai']): ?>
                                                <span class="stock-badge low-stock">
                                                    <span class="material-icons">warning</span>
                                                    <?php echo $row['bahan_stok']; ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="stock-badge available-stock">
                                                    <span class="material-icons">check</span>
                                                    <?php echo $row['bahan_stok']; ?>
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <a href="recipe.php?id=<?php echo $menu_id; ?>&delete_id=<?php echo $row['id']; ?>"
                                               class="btn-action-delete"
                                               onclick="return confirm('Hapus bahan ini dari resep?')">
                                                <span class="material-icons">delete</span>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php 
                                        endwhile;
                                    else:
                                    ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <span class="material-icons empty-icon">inventory_2</span>
                                            <p class="text-muted mt-3 mb-0">Belum ada bahan ditambahkan</p>
                                            <p class="text-muted small">Gunakan form di samping untuk menambahkan bahan</p>
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
    </div>
</div>
<link rel="stylesheet" href="<?php echo ASSETS_PATH; ?>css/menu/recipe.css">

<?php include '../../includes/footer.php'; ?>