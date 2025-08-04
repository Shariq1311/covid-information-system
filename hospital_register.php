<?php
session_start();
include 'db.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $hospital_name = trim($_POST['hospital_name']);
    $registration_number = trim($_POST['registration_number']);
    $license_number = trim($_POST['license_number']);
    $contact_person = trim($_POST['contact_person']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $city = trim($_POST['city']);
    $state = trim($_POST['state']);
    $pincode = trim($_POST['pincode']);
    $bed_capacity = intval($_POST['bed_capacity']);
    $available_beds = intval($_POST['available_beds']);
    $covid_testing = isset($_POST['covid_testing']) ? 1 : 0;
    $vaccination_available = isset($_POST['vaccination_available']) ? 1 : 0;
    $facilities = trim($_POST['facilities']);
    $specializations = trim($_POST['specializations']);

    // Check if username/email already exists
    $check = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $check->bind_param("ss", $username, $email);
    $check->execute();
    $check->store_result();
    
    if ($check->num_rows > 0) {
        $message = "Username or email already exists.";
    } else {
        // Start transaction
        $conn->begin_transaction();
        
        try {
            // Insert user
            $user_stmt = $conn->prepare("INSERT INTO users (username, email, password, role, full_name, phone, status) VALUES (?, ?, ?, 'hospital', ?, ?, 'pending')");
            $user_stmt->bind_param("sssss", $username, $email, $password, $contact_person, $phone);
            
            if (!$user_stmt->execute()) {
                throw new Exception("Failed to create user account");
            }
            
            $user_id = $conn->insert_id;
            
            // Insert hospital
            $hospital_stmt = $conn->prepare("INSERT INTO hospitals (user_id, hospital_name, registration_number, license_number, contact_person, phone, email, address, city, state, pincode, bed_capacity, available_beds, covid_testing, vaccination_available, facilities, specializations, approval_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')");
            
            $hospital_stmt->bind_param("issssssssssiiiiss", $user_id, $hospital_name, $registration_number, $license_number, $contact_person, $phone, $email, $address, $city, $state, $pincode, $bed_capacity, $available_beds, $covid_testing, $vaccination_available, $facilities, $specializations);
            
            if (!$hospital_stmt->execute()) {
                throw new Exception("Failed to create hospital profile");
            }
            
            // Commit transaction
            $conn->commit();
            
            $_SESSION['user'] = $username;
            $_SESSION['user_id'] = $user_id;
            $_SESSION['role'] = 'hospital';
            
            header("Location: hospital_dashboard.php");
            exit();
            
        } catch (Exception $e) {
            // Rollback transaction
            $conn->rollback();
            $message = "Registration failed: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Registration - COVID Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-light">
    <?php include 'layout/header.php'; ?>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-hospital me-2"></i>Hospital Registration
                        </h4>
                    </div>
                    <div class="card-body">
                        <?php if ($message): ?>
                            <div class="alert alert-<?php echo strpos($message, 'success') !== false ? 'success' : 'danger'; ?>">
                                <?php echo $message; ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST">
                            <!-- Login Information -->
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="text-primary mb-3">
                                        <i class="fas fa-user-circle me-2"></i>Login Information
                                    </h5>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Username *</label>
                                    <input type="text" name="username" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email *</label>
                                    <input type="email" name="email" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Password *</label>
                                    <input type="password" name="password" class="form-control" required minlength="6">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Confirm Password *</label>
                                    <input type="password" name="confirm_password" class="form-control" required minlength="6">
                                </div>
                            </div>

                            <hr>

                            <!-- Hospital Information -->
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="text-primary mb-3">
                                        <i class="fas fa-hospital me-2"></i>Hospital Information
                                    </h5>
                                </div>
                                <div class="col-md-8 mb-3">
                                    <label class="form-label">Hospital Name *</label>
                                    <input type="text" name="hospital_name" class="form-control" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Registration Number</label>
                                    <input type="text" name="registration_number" class="form-control">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">License Number</label>
                                    <input type="text" name="license_number" class="form-control">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Contact Person *</label>
                                    <input type="text" name="contact_person" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Phone Number *</label>
                                    <input type="tel" name="phone" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Pincode</label>
                                    <input type="text" name="pincode" class="form-control">
                                </div>
                            </div>

                            <!-- Address Information -->
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="text-primary mb-3">
                                        <i class="fas fa-map-marker-alt me-2"></i>Address Information
                                    </h5>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label">Complete Address *</label>
                                    <textarea name="address" class="form-control" rows="3" required></textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">City *</label>
                                    <input type="text" name="city" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">State *</label>
                                    <input type="text" name="state" class="form-control" required>
                                </div>
                            </div>

                            <!-- Capacity & Services -->
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="text-primary mb-3">
                                        <i class="fas fa-bed me-2"></i>Capacity & Services
                                    </h5>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Total Bed Capacity</label>
                                    <input type="number" name="bed_capacity" class="form-control" min="0" value="0">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Available Beds</label>
                                    <input type="number" name="available_beds" class="form-control" min="0" value="0">
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label">Services Offered</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="covid_testing" id="covid_testing" checked>
                                        <label class="form-check-label" for="covid_testing">
                                            COVID-19 Testing
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="vaccination_available" id="vaccination_available" checked>
                                        <label class="form-check-label" for="vaccination_available">
                                            COVID-19 Vaccination
                                        </label>
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label">Facilities</label>
                                    <textarea name="facilities" class="form-control" rows="3" placeholder="List your hospital facilities (ICU, Emergency, Laboratory, etc.)"></textarea>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label">Specializations</label>
                                    <textarea name="specializations" class="form-control" rows="2" placeholder="List medical specializations available"></textarea>
                                </div>
                            </div>

                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Note:</strong> Your hospital registration will be reviewed by our admin team. You will be notified once approved.
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fas fa-hospital me-2"></i>Register Hospital
                                </button>
                            </div>
                        </form>

                        <div class="text-center mt-3">
                            <p>Already have an account? <a href="login.php">Login here</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'layout/footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Password confirmation validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const password = document.querySelector('input[name="password"]').value;
            const confirmPassword = document.querySelector('input[name="confirm_password"]').value;
            
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Passwords do not match!');
            }
        });
        
        // Ensure available beds doesn't exceed total capacity
        document.querySelector('input[name="available_beds"]').addEventListener('input', function() {
            const totalBeds = parseInt(document.querySelector('input[name="bed_capacity"]').value) || 0;
            const availableBeds = parseInt(this.value) || 0;
            
            if (availableBeds > totalBeds) {
                this.value = totalBeds;
            }
        });
    </script>
</body>
</html>
