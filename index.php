<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}
require_once 'config/database.php';

$chart_data = [];
$chart_labels = [];
for ($i = 6; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $result = $conn->query("SELECT COALESCE(SUM(total_bayar), 0) as total FROM transaksi WHERE DATE(created_at) = '$date'");
    $row = $result->fetch_assoc();
    $chart_labels[] = date('d/m', strtotime($date));
    $chart_data[] = $row['total'] ?? 0;
}
?>
<?php include 'includes/header.php'; ?>

<?php include 'includes/sidebar.php'; ?>

<link rel="stylesheet" href="<?php echo ASSETS_PATH; ?>css/dashboard.css">

<div class="main-content">
    <?php include 'includes/navbar.php'; ?>

    <div class="container-fluid p-3 p-md-4">
        <h2 class="mb-4 fw-bold" style="color: #212529;">Dashboard Overview</h2>

        <!-- Stats Cards -->
        <div class="row g-3 g-md-4">
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="dashboard-card card-primary">
                    <div class="card-icon">
                        <i class="bi bi-cup-straw"></i>
                    </div>
                    <div class="card-title-text">Total Menu</div>
                    <?php
                    $menu_count = $conn->query("SELECT COUNT(*) as count FROM menu")->fetch_assoc()['count'];
                    ?>
                    <div class="card-value"><?php echo $menu_count; ?></div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-lg-4">
                <div class="dashboard-card card-success">
                    <div class="card-icon">
                        <i class="bi bi-cart-check"></i>
                    </div>
                    <div class="card-title-text">Total Transaksi</div>
                    <?php
                    $trans_count = $conn->query("SELECT COUNT(*) as count FROM transaksi")->fetch_assoc()['count'];
                    ?>
                    <div class="card-value"><?php echo $trans_count; ?></div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-lg-4">
                <div class="dashboard-card card-warning">
                    <div class="card-icon">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                    <div class="card-title-text">Pendapatan Hari Ini</div>
                    <?php
                    $today = date('Y-m-d');
                    $income = $conn->query("SELECT SUM(total_bayar) as total FROM transaksi WHERE DATE(created_at) = '$today'")->fetch_assoc()['total'];
                    ?>
                    <div class="card-value">Rp <?php echo number_format($income ?? 0, 0, ',', '.'); ?></div>
                </div>
            </div>
        </div>

        <!-- Sales Chart -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="chart-container">
                    <h3 class="chart-title">
                        <i class="bi bi-graph-up me-2" style="color: #FFCC00;"></i>
                        Grafik Penjualan 7 Hari Terakhir
                    </h3>
                    <div style="position: relative; height: 300px;">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Transactions Table -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="chart-container">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="chart-title mb-0">
                            <i class="bi bi-receipt me-2" style="color: #FFCC00;"></i>
                            Transaksi Terbaru
                        </h3>
                        <a href="pages/transaksi/index.php" class="btn btn-sm" style="background-color: #FFCC00; color: #212529; font-weight: 500; border-radius: 8px; padding: 0.5rem 1rem;">
                            Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table custom-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tanggal</th>
                                    <th>Kasir</th>
                                    <th>Total Bayar</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $recent_query = "SELECT t.*, u.nama as kasir_nama 
                                                 FROM transaksi t 
                                                 JOIN user u ON t.user_id = u.id 
                                                 ORDER BY t.created_at DESC LIMIT 5";
                                $recent_result = $conn->query($recent_query);
                                
                                if ($recent_result->num_rows > 0):
                                    while ($row = $recent_result->fetch_assoc()):
                                ?>
                                <tr>
                                    <td>
                                        <span class="badge-id">#<?php echo $row['id']; ?></span>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-medium"><?php echo date('d M Y', strtotime($row['created_at'])); ?></span>
                                            <small class="text-muted"><?php echo date('H:i', strtotime($row['created_at'])); ?></small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="user-avatar me-2">
                                                <i class="bi bi-person"></i>
                                            </div>
                                            <span><?php echo htmlspecialchars($row['kasir_nama']); ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-semibold" style="color: #212529;">
                                            Rp <?php echo number_format($row['total_bayar'], 0, ',', '.'); ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="pages/transaksi/detail.php?id=<?php echo $row['id']; ?>" 
                                           class="btn btn-sm btn-detail">
                                            <i class="bi bi-eye me-1"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                                <?php 
                                    endwhile;
                                else:
                                ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <i class="bi bi-inbox" style="font-size: 2rem; color: #dee2e6;"></i>
                                        <p class="text-muted mt-2 mb-0">Belum ada transaksi</p>
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


<script>
    // Sales Chart
    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($chart_labels); ?>,
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: <?php echo json_encode($chart_data); ?>,
                backgroundColor: 'rgba(255, 204, 0, 0.1)',
                borderColor: '#FFCC00',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#FFCC00',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        font: {
                            family: 'Poppins',
                            size: 12
                        },
                        padding: 15,
                        usePointStyle: true
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: {
                        family: 'Poppins',
                        size: 13
                    },
                    bodyFont: {
                        family: 'Poppins',
                        size: 12
                    },
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                            return label;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        font: {
                            family: 'Poppins',
                            size: 11
                        },
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    ticks: {
                        font: {
                            family: 'Poppins',
                            size: 11
                        }
                    },
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
</script>

<?php include 'includes/footer.php'; ?>