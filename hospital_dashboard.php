<?php
session_start();
include 'db.php';

// Check if user is hospital
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'hospital') {
    header("Location: login_new.php");
    exit();
}

// Get hospital information
$user_id = $_SESSION['user_id'] ?? 1;
$hospital_query = $conn->prepare("SELECT h.*, u.status as user_status FROM hospitals h JOIN users u ON h.user_id = u.id WHERE h.user_id = ?");
$hospital_query->bind_param("i", $user_id);
$hospital_query->execute();
$hospital = $hospital_query->get_result()->fetch_assoc();

if (!$hospital) {
    // Redirect to hospital registration if hospital profile not found
    header("Location: hospital_register.php");
    exit();
}

// Get dashboard statistics
$stats = [];

// Total patients served
$result = $conn->query("SELECT COUNT(DISTINCT patient_id) as count FROM covid_tests WHERE hospital_id = " . $hospital['id']);
$stats['total_patients'] = $result->fetch_assoc()['count'];

// Pending appointments
$result = $conn->query("SELECT COUNT(*) as count FROM appointments WHERE hospital_id = " . $hospital['id'] . " AND status = 'pending'");
$stats['pending_appointments'] = $result->fetch_assoc()['count'];

// Tests conducted this month
$result = $conn->query("SELECT COUNT(*) as count FROM covid_tests WHERE hospital_id = " . $hospital['id'] . " AND MONTH(test_date) = MONTH(NOW()) AND YEAR(test_date) = YEAR(NOW())");
$stats['monthly_tests'] = $result->fetch_assoc()['count'];

// Vaccinations given this month
$result = $conn->query("SELECT COUNT(*) as count FROM vaccinations WHERE hospital_id = " . $hospital['id'] . " AND status = 'completed' AND MONTH(vaccination_date) = MONTH(NOW()) AND YEAR(vaccination_date) = YEAR(NOW())");
$stats['monthly_vaccinations'] = $result->fetch_assoc()['count'];

// Recent appointments
$recent_appointments = $conn->query("
    SELECT a.*, p.full_name as patient_name, p.phone as patient_phone
    FROM appointments a 
    JOIN patients p ON a.patient_id = p.id 
    WHERE a.hospital_id = " . $hospital['id'] . "
    ORDER BY a.appointment_date DESC, a.appointment_time DESC 
    LIMIT 10
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Dashboard - <?php echo htmlspecialchars($hospital['hospital_name']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-light">
    <?php include 'layout/hospital_header.php'; ?>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 bg-dark p-0">
                <div class="d-flex flex-column p-3">
                    <h6 class="text-white mb-4">Hospital Panel</h6>
                    <ul class="nav nav-pills flex-column">
                        <li class="nav-item mb-2">
                            <a href="hospital_dashboard.php" class="nav-link text-white active">
                                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a href="hospital_patients.php" class="nav-link text-white">
                                <i class="fas fa-users me-2"></i>Patients
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a href="hospital_appointments.php" class="nav-link text-white">
                                <i class="fas fa-calendar me-2"></i>Appointments
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a href="hospital_tests.php" class="nav-link text-white">
                                <i class="fas fa-vial me-2"></i>COVID Tests
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a href="hospital_vaccinations.php" class="nav-link text-white">
                                <i class="fas fa-syringe me-2"></i>Vaccinations
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a href="hospital_inventory.php" class="nav-link text-white">
                                <i class="fas fa-boxes me-2"></i>Vaccine Inventory
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a href="hospital_profile.php" class="nav-link text-white">
                                <i class="fas fa-hospital me-2"></i>Hospital Profile
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 p-4">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2><?php echo htmlspecialchars($hospital['hospital_name']); ?></h2>
                        <div class="text-muted">
                            Hospital Dashboard - 
                            <span class="badge bg-<?php echo $hospital['approval_status'] == 'approved' ? 'success' : ($hospital['approval_status'] == 'pending' ? 'warning' : 'danger'); ?>">
                                <?php echo ucfirst($hospital['approval_status']); ?>
                            </span>
                        </div>
                    </div>
                    <div class="text-muted">
                        Welcome, <?php echo htmlspecialchars($_SESSION['user']); ?>
                    </div>
                </div>

                <?php if ($hospital['approval_status'] == 'pending'): ?>
                    <div class="alert alert-warning">
                        <i class="fas fa-clock me-2"></i>
                        Your hospital registration is pending admin approval. Some features may be limited until approved.
                    </div>
                <?php elseif ($hospital['approval_status'] == 'rejected'): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-times-circle me-2"></i>
                        Your hospital registration has been rejected. Please contact admin for more information.
                    </div>
                <?php endif; ?>

                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-md-3 mb-3">
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
                    <div class="col-md-3 mb-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title">Pending Appointments</h6>
                                        <h2><?php echo $stats['pending_appointments']; ?></h2>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-clock fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title">Tests This Month</h6>
                                        <h2><?php echo $stats['monthly_tests']; ?></h2>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-vial fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title">Vaccinations This Month</h6>
                                        <h2><?php echo $stats['monthly_vaccinations']; ?></h2>
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
                                        <a href="hospital_appointments.php?action=add" class="btn btn-primary w-100 mb-2">
                                            <i class="fas fa-plus me-2"></i>Schedule Appointment
                                        </a>
                                    </div>
                                    <div class="col-md-3">
                                        <a href="hospital_tests.php?action=add" class="btn btn-info w-100 mb-2">
                                            <i class="fas fa-vial me-2"></i>Add Test Result
                                        </a>
                                    </div>
                                    <div class="col-md-3">
                                        <a href="hospital_vaccinations.php?action=add" class="btn btn-success w-100 mb-2">
                                            <i class="fas fa-syringe me-2"></i>Record Vaccination
                                        </a>
                                    </div>
                                    <div class="col-md-3">
                                        <a href="hospital_inventory.php" class="btn btn-warning w-100 mb-2">
                                            <i class="fas fa-boxes me-2"></i>Manage Inventory
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Appointments -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h5>Recent Appointments</h5>
                                <a href="hospital_appointments.php" class="btn btn-sm btn-outline-primary">View All</a>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Date & Time</th>
                                                <th>Patient</th>
                                                <th>Type</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if ($recent_appointments && $recent_appointments->num_rows > 0): ?>
                                                <?php while ($appointment = $recent_appointments->fetch_assoc()): ?>
                                                <tr>
                                                    <td>
                                                        <?php echo date('M d, Y', strtotime($appointment['appointment_date'])); ?><br>
                                                        <small class="text-muted"><?php echo date('h:i A', strtotime($appointment['appointment_time'])); ?></small>
                                                    </td>
                                                    <td>
                                                        <strong><?php echo htmlspecialchars($appointment['patient_name']); ?></strong><br>
                                                        <small class="text-muted"><?php echo htmlspecialchars($appointment['patient_phone']); ?></small>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-secondary">
                                                            <?php echo str_replace('_', ' ', ucwords($appointment['appointment_type'])); ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-<?php 
                                                            echo match($appointment['status']) {
                                                                'confirmed' => 'success',
                                                                'completed' => 'primary',
                                                                'cancelled' => 'danger',
                                                                'pending' => 'warning',
                                                                default => 'secondary'
                                                            };
                                                        ?>">
                                                            <?php echo ucfirst($appointment['status']); ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group btn-group-sm" role="group">
                                                            <a href="hospital_appointments.php?view=<?php echo $appointment['id']; ?>" class="btn btn-outline-primary">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            <?php if ($appointment['status'] == 'pending'): ?>
                                                                <a href="hospital_appointments.php?confirm=<?php echo $appointment['id']; ?>" class="btn btn-outline-success">
                                                                    <i class="fas fa-check"></i>
                                                                </a>
                                                            <?php endif; ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php endwhile; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="5" class="text-center text-muted">No recent appointments found</td>
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
