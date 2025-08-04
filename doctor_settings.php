<?php
session_start();
require_once 'db.php';

// Check if user is logged in and is doctor
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'doctor') {
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

// Get current user data
$user_stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$user_stmt->bind_param("i", $_SESSION['user_id']);
$user_stmt->execute();
$user_data = $user_stmt->get_result()->fetch_assoc();

$page_title = "Doctor Settings - COVID Portal";
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
    <?php include 'layout/doctor_header.php'; ?>

    <div class="container-fluid">
        <div class="row">
            <main class="col-12">
                <div class="container mt-4">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h1 class="h2"><i class="fas fa-cog me-2"></i>Doctor Settings</h1>
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
                        <!-- Profile Settings -->
                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="fas fa-user-md me-2"></i>Doctor Profile</h5>
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
                                        <div class="mb-3">
                                            <label for="username" class="form-label">Username</label>
                                            <input type="text" class="form-control" id="username" name="username" 
                                                   value="<?php echo htmlspecialchars($user_data['username']); ?>" readonly>
                                            <div class="form-text">Username cannot be changed</div>
                                        </div>
                                        <button type="submit" name="update_profile" class="btn btn-primary">
                                            <i class="fas fa-save me-2"></i>Update Profile
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Password Settings -->
                        <div class="col-md-6 mb-4">
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
                                            <div class="form-text">Password must be at least 6 characters long</div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="confirm_password" class="form-label">Confirm New Password</label>
                                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                        </div>
                                        <button type="submit" name="change_password" class="btn btn-warning">
                                            <i class="fas fa-key me-2"></i>Change Password
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Doctor Information -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Account Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6>Account Details</h6>
                                            <table class="table table-sm">
                                                <tr>
                                                    <td><strong>User ID:</strong></td>
                                                    <td><?php echo $user_data['id']; ?></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Role:</strong></td>
                                                    <td><span class="badge bg-info"><?php echo ucfirst($user_data['role']); ?></span></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Status:</strong></td>
                                                    <td><span class="badge bg-success"><?php echo ucfirst($user_data['status']); ?></span></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Account Created:</strong></td>
                                                    <td><?php echo date('M d, Y g:i A', strtotime($user_data['created_at'])); ?></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Last Updated:</strong></td>
                                                    <td><?php echo date('M d, Y g:i A', strtotime($user_data['updated_at'])); ?></td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>Quick Actions</h6>
                                            <div class="d-grid gap-2">
                                                <a href="doctor_dashboard.php" class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-tachometer-alt me-2"></i>Go to Dashboard
                                                </a>
                                                <a href="patient_search.php" class="btn btn-outline-info btn-sm">
                                                    <i class="fas fa-search me-2"></i>Find Patients
                                                </a>
                                                <a href="symptoms.php" class="btn btn-outline-warning btn-sm">
                                                    <i class="fas fa-notes-medical me-2"></i>COVID Symptoms Guide
                                                </a>
                                                <a href="prevention.php" class="btn btn-outline-success btn-sm">
                                                    <i class="fas fa-shield-alt me-2"></i>Prevention Guidelines
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Professional Note -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <h6><i class="fas fa-info-circle me-2"></i>Professional Note</h6>
                                <p class="mb-0">
                                    As a healthcare professional, ensure your profile information is accurate and up-to-date. 
                                    This helps maintain trust and ensures patients can reach you when needed.
                                </p>
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
