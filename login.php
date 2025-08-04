<?php
include 'db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $hashed_password, $role);
        $stmt->fetch();
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user'] = $username;
            $_SESSION['user_id'] = $user_id;
            $_SESSION['role'] = $role;
            
            // Redirect based on role
            switch ($role) {
                case 'admin':
                    header("Location: admin_dashboard.php");
                    break;
                case 'hospital':
                    header("Location: hospital_dashboard.php");
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
            $error = "Invalid username or password.";
        }
    } else {
        $error = "Invalid username or password.";
    }
}

$page_title = "Login - COVID-19 Portal";
?>
<?php include 'layout/app_header.php'; ?>

    <!-- Custom CSS for Login Page -->
    <style>
        .login-container {
            min-height: 80vh;
            display: flex;
            align-items: center;
        }
        .login-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .login-header {
            background: linear-gradient(45deg, #007bff, #0056b3);
            color: white;
            border-radius: 15px 15px 0 0;
        }
        .btn-login {
            background: linear-gradient(45deg, #007bff, #0056b3);
            border: none;
            transition: all 0.3s ease;
        }
        .btn-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0,123,255,0.4);
        }
        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
        }
        .floating-icons {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            overflow: hidden;
            z-index: -1;
        }
        .floating-icon {
            position: absolute;
            color: rgba(255,255,255,0.1);
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
    </style>

    <!-- Floating Background Icons -->
    <div class="floating-icons">
        <i class="fas fa-virus floating-icon" style="top: 10%; left: 10%; font-size: 3rem; animation-delay: 0s;"></i>
        <i class="fas fa-shield-virus floating-icon" style="top: 20%; right: 15%; font-size: 2rem; animation-delay: 1s;"></i>
        <i class="fas fa-syringe floating-icon" style="bottom: 30%; left: 20%; font-size: 2.5rem; animation-delay: 2s;"></i>
        <i class="fas fa-hospital floating-icon" style="bottom: 20%; right: 25%; font-size: 2rem; animation-delay: 3s;"></i>
    </div>

    <div class="container login-container">
        <div class="row justify-content-center w-100">
            <div class="col-md-6 col-lg-5">
                <div class="card login-card">
                    <div class="card-header login-header text-center py-4">
                        <h2 class="mb-2">
                            <i class="fas fa-shield-virus me-2"></i>
                            COVID-19 Portal
                        </h2>
                        <p class="mb-0 opacity-75">Sign in to your account</p>
                    </div>
                    
                    <div class="card-body p-4">
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <?php echo htmlspecialchars($error); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form method="post">
                            <div class="mb-3">
                                <label for="username" class="form-label">
                                    <i class="fas fa-user me-2"></i>Username or Email
                                </label>
                                <input 
                                    type="text" 
                                    name="username" 
                                    id="username" 
                                    class="form-control form-control-lg" 
                                    placeholder="Enter your username or email"
                                    required 
                                    autofocus
                                >
                            </div>
                            
                            <div class="mb-4">
                                <label for="password" class="form-label">
                                    <i class="fas fa-lock me-2"></i>Password
                                </label>
                                <div class="input-group">
                                    <input 
                                        type="password" 
                                        name="password" 
                                        id="password" 
                                        class="form-control form-control-lg" 
                                        placeholder="Enter your password"
                                        required
                                    >
                                    <button 
                                        type="button" 
                                        class="btn btn-outline-secondary" 
                                        onclick="togglePassword()"
                                        id="toggleBtn"
                                    >
                                        <i class="fas fa-eye" id="toggleIcon"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-login btn-lg text-white">
                                    <i class="fas fa-sign-in-alt me-2"></i>
                                    Sign In
                                </button>
                            </div>
                        </form>
                        
                        <div class="text-center">
                            <p class="text-muted mb-3">Don't have an account?</p>
                            <div class="d-grid gap-2">
                                <a href="register.php" class="btn btn-outline-primary">
                                    <i class="fas fa-user-plus me-2"></i>Register as Patient
                                </a>
                                <a href="hospital_register.php" class="btn btn-outline-success">
                                    <i class="fas fa-hospital me-2"></i>Register Hospital
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer bg-light text-center">
                        <small class="text-muted">
                            Need help? <a href="contact.php" class="text-decoration-none">Contact Support</a>
                        </small>
                    </div>
                </div>
                
                <!-- Quick Access Links -->
                <div class="row mt-4">
                    <div class="col-6">
                        <div class="card bg-transparent border-light text-white text-center">
                            <div class="card-body py-3">
                                <i class="fas fa-search fa-2x mb-2"></i>
                                <p class="mb-0 small">Find Hospitals</p>
                                <a href="patient_search.php" class="stretched-link text-white"></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card bg-transparent border-light text-white text-center">
                            <div class="card-body py-3">
                                <i class="fas fa-info-circle fa-2x mb-2"></i>
                                <p class="mb-0 small">COVID Info</p>
                                <a href="symptoms.php" class="stretched-link text-white"></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function togglePassword() {
            const password = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (password.type === 'password') {
                password.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                password.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // Auto-hide alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>

<?php include 'layout/app_footer.php'; ?>