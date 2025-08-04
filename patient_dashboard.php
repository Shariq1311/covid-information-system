<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'patient') {
    header("Location: login_new.php");
    exit();
}

$patient_id = $_SESSION['user_id'];

// Fetch patient information
$stmt = $conn->prepare("SELECT * FROM patients WHERE user_id = ?");
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$patient = $stmt->get_result()->fetch_assoc();

// Fetch recent appointments
$stmt = $conn->prepare("
    SELECT a.*, h.hospital_name, CONCAT(h.address, ', ', h.city, ', ', h.state) as location 
    FROM appointments a 
    JOIN hospitals h ON a.hospital_id = h.id 
    WHERE a.patient_id = ? 
    ORDER BY a.appointment_date DESC, a.appointment_time DESC 
    LIMIT 5
");
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$appointments = $stmt->get_result();

// Fetch COVID test results
$stmt = $conn->prepare("
    SELECT ct.*, h.hospital_name 
    FROM covid_tests ct 
    JOIN hospitals h ON ct.hospital_id = h.id 
    WHERE ct.patient_id = ? 
    ORDER BY ct.test_date DESC 
    LIMIT 5
");
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$test_results = $stmt->get_result();

// Fetch vaccination records
$stmt = $conn->prepare("
    SELECT v.*, vac.vaccine_name, h.hospital_name 
    FROM vaccinations v 
    JOIN vaccines vac ON v.vaccine_id = vac.id 
    JOIN hospitals h ON v.hospital_id = h.id 
    WHERE v.patient_id = ? 
    ORDER BY v.vaccination_date DESC
");
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$vaccinations = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Patient Dashboard - COVID-19 Portal</title>
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
        .recent-activity {
            max-height: 400px;
            overflow-y: auto;
        }
    </style>
</head>
<body class="bg-light">
    <?php include 'layout/patient_header.php'; ?>

    <div class="container-fluid py-4">
        <!-- Welcome Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body bg-primary text-white rounded">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h2 class="mb-1">
                                    <i class="fas fa-user-circle me-2"></i>
                                    Welcome back, <?php echo htmlspecialchars($patient['first_name'] ?? $_SESSION['user']); ?>!
                                </h2>
                                <p class="mb-0 opacity-75">Manage your COVID-19 healthcare journey</p>
                            </div>
                            <div class="col-md-4 text-md-end">
                                <a href="patient_search.php" class="btn btn-light btn-lg">
                                    <i class="fas fa-plus me-2"></i>Book New Appointment
                                </a>
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
                        <div class="text-info mb-2">
                            <i class="fas fa-calendar-check fa-2x"></i>
                        </div>
                        <h4 class="mb-1"><?php echo $appointments->num_rows; ?></h4>
                        <small class="text-muted">Total Appointments</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stats-card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="text-warning mb-2">
                            <i class="fas fa-vial fa-2x"></i>
                        </div>
                        <h4 class="mb-1"><?php echo $test_results->num_rows; ?></h4>
                        <small class="text-muted">COVID Tests</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stats-card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="text-success mb-2">
                            <i class="fas fa-syringe fa-2x"></i>
                        </div>
                        <h4 class="mb-1"><?php echo $vaccinations->num_rows; ?></h4>
                        <small class="text-muted">Vaccinations</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stats-card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="text-danger mb-2">
                            <i class="fas fa-shield-virus fa-2x"></i>
                        </div>
                        <h4 class="mb-1">
                            <?php 
                            $vaccination_status = $vaccinations->num_rows > 0 ? 'Protected' : 'Pending';
                            echo $vaccination_status;
                            ?>
                        </h4>
                        <small class="text-muted">Protection Status</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Recent Appointments -->
            <div class="col-lg-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-bottom-0 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-calendar-alt text-info me-2"></i>Recent Appointments
                        </h5>
                        <a href="patient_search.php" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-plus me-1"></i>Book New
                        </a>
                    </div>
                    <div class="card-body p-0">
                        <div class="recent-activity">
                            <?php if ($appointments->num_rows > 0): ?>
                                <?php while ($appointment = $appointments->fetch_assoc()): ?>
                                <div class="d-flex align-items-center p-3 border-bottom">
                                    <div class="flex-shrink-0">
                                        <div class="bg-info-subtle text-info rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="fas fa-hospital"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1"><?php echo htmlspecialchars($appointment['hospital_name']); ?></h6>
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
                                    <p class="text-muted">No appointments yet</p>
                                    <a href="patient_search.php" class="btn btn-primary">Book Your First Appointment</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Test Results -->
            <div class="col-lg-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-bottom-0">
                        <h5 class="mb-0">
                            <i class="fas fa-vial text-warning me-2"></i>COVID Test Results
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="recent-activity">
                            <?php if ($test_results->num_rows > 0): ?>
                                <?php while ($test = $test_results->fetch_assoc()): ?>
                                <div class="d-flex align-items-center p-3 border-bottom">
                                    <div class="flex-shrink-0">
                                        <div class="bg-<?php echo $test['result'] === 'negative' ? 'success' : 'danger'; ?>-subtle 
                                                    text-<?php echo $test['result'] === 'negative' ? 'success' : 'danger'; ?> 
                                                    rounded-circle d-flex align-items-center justify-content-center" 
                                             style="width: 40px; height: 40px;">
                                            <i class="fas fa-<?php echo $test['result'] === 'negative' ? 'check' : 'times'; ?>"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1"><?php echo htmlspecialchars($test['test_type']); ?></h6>
                                        <p class="mb-1 text-muted small">
                                            <?php echo htmlspecialchars($test['hospital_name']); ?>
                                        </p>
                                        <small class="text-muted">
                                            <i class="fas fa-calendar me-1"></i>
                                            <?php echo date('M d, Y', strtotime($test['test_date'])); ?>
                                        </small>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <span class="badge bg-<?php echo $test['result'] === 'negative' ? 'success' : 'danger'; ?>">
                                            <?php echo ucfirst($test['result']); ?>
                                        </span>
                                    </div>
                                </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <div class="text-center p-4">
                                    <i class="fas fa-vial fa-2x text-muted mb-3"></i>
                                    <p class="text-muted">No test results available</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Vaccination Records -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom-0">
                        <h5 class="mb-0">
                            <i class="fas fa-syringe text-success me-2"></i>Vaccination History
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if ($vaccinations->num_rows > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Vaccine</th>
                                            <th>Hospital</th>
                                            <th>Date</th>
                                            <th>Dose</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($vaccination = $vaccinations->fetch_assoc()): ?>
                                        <tr>
                                            <td>
                                                <strong><?php echo htmlspecialchars($vaccination['vaccine_name']); ?></strong>
                                            </td>
                                            <td><?php echo htmlspecialchars($vaccination['hospital_name']); ?></td>
                                            <td><?php echo date('M d, Y', strtotime($vaccination['vaccination_date'])); ?></td>
                                            <td>
                                                <span class="badge bg-info">
                                                    Dose <?php echo $vaccination['dose_number']; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check me-1"></i>Completed
                                                </span>
                                            </td>
                                        </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <i class="fas fa-syringe fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No Vaccination Records</h5>
                                <p class="text-muted">Book your COVID-19 vaccination to stay protected</p>
                                <a href="patient_search.php?service=vaccination" class="btn btn-success">
                                    <i class="fas fa-syringe me-2"></i>Book Vaccination
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'layout/footer.php'; ?>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
                        <td>Follow-up visit</td>
                    </tr>
                </tbody>
            </table>
            <hr>
            <h5>Upcoming Appointments</h5>
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
