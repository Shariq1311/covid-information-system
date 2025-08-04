<?php
session_start();
include 'db.php';

// Check if user is admin
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header("Location: login_new.php");
    exit();
}

// Handle appointment actions
if ($_POST && isset($_POST['action'])) {
    $appointment_id = intval($_POST['appointment_id']);
    $action = $_POST['action'];
    
    if ($action === 'approve') {
        $stmt = $conn->prepare("UPDATE appointments SET status = 'confirmed' WHERE id = ?");
        $stmt->bind_param("i", $appointment_id);
        if ($stmt->execute()) {
            $success = "Appointment approved successfully.";
        }
    } elseif ($action === 'cancel') {
        $stmt = $conn->prepare("UPDATE appointments SET status = 'cancelled' WHERE id = ?");
        $stmt->bind_param("i", $appointment_id);
        if ($stmt->execute()) {
            $success = "Appointment cancelled successfully.";
        }
    } elseif ($action === 'complete') {
        $stmt = $conn->prepare("UPDATE appointments SET status = 'completed' WHERE id = ?");
        $stmt->bind_param("i", $appointment_id);
        if ($stmt->execute()) {
            $success = "Appointment marked as completed.";
        }
    }
}

// Get all appointments
$appointments_query = "
    SELECT a.*, p.full_name as patient_name, h.hospital_name, u.email as patient_email, u.phone as patient_phone
    FROM appointments a 
    LEFT JOIN patients pat ON a.patient_id = pat.id
    LEFT JOIN users u ON pat.user_id = u.id
    LEFT JOIN users p ON pat.user_id = p.id
    LEFT JOIN hospitals h ON a.hospital_id = h.id 
    ORDER BY a.appointment_date DESC, a.appointment_time DESC
";
$appointments_result = $conn->query($appointments_query);

$page_title = "Appointment Management - Admin Portal";
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
                            <a href="admin_vaccinations.php" class="nav-link text-white">
                                <i class="fas fa-syringe me-2"></i>Vaccinations
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="admin_appointments.php" class="nav-link text-white active">
                                <i class="fas fa-calendar-alt me-2"></i>Appointments
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
                    <h1 class="h2"><i class="fas fa-calendar-alt me-2"></i>Appointment Management</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="fas fa-filter me-1"></i>Filter
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="?filter=today">Today's Appointments</a></li>
                                <li><a class="dropdown-item" href="?filter=pending">Pending Approval</a></li>
                                <li><a class="dropdown-item" href="?filter=confirmed">Confirmed</a></li>
                                <li><a class="dropdown-item" href="?filter=completed">Completed</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="admin_appointments.php">All Appointments</a></li>
                            </ul>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.print()">
                            <i class="fas fa-print me-1"></i>Print Report
                        </button>
                    </div>
                </div>

                <?php if (isset($success)): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i><?php echo $success; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Appointment Statistics -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-calendar fa-2x me-3"></i>
                                    <div>
                                        <h4 class="mb-0"><?php echo $appointments_result ? $appointments_result->num_rows : 0; ?></h4>
                                        <small>Total Appointments</small>
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
                                            if ($appointments_result) {
                                                $appointments_result->data_seek(0);
                                                while ($appointment = $appointments_result->fetch_assoc()) {
                                                    if ($appointment['status'] === 'pending') $pending_count++;
                                                }
                                                echo $pending_count;
                                            }
                                            ?>
                                        </h4>
                                        <small>Pending Approval</small>
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
                                            $confirmed_count = 0;
                                            if ($appointments_result) {
                                                $appointments_result->data_seek(0);
                                                while ($appointment = $appointments_result->fetch_assoc()) {
                                                    if ($appointment['status'] === 'confirmed') $confirmed_count++;
                                                }
                                                echo $confirmed_count;
                                            }
                                            ?>
                                        </h4>
                                        <small>Confirmed</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-calendar-day fa-2x me-3"></i>
                                    <div>
                                        <h4 class="mb-0">
                                            <?php 
                                            $today_count = 0;
                                            $today = date('Y-m-d');
                                            if ($appointments_result) {
                                                $appointments_result->data_seek(0);
                                                while ($appointment = $appointments_result->fetch_assoc()) {
                                                    if ($appointment['appointment_date'] && date('Y-m-d', strtotime($appointment['appointment_date'])) === $today) {
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

                <!-- Appointments Table -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Appointment Records</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-primary">
                                    <tr>
                                        <th>ID</th>
                                        <th>Patient</th>
                                        <th>Hospital</th>
                                        <th>Service Type</th>
                                        <th>Date & Time</th>
                                        <th>Status</th>
                                        <th>Priority</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($appointments_result && $appointments_result->num_rows > 0): ?>
                                        <?php 
                                        $appointments_result->data_seek(0); // Reset pointer
                                        while ($appointment = $appointments_result->fetch_assoc()): 
                                        ?>
                                            <tr>
                                                <td><strong>#<?php echo $appointment['id']; ?></strong></td>
                                                <td>
                                                    <?php echo htmlspecialchars($appointment['patient_name'] ?: 'N/A'); ?>
                                                    <br><small class="text-muted"><?php echo htmlspecialchars($appointment['patient_email'] ?: ''); ?></small>
                                                </td>
                                                <td><?php echo htmlspecialchars($appointment['hospital_name'] ?: 'N/A'); ?></td>
                                                <td>
                                                    <span class="badge bg-secondary">
                                                        <?php echo htmlspecialchars(ucfirst($appointment['service_type'] ?: 'General')); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php if ($appointment['appointment_date']): ?>
                                                        <?php echo date('M d, Y', strtotime($appointment['appointment_date'])); ?>
                                                        <br><small class="text-muted">
                                                            <?php echo $appointment['appointment_time'] ? date('g:i A', strtotime($appointment['appointment_time'])) : 'No time set'; ?>
                                                        </small>
                                                    <?php else: ?>
                                                        <span class="text-muted">Not scheduled</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($appointment['status'] === 'pending'): ?>
                                                        <span class="badge bg-warning">Pending</span>
                                                    <?php elseif ($appointment['status'] === 'confirmed'): ?>
                                                        <span class="badge bg-success">Confirmed</span>
                                                    <?php elseif ($appointment['status'] === 'completed'): ?>
                                                        <span class="badge bg-primary">Completed</span>
                                                    <?php elseif ($appointment['status'] === 'cancelled'): ?>
                                                        <span class="badge bg-danger">Cancelled</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary">Unknown</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($appointment['priority'] === 'high'): ?>
                                                        <span class="badge bg-danger">High</span>
                                                    <?php elseif ($appointment['priority'] === 'medium'): ?>
                                                        <span class="badge bg-warning">Medium</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-info">Normal</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <button type="button" class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#viewAppointmentModal<?php echo $appointment['id']; ?>">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                        
                                                        <?php if ($appointment['status'] === 'pending'): ?>
                                                            <form method="POST" style="display: inline;">
                                                                <input type="hidden" name="appointment_id" value="<?php echo $appointment['id']; ?>">
                                                                <input type="hidden" name="action" value="approve">
                                                                <button type="submit" class="btn btn-outline-success" onclick="return confirm('Approve this appointment?')">
                                                                    <i class="fas fa-check"></i>
                                                                </button>
                                                            </form>
                                                        <?php endif; ?>
                                                        
                                                        <?php if ($appointment['status'] === 'confirmed'): ?>
                                                            <form method="POST" style="display: inline;">
                                                                <input type="hidden" name="appointment_id" value="<?php echo $appointment['id']; ?>">
                                                                <input type="hidden" name="action" value="complete">
                                                                <button type="submit" class="btn btn-outline-primary" onclick="return confirm('Mark as completed?')">
                                                                    <i class="fas fa-check-double"></i>
                                                                </button>
                                                            </form>
                                                        <?php endif; ?>
                                                        
                                                        <?php if ($appointment['status'] !== 'cancelled' && $appointment['status'] !== 'completed'): ?>
                                                            <form method="POST" style="display: inline;">
                                                                <input type="hidden" name="appointment_id" value="<?php echo $appointment['id']; ?>">
                                                                <input type="hidden" name="action" value="cancel">
                                                                <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Cancel this appointment?')">
                                                                    <i class="fas fa-times"></i>
                                                                </button>
                                                            </form>
                                                        <?php endif; ?>
                                                    </div>

                                                    <!-- Appointment Details Modal -->
                                                    <div class="modal fade" id="viewAppointmentModal<?php echo $appointment['id']; ?>" tabindex="-1">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">Appointment Details #<?php echo $appointment['id']; ?></h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <h6 class="text-primary">Patient Information</h6>
                                                                            <div class="row mb-2">
                                                                                <div class="col-sm-4"><strong>Name:</strong></div>
                                                                                <div class="col-sm-8"><?php echo htmlspecialchars($appointment['patient_name'] ?: 'N/A'); ?></div>
                                                                            </div>
                                                                            <div class="row mb-2">
                                                                                <div class="col-sm-4"><strong>Email:</strong></div>
                                                                                <div class="col-sm-8"><?php echo htmlspecialchars($appointment['patient_email'] ?: 'N/A'); ?></div>
                                                                            </div>
                                                                            <div class="row mb-2">
                                                                                <div class="col-sm-4"><strong>Phone:</strong></div>
                                                                                <div class="col-sm-8"><?php echo htmlspecialchars($appointment['patient_phone'] ?: 'N/A'); ?></div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <h6 class="text-success">Appointment Details</h6>
                                                                            <div class="row mb-2">
                                                                                <div class="col-sm-4"><strong>Hospital:</strong></div>
                                                                                <div class="col-sm-8"><?php echo htmlspecialchars($appointment['hospital_name'] ?: 'N/A'); ?></div>
                                                                            </div>
                                                                            <div class="row mb-2">
                                                                                <div class="col-sm-4"><strong>Service:</strong></div>
                                                                                <div class="col-sm-8"><?php echo htmlspecialchars(ucfirst($appointment['service_type'] ?: 'General')); ?></div>
                                                                            </div>
                                                                            <div class="row mb-2">
                                                                                <div class="col-sm-4"><strong>Date:</strong></div>
                                                                                <div class="col-sm-8"><?php echo $appointment['appointment_date'] ? date('M d, Y', strtotime($appointment['appointment_date'])) : 'Not scheduled'; ?></div>
                                                                            </div>
                                                                            <div class="row mb-2">
                                                                                <div class="col-sm-4"><strong>Time:</strong></div>
                                                                                <div class="col-sm-8"><?php echo $appointment['appointment_time'] ? date('g:i A', strtotime($appointment['appointment_time'])) : 'Not scheduled'; ?></div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <hr>
                                                                    
                                                                    <div class="row">
                                                                        <div class="col-md-4">
                                                                            <div class="row mb-2">
                                                                                <div class="col-sm-5"><strong>Status:</strong></div>
                                                                                <div class="col-sm-7">
                                                                                    <?php if ($appointment['status'] === 'pending'): ?>
                                                                                        <span class="badge bg-warning">Pending</span>
                                                                                    <?php elseif ($appointment['status'] === 'confirmed'): ?>
                                                                                        <span class="badge bg-success">Confirmed</span>
                                                                                    <?php elseif ($appointment['status'] === 'completed'): ?>
                                                                                        <span class="badge bg-primary">Completed</span>
                                                                                    <?php elseif ($appointment['status'] === 'cancelled'): ?>
                                                                                        <span class="badge bg-danger">Cancelled</span>
                                                                                    <?php endif; ?>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="row mb-2">
                                                                                <div class="col-sm-5"><strong>Priority:</strong></div>
                                                                                <div class="col-sm-7">
                                                                                    <?php if ($appointment['priority'] === 'high'): ?>
                                                                                        <span class="badge bg-danger">High</span>
                                                                                    <?php elseif ($appointment['priority'] === 'medium'): ?>
                                                                                        <span class="badge bg-warning">Medium</span>
                                                                                    <?php else: ?>
                                                                                        <span class="badge bg-info">Normal</span>
                                                                                    <?php endif; ?>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="row mb-2">
                                                                                <div class="col-sm-5"><strong>Created:</strong></div>
                                                                                <div class="col-sm-7">
                                                                                    <small><?php echo $appointment['created_at'] ? date('M d, Y', strtotime($appointment['created_at'])) : 'N/A'; ?></small>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <?php if ($appointment['symptoms'] || $appointment['notes']): ?>
                                                                    <hr>
                                                                    <div class="row">
                                                                        <?php if ($appointment['symptoms']): ?>
                                                                        <div class="col-md-6">
                                                                            <h6 class="text-warning">Symptoms:</h6>
                                                                            <p><?php echo htmlspecialchars($appointment['symptoms']); ?></p>
                                                                        </div>
                                                                        <?php endif; ?>
                                                                        <?php if ($appointment['notes']): ?>
                                                                        <div class="col-md-6">
                                                                            <h6 class="text-info">Notes:</h6>
                                                                            <p><?php echo htmlspecialchars($appointment['notes']); ?></p>
                                                                        </div>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                    <?php endif; ?>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="8" class="text-center text-muted">No appointment records found.</td>
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
