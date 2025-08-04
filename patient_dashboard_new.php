<?php
session_start();
include 'db.php';

// Check if user is patient
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'patient') {
    header("Location: login_new.php");
    exit();
}

// Get or create patient profile
$user_id = $_SESSION['user_id'] ?? 1;
$patient_query = $conn->prepare("SELECT * FROM patients WHERE user_id = ?");
$patient_query->bind_param("i", $user_id);
$patient_query->execute();
$patient = $patient_query->get_result()->fetch_assoc();

// If no patient profile exists, create one
if (!$patient) {
    $user_info = $conn->prepare("SELECT username, email, full_name, phone FROM users WHERE id = ?");
    $user_info->bind_param("i", $user_id);
    $user_info->execute();
    $user_data = $user_info->get_result()->fetch_assoc();
    
    if ($user_data) {
        // Create basic patient profile
        $patient_id = 'P' . str_pad($user_id, 6, '0', STR_PAD_LEFT);
        $create_patient = $conn->prepare("INSERT INTO patients (user_id, patient_id, full_name, phone, email) VALUES (?, ?, ?, ?, ?)");
        $create_patient->bind_param("issss", $user_id, $patient_id, $user_data['full_name'], $user_data['phone'], $user_data['email']);
        $create_patient->execute();
        
        // Fetch the newly created patient
        $patient_query->execute();
        $patient = $patient_query->get_result()->fetch_assoc();
    }
}

// Get dashboard statistics
$stats = [];

// Recent appointments
$recent_appointments = $conn->query("
    SELECT a.*, h.hospital_name, h.phone as hospital_phone
    FROM appointments a 
    JOIN hospitals h ON a.hospital_id = h.id 
    WHERE a.patient_id = " . ($patient['id'] ?? 0) . "
    ORDER BY a.appointment_date DESC, a.appointment_time DESC 
    LIMIT 5
");

// Recent test results
$recent_tests = $conn->query("
    SELECT ct.*, h.hospital_name
    FROM covid_tests ct 
    JOIN hospitals h ON ct.hospital_id = h.id 
    WHERE ct.patient_id = " . ($patient['id'] ?? 0) . "
    ORDER BY ct.test_date DESC 
    LIMIT 3
");

// Vaccination history
$vaccinations = $conn->query("
    SELECT v.*, h.hospital_name, vac.vaccine_name
    FROM vaccinations v 
    JOIN hospitals h ON v.hospital_id = h.id 
    JOIN vaccines vac ON v.vaccine_id = vac.id
    WHERE v.patient_id = " . ($patient['id'] ?? 0) . " AND v.status = 'completed'
    ORDER BY v.vaccination_date DESC
");

// Count statistics
$appointment_count = $conn->query("SELECT COUNT(*) as count FROM appointments WHERE patient_id = " . ($patient['id'] ?? 0))->fetch_assoc()['count'];
$test_count = $conn->query("SELECT COUNT(*) as count FROM covid_tests WHERE patient_id = " . ($patient['id'] ?? 0))->fetch_assoc()['count'];
$vaccination_count = $conn->query("SELECT COUNT(*) as count FROM vaccinations WHERE patient_id = " . ($patient['id'] ?? 0) . " AND status = 'completed'")->fetch_assoc()['count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Dashboard - COVID Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-light">
    <?php include 'layout/patient_header.php'; ?>

    <div class="container my-4">
        <!-- Welcome Header -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card bg-gradient bg-primary text-white">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h2 class="mb-1">Welcome, <?php echo htmlspecialchars($patient['full_name'] ?? $_SESSION['user']); ?>!</h2>
                                <p class="mb-0">
                                    Patient ID: <?php echo htmlspecialchars($patient['patient_id'] ?? 'Not Assigned'); ?>
                                    <?php if ($patient && $patient['date_of_birth']): ?>
                                        | Age: <?php echo date_diff(date_create($patient['date_of_birth']), date_create('now'))->y; ?> years
                                    <?php endif; ?>
                                </p>
                            </div>
                            <div class="col-md-4 text-end">
                                <a href="patient_profile.php" class="btn btn-light">
                                    <i class="fas fa-user-edit me-2"></i>Update Profile
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Total Appointments</h6>
                                <h2><?php echo $appointment_count; ?></h2>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-calendar fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">COVID Tests</h6>
                                <h2><?php echo $test_count; ?></h2>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-vial fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Vaccinations</h6>
                                <h2><?php echo $vaccination_count; ?></h2>
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
                        <h5><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <a href="patient_search.php" class="btn btn-primary w-100 mb-2">
                                    <i class="fas fa-search me-2"></i>Find Hospitals
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="patient_search.php?service=testing" class="btn btn-info w-100 mb-2">
                                    <i class="fas fa-vial me-2"></i>Book COVID Test
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="patient_search.php?service=vaccination" class="btn btn-success w-100 mb-2">
                                    <i class="fas fa-syringe me-2"></i>Book Vaccination
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="patient_results.php" class="btn btn-secondary w-100 mb-2">
                                    <i class="fas fa-file-medical me-2"></i>View Results
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Health Guidelines -->
        <div class="row">
            <div class="col-md-12">
                <div class="card bg-light">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-shield-alt me-2"></i>COVID-19 Health Guidelines
                        </h5>
                        <div class="row">
                            <div class="col-md-3 text-center">
                                <i class="fas fa-head-side-mask fa-3x text-primary mb-2"></i>
                                <h6>Wear Mask</h6>
                                <p class="small">Always wear a mask in public places</p>
                            </div>
                            <div class="col-md-3 text-center">
                                <i class="fas fa-hands-wash fa-3x text-info mb-2"></i>
                                <h6>Wash Hands</h6>
                                <p class="small">Wash hands frequently with soap</p>
                            </div>
                            <div class="col-md-3 text-center">
                                <i class="fas fa-people-arrows fa-3x text-warning mb-2"></i>
                                <h6>Social Distance</h6>
                                <p class="small">Maintain 6 feet distance</p>
                            </div>
                            <div class="col-md-3 text-center">
                                <i class="fas fa-syringe fa-3x text-success mb-2"></i>
                                <h6>Get Vaccinated</h6>
                                <p class="small">Complete your vaccination</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'layout/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
