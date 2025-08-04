<?php
session_start();
include 'db.php';

// Check if user is admin
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header("Location: login_new.php");
    exit();
}

// Get dashboard statistics
$stats = [];

// Total patients
$result = $conn->query("SELECT COUNT(*) as count FROM patients");
$stats['total_patients'] = $result->fetch_assoc()['count'];

// Total hospitals
$result = $conn->query("SELECT COUNT(*) as count FROM hospitals");
$stats['total_hospitals'] = $result->fetch_assoc()['count'];

// Pending hospital approvals
$result = $conn->query("SELECT COUNT(*) as count FROM hospitals WHERE approval_status = 'pending'");
$stats['pending_hospitals'] = $result->fetch_assoc()['count'];

// Total tests conducted
$result = $conn->query("SELECT COUNT(*) as count FROM covid_tests");
$stats['total_tests'] = $result->fetch_assoc()['count'];

// Total vaccinations
$result = $conn->query("SELECT COUNT(*) as count FROM vaccinations WHERE status = 'completed'");
$stats['total_vaccinations'] = $result->fetch_assoc()['count'];

// Recent activities
$recent_activities = $conn->query("
    SELECT 'test' as type, ct.test_date as date, p.full_name as patient_name, h.hospital_name, ct.result
    FROM covid_tests ct 
    JOIN patients p ON ct.patient_id = p.id 
    JOIN hospitals h ON ct.hospital_id = h.id 
    WHERE ct.created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
    UNION ALL
    SELECT 'vaccination' as type, v.vaccination_date as date, p.full_name as patient_name, h.hospital_name, v.status as result
    FROM vaccinations v 
    JOIN patients p ON v.patient_id = p.id 
    JOIN hospitals h ON v.hospital_id = h.id 
    WHERE v.created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
    ORDER BY date DESC LIMIT 10
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - COVID Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-light">
    <?php include 'layout/admin_header.php'; ?>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 bg-dark p-0">
                <div class="d-flex flex-column p-3">
                    <h5 class="text-white mb-4">Admin Panel</h5>
                    <ul class="nav nav-pills flex-column">
                        <li class="nav-item mb-2">
                            <a href="admin_dashboard.php" class="nav-link text-white active">
                                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a href="admin_patients.php" class="nav-link text-white">
                                <i class="fas fa-users me-2"></i>Patients
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a href="admin_hospitals.php" class="nav-link text-white">
                                <i class="fas fa-hospital me-2"></i>Hospitals
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a href="admin_tests.php" class="nav-link text-white">
                                <i class="fas fa-vial me-2"></i>COVID Tests
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a href="admin_vaccinations.php" class="nav-link text-white">
                                <i class="fas fa-syringe me-2"></i>Vaccinations
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a href="admin_appointments.php" class="nav-link text-white">
                                <i class="fas fa-calendar me-2"></i>Appointments
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a href="admin_vaccines.php" class="nav-link text-white">
                                <i class="fas fa-pills me-2"></i>Vaccine Management
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a href="admin_reports.php" class="nav-link text-white">
                                <i class="fas fa-chart-bar me-2"></i>Reports
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a href="admin_settings.php" class="nav-link text-white">
                                <i class="fas fa-cog me-2"></i>Settings
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Admin Dashboard</h2>
                    <div class="text-muted">
                        Welcome, <?php echo htmlspecialchars($_SESSION['user']); ?>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-md-2 mb-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title">Total Patients</h6>
                                        <h2><?php echo $stats['total_patients']; ?></h2>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-users fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 mb-3">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title">Total Hospitals</h6>
                                        <h2><?php echo $stats['total_hospitals']; ?></h2>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-hospital fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 mb-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title">Pending Approvals</h6>
                                        <h2><?php echo $stats['pending_hospitals']; ?></h2>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-clock fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 mb-3">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title">Total Tests</h6>
                                        <h2><?php echo $stats['total_tests']; ?></h2>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-vial fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 mb-3">
                        <div class="card bg-secondary text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title">Vaccinations</h6>
                                        <h2><?php echo $stats['total_vaccinations']; ?></h2>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-syringe fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h5>Quick Actions</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <a href="admin_hospitals.php?action=approve" class="btn btn-primary w-100 mb-2">
                                            <i class="fas fa-check me-2"></i>Approve Hospitals
                                        </a>
                                    </div>
                                    <div class="col-md-3">
                                        <a href="admin_vaccines.php?action=add" class="btn btn-success w-100 mb-2">
                                            <i class="fas fa-plus me-2"></i>Add Vaccine
                                        </a>
                                    </div>
                                    <div class="col-md-3">
                                        <a href="admin_reports.php?type=daily" class="btn btn-info w-100 mb-2">
                                            <i class="fas fa-download me-2"></i>Download Reports
                                        </a>
                                    </div>
                                    <div class="col-md-3">
                                        <a href="admin_settings.php" class="btn btn-warning w-100 mb-2">
                                            <i class="fas fa-cog me-2"></i>System Settings
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activities -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h5>Recent Activities (Last 7 Days)</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Type</th>
                                                <th>Date</th>
                                                <th>Patient</th>
                                                <th>Hospital</th>
                                                <th>Status/Result</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if ($recent_activities->num_rows > 0): ?>
                                                <?php while ($activity = $recent_activities->fetch_assoc()): ?>
                                                <tr>
                                                    <td>
                                                        <span class="badge bg-<?php echo $activity['type'] == 'test' ? 'info' : 'success'; ?>">
                                                            <?php echo strtoupper($activity['type']); ?>
                                                        </span>
                                                    </td>
                                                    <td><?php echo date('M d, Y', strtotime($activity['date'])); ?></td>
                                                    <td><?php echo htmlspecialchars($activity['patient_name']); ?></td>
                                                    <td><?php echo htmlspecialchars($activity['hospital_name']); ?></td>
                                                    <td>
                                                        <span class="badge bg-<?php 
                                                            echo match($activity['result']) {
                                                                'positive' => 'danger',
                                                                'negative' => 'success',
                                                                'completed' => 'success',
                                                                'pending' => 'warning',
                                                                default => 'secondary'
                                                            };
                                                        ?>">
                                                            <?php echo ucfirst($activity['result']); ?>
                                                        </span>
                                                    </td>
                                                </tr>
                                                <?php endwhile; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="5" class="text-center text-muted">No recent activities found</td>
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
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
