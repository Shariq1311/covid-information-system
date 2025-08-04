<?php
session_start();
require_once 'db.php';

// Check if user is logged in and is hospital
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'hospital') {
    header("Location: login_new.php");
    exit();
}

// Handle settings update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $success_message = '';
    $error_message = '';
    
    if (isset($_POST['update_profile'])) {
        $full_name = $_POST['full_name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        
        $update_stmt = $conn->prepare("UPDATE users SET full_name = ?, email = ?, phone = ? WHERE id = ?");
        $update_stmt->bind_param("sssi", $full_name, $email, $phone, $_SESSION['user_id']);
        
        if ($update_stmt->execute()) {
            $success_message = "Profile updated successfully!";
            $_SESSION['user'] = $full_name; // Update session
        } else {
            $error_message = "Error updating profile: " . $conn->error;
        }
    }
    
    if (isset($_POST['update_hospital'])) {
        $hospital_name = $_POST['hospital_name'];
        $contact_person = $_POST['contact_person'];
        $address = $_POST['address'];
        $city = $_POST['city'];
        $state = $_POST['state'];
        $pincode = $_POST['pincode'];
        $facilities = $_POST['facilities'];
        $bed_capacity = $_POST['bed_capacity'];
        $available_beds = $_POST['available_beds'];
        $covid_testing = isset($_POST['covid_testing']) ? 1 : 0;
        $vaccination_available = isset($_POST['vaccination_available']) ? 1 : 0;
        
        $hospital_stmt = $conn->prepare("
            UPDATE hospitals SET 
            hospital_name = ?, contact_person = ?, address = ?, city = ?, state = ?, 
            pincode = ?, facilities = ?, bed_capacity = ?, available_beds = ?, 
            covid_testing = ?, vaccination_available = ?
            WHERE user_id = ?
        ");
        $hospital_stmt->bind_param("sssssssiiii", 
            $hospital_name, $contact_person, $address, $city, $state, 
            $pincode, $facilities, $bed_capacity, $available_beds, 
            $covid_testing, $vaccination_available, $_SESSION['user_id']
        );
        
        if ($hospital_stmt->execute()) {
            $success_message = "Hospital information updated successfully!";
        } else {
            $error_message = "Error updating hospital information: " . $conn->error;
        }
    }
    
    if (isset($_POST['change_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        
        // Verify current password
        $check_stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
        $check_stmt->bind_param("i", $_SESSION['user_id']);
        $check_stmt->execute();
        $result = $check_stmt->get_result();
        $user = $result->fetch_assoc();
        
        if (password_verify($current_password, $user['password'])) {
            if ($new_password === $confirm_password) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $pass_stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
                $pass_stmt->bind_param("si", $hashed_password, $_SESSION['user_id']);
                
                if ($pass_stmt->execute()) {
                    $success_message = "Password changed successfully!";
                } else {
                    $error_message = "Error changing password: " . $conn->error;
                }
            } else {
                $error_message = "New passwords do not match!";
            }
        } else {
            $error_message = "Current password is incorrect!";
        }
    }
}

// Get current user and hospital data
$user_stmt = $conn->prepare("
    SELECT u.*, h.hospital_name, h.registration_number, h.contact_person, h.address, 
           h.city, h.state, h.pincode, h.facilities, h.bed_capacity, h.available_beds,
           h.covid_testing, h.vaccination_available, h.approval_status
    FROM users u 
    LEFT JOIN hospitals h ON u.id = h.user_id 
    WHERE u.id = ?
");
$user_stmt->bind_param("i", $_SESSION['user_id']);
$user_stmt->execute();
$user_data = $user_stmt->get_result()->fetch_assoc();

$page_title = "Hospital Settings - COVID Portal";
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
    <?php include 'layout/hospital_header.php'; ?>

    <div class="container-fluid">
        <div class="row">
            <main class="col-12">
                <div class="container mt-4">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h1 class="h2"><i class="fas fa-cog me-2"></i>Hospital Settings</h1>
                        <div class="badge bg-<?php echo $user_data['approval_status'] === 'approved' ? 'success' : ($user_data['approval_status'] === 'pending' ? 'warning' : 'danger'); ?>">
                            <?php echo ucfirst($user_data['approval_status']); ?>
                        </div>
                    </div>

                    <?php if (isset($success_message) && $success_message): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i><?php echo $success_message; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($error_message) && $error_message): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i><?php echo $error_message; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <div class="row">
                        <!-- Hospital Information -->
                        <div class="col-md-8 mb-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="fas fa-hospital me-2"></i>Hospital Information</h5>
                                </div>
                                <div class="card-body">
                                    <form method="POST">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="hospital_name" class="form-label">Hospital Name</label>
                                                <input type="text" class="form-control" id="hospital_name" name="hospital_name" 
                                                       value="<?php echo htmlspecialchars($user_data['hospital_name'] ?? ''); ?>" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="contact_person" class="form-label">Contact Person</label>
                                                <input type="text" class="form-control" id="contact_person" name="contact_person" 
                                                       value="<?php echo htmlspecialchars($user_data['contact_person'] ?? ''); ?>" required>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="address" class="form-label">Address</label>
                                            <textarea class="form-control" id="address" name="address" rows="2" required><?php echo htmlspecialchars($user_data['address'] ?? ''); ?></textarea>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label for="city" class="form-label">City</label>
                                                <input type="text" class="form-control" id="city" name="city" 
                                                       value="<?php echo htmlspecialchars($user_data['city'] ?? ''); ?>" required>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="state" class="form-label">State</label>
                                                <input type="text" class="form-control" id="state" name="state" 
                                                       value="<?php echo htmlspecialchars($user_data['state'] ?? ''); ?>" required>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="pincode" class="form-label">Pincode</label>
                                                <input type="text" class="form-control" id="pincode" name="pincode" 
                                                       value="<?php echo htmlspecialchars($user_data['pincode'] ?? ''); ?>">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="facilities" class="form-label">Facilities</label>
                                            <textarea class="form-control" id="facilities" name="facilities" rows="3"><?php echo htmlspecialchars($user_data['facilities'] ?? ''); ?></textarea>
                                            <div class="form-text">List available facilities (ICU, Emergency, Laboratory, etc.)</div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="bed_capacity" class="form-label">Total Bed Capacity</label>
                                                <input type="number" class="form-control" id="bed_capacity" name="bed_capacity" 
                                                       value="<?php echo $user_data['bed_capacity'] ?? 0; ?>" min="0">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="available_beds" class="form-label">Available Beds</label>
                                                <input type="number" class="form-control" id="available_beds" name="available_beds" 
                                                       value="<?php echo $user_data['available_beds'] ?? 0; ?>" min="0">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="covid_testing" name="covid_testing" 
                                                           <?php echo ($user_data['covid_testing'] ?? 0) ? 'checked' : ''; ?>>
                                                    <label class="form-check-label" for="covid_testing">
                                                        COVID-19 Testing Available
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="vaccination_available" name="vaccination_available" 
                                                           <?php echo ($user_data['vaccination_available'] ?? 0) ? 'checked' : ''; ?>>
                                                    <label class="form-check-label" for="vaccination_available">
                                                        Vaccination Available
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" name="update_hospital" class="btn btn-primary">
                                            <i class="fas fa-save me-2"></i>Update Hospital Information
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <!-- User Profile Settings -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="fas fa-user me-2"></i>User Profile</h5>
                                </div>
                                <div class="card-body">
                                    <form method="POST">
                                        <div class="mb-3">
                                            <label for="full_name" class="form-label">Full Name</label>
                                            <input type="text" class="form-control" id="full_name" name="full_name" 
                                                   value="<?php echo htmlspecialchars($user_data['full_name']); ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="email" name="email" 
                                                   value="<?php echo htmlspecialchars($user_data['email']); ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="phone" class="form-label">Phone</label>
                                            <input type="tel" class="form-control" id="phone" name="phone" 
                                                   value="<?php echo htmlspecialchars($user_data['phone']); ?>">
                                        </div>
                                        <button type="submit" name="update_profile" class="btn btn-success btn-sm">
                                            <i class="fas fa-save me-2"></i>Update Profile
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <!-- Change Password -->
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="fas fa-lock me-2"></i>Change Password</h5>
                                </div>
                                <div class="card-body">
                                    <form method="POST">
                                        <div class="mb-3">
                                            <label for="current_password" class="form-label">Current Password</label>
                                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="new_password" class="form-label">New Password</label>
                                            <input type="password" class="form-control" id="new_password" name="new_password" 
                                                   minlength="6" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="confirm_password" class="form-label">Confirm Password</label>
                                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                        </div>
                                        <button type="submit" name="change_password" class="btn btn-warning btn-sm">
                                            <i class="fas fa-key me-2"></i>Change Password
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Password confirmation validation
        document.getElementById('confirm_password').addEventListener('input', function() {
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = this.value;
            
            if (newPassword !== confirmPassword) {
                this.setCustomValidity('Passwords do not match');
            } else {
                this.setCustomValidity('');
            }
        });
    </script>
</body>
</html>
