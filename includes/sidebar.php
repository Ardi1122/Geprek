<?php
    require_once __DIR__ . '/../config/constant.php';
?>

<!-- Sidebar Overlay for Mobile -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <a href="../index.php" class="sidebar-brand">
            <i class="bi bi-shop"></i>
            <span>Geprek</span>
        </a>
    </div>
    <div class="sidebar-menu">
        <ul class="nav nav-pills flex-column">
            <li class="nav-item">
                <a href="<?php echo BASE_PATH;?>index.php"
                    class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : ''; ?>">
                    <i class="bi bi-speedometer2 me-2"></i>
                    Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo BASE_PATH; ?>pages/menu/index.php"
                    class="nav-link <?php echo (strpos($_SERVER['PHP_SELF'], '/menu/') !== false) ? 'active' : ''; ?>">
                    <i class="bi bi-cup-straw me-2"></i>
                    Menu
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo BASE_PATH; ?>pages/bahan/index.php"
                    class="nav-link <?php echo (strpos($_SERVER['PHP_SELF'], '/bahan/') !== false) ? 'active' : ''; ?>">
                    <i class="bi bi-box-seam me-2"></i>
                    Bahan Baku
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo BASE_PATH; ?>pages/transaksi/index.php"
                    class="nav-link <?php echo (strpos($_SERVER['PHP_SELF'], '/transaksi/') !== false) ? 'active' : ''; ?>">
                    <i class="bi bi-cart me-2"></i>
                    Transaksi
                </a>
            </li>
            <?php if ($_SESSION['role'] == 'pemilik'): ?>
                <li class="nav-item">
                    <a href="<?php echo BASE_PATH; ?>pages/user/index.php"
                        class="nav-link <?php echo (strpos($_SERVER['PHP_SELF'], '/user/') !== false) ? 'active' : ''; ?>">
                        <i class="bi bi-people me-2"></i>
                        User
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo BASE_PATH; ?>pages/chat/index.php"
                        class="nav-link <?php echo (strpos($_SERVER['PHP_SELF'], '/chat/') !== false) ? 'active' : ''; ?>">
                        <i class="bi bi-robot me-2"></i>
                        Chat Assistant
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</div>