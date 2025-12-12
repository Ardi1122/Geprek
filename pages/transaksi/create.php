<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../auth/login.php");
    exit();
}
require_once '../../config/database.php';
$path_to_root = "../..";

// Fetch Menus for the dropdown
$menus = [];
$result = $conn->query("SELECT * FROM menu WHERE status = 'tersedia'");
while ($row = $result->fetch_assoc()) {
    $menus[] = $row;
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
                <h2 class="fw-bold mb-1" style="color: #212529;">Transaksi Baru</h2>
                <p class="text-muted mb-0">Buat transaksi penjualan baru</p>
            </div>
            <a href="index.php" class="btn-back">
                <span class="material-icons me-2" style="font-size: 18px;">arrow_back</span>
                Kembali
            </a>
        </div>

        <form action="process_add.php" method="POST" id="transaksiForm">
            <div class="row g-4">
                <!-- Items Section -->
                <div class="col-lg-8">
                    <div class="card-modern">
                        <div class="card-modern-header">
                            <span class="material-icons text-warning me-2">shopping_cart</span>
                            <h5 class="mb-0">Daftar Item</h5>
                        </div>
                        <div class="card-modern-body p-0">
                            <div class="table-responsive">
                                <table class="table-items" id="itemsTable">
                                    <thead>
                                        <tr>
                                            <th>Menu</th>
                                            <th width="120">Harga</th>
                                            <th width="100">Qty</th>
                                            <th width="150">Subtotal</th>
                                            <th width="60">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <select class="form-select-modern menu-select" name="items[0][menu_id]" required
                                                    onchange="updatePrice(this)">
                                                    <option value="">-- Pilih Menu --</option>
                                                    <?php foreach ($menus as $menu): ?>
                                                        <option value="<?php echo $menu['id']; ?>"
                                                            data-price="<?php echo $menu['harga']; ?>">
                                                            <?php echo htmlspecialchars($menu['nama']); ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control-modern price-input"
                                                    name="items[0][harga_satuan]" readonly>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control-modern qty-input" name="items[0][jumlah]"
                                                    value="1" min="1" required onchange="updateSubtotal(this)"
                                                    onkeyup="updateSubtotal(this)">
                                            </td>
                                            <td>
                                                <input type="number" class="form-control-modern subtotal-input"
                                                    name="items[0][subtotal]" readonly>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn-remove" title="Hapus">
                                                    <span class="material-icons">close</span>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="p-3 border-top">
                                <button type="button" class="btn-add-item" id="addRow">
                                    <span class="material-icons me-2">add</span>
                                    Tambah Item
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Section -->
                <div class="col-lg-4">
                    <div class="card-modern sticky-top" style="top: 20px;">
                        <div class="card-modern-header">
                            <span class="material-icons text-warning me-2">payments</span>
                            <h5 class="mb-0">Pembayaran</h5>
                        </div>
                        <div class="card-modern-body">
                            <!-- Total Amount -->
                            <div class="payment-item total-item">
                                <label>Total Bayar</label>
                                <div class="payment-value total-value">
                                    <span class="currency">Rp</span>
                                    <input type="number" class="form-control-total" id="total_bayar"
                                        name="total_bayar" value="0" readonly>
                                </div>
                            </div>

                            <!-- Payment Amount -->
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    Uang Bayar <span class="text-danger">*</span>
                                </label>
                                <div class="input-group-modern">
                                    <span class="input-prefix">Rp</span>
                                    <input type="number" class="form-control-modern" id="uang_bayar" 
                                           name="uang_bayar" required
                                           placeholder="0"
                                           onkeyup="updateChange()">
                                </div>
                            </div>

                            <!-- Change -->
                            <div class="payment-item change-item">
                                <label>Kembalian</label>
                                <div class="payment-value change-value">
                                    <span class="currency">Rp</span>
                                    <input type="number" class="form-control-change" id="kembalian" 
                                           name="kembalian" value="0" readonly>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn-primary-modern w-100 mt-3">
                                <span class="material-icons me-2" style="font-size: 18px;">check_circle</span>
                                Simpan Transaksi
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<link rel="stylesheet" href="<?php echo ASSETS_PATH; ?>css/transaksi/create.css">

<script>
    let rowCount = 1;
    const menus = <?php echo json_encode($menus); ?>;

    document.getElementById('addRow').addEventListener('click', function () {
        const tableBody = document.querySelector('#itemsTable tbody');
        const newRow = document.createElement('tr');

        let menuOptions = '<option value="">-- Pilih Menu --</option>';
        menus.forEach(menu => {
            menuOptions += `<option value="${menu.id}" data-price="${menu.harga}">${menu.nama}</option>`;
        });

        newRow.innerHTML = `
            <td>
                <select class="form-select-modern menu-select" name="items[${rowCount}][menu_id]" required onchange="updatePrice(this)">
                    ${menuOptions}
                </select>
            </td>
            <td>
                <input type="number" class="form-control-modern price-input" name="items[${rowCount}][harga_satuan]" readonly>
            </td>
            <td>
                <input type="number" class="form-control-modern qty-input" name="items[${rowCount}][jumlah]" value="1" min="1" required onchange="updateSubtotal(this)" onkeyup="updateSubtotal(this)">
            </td>
            <td>
                <input type="number" class="form-control-modern subtotal-input" name="items[${rowCount}][subtotal]" readonly>
            </td>
            <td class="text-center">
                <button type="button" class="btn-remove" title="Hapus">
                    <span class="material-icons">close</span>
                </button>
            </td>
        `;

        tableBody.appendChild(newRow);
        rowCount++;
    });

    document.querySelector('#itemsTable').addEventListener('click', function (e) {
        if (e.target.closest('.btn-remove')) {
            if (document.querySelectorAll('#itemsTable tbody tr').length > 1) {
                e.target.closest('tr').remove();
                calculateTotal();
            } else {
                alert('Minimal harus ada 1 item!');
            }
        }
    });

    function updatePrice(selectElement) {
        const row = selectElement.closest('tr');
        const price = selectElement.options[selectElement.selectedIndex].dataset.price || 0;
        row.querySelector('.price-input').value = price;
        updateSubtotal(row.querySelector('.qty-input'));
    }

    function updateSubtotal(inputElement) {
        const row = inputElement.closest('tr');
        const price = parseFloat(row.querySelector('.price-input').value) || 0;
        const qty = parseInt(row.querySelector('.qty-input').value) || 0;
        const subtotal = price * qty;
        row.querySelector('.subtotal-input').value = subtotal;
        calculateTotal();
    }

    function calculateTotal() {
        let total = 0;
        document.querySelectorAll('.subtotal-input').forEach(input => {
            total += parseFloat(input.value) || 0;
        });
        document.getElementById('total_bayar').value = total;
        updateChange();
    }

    function updateChange() {
        const total = parseFloat(document.getElementById('total_bayar').value) || 0;
        const paid = parseFloat(document.getElementById('uang_bayar').value) || 0;
        const change = paid - total;
        document.getElementById('kembalian').value = change >= 0 ? change : 0;
    }
</script>

<?php include '../../includes/footer.php'; ?>