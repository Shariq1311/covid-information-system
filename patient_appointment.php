<?php
session_start();
include 'db.php';

// Check if user is patient
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'patient') {
    header("Location: login_new.php");
    exit();
}

$hospital_id = $_GET['hospital_id'] ?? null;
$message = '';

if (!$hospital_id) {
    header("Location: patient_search.php");
    exit();
}

// Get hospital information
$hospital_query = $conn->prepare("SELECT * FROM hospitals WHERE id = ? AND approval_status = 'approved'");
$hospital_query->bind_param("i", $hospital_id);
$hospital_query->execute();
$hospital = $hospital_query->get_result()->fetch_assoc();

if (!$hospital) {
    header("Location: patient_search.php");
    exit();
}

// Get patient information
$user_id = $_SESSION['user_id'] ?? 1;
$patient_query = $conn->prepare("SELECT * FROM patients WHERE user_id = ?");
$patient_query->bind_param("i", $user_id);
$patient_query->execute();
$patient = $patient_query->get_result()->fetch_assoc();

// Handle appointment booking
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $appointment_type = $_POST['appointment_type'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];
    $purpose = trim($_POST['purpose']);
    $symptoms = trim($_POST['symptoms']);
    $priority = $_POST['priority'];

    // Check if appointment slot is available
    $check_slot = $conn->prepare("SELECT COUNT(*) as count FROM appointments WHERE hospital_id = ? AND appointment_date = ? AND appointment_time = ? AND status NOT IN ('cancelled')");
    $check_slot->bind_param("iss", $hospital_id, $appointment_date, $appointment_time);
    $check_slot->execute();
    $slot_count = $check_slot->get_result()->fetch_assoc()['count'];

    if ($slot_count > 0) {
        $message = "error:This time slot is already booked. Please choose another time.";
    } else {
        // Insert appointment
        $stmt = $conn->prepare("INSERT INTO appointments (patient_id, hospital_id, appointment_type, appointment_date, appointment_time, purpose, symptoms, priority, status, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending', ?)");
        $stmt->bind_param("iissssisi", $patient['id'], $hospital_id, $appointment_type, $appointment_date, $appointment_time, $purpose, $symptoms, $priority, $user_id);

        if ($stmt->execute()) {
            $appointment_id = $conn->insert_id;
            $message = "success:Appointment booked successfully! Your appointment ID is #" . $appointment_id;
        } else {
            $message = "error:Failed to book appointment. Please try again.";
        }
    }
}

// Get available services
$services = [];
if ($hospital['covid_testing']) {
    $services[] = ['value' => 'covid_test', 'label' => 'COVID-19 Testing'];
}
if ($hospital['vaccination_available']) {
    $services[] = ['value' => 'vaccination', 'label' => 'COVID-19 Vaccination'];
}
$services[] = ['value' => 'consultation', 'label' => 'Medical Consultation'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment - <?php echo htmlspecialchars($hospital['hospital_name']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-light">
    <?php include 'layout/patient_header.php'; ?>

    <div class="container my-5">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-md-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="patient_dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="patient_search.php">Search Hospitals</a></li>
                        <li class="breadcrumb-item active">Book Appointment</li>
                    </ol>
                </nav>
                <h2><i class="fas fa-calendar-plus me-2"></i>Book Appointment</h2>
            </div>
        </div>

        <?php if ($message): ?>
            <?php list($type, $text) = explode(':', $message, 2); ?>
            <div class="alert alert-<?php echo $type == 'success' ? 'success' : 'danger'; ?> alert-dismissible fade show">
                <?php echo $text; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <!-- Hospital Information -->
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Hospital Information</h5>
                    </div>
                    <div class="card-body">
                        <h6><?php echo htmlspecialchars($hospital['hospital_name']); ?></h6>
                        <p class="card-text">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            <?php echo htmlspecialchars($hospital['address']); ?><br>
                            <?php echo htmlspecialchars($hospital['city']); ?>, <?php echo htmlspecialchars($hospital['state']); ?>
                        </p>
                        <p class="card-text">
                            <i class="fas fa-phone me-2"></i>
                            <?php echo htmlspecialchars($hospital['phone']); ?>
                        </p>
                        <p class="card-text">
                            <i class="fas fa-envelope me-2"></i>
                            <?php echo htmlspecialchars($hospital['email']); ?>
                        </p>
                        
                        <h6 class="mt-3">Available Services</h6>
                        <?php if ($hospital['covid_testing']): ?>
                            <span class="badge bg-info me-1">COVID Testing</span>
                        <?php endif; ?>
                        <?php if ($hospital['vaccination_available']): ?>
                            <span class="badge bg-success me-1">Vaccination</span>
                        <?php endif; ?>

                        <h6 class="mt-3">Capacity</h6>
                        <div class="progress mb-2">
                            <div class="progress-bar bg-success" style="width: <?php echo ($hospital['available_beds'] / max($hospital['bed_capacity'], 1)) * 100; ?>%"></div>
                        </div>
                        <small class="text-muted">
                            <?php echo $hospital['available_beds']; ?> of <?php echo $hospital['bed_capacity']; ?> beds available
                        </small>
                    </div>
                </div>
            </div>

            <!-- Appointment Form -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Book Your Appointment</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" id="appointmentForm">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Appointment Type *</label>
                                    <select name="appointment_type" class="form-select" required>
                                        <option value="">Select Service</option>
                                        <?php foreach ($services as $service): ?>
                                            <option value="<?php echo $service['value']; ?>"><?php echo $service['label']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Priority</label>
                                    <select name="priority" class="form-select">
                                        <option value="medium">Normal</option>
                                        <option value="high">High</option>
                                        <option value="urgent">Urgent</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Preferred Date *</label>
                                    <input type="date" name="appointment_date" class="form-control" required min="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d', strtotime('+30 days')); ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Preferred Time *</label>
                                    <select name="appointment_time" class="form-select" required>
                                        <option value="">Select Time</option>
                                        <option value="09:00:00">09:00 AM</option>
                                        <option value="09:30:00">09:30 AM</option>
                                        <option value="10:00:00">10:00 AM</option>
                                        <option value="10:30:00">10:30 AM</option>
                                        <option value="11:00:00">11:00 AM</option>
                                        <option value="11:30:00">11:30 AM</option>
                                        <option value="12:00:00">12:00 PM</option>
                                        <option value="14:00:00">02:00 PM</option>
                                        <option value="14:30:00">02:30 PM</option>
                                        <option value="15:00:00">03:00 PM</option>
                                        <option value="15:30:00">03:30 PM</option>
                                        <option value="16:00:00">04:00 PM</option>
                                        <option value="16:30:00">04:30 PM</option>
                                        <option value="17:00:00">05:00 PM</option>
                                    </select>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label">Purpose/Reason for Visit</label>
                                    <textarea name="purpose" class="form-control" rows="3" placeholder="Please describe the reason for your visit..."></textarea>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label">Current Symptoms (if any)</label>
                                    <textarea name="symptoms" class="form-control" rows="3" placeholder="Describe any symptoms you are experiencing..."></textarea>
                                </div>
                            </div>

                            <?php if ($patient): ?>
                                <div class="alert alert-info">
                                    <h6><i class="fas fa-info-circle me-2"></i>Your Information</h6>
                                    <p class="mb-1"><strong>Name:</strong> <?php echo htmlspecialchars($patient['full_name']); ?></p>
                                    <p class="mb-1"><strong>Phone:</strong> <?php echo htmlspecialchars($patient['phone']); ?></p>
                                    <p class="mb-0"><strong>Patient ID:</strong> <?php echo htmlspecialchars($patient['patient_id']); ?></p>
                                    <small class="text-muted">Make sure your contact information is correct. <a href="patient_profile.php">Update profile</a></small>
                                </div>
                            <?php endif; ?>

                            <div class="alert alert-warning">
                                <h6><i class="fas fa-exclamation-triangle me-2"></i>Important Notes</h6>
                                <ul class="mb-0">
                                    <li>Please arrive 15 minutes before your scheduled appointment</li>
                                    <li>Carry a valid photo ID and any relevant medical documents</li>
                                    <li>Wear a mask and follow COVID-19 safety protocols</li>
                                    <li>Appointment confirmation will be sent via SMS/Email</li>
                                </ul>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-calendar-check me-2"></i>Book Appointment
                                </button>
                                <a href="patient_search.php" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Back to Search
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Information -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card bg-light">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-clock me-2"></i>Operating Hours & Guidelines
                        </h5>
                        <div class="row">
                            <div class="col-md-6">
                                <h6>General Operating Hours</h6>
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-clock text-primary me-2"></i>Monday - Friday: 9:00 AM - 5:00 PM</li>
                                    <li><i class="fas fa-clock text-primary me-2"></i>Saturday: 9:00 AM - 2:00 PM</li>
                                    <li><i class="fas fa-clock text-primary me-2"></i>Sunday: Emergency Only</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6>COVID-19 Testing Guidelines</h6>
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-check text-success me-2"></i>RT-PCR results: 24-48 hours</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Rapid Antigen: 30 minutes</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Fasting not required</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'layout/footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Form validation
        document.getElementById('appointmentForm').addEventListener('submit', function(e) {
            const appointmentDate = document.querySelector('input[name="appointment_date"]').value;
            const appointmentTime = document.querySelector('select[name="appointment_time"]').value;
            
            if (!appointmentDate || !appointmentTime) {
                e.preventDefault();
                alert('Please select both date and time for your appointment.');
                return;
            }

            // Check if appointment date is not in the past
            const selectedDate = new Date(appointmentDate);
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            if (selectedDate < today) {
                e.preventDefault();
                alert('Please select a future date for your appointment.');
                return;
            }
        });

        // Auto-select service based on URL parameter
        const urlParams = new URLSearchParams(window.location.search);
        const service = urlParams.get('service');
        if (service) {
            const serviceSelect = document.querySelector('select[name="appointment_type"]');
            if (service === 'testing') {
                serviceSelect.value = 'covid_test';
            } else if (service === 'vaccination') {
                serviceSelect.value = 'vaccination';
            }
        }
    </script>
</body>
</html>
