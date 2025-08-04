<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'doctor') {
    header("Location: login_new.php");
    exit();
}

$doctor_id = $_SESSION['user_id'];

// Fetch recent appointments for this doctor
$stmt = $conn->prepare("
    SELECT a.*, p.full_name, p.phone, h.hospital_name 
    FROM appointments a 
    JOIN patients p ON a.patient_id = p.id 
    JOIN hospitals h ON a.hospital_id = h.id 
    WHERE a.doctor_id = ? 
    ORDER BY a.appointment_date DESC, a.appointment_time DESC 
    LIMIT 10
");
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$appointments = $stmt->get_result();

// Get today's appointments
$stmt = $conn->prepare("
    SELECT a.*, p.full_name, p.phone, h.hospital_name 
    FROM appointments a 
    JOIN patients p ON a.patient_id = p.id 
    JOIN hospitals h ON a.hospital_id = h.id 
    WHERE a.doctor_id = ? AND DATE(a.appointment_date) = CURDATE()
    ORDER BY a.appointment_time ASC
");
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$today_appointments = $stmt->get_result();

// Get statistics
$stats_query = "
    SELECT 
        COUNT(*) as total_appointments,
        SUM(CASE WHEN DATE(appointment_date) = CURDATE() THEN 1 ELSE 0 END) as today_appointments,
        SUM(CASE WHEN status = 'confirmed' THEN 1 ELSE 0 END) as confirmed_appointments,
        SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_appointments
    FROM appointments 
    WHERE doctor_id = ?
";
$stmt = $conn->prepare($stats_query);
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$stats = $stmt->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Doctor Dashboard - COVID-19 Portal</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
    
    <style>
        .stats-card {
            transition: transform 0.2s ease-in-out;
        }
        .stats-card:hover {
            transform: translateY(-2px);
        }
        .appointment-card {
            border-left: 4px solid #007bff;
            transition: all 0.2s ease-in-out;
        }
        .appointment-card:hover {
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body class="bg-light">
    <?php 
    // Include doctor header if exists, otherwise use general header
    if (file_exists('layout/doctor_header.php')) {
        include 'layout/doctor_header.php';
    } else {
        include 'layout/header.php';
    }
    ?>

    <div class="container-fluid py-4">
        <!-- Welcome Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body bg-success text-white rounded">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h2 class="mb-1">
                                    <i class="fas fa-user-md me-2"></i>
                                    Welcome, Dr. <?php echo htmlspecialchars($_SESSION['user']); ?>!
                                </h2>
                                <p class="mb-0 opacity-75">Manage your patients and appointments</p>
                            </div>
                            <div class="col-md-4 text-md-end">
                                <div class="d-flex flex-wrap gap-2 justify-content-md-end">
                                    <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#scheduleModal">
                                        <i class="fas fa-calendar-plus me-2"></i>Set Schedule
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card stats-card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="text-primary mb-2">
                            <i class="fas fa-calendar-check fa-2x"></i>
                        </div>
                        <h4 class="mb-1"><?php echo $stats['total_appointments'] ?? 0; ?></h4>
                        <small class="text-muted">Total Appointments</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stats-card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="text-info mb-2">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                        <h4 class="mb-1"><?php echo $stats['today_appointments'] ?? 0; ?></h4>
                        <small class="text-muted">Today's Appointments</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stats-card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="text-warning mb-2">
                            <i class="fas fa-hourglass-half fa-2x"></i>
                        </div>
                        <h4 class="mb-1"><?php echo $stats['confirmed_appointments'] ?? 0; ?></h4>
                        <small class="text-muted">Confirmed</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stats-card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="text-success mb-2">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                        <h4 class="mb-1"><?php echo $stats['completed_appointments'] ?? 0; ?></h4>
                        <small class="text-muted">Completed</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Today's Appointments -->
            <div class="col-lg-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-bottom-0">
                        <h5 class="mb-0">
                            <i class="fas fa-calendar-day text-info me-2"></i>Today's Schedule
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div style="max-height: 400px; overflow-y: auto;">
                            <?php if ($today_appointments->num_rows > 0): ?>
                                <?php while ($appointment = $today_appointments->fetch_assoc()): ?>
                                <div class="appointment-card border-0 border-start border-4 border-info p-3 m-2 bg-light rounded">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">
                                                <?php echo htmlspecialchars($appointment['first_name'] . ' ' . $appointment['last_name']); ?>
                                            </h6>
                                            <p class="mb-1 text-muted small">
                                                <i class="fas fa-phone me-1"></i>
                                                <?php echo htmlspecialchars($appointment['phone']); ?>
                                            </p>
                                            <p class="mb-1 text-muted small">
                                                <i class="fas fa-stethoscope me-1"></i>
                                                <?php echo htmlspecialchars($appointment['service_type']); ?>
                                            </p>
                                            <small class="text-muted">
                                                <i class="fas fa-clock me-1"></i>
                                                <?php echo date('g:i A', strtotime($appointment['appointment_time'])); ?>
                                            </small>
                                        </div>
                                        <span class="badge bg-<?php 
                                            echo $appointment['status'] === 'confirmed' ? 'success' : 
                                                ($appointment['status'] === 'pending' ? 'warning' : 'secondary'); 
                                        ?>">
                                            <?php echo ucfirst($appointment['status']); ?>
                                        </span>
                                    </div>
                                </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <div class="text-center p-4">
                                    <i class="fas fa-calendar-times fa-2x text-muted mb-3"></i>
                                    <p class="text-muted">No appointments scheduled for today</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Appointments -->
            <div class="col-lg-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-bottom-0">
                        <h5 class="mb-0">
                            <i class="fas fa-history text-primary me-2"></i>Recent Appointments
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div style="max-height: 400px; overflow-y: auto;">
                            <?php if ($appointments->num_rows > 0): ?>
                                <?php while ($appointment = $appointments->fetch_assoc()): ?>
                                <div class="d-flex align-items-center p-3 border-bottom hover-bg-light">
                                    <div class="flex-shrink-0">
                                        <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1">
                                            <?php echo htmlspecialchars($appointment['first_name'] . ' ' . $appointment['last_name']); ?>
                                        </h6>
                                        <p class="mb-1 text-muted small">
                                            <?php echo htmlspecialchars($appointment['service_type']); ?>
                                        </p>
                                        <small class="text-muted">
                                            <i class="fas fa-calendar me-1"></i>
                                            <?php echo date('M d, Y', strtotime($appointment['appointment_date'])); ?>
                                            <i class="fas fa-clock ms-2 me-1"></i>
                                            <?php echo date('g:i A', strtotime($appointment['appointment_time'])); ?>
                                        </small>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <span class="badge bg-<?php 
                                            echo $appointment['status'] === 'completed' ? 'success' : 
                                                ($appointment['status'] === 'confirmed' ? 'info' : 
                                                ($appointment['status'] === 'pending' ? 'warning' : 'secondary')); 
                                        ?>">
                                            <?php echo ucfirst($appointment['status']); ?>
                                        </span>
                                    </div>
                                </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <div class="text-center p-4">
                                    <i class="fas fa-calendar-times fa-2x text-muted mb-3"></i>
                                    <p class="text-muted">No appointments found</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom-0">
                        <h5 class="mb-0">
                            <i class="fas fa-tools text-warning me-2"></i>Quick Actions
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <button class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#scheduleModal">
                                    <i class="fas fa-calendar-plus d-block mb-2 fa-2x"></i>
                                    Set Schedule
                                </button>
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-outline-success w-100">
                                    <i class="fas fa-notes-medical d-block mb-2 fa-2x"></i>
                                    Add Prescription
                                </button>
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-outline-info w-100">
                                    <i class="fas fa-vial d-block mb-2 fa-2x"></i>
                                    Test Results
                                </button>
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-outline-warning w-100">
                                    <i class="fas fa-chart-line d-block mb-2 fa-2x"></i>
                                    View Reports
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Schedule Modal -->
    <div class="modal fade" id="scheduleModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-calendar-plus me-2"></i>Set Your Schedule
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label class="form-label">Available Days</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="monday">
                                <label class="form-check-label" for="monday">Monday</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="tuesday">
                                <label class="form-check-label" for="tuesday">Tuesday</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="wednesday">
                                <label class="form-check-label" for="wednesday">Wednesday</label>
                            </div>
                            <!-- Add more days as needed -->
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Start Time</label>
                                    <input type="time" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">End Time</label>
                                    <input type="time" class="form-control">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Save Schedule</button>
                </div>
            </div>
        </div>
    </div>

    <?php include 'layout/footer.php'; ?>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
                        <td>2024-04-15</td>
                    </tr>
                </tbody>
            </table>
            <hr>
            <h5>Appointments</h5>
            <p class="text-muted">This section will display your upcoming appointments.</p>
            <!-- TODO: Add appointment management here -->
        </div>
    </div>
</div>
<?php include 'layout/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/main.js"></script>
</body>
</html>
