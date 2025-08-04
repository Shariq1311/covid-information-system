<?php
session_start();
include 'db.php';

// Check if user is admin
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header("Location: login_new.php");
    exit();
}

// Get all COVID tests
$tests_query = "
    SELECT ct.*, p.full_name as patient_name, h.hospital_name, u.email as patient_email
    FROM covid_tests ct 
    LEFT JOIN patients pat ON ct.patient_id = pat.id
    LEFT JOIN users u ON pat.user_id = u.id
    LEFT JOIN users p ON pat.user_id = p.id
    LEFT JOIN hospitals h ON ct.hospital_id = h.id 
    ORDER BY ct.test_date DESC
";
$tests_result = $conn->query($tests_query);

$page_title = "COVID Test Management - Admin Portal";
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
                            <a href="admin_tests.php" class="nav-link text-white active">
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
                            <a href="admin_reports.php" class="nav-link text-white">
                                <i class="fas fa-chart-bar me-2"></i>Reports
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2"><i class="fas fa-vial me-2"></i>COVID Test Management</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.print()">
                            <i class="fas fa-print me-1"></i>Print Report
                        </button>
                    </div>
                </div>

                <!-- Test Statistics -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-vial fa-2x me-3"></i>
                                    <div>
                                        <h4 class="mb-0"><?php echo $tests_result ? $tests_result->num_rows : 0; ?></h4>
                                        <small>Total Tests</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-check-circle fa-2x me-3"></i>
                                    <div>
                                        <h4 class="mb-0">
                                            <?php 
                                            $negative_count = 0;
                                            if ($tests_result) {
                                                $tests_result->data_seek(0);
                                                while ($test = $tests_result->fetch_assoc()) {
                                                    if ($test['result'] === 'negative') $negative_count++;
                                                }
                                                echo $negative_count;
                                            }
                                            ?>
                                        </h4>
                                        <small>Negative Results</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-danger text-white">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
                                    <div>
                                        <h4 class="mb-0">
                                            <?php 
                                            $positive_count = 0;
                                            if ($tests_result) {
                                                $tests_result->data_seek(0);
                                                while ($test = $tests_result->fetch_assoc()) {
                                                    if ($test['result'] === 'positive') $positive_count++;
                                                }
                                                echo $positive_count;
                                            }
                                            ?>
                                        </h4>
                                        <small>Positive Results</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-clock fa-2x me-3"></i>
                                    <div>
                                        <h4 class="mb-0">
                                            <?php 
                                            $pending_count = 0;
                                            if ($tests_result) {
                                                $tests_result->data_seek(0);
                                                while ($test = $tests_result->fetch_assoc()) {
                                                    if ($test['result'] === 'pending') $pending_count++;
                                                }
                                                echo $pending_count;
                                            }
                                            ?>
                                        </h4>
                                        <small>Pending Results</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tests Table -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">COVID Test Records</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-primary">
                                    <tr>
                                        <th>Test ID</th>
                                        <th>Patient</th>
                                        <th>Hospital</th>
                                        <th>Test Type</th>
                                        <th>Test Date</th>
                                        <th>Result</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($tests_result && $tests_result->num_rows > 0): ?>
                                        <?php 
                                        $tests_result->data_seek(0); // Reset pointer
                                        while ($test = $tests_result->fetch_assoc()): 
                                        ?>
                                            <tr>
                                                <td><strong>#<?php echo $test['id']; ?></strong></td>
                                                <td>
                                                    <?php echo htmlspecialchars($test['patient_name'] ?: 'N/A'); ?>
                                                    <br><small class="text-muted"><?php echo htmlspecialchars($test['patient_email'] ?: ''); ?></small>
                                                </td>
                                                <td><?php echo htmlspecialchars($test['hospital_name'] ?: 'N/A'); ?></td>
                                                <td>
                                                    <span class="badge bg-secondary">
                                                        <?php echo htmlspecialchars(ucfirst($test['test_type'] ?: 'Standard')); ?>
                                                    </span>
                                                </td>
                                                <td><?php echo $test['test_date'] ? date('M d, Y', strtotime($test['test_date'])) : 'N/A'; ?></td>
                                                <td>
                                                    <?php if ($test['result'] === 'positive'): ?>
                                                        <span class="badge bg-danger">Positive</span>
                                                    <?php elseif ($test['result'] === 'negative'): ?>
                                                        <span class="badge bg-success">Negative</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-warning">Pending</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($test['status'] === 'completed'): ?>
                                                        <span class="badge bg-success">Completed</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-warning">In Progress</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#viewTestModal<?php echo $test['id']; ?>">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </td>
                                            </tr>

                                            <!-- Test Details Modal -->
                                            <div class="modal fade" id="viewTestModal<?php echo $test['id']; ?>" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Test Details #<?php echo $test['id']; ?></h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-sm-4"><strong>Patient:</strong></div>
                                                                <div class="col-sm-8"><?php echo htmlspecialchars($test['patient_name'] ?: 'N/A'); ?></div>
                                                            </div>
                                                            <div class="row mt-2">
                                                                <div class="col-sm-4"><strong>Hospital:</strong></div>
                                                                <div class="col-sm-8"><?php echo htmlspecialchars($test['hospital_name'] ?: 'N/A'); ?></div>
                                                            </div>
                                                            <div class="row mt-2">
                                                                <div class="col-sm-4"><strong>Test Type:</strong></div>
                                                                <div class="col-sm-8"><?php echo htmlspecialchars(ucfirst($test['test_type'] ?: 'Standard')); ?></div>
                                                            </div>
                                                            <div class="row mt-2">
                                                                <div class="col-sm-4"><strong>Test Date:</strong></div>
                                                                <div class="col-sm-8"><?php echo $test['test_date'] ? date('M d, Y g:i A', strtotime($test['test_date'])) : 'N/A'; ?></div>
                                                            </div>
                                                            <div class="row mt-2">
                                                                <div class="col-sm-4"><strong>Result:</strong></div>
                                                                <div class="col-sm-8">
                                                                    <?php if ($test['result'] === 'positive'): ?>
                                                                        <span class="badge bg-danger">Positive</span>
                                                                    <?php elseif ($test['result'] === 'negative'): ?>
                                                                        <span class="badge bg-success">Negative</span>
                                                                    <?php else: ?>
                                                                        <span class="badge bg-warning">Pending</span>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                            <div class="row mt-2">
                                                                <div class="col-sm-4"><strong>Status:</strong></div>
                                                                <div class="col-sm-8">
                                                                    <?php if ($test['status'] === 'completed'): ?>
                                                                        <span class="badge bg-success">Completed</span>
                                                                    <?php else: ?>
                                                                        <span class="badge bg-warning">In Progress</span>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                            <?php if ($test['notes']): ?>
                                                            <div class="row mt-2">
                                                                <div class="col-sm-4"><strong>Notes:</strong></div>
                                                                <div class="col-sm-8"><?php echo htmlspecialchars($test['notes']); ?></div>
                                                            </div>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="8" class="text-center text-muted">No COVID test records found.</td>
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
</body>
</html>
