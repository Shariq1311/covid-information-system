<?php
$page_title = "Find Hospitals - COVID-19 Portal";
include 'db.php';

// Initialize variables
$search_city = $_GET['city'] ?? '';
$search_service = $_GET['service'] ?? '';
$search_name = $_GET['name'] ?? '';
$hospitals = null;
$cities_result = null;
$error_message = '';

// Check database connection
if ($conn->connect_error) {
    $error_message = "Database connection failed: " . $conn->connect_error;
} else {
    try {
        // Get all available cities for filter dropdown
        $cities_query = "SELECT DISTINCT city FROM hospitals WHERE approval_status = 'approved' ORDER BY city";
        $cities_result = $conn->query($cities_query);

        // Build search query
        $search_query = "SELECT h.*, 
                                CASE 
                                    WHEN h.covid_testing = 1 AND h.vaccination_available = 1 THEN 'Both Testing & Vaccination' 
                                    WHEN h.covid_testing = 1 THEN 'COVID Testing' 
                                    WHEN h.vaccination_available = 1 THEN 'Vaccination' 
                                    ELSE 'General Services' 
                                END as services_offered
                         FROM hospitals h 
                         WHERE h.approval_status = 'approved'";

        $params = [];
        $types = '';

        if ($search_city) {
            $search_query .= " AND (h.city LIKE ? OR h.state LIKE ?)";
            $params[] = "%$search_city%";
            $params[] = "%$search_city%";
            $types .= 'ss';
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

        $search_query .= " ORDER BY h.city, h.hospital_name";

        $stmt = $conn->prepare($search_query);
        if ($stmt) {
            if (!empty($params)) {
                $stmt->bind_param($types, ...$params);
            }
            $stmt->execute();
            $hospitals = $stmt->get_result();
        } else {
            $error_message = "Query preparation failed: " . $conn->error;
        }
    } catch (Exception $e) {
        $error_message = "An error occurred: " . $e->getMessage();
    }
}

$additional_css = "
<style>
    .search-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 60px 0;
    }
    .hospital-card {
        transition: transform 0.3s ease;
        border: none;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }
    .hospital-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    .service-badge {
        display: inline-block;
        padding: 4px 8px;
        margin: 2px;
        border-radius: 12px;
        font-size: 0.8rem;
        font-weight: 500;
    }
    .badge-testing {
        background-color: #e3f2fd;
        color: #1976d2;
    }
    .badge-vaccination {
        background-color: #e8f5e8;
        color: #2e7d32;
    }
    .badge-both {
        background-color: #fff3e0;
        color: #f57c00;
    }
</style>
";

include('./layout/app_header.php'); 
?>

<!-- Search Section -->
<section class="search-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-5 fw-bold mb-4">Find Healthcare Providers</h1>
                <p class="lead mb-5">Search for hospitals and healthcare facilities offering COVID-19 services across Pakistan</p>
                
                <div class="card shadow-lg">
                    <div class="card-body p-4">
                        <form method="GET" action="" class="text-dark">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="name" class="form-label">Hospital Name</label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="name" 
                                           name="name" 
                                           placeholder="Enter hospital name..."
                                           value="<?php echo htmlspecialchars($search_name); ?>">
                                </div>
                                <div class="col-md-4">
                                    <label for="city" class="form-label">City/State</label>
                                    <select class="form-select" id="city" name="city">
                                        <option value="">All Cities</option>
                                        <?php 
                                        if ($cities_result && $cities_result->num_rows > 0) {
                                            while ($city = $cities_result->fetch_assoc()) {
                                                $selected = ($search_city == $city['city']) ? 'selected' : '';
                                                echo '<option value="' . htmlspecialchars($city['city']) . '" ' . $selected . '>';
                                                echo htmlspecialchars($city['city']);
                                                echo '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="service" class="form-label">Service Type</label>
                                    <select class="form-select" id="service" name="service">
                                        <option value="">All Services</option>
                                        <option value="testing" <?php echo $search_service == 'testing' ? 'selected' : ''; ?>>COVID Testing</option>
                                        <option value="vaccination" <?php echo $search_service == 'vaccination' ? 'selected' : ''; ?>>Vaccination</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-12 text-center">
                                    <button type="submit" class="btn btn-primary btn-lg px-5 me-3">
                                        <i class="fas fa-search me-2"></i>Search Hospitals
                                    </button>
                                    <a href="?" class="btn btn-outline-secondary btn-lg px-4">
                                        <i class="fas fa-refresh me-2"></i>Clear Filters
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Results Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <?php if ($error_message): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <?php echo htmlspecialchars($error_message); ?>
                    </div>
                <?php else: ?>
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3>
                            <?php 
                            if ($hospitals && $hospitals->num_rows > 0) {
                                $total_hospitals = $hospitals->num_rows;
                                echo "Found $total_hospitals Hospital" . ($total_hospitals > 1 ? 's' : '');
                            } else {
                                echo 'No Hospitals Found';
                            }
                            ?>
                        </h3>
                        <div class="text-muted">
                            <?php if ($search_city || $search_service || $search_name): ?>
                                <small>
                                    Filtered by: 
                                    <?php 
                                    $filters = [];
                                    if ($search_name) $filters[] = "Name: '$search_name'";
                                    if ($search_city) $filters[] = "City: '$search_city'";
                                    if ($search_service) $filters[] = "Service: " . ucfirst($search_service);
                                    echo implode(', ', $filters);
                                    ?>
                                </small>
                            <?php endif; ?>
                        </div>
                    </div>

                <?php if ($hospitals && $hospitals->num_rows > 0): ?>
                    <div class="row">
                        <?php while ($hospital = $hospitals->fetch_assoc()): ?>
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card hospital-card h-100">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <h5 class="card-title text-primary mb-0">
                                                <?php echo htmlspecialchars($hospital['hospital_name']); ?>
                                            </h5>
                                            <?php if ($hospital['services_offered'] == 'Both Testing & Vaccination'): ?>
                                                <span class="service-badge badge-both">Both Services</span>
                                            <?php elseif ($hospital['services_offered'] == 'COVID Testing'): ?>
                                                <span class="service-badge badge-testing">Testing</span>
                                            <?php elseif ($hospital['services_offered'] == 'Vaccination'): ?>
                                                <span class="service-badge badge-vaccination">Vaccination</span>
                                            <?php endif; ?>
                                        </div>

                                        <div class="mb-3">
                                            <p class="card-text mb-2">
                                                <i class="fas fa-map-marker-alt text-danger me-2"></i>
                                                <?php echo htmlspecialchars($hospital['address']); ?>
                                            </p>
                                            <p class="card-text mb-2">
                                                <i class="fas fa-city text-info me-2"></i>
                                                <?php echo htmlspecialchars($hospital['city'] . ', ' . $hospital['state']); ?>
                                            </p>
                                            <p class="card-text mb-2">
                                                <i class="fas fa-phone text-success me-2"></i>
                                                <?php echo htmlspecialchars($hospital['phone'] ?? 'Contact via portal'); ?>
                                            </p>
                                        </div>

                                        <div class="mb-3">
                                            <h6 class="text-muted mb-2">Available Services:</h6>
                                            <div>
                                                <?php if ($hospital['covid_testing']): ?>
                                                    <span class="badge bg-info me-1 mb-1">
                                                        <i class="fas fa-vial me-1"></i>COVID Testing
                                                    </span>
                                                <?php endif; ?>
                                                <?php if ($hospital['vaccination_available']): ?>
                                                    <span class="badge bg-success me-1 mb-1">
                                                        <i class="fas fa-syringe me-1"></i>Vaccination
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <div class="row text-center">
                                                <div class="col-6">
                                                    <small class="text-muted">Total Beds</small>
                                                    <div class="fw-bold"><?php echo $hospital['bed_capacity'] ?? 'N/A'; ?></div>
                                                </div>
                                                <div class="col-6">
                                                    <small class="text-muted">Available</small>
                                                    <div class="fw-bold text-success"><?php echo $hospital['available_beds'] ?? 'N/A'; ?></div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-grid gap-2">
                                            <a href="register.php" class="btn btn-primary">
                                                <i class="fas fa-calendar-plus me-2"></i>Book Appointment
                                            </a>
                                            <small class="text-muted text-center">
                                                <i class="fas fa-info-circle me-1"></i>
                                                Registration required to book appointments
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <i class="fas fa-search fa-4x text-muted"></i>
                        </div>
                        <h4 class="text-muted mb-3">No hospitals found</h4>
                        <p class="text-muted mb-4">Try adjusting your search criteria or browse all available hospitals.</p>
                        <a href="?" class="btn btn-primary">
                            <i class="fas fa-list me-2"></i>View All Hospitals
                        </a>
                    </div>
                <?php endif; ?>
                <?php endif; // End of error check ?>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h3 class="mb-3">Ready to Book Your Appointment?</h3>
                <p class="text-muted mb-4">Join our platform to book COVID-19 tests and vaccinations at verified healthcare facilities across Pakistan.</p>
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <a href="register.php" class="btn btn-primary btn-lg px-4">
                        <i class="fas fa-user-plus me-2"></i>Register as Patient
                    </a>
                    <a href="hospital_register.php" class="btn btn-outline-primary btn-lg px-4">
                        <i class="fas fa-hospital me-2"></i>Register Your Hospital
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php 
$additional_js = "
<script>
    // Add smooth scrolling for anchor links
    document.querySelectorAll('a[href^=\"#\"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });

    // Add loading state to search form
    document.querySelector('form').addEventListener('submit', function() {
        const submitBtn = this.querySelector('button[type=\"submit\"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class=\"fas fa-spinner fa-spin me-2\"></i>Searching...';
        submitBtn.disabled = true;
        
        // Re-enable after 3 seconds in case of issues
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 3000);
    });
</script>
";

include('./layout/app_footer.php'); 
?>
