<?php
include 'db.php';
session_start();

// Check database connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email'] ?? $username); // Use username as email if not provided
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];
    $full_name = trim($_POST['full_name'] ?? $username);
    $phone = trim($_POST['phone'] ?? '');

    // Check if username/email already exists
    $check = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $check->bind_param("ss", $username, $email);
    $check->execute();
    $check->store_result();
    if ($check->num_rows > 0) {
        $error = "This username/email is already registered.";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, role, full_name, phone, status) VALUES (?, ?, ?, ?, ?, ?, 'active')");
        $stmt->bind_param("ssssss", $username, $email, $password, $role, $full_name, $phone);

        if ($stmt->execute()) {
            $_SESSION['user'] = $username;
            $_SESSION['user_id'] = $conn->insert_id;
            $_SESSION['role'] = $role;
            
            // Redirect based on role
            switch ($role) {
                case 'admin':
                    header("Location: admin_dashboard.php");
                    break;
                case 'hospital':
                    header("Location: hospital_register.php"); // Complete hospital registration
                    break;
                case 'doctor':
                    header("Location: doctor_dashboard.php");
                    break;
                case 'patient':
                default:
                    header("Location: patient_dashboard.php");
                    break;
            }
            exit();
        } else {
            $error = "Registration failed.";
        }
    }
}

$page_title = "Patient Registration - COVID-19 Portal";
include 'layout/app_header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow border-0" style="border-radius: 15px;">
                <div class="card-header bg-primary text-white text-center" style="border-radius: 15px 15px 0 0;">
                    <h2 class="mb-2">
                        <i class="fas fa-user-plus me-2"></i>
                        Patient Registration
                    </h2>
                    <p class="mb-0 opacity-75">Create your account to access COVID-19 services</p>
                </div>
                
                <div class="card-body p-4">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <?php echo htmlspecialchars($error); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form method="post" id="registrationForm">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="full_name" class="form-label">
                                    <i class="fas fa-user me-1"></i>Full Name *
                                </label>
                                <input 
                                    type="text" 
                                    name="full_name" 
                                    id="full_name" 
                                    class="form-control" 
                                    placeholder="Enter your full name"
                                    required 
                                    autofocus
                                >
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="username" class="form-label">
                                    <i class="fas fa-at me-1"></i>Username *
                                </label>
                                <input 
                                    type="text" 
                                    name="username" 
                                    id="username" 
                                    class="form-control" 
                                    placeholder="Choose a username"
                                    required
                                >
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope me-1"></i>Email Address *
                                </label>
                                <input 
                                    type="email" 
                                    name="email" 
                                    id="email" 
                                    class="form-control" 
                                    placeholder="your@email.com"
                                    required
                                >
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">
                                    <i class="fas fa-phone me-1"></i>Phone Number
                                </label>
                                <input 
                                    type="tel" 
                                    name="phone" 
                                    id="phone" 
                                    class="form-control" 
                                    placeholder="+92 300 1234567"
                                >
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">
                                <i class="fas fa-lock me-1"></i>Password *
                            </label>
                            <div class="input-group">
                                <input 
                                    type="password" 
                                    name="password" 
                                    id="password" 
                                    class="form-control" 
                                    placeholder="Create a strong password"
                                    required
                                    minlength="6"
                                >
                                <button 
                                    type="button" 
                                    class="btn btn-outline-secondary" 
                                    onclick="togglePasswordVisibility('password')"
                                >
                                    <i class="fas fa-eye" id="passwordIcon"></i>
                                </button>
                            </div>
                            <small class="form-text text-muted">Minimum 6 characters required</small>
                        </div>

                        <div class="mb-3">
                            <label for="role" class="form-label">
                                <i class="fas fa-user-tag me-1"></i>Account Type
                            </label>
                            <select name="role" id="role" class="form-select" required>
                                <option value="patient" selected>Patient</option>
                                <option value="doctor">Doctor</option>
                            </select>
                            <small class="form-text text-muted">
                                For hospital registration, please use the <a href="hospital_register.php">Hospital Registration Form</a>
                            </small>
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-user-plus me-2"></i>
                                Create Account
                            </button>
                        </div>
                    </form>
                </div>
                
                <div class="card-footer bg-light text-center" style="border-radius: 0 0 15px 15px;">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <p class="text-muted mb-2">Healthcare Provider?</p>
                            <a href="hospital_register.php" class="btn btn-success btn-sm me-2">
                                <i class="fas fa-hospital me-1"></i>Register Hospital
                            </a>
                        </div>
                        <div class="col-12">
                            <small class="text-muted">
                                Already have an account? 
                                <a href="login.php" class="text-decoration-none fw-bold">Login here</a>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function togglePasswordVisibility(inputId) {
    const passwordInput = document.getElementById(inputId);
    const icon = document.getElementById(inputId + 'Icon');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Form validation
document.getElementById('registrationForm').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    if (password.length < 6) {
        e.preventDefault();
        alert('Password must be at least 6 characters long');
        return false;
    }
});
</script>

<?php include 'layout/app_footer.php'; ?>
