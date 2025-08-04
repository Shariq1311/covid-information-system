<?php
session_start();
include 'db.php';

// Check if user is patient
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'patient') {
    header("Location: login_new.php");
    exit();
}

// Get patient information
$user_id = $_SESSION['user_id'] ?? 1;
$patient_query = $conn->prepare("SELECT * FROM patients WHERE user_id = ?");
$patient_query->bind_param("i", $user_id);
$patient_query->execute();
$patient = $patient_query->get_result()->fetch_assoc();

// Search functionality
$search_city = $_GET['city'] ?? '';
$search_service = $_GET['service'] ?? '';
$search_name = $_GET['name'] ?? '';

$search_query = "SELECT h.*, 
                        CASE 
                            WHEN h.covid_testing = 1 AND h.vaccination_available = 1 THEN 'Both' 
                            WHEN h.covid_testing = 1 THEN 'COVID Testing' 
                            WHEN h.vaccination_available = 1 THEN 'Vaccination' 
                            ELSE 'None' 
                        END as services_offered
                 FROM hospitals h 
                 WHERE h.approval_status = 'approved'";

$params = [];
$types = '';

if ($search_city) {
    $search_query .= " AND h.city LIKE ?";
    $params[] = "%$search_city%";
    $types .= 's';
}

if ($search_service == 'testing') {
    $search_query .= " AND h.covid_testing = 1";
} elseif ($search_service == 'vaccination') {
    $search_query .= " AND h.vaccination_available = 1";
}

if ($search_name) {
    $search_query .= " AND h.hospital_name LIKE ?";
    $params[] = "%$search_name%";
    $types .= 's';
}

$search_query .= " ORDER BY h.hospital_name";

$stmt = $conn->prepare($search_query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$hospitals = $stmt->get_result();

// Get all cities for filter
$cities = $conn->query("SELECT DISTINCT city FROM hospitals WHERE approval_status = 'approved' ORDER BY city");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Hospitals - COVID Portal</title>
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
                <h2 class="text-center mb-4">
                    <i class="fas fa-search me-2"></i>Search COVID-19 Testing & Vaccination Centers
                </h2>
            </div>
        </div>

        <!-- Search Filters -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Hospital Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Enter hospital name..." value="<?php echo htmlspecialchars($search_name); ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">City</label>
                        <select name="city" class="form-select">
                            <option value="">All Cities</option>
                            <?php while ($city = $cities->fetch_assoc()): ?>
                                <option value="<?php echo htmlspecialchars($city['city']); ?>" <?php echo $search_city == $city['city'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($city['city']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Service Required</label>
                        <select name="service" class="form-select">
                            <option value="">All Services</option>
                            <option value="testing" <?php echo $search_service == 'testing' ? 'selected' : ''; ?>>COVID Testing</option>
                            <option value="vaccination" <?php echo $search_service == 'vaccination' ? 'selected' : ''; ?>>Vaccination</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-2"></i>Search
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Search Results -->
        <div class="row">
            <?php if ($hospitals && $hospitals->num_rows > 0): ?>
                <?php while ($hospital = $hospitals->fetch_assoc()): ?>
                    <div class="col-md-6 mb-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <h5 class="card-title text-primary">
                                        <?php echo htmlspecialchars($hospital['hospital_name']); ?>
                                    </h5>
                                    <div class="text-end">
                                        <?php if ($hospital['covid_testing']): ?>
                                            <span class="badge bg-info mb-1">COVID Testing</span><br>
                                        <?php endif; ?>
                                        <?php if ($hospital['vaccination_available']): ?>
                                            <span class="badge bg-success">Vaccination</span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <p class="card-text mb-2">
                                        <i class="fas fa-map-marker-alt text-muted me-2"></i>
                                        <?php echo htmlspecialchars($hospital['address']); ?><br>
                                        <span class="ms-3"><?php echo htmlspecialchars($hospital['city']); ?>, <?php echo htmlspecialchars($hospital['state']); ?></span>
                                    </p>
                                    <p class="card-text mb-2">
                                        <i class="fas fa-phone text-muted me-2"></i>
                                        <?php echo htmlspecialchars($hospital['phone']); ?>
                                    </p>
                                    <?php if ($hospital['contact_person']): ?>
                                        <p class="card-text mb-2">
                                            <i class="fas fa-user text-muted me-2"></i>
                                            Contact: <?php echo htmlspecialchars($hospital['contact_person']); ?>
                                        </p>
                                    <?php endif; ?>
                                </div>

                                <?php if ($hospital['facilities']): ?>
                                    <div class="mb-3">
                                        <small class="text-muted">
                                            <strong>Facilities:</strong> <?php echo htmlspecialchars($hospital['facilities']); ?>
                                        </small>
                                    </div>
                                <?php endif; ?>

                                <div class="row">
                                    <div class="col-6">
                                        <small class="text-muted">
                                            <i class="fas fa-bed me-1"></i>
                                            Beds: <?php echo $hospital['available_beds']; ?>/<?php echo $hospital['bed_capacity']; ?>
                                        </small>
                                    </div>
                                    <div class="col-6 text-end">
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#detailsModal<?php echo $hospital['id']; ?>">
                                                <i class="fas fa-info-circle"></i> Details
                                            </button>
                                            <a href="patient_appointment.php?hospital_id=<?php echo $hospital['id']; ?>" class="btn btn-sm btn-primary">
                                                <i class="fas fa-calendar-plus"></i> Book
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Hospital Details Modal -->
                    <div class="modal fade" id="detailsModal<?php echo $hospital['id']; ?>" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title"><?php echo htmlspecialchars($hospital['hospital_name']); ?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6>Contact Information</h6>
                                            <p><strong>Address:</strong><br><?php echo htmlspecialchars($hospital['address']); ?><br>
                                            <?php echo htmlspecialchars($hospital['city']); ?>, <?php echo htmlspecialchars($hospital['state']); ?>
                                            <?php if ($hospital['pincode']): ?> - <?php echo htmlspecialchars($hospital['pincode']); ?><?php endif; ?>
                                            </p>
                                            <p><strong>Phone:</strong> <?php echo htmlspecialchars($hospital['phone']); ?></p>
                                            <p><strong>Email:</strong> <?php echo htmlspecialchars($hospital['email']); ?></p>
                                            <?php if ($hospital['contact_person']): ?>
                                                <p><strong>Contact Person:</strong> <?php echo htmlspecialchars($hospital['contact_person']); ?></p>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>Services & Capacity</h6>
                                            <p><strong>Services:</strong><br>
                                            <?php if ($hospital['covid_testing']): ?>
                                                <span class="badge bg-info me-1">COVID-19 Testing</span><br>
                                            <?php endif; ?>
                                            <?php if ($hospital['vaccination_available']): ?>
                                                <span class="badge bg-success me-1">COVID-19 Vaccination</span><br>
                                            <?php endif; ?>
                                            </p>
                                            <p><strong>Bed Capacity:</strong> <?php echo $hospital['bed_capacity']; ?></p>
                                            <p><strong>Available Beds:</strong> 
                                                <span class="<?php echo $hospital['available_beds'] > 0 ? 'text-success' : 'text-danger'; ?>">
                                                    <?php echo $hospital['available_beds']; ?>
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                    <?php if ($hospital['facilities']): ?>
                                        <div class="row">
                                            <div class="col-12">
                                                <h6>Facilities</h6>
                                                <p><?php echo htmlspecialchars($hospital['facilities']); ?></p>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($hospital['specializations']): ?>
                                        <div class="row">
                                            <div class="col-12">
                                                <h6>Specializations</h6>
                                                <p><?php echo htmlspecialchars($hospital['specializations']); ?></p>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <a href="patient_appointment.php?hospital_id=<?php echo $hospital['id']; ?>" class="btn btn-primary">
                                        <i class="fas fa-calendar-plus me-2"></i>Book Appointment
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-search fa-4x text-muted mb-3"></i>
                            <h4 class="text-muted">No hospitals found</h4>
                            <p class="text-muted">Try adjusting your search criteria to find hospitals in your area.</p>
                            <a href="patient_search.php" class="btn btn-primary">Clear Filters</a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Quick Info Section -->
        <div class="row mt-5">
            <div class="col-md-12">
                <div class="card bg-light">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-info-circle me-2"></i>Important Information
                        </h5>
                        <div class="row">
                            <div class="col-md-4">
                                <h6><i class="fas fa-vial text-info me-2"></i>COVID-19 Testing</h6>
                                <ul class="list-unstyled">
                                    <li>• RT-PCR Test: 24-48 hours result</li>
                                    <li>• Rapid Antigen: 30 minutes result</li>
                                    <li>• Carry valid ID proof</li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <h6><i class="fas fa-syringe text-success me-2"></i>Vaccination</h6>
                                <ul class="list-unstyled">
                                    <li>• Bring vaccination card</li>
                                    <li>• Wait 15 minutes post-vaccination</li>
                                    <li>• Schedule second dose if required</li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <h6><i class="fas fa-calendar text-primary me-2"></i>Appointment Tips</h6>
                                <ul class="list-unstyled">
                                    <li>• Book in advance</li>
                                    <li>• Arrive 15 minutes early</li>
                                    <li>• Follow safety protocols</li>
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
</body>
</html>
