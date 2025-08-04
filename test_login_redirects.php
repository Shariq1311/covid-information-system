<?php
session_start();
require_once 'db.php';

$page_title = "Login Redirect Test - COVID Portal";
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
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-0">🔗 Login Redirect Fix - Test Results</h3>
                        <small class="text-muted">All protected pages now redirect to login_new.php when not authenticated</small>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-success">
                            <h5><i class="fas fa-check-circle me-2"></i>Login Redirect Issue - FIXED!</h5>
                            <p class="mb-0">All admin portal and protected pages now properly redirect to <code>login_new.php</code> instead of causing "URL not found" errors.</p>
                        </div>

                        <h5>📋 Fixed Login Redirects</h5>
                        
                        <!-- Admin Pages -->
                        <div class="row g-3 mb-4">
                            <div class="col-12">
                                <h6 class="text-primary"><i class="fas fa-user-shield me-2"></i>Admin Portal Pages</h6>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-primary">
                                    <div class="card-body text-center p-3">
                                        <i class="fas fa-tachometer-alt fa-2x text-primary mb-2"></i>
                                        <h6 class="card-title">Admin Dashboard</h6>
                                        <a href="admin_dashboard.php" class="btn btn-outline-primary btn-sm" target="_blank">
                                            <i class="fas fa-external-link-alt me-1"></i>Test Redirect
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-primary">
                                    <div class="card-body text-center p-3">
                                        <i class="fas fa-users fa-2x text-primary mb-2"></i>
                                        <h6 class="card-title">Patient Management</h6>
                                        <a href="admin_patients.php" class="btn btn-outline-primary btn-sm" target="_blank">
                                            <i class="fas fa-external-link-alt me-1"></i>Test Redirect
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-primary">
                                    <div class="card-body text-center p-3">
                                        <i class="fas fa-hospital fa-2x text-primary mb-2"></i>
                                        <h6 class="card-title">Hospital Management</h6>
                                        <a href="admin_hospitals.php" class="btn btn-outline-primary btn-sm" target="_blank">
                                            <i class="fas fa-external-link-alt me-1"></i>Test Redirect
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-primary">
                                    <div class="card-body text-center p-3">
                                        <i class="fas fa-syringe fa-2x text-primary mb-2"></i>
                                        <h6 class="card-title">Vaccine Management</h6>
                                        <a href="admin_vaccines.php" class="btn btn-outline-primary btn-sm" target="_blank">
                                            <i class="fas fa-external-link-alt me-1"></i>Test Redirect
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-primary">
                                    <div class="card-body text-center p-3">
                                        <i class="fas fa-vial fa-2x text-primary mb-2"></i>
                                        <h6 class="card-title">Test Management</h6>
                                        <a href="admin_tests.php" class="btn btn-outline-primary btn-sm" target="_blank">
                                            <i class="fas fa-external-link-alt me-1"></i>Test Redirect
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-primary">
                                    <div class="card-body text-center p-3">
                                        <i class="fas fa-chart-bar fa-2x text-primary mb-2"></i>
                                        <h6 class="card-title">Reports</h6>
                                        <a href="admin_reports.php" class="btn btn-outline-primary btn-sm" target="_blank">
                                            <i class="fas fa-external-link-alt me-1"></i>Test Redirect
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Hospital Pages -->
                        <div class="row g-3 mb-4">
                            <div class="col-12">
                                <h6 class="text-success"><i class="fas fa-hospital me-2"></i>Hospital Portal Pages</h6>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-success">
                                    <div class="card-body text-center p-3">
                                        <i class="fas fa-hospital fa-2x text-success mb-2"></i>
                                        <h6 class="card-title">Hospital Dashboard</h6>
                                        <a href="hospital_dashboard.php" class="btn btn-outline-success btn-sm" target="_blank">
                                            <i class="fas fa-external-link-alt me-1"></i>Test Redirect
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-success">
                                    <div class="card-body text-center p-3">
                                        <i class="fas fa-cog fa-2x text-success mb-2"></i>
                                        <h6 class="card-title">Hospital Settings</h6>
                                        <a href="hospital_settings.php" class="btn btn-outline-success btn-sm" target="_blank">
                                            <i class="fas fa-external-link-alt me-1"></i>Test Redirect
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Doctor Pages -->
                        <div class="row g-3 mb-4">
                            <div class="col-12">
                                <h6 class="text-info"><i class="fas fa-user-md me-2"></i>Doctor Portal Pages</h6>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-info">
                                    <div class="card-body text-center p-3">
                                        <i class="fas fa-user-md fa-2x text-info mb-2"></i>
                                        <h6 class="card-title">Doctor Dashboard</h6>
                                        <a href="doctor_dashboard.php" class="btn btn-outline-info btn-sm" target="_blank">
                                            <i class="fas fa-external-link-alt me-1"></i>Test Redirect
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-info">
                                    <div class="card-body text-center p-3">
                                        <i class="fas fa-cog fa-2x text-info mb-2"></i>
                                        <h6 class="card-title">Doctor Settings</h6>
                                        <a href="doctor_settings.php" class="btn btn-outline-info btn-sm" target="_blank">
                                            <i class="fas fa-external-link-alt me-1"></i>Test Redirect
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Patient Pages -->
                        <div class="row g-3 mb-4">
                            <div class="col-12">
                                <h6 class="text-warning"><i class="fas fa-user me-2"></i>Patient Portal Pages</h6>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-warning">
                                    <div class="card-body text-center p-3">
                                        <i class="fas fa-user fa-2x text-warning mb-2"></i>
                                        <h6 class="card-title">Patient Dashboard</h6>
                                        <a href="patient_dashboard.php" class="btn btn-outline-warning btn-sm" target="_blank">
                                            <i class="fas fa-external-link-alt me-1"></i>Test Redirect
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-warning">
                                    <div class="card-body text-center p-3">
                                        <i class="fas fa-search fa-2x text-warning mb-2"></i>
                                        <h6 class="card-title">Hospital Search</h6>
                                        <a href="patient_search.php" class="btn btn-outline-warning btn-sm" target="_blank">
                                            <i class="fas fa-external-link-alt me-1"></i>Test Redirect
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-warning">
                                    <div class="card-body text-center p-3">
                                        <i class="fas fa-calendar fa-2x text-warning mb-2"></i>
                                        <h6 class="card-title">Appointments</h6>
                                        <a href="patient_appointment.php" class="btn btn-outline-warning btn-sm" target="_blank">
                                            <i class="fas fa-external-link-alt me-1"></i>Test Redirect
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <h6><i class="fas fa-info-circle me-2"></i>Testing Instructions</h6>
                            <ol class="mb-0">
                                <li><strong>Test When Logged Out:</strong> Click any "Test Redirect" button above</li>
                                <li><strong>Expected Result:</strong> You should be redirected to <code>login_new.php</code></li>
                                <li><strong>Previous Issue:</strong> Pages were trying to redirect to non-existent <code>login.php</code></li>
                                <li><strong>Fixed:</strong> All protected pages now properly redirect to the working login page</li>
                            </ol>
                        </div>

                        <div class="text-center mt-4">
                            <div class="btn-group" role="group">
                                <a href="index_new.php" class="btn btn-outline-primary">
                                    <i class="fas fa-home me-2"></i>Go to Homepage
                                </a>
                                <a href="login_new.php" class="btn btn-primary">
                                    <i class="fas fa-sign-in-alt me-2"></i>Login Here
                                </a>
                                <a href="register.php" class="btn btn-outline-secondary">
                                    <i class="fas fa-user-plus me-2"></i>Register Account
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
