<nav class="navbar navbar-expand-lg top-navbar">
    <div class="container-fluid">
        <button class="mobile-menu-toggle" type="button" onclick="toggleSidebar()">
            <i class="bi bi-list"></i>
        </button>
        
        <div class="ms-auto">
            <div class="dropdown user-dropdown">
                <button class="btn dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle me-2"></i>
                    <span class="d-none d-sm-inline"><?php echo htmlspecialchars($_SESSION['nama']); ?></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <li>
                        <a class="dropdown-item" href="<?php echo BASE_PATH; ?>auth/logout.php">
                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<script>
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    sidebar.classList.toggle('show');
    overlay.classList.toggle('show');
}

// Close sidebar when clicking on a link (mobile)
document.querySelectorAll('.sidebar .nav-link').forEach(link => {
    link.addEventListener('click', function() {
        if (window.innerWidth <= 991) {
            toggleSidebar();
        }
    });
});
</script>