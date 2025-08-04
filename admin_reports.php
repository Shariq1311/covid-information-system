<?php
session_start();
include 'db.php';

// Check if user is admin
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header("Location: login_new.php");
    exit();
}

// Get report statistics
$stats = [];

// Daily statistics
$today = date('Y-m-d');
$stats['today_tests'] = $conn->query("SELECT COUNT(*) as count FROM covid_tests WHERE DATE(test_date) = '$today'")->fetch_assoc()['count'];
$stats['today_vaccinations'] = $conn->query("SELECT COUNT(*) as count FROM vaccinations WHERE DATE(vaccination_date) = '$today'")->fetch_assoc()['count'];

// Weekly statistics
$week_ago = date('Y-m-d', strtotime('-7 days'));
$stats['week_tests'] = $conn->query("SELECT COUNT(*) as count FROM covid_tests WHERE test_date >= '$week_ago'")->fetch_assoc()['count'];
$stats['week_vaccinations'] = $conn->query("SELECT COUNT(*) as count FROM vaccinations WHERE vaccination_date >= '$week_ago'")->fetch_assoc()['count'];

// Monthly statistics
$month_ago = date('Y-m-d', strtotime('-30 days'));
$stats['month_tests'] = $conn->query("SELECT COUNT(*) as count FROM covid_tests WHERE test_date >= '$month_ago'")->fetch_assoc()['count'];
$stats['month_vaccinations'] = $conn->query("SELECT COUNT(*) as count FROM vaccinations WHERE vaccination_date >= '$month_ago'")->fetch_assoc()['count'];

// Test results breakdown
$test_results = $conn->query("
    SELECT result, COUNT(*) as count 
    FROM covid_tests 
    WHERE test_date >= '$month_ago' 
    GROUP BY result
")->fetch_all(MYSQLI_ASSOC);

// Top performing hospitals
$top_hospitals = $conn->query("
    SELECT h.hospital_name, 
           COUNT(DISTINCT ct.id) as total_tests,
           COUNT(DISTINCT v.id) as total_vaccinations
    FROM hospitals h
    LEFT JOIN covid_tests ct ON h.id = ct.hospital_id AND ct.test_date >= '$month_ago'
    LEFT JOIN vaccinations v ON h.id = v.hospital_id AND v.vaccination_date >= '$month_ago'
    WHERE h.approval_status = 'approved'
    GROUP BY h.id, h.hospital_name
    ORDER BY (COUNT(DISTINCT ct.id) + COUNT(DISTINCT v.id)) DESC
    LIMIT 10
")->fetch_all(MYSQLI_ASSOC);

$page_title = "Reports & Analytics - Admin Portal";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <?php include 'layout/admin_header.php'; ?>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar bg-primary">
                <div class="sidebar-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a href="admin_dashboard.php" class="nav-link text-white">
                                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="admin_patients.php" class="nav-link text-white">
                                <i class="fas fa-users me-2"></i>Patients
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="admin_hospitals.php" class="nav-link text-white">
                                <i class="fas fa-hospital me-2"></i>Hospitals
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="admin_tests.php" class="nav-link text-white">
                                <i class="fas fa-vial me-2"></i>COVID Tests
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="admin_vaccinations.php" class="nav-link text-white">
                                <i class="fas fa-syringe me-2"></i>Vaccinations
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="admin_vaccines.php" class="nav-link text-white">
                                <i class="fas fa-pills me-2"></i>Vaccine Inventory
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="admin_reports.php" class="nav-link text-white active">
                                <i class="fas fa-chart-bar me-2"></i>Reports
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2"><i class="fas fa-chart-bar me-2"></i>Reports & Analytics</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.print()">
                            <i class="fas fa-print me-1"></i>Print Report
                        </button>
                        <button type="button" class="btn btn-sm btn-primary ms-2" onclick="exportData()">
                            <i class="fas fa-download me-1"></i>Export Data
                        </button>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-calendar-day fa-2x me-3"></i>
                                    <div>
                                        <h4 class="mb-0"><?php echo $stats['today_tests']; ?></h4>
                                        <small>Tests Today</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-syringe fa-2x me-3"></i>
                                    <div>
                                        <h4 class="mb-0"><?php echo $stats['today_vaccinations']; ?></h4>
                                        <small>Vaccinations Today</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-calendar-week fa-2x me-3"></i>
                                    <div>
                                        <h4 class="mb-0"><?php echo $stats['week_tests']; ?></h4>
                                        <small>Tests This Week</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-calendar-alt fa-2x me-3"></i>
                                    <div>
                                        <h4 class="mb-0"><?php echo $stats['month_tests']; ?></h4>
                                        <small>Tests This Month</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Row -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Test Results Distribution (Last 30 Days)</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="testResultsChart" width="400" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Tests vs Vaccinations Comparison</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="comparisonChart" width="400" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Top Hospitals Table -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Top Performing Hospitals (Last 30 Days)</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-primary">
                                    <tr>
                                        <th>Rank</th>
                                        <th>Hospital Name</th>
                                        <th>Total Tests</th>
                                        <th>Total Vaccinations</th>
                                        <th>Combined Total</th>
                                        <th>Performance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($top_hospitals)): ?>
                                        <?php foreach ($top_hospitals as $index => $hospital): ?>
                                            <?php $total = $hospital['total_tests'] + $hospital['total_vaccinations']; ?>
                                            <tr>
                                                <td>
                                                    <span class="badge <?php echo $index < 3 ? 'bg-warning' : 'bg-secondary'; ?>">
                                                        #<?php echo $index + 1; ?>
                                                    </span>
                                                </td>
                                                <td><strong><?php echo htmlspecialchars($hospital['hospital_name']); ?></strong></td>
                                                <td>
                                                    <span class="badge bg-primary"><?php echo $hospital['total_tests']; ?></span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-success"><?php echo $hospital['total_vaccinations']; ?></span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-info"><?php echo $total; ?></span>
                                                </td>
                                                <td>
                                                    <?php if ($total > 50): ?>
                                                        <span class="badge bg-success">Excellent</span>
                                                    <?php elseif ($total > 20): ?>
                                                        <span class="badge bg-warning">Good</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary">Average</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">No data available for the selected period.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Test Results Chart
        const testResultsCtx = document.getElementById('testResultsChart').getContext('2d');
        const testResultsData = <?php echo json_encode($test_results); ?>;
        
        const labels = testResultsData.map(item => item.result || 'Unknown');
        const data = testResultsData.map(item => item.count);
        const colors = ['#28a745', '#dc3545', '#ffc107', '#6c757d'];
        
        new Chart(testResultsCtx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: colors.slice(0, labels.length),
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Comparison Chart
        const comparisonCtx = document.getElementById('comparisonChart').getContext('2d');
        new Chart(comparisonCtx, {
            type: 'bar',
            data: {
                labels: ['Today', 'This Week', 'This Month'],
                datasets: [{
                    label: 'Tests',
                    data: [<?php echo $stats['today_tests']; ?>, <?php echo $stats['week_tests']; ?>, <?php echo $stats['month_tests']; ?>],
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }, {
                    label: 'Vaccinations',
                    data: [<?php echo $stats['today_vaccinations']; ?>, <?php echo $stats['week_vaccinations']; ?>, <?php echo $stats['month_vaccinations']; ?>],
                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Export function
        function exportData() {
            alert('Export functionality would be implemented here. This could generate CSV, PDF, or Excel reports.');
        }
    </script>
</body>
</html>
