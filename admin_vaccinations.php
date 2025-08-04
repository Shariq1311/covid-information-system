<?php
session_start();
include 'db.php';

// Check if user is admin
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header("Location: login_new.php");
    exit();
}

// Get all vaccinations
$vaccinations_query = "
    SELECT v.*, p.full_name as patient_name, h.hospital_name, u.email as patient_email,
           vac.vaccine_name, vac.manufacturer
    FROM vaccinations v 
    LEFT JOIN patients pat ON v.patient_id = pat.id
    LEFT JOIN users u ON pat.user_id = u.id
    LEFT JOIN users p ON pat.user_id = p.id
    LEFT JOIN hospitals h ON v.hospital_id = h.id 
    LEFT JOIN vaccines vac ON v.vaccine_id = vac.id
    ORDER BY v.vaccination_date DESC
";
$vaccinations_result = $conn->query($vaccinations_query);

$page_title = "Vaccination Management - Admin Portal";
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
                            <a href="admin_tests.php" class="nav-link text-white">
                                <i class="fas fa-vial me-2"></i>COVID Tests
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="admin_vaccinations.php" class="nav-link text-white active">
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
                    <h1 class="h2"><i class="fas fa-syringe me-2"></i>Vaccination Management</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.print()">
                            <i class="fas fa-print me-1"></i>Print Report
                        </button>
                    </div>
                </div>

                <!-- Vaccination Statistics -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-syringe fa-2x me-3"></i>
                                    <div>
                                        <h4 class="mb-0"><?php echo $vaccinations_result ? $vaccinations_result->num_rows : 0; ?></h4>
                                        <small>Total Vaccinations</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-check-circle fa-2x me-3"></i>
                                    <div>
                                        <h4 class="mb-0">
                                            <?php 
                                            $completed_count = 0;
                                            if ($vaccinations_result) {
                                                $vaccinations_result->data_seek(0);
                                                while ($vaccination = $vaccinations_result->fetch_assoc()) {
                                                    if ($vaccination['status'] === 'completed') $completed_count++;
                                                }
                                                echo $completed_count;
                                            }
                                            ?>
                                        </h4>
                                        <small>Completed</small>
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
                                            $scheduled_count = 0;
                                            if ($vaccinations_result) {
                                                $vaccinations_result->data_seek(0);
                                                while ($vaccination = $vaccinations_result->fetch_assoc()) {
                                                    if ($vaccination['status'] === 'scheduled') $scheduled_count++;
                                                }
                                                echo $scheduled_count;
                                            }
                                            ?>
                                        </h4>
                                        <small>Scheduled</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-calendar fa-2x me-3"></i>
                                    <div>
                                        <h4 class="mb-0">
                                            <?php 
                                            $today_count = 0;
                                            if ($vaccinations_result) {
                                                $vaccinations_result->data_seek(0);
                                                while ($vaccination = $vaccinations_result->fetch_assoc()) {
                                                    if ($vaccination['vaccination_date'] && date('Y-m-d', strtotime($vaccination['vaccination_date'])) === date('Y-m-d')) {
                                                        $today_count++;
                                                    }
                                                }
                                                echo $today_count;
                                            }
                                            ?>
                                        </h4>
                                        <small>Today</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Vaccinations Table -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Vaccination Records</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-primary">
                                    <tr>
                                        <th>ID</th>
                                        <th>Patient</th>
                                        <th>Vaccine</th>
                                        <th>Hospital</th>
                                        <th>Dose Number</th>
                                        <th>Vaccination Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($vaccinations_result && $vaccinations_result->num_rows > 0): ?>
                                        <?php 
                                        $vaccinations_result->data_seek(0); // Reset pointer
                                        while ($vaccination = $vaccinations_result->fetch_assoc()): 
                                        ?>
                                            <tr>
                                                <td><strong>#<?php echo $vaccination['id']; ?></strong></td>
                                                <td>
                                                    <?php echo htmlspecialchars($vaccination['patient_name'] ?: 'N/A'); ?>
                                                    <br><small class="text-muted"><?php echo htmlspecialchars($vaccination['patient_email'] ?: ''); ?></small>
                                                </td>
                                                <td>
                                                    <strong><?php echo htmlspecialchars($vaccination['vaccine_name'] ?: 'N/A'); ?></strong>
                                                    <br><small class="text-muted"><?php echo htmlspecialchars($vaccination['manufacturer'] ?: ''); ?></small>
                                                </td>
                                                <td><?php echo htmlspecialchars($vaccination['hospital_name'] ?: 'N/A'); ?></td>
                                                <td>
                                                    <span class="badge bg-info">
                                                        Dose <?php echo $vaccination['dose_number'] ?: 1; ?>
                                                    </span>
                                                </td>
                                                <td><?php echo $vaccination['vaccination_date'] ? date('M d, Y', strtotime($vaccination['vaccination_date'])) : 'N/A'; ?></td>
                                                <td>
                                                    <?php if ($vaccination['status'] === 'completed'): ?>
                                                        <span class="badge bg-success">Completed</span>
                                                    <?php elseif ($vaccination['status'] === 'scheduled'): ?>
                                                        <span class="badge bg-warning">Scheduled</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary">Pending</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#viewVaccinationModal<?php echo $vaccination['id']; ?>">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </td>
                                            </tr>

                                            <!-- Vaccination Details Modal -->
                                            <div class="modal fade" id="viewVaccinationModal<?php echo $vaccination['id']; ?>" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Vaccination Details #<?php echo $vaccination['id']; ?></h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-sm-4"><strong>Patient:</strong></div>
                                                                <div class="col-sm-8"><?php echo htmlspecialchars($vaccination['patient_name'] ?: 'N/A'); ?></div>
                                                            </div>
                                                            <div class="row mt-2">
                                                                <div class="col-sm-4"><strong>Email:</strong></div>
                                                                <div class="col-sm-8"><?php echo htmlspecialchars($vaccination['patient_email'] ?: 'N/A'); ?></div>
                                                            </div>
                                                            <div class="row mt-2">
                                                                <div class="col-sm-4"><strong>Vaccine:</strong></div>
                                                                <div class="col-sm-8"><?php echo htmlspecialchars($vaccination['vaccine_name'] ?: 'N/A'); ?></div>
                                                            </div>
                                                            <div class="row mt-2">
                                                                <div class="col-sm-4"><strong>Manufacturer:</strong></div>
                                                                <div class="col-sm-8"><?php echo htmlspecialchars($vaccination['manufacturer'] ?: 'N/A'); ?></div>
                                                            </div>
                                                            <div class="row mt-2">
                                                                <div class="col-sm-4"><strong>Hospital:</strong></div>
                                                                <div class="col-sm-8"><?php echo htmlspecialchars($vaccination['hospital_name'] ?: 'N/A'); ?></div>
                                                            </div>
                                                            <div class="row mt-2">
                                                                <div class="col-sm-4"><strong>Dose Number:</strong></div>
                                                                <div class="col-sm-8">
                                                                    <span class="badge bg-info">Dose <?php echo $vaccination['dose_number'] ?: 1; ?></span>
                                                                </div>
                                                            </div>
                                                            <div class="row mt-2">
                                                                <div class="col-sm-4"><strong>Vaccination Date:</strong></div>
                                                                <div class="col-sm-8"><?php echo $vaccination['vaccination_date'] ? date('M d, Y g:i A', strtotime($vaccination['vaccination_date'])) : 'N/A'; ?></div>
                                                            </div>
                                                            <div class="row mt-2">
                                                                <div class="col-sm-4"><strong>Status:</strong></div>
                                                                <div class="col-sm-8">
                                                                    <?php if ($vaccination['status'] === 'completed'): ?>
                                                                        <span class="badge bg-success">Completed</span>
                                                                    <?php elseif ($vaccination['status'] === 'scheduled'): ?>
                                                                        <span class="badge bg-warning">Scheduled</span>
                                                                    <?php else: ?>
                                                                        <span class="badge bg-secondary">Pending</span>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                            <?php if ($vaccination['administered_by']): ?>
                                                            <div class="row mt-2">
                                                                <div class="col-sm-4"><strong>Administered By:</strong></div>
                                                                <div class="col-sm-8"><?php echo htmlspecialchars($vaccination['administered_by']); ?></div>
                                                            </div>
                                                            <?php endif; ?>
                                                            <?php if ($vaccination['side_effects']): ?>
                                                            <div class="row mt-2">
                                                                <div class="col-sm-4"><strong>Side Effects:</strong></div>
                                                                <div class="col-sm-8"><?php echo htmlspecialchars($vaccination['side_effects']); ?></div>
                                                            </div>
                                                            <?php endif; ?>
                                                            <?php if ($vaccination['notes']): ?>
                                                            <div class="row mt-2">
                                                                <div class="col-sm-4"><strong>Notes:</strong></div>
                                                                <div class="col-sm-8"><?php echo htmlspecialchars($vaccination['notes']); ?></div>
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
                                            <td colspan="8" class="text-center text-muted">No vaccination records found.</td>
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
