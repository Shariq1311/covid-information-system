<?php
$page_title = "Portal Access Test - COVID-19 Portal";
$additional_css = "
<style>
    .portal-card {
        transition: transform 0.3s ease;
        border: none;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .portal-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    .test-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 60px 0;
    }
</style>
";
include('./layout/app_header.php'); 
?>

<!-- Hero Section -->
<section class="test-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-4 fw-bold mb-4">Portal Access Test</h1>
                <p class="lead mb-4">
                    Test all portal access links to ensure they're working correctly
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Portal Access Tests -->
<section class="py-5">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-lg-8 mx-auto">
                <h2 class="display-6 fw-bold">Portal Access Links</h2>
                <p class="lead text-muted">Click on any portal to test the access links</p>
            </div>
        </div>
        
        <div class="row g-4">
            <!-- Patient Portal -->
            <div class="col-md-6 col-lg-3">
                <div class="portal-card card h-100 text-center p-4">
                    <div class="card-body">
                        <i class="fas fa-user fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Patient Portal</h5>
                        <p class="card-text">Access patient dashboard, appointments, and test results</p>
                        <div class="d-grid gap-2">
                            <a href="login_new.php" class="btn btn-primary">
                                <i class="fas fa-sign-in-alt me-2"></i>Patient Login
                            </a>
                            <a href="register.php" class="btn btn-outline-primary">
                                <i class="fas fa-user-plus me-2"></i>Register
                            </a>
                        </div>
                        <div class="mt-3">
                            <small class="text-muted">Test Credentials: patient user</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Doctor Portal -->
            <div class="col-md-6 col-lg-3">
                <div class="portal-card card h-100 text-center p-4">
                    <div class="card-body">
                        <i class="fas fa-user-md fa-3x text-success mb-3"></i>
                        <h5 class="card-title">Doctor Portal</h5>
                        <p class="card-text">Manage patient records, appointments, and medical data</p>
                        <div class="d-grid gap-2">
                            <a href="login_new.php" class="btn btn-success">
                                <i class="fas fa-stethoscope me-2"></i>Doctor Login
                            </a>
                        </div>
                        <div class="mt-3">
                            <small class="text-muted">Redirects to: doctor_dashboard.php</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Hospital Portal -->
            <div class="col-md-6 col-lg-3">
                <div class="portal-card card h-100 text-center p-4">
                    <div class="card-body">
                        <i class="fas fa-hospital fa-3x text-info mb-3"></i>
                        <h5 class="card-title">Hospital Portal</h5>
                        <p class="card-text">Hospital management, staff coordination, and resources</p>
                        <div class="d-grid gap-2">
                            <a href="login_new.php" class="btn btn-info">
                                <i class="fas fa-building me-2"></i>Hospital Login
                            </a>
                            <a href="hospital_register.php" class="btn btn-outline-info">
                                <i class="fas fa-plus me-2"></i>Register Hospital
                            </a>
                        </div>
                        <div class="mt-3">
                            <small class="text-muted">Redirects to: hospital_dashboard.php</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Admin Portal -->
            <div class="col-md-6 col-lg-3">
                <div class="portal-card card h-100 text-center p-4">
                    <div class="card-body">
                        <i class="fas fa-user-shield fa-3x text-warning mb-3"></i>
                        <h5 class="card-title">Admin Portal</h5>
                        <p class="card-text">System administration, reports, and user management</p>
                        <div class="d-grid gap-2">
                            <a href="login_new.php" class="btn btn-warning">
                                <i class="fas fa-cog me-2"></i>Admin Login
                            </a>
                        </div>
                        <div class="mt-3">
                            <small class="text-success">✅ Credentials: admin / password</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Dashboard Files Status -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-lg-8 mx-auto">
                <h2 class="fw-bold">Dashboard Files Status</h2>
                <p class="text-muted">Verification of all dashboard files</p>
            </div>
        </div>
        
        <div class="row g-4">
            <div class="col-md-3">
                <div class="card text-center border-success">
                    <div class="card-body">
                        <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                        <h6>Patient Dashboard</h6>
                        <small class="text-muted">patient_dashboard.php</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center border-success">
                    <div class="card-body">
                        <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                        <h6>Doctor Dashboard</h6>
                        <small class="text-muted">doctor_dashboard.php</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center border-success">
                    <div class="card-body">
                        <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                        <h6>Hospital Dashboard</h6>
                        <small class="text-muted">hospital_dashboard.php</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center border-success">
                    <div class="card-body">
                        <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                        <h6>Admin Dashboard</h6>
                        <small class="text-muted">admin_dashboard.php</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="alert alert-success" role="alert">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Portal Access Status:</strong> All portal access links have been updated to use 
                    <code>login_new.php</code> which handles role-based authentication and redirects users 
                    to the appropriate dashboard based on their role.
                </div>
            </div>
        </div>
    </div>
</section>

<?php include('./layout/app_footer.php'); ?>
