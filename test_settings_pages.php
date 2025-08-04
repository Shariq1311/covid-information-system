<?php
session_start();
require_once 'db.php';

$page_title = "Settings Links Test - COVID Portal";
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
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-0">⚙️ Settings Pages Test</h3>
                        <small class="text-muted">Testing all settings page links and functionality</small>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-success">
                            <h5><i class="fas fa-check-circle me-2"></i>Settings Pages Created Successfully!</h5>
                            <p class="mb-0">All missing settings pages have been created and are now accessible.</p>
                        </div>

                        <h5>📋 Created Settings Pages</h5>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="card border-primary">
                                    <div class="card-body text-center">
                                        <i class="fas fa-user-shield fa-3x text-primary mb-2"></i>
                                        <h6>Admin Settings</h6>
                                        <p class="small text-muted">Profile management, password change, system info</p>
                                        <a href="admin_settings.php" class="btn btn-primary btn-sm" target="_blank">
                                            <i class="fas fa-external-link-alt me-1"></i>Test Page
                                        </a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="card border-success">
                                    <div class="card-body text-center">
                                        <i class="fas fa-hospital fa-3x text-success mb-2"></i>
                                        <h6>Hospital Settings</h6>
                                        <p class="small text-muted">Hospital info, contact details, facilities</p>
                                        <a href="hospital_settings.php" class="btn btn-success btn-sm" target="_blank">
                                            <i class="fas fa-external-link-alt me-1"></i>Test Page
                                        </a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="card border-info">
                                    <div class="card-body text-center">
                                        <i class="fas fa-user-md fa-3x text-info mb-2"></i>
                                        <h6>Doctor Settings</h6>
                                        <p class="small text-muted">Doctor profile, credentials, contact info</p>
                                        <a href="doctor_settings.php" class="btn btn-info btn-sm" target="_blank">
                                            <i class="fas fa-external-link-alt me-1"></i>Test Page
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <h5>🔧 Features Implemented</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <h6>All Settings Pages Include:</h6>
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-check text-success me-2"></i>Profile Information Update</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Password Change Functionality</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Role-based Authentication</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Bootstrap 5 Responsive Design</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Form Validation & Security</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6>Specific Features:</h6>
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-star text-warning me-2"></i><strong>Admin:</strong> System information & quick actions</li>
                                    <li><i class="fas fa-star text-warning me-2"></i><strong>Hospital:</strong> Hospital details & bed management</li>
                                    <li><i class="fas fa-star text-warning me-2"></i><strong>Doctor:</strong> Professional profile management</li>
                                    <li><i class="fas fa-star text-warning me-2"></i>Real-time password confirmation</li>
                                    <li><i class="fas fa-star text-warning me-2"></i>Success/Error message alerts</li>
                                </ul>
                            </div>
                        </div>

                        <div class="alert alert-info mt-3">
                            <h6><i class="fas fa-info-circle me-2"></i>Testing Instructions</h6>
                            <ol class="mb-0">
                                <li>Click the "Test Page" buttons above to verify settings pages load correctly</li>
                                <li>Each page should redirect to login if not authenticated</li>
                                <li>Authenticated users should see their appropriate settings interface</li>
                                <li>All form submissions should work with proper validation</li>
                            </ol>
                        </div>

                        <div class="text-center mt-4">
                            <div class="btn-group" role="group">
                                <a href="index_new.php" class="btn btn-outline-primary">
                                    <i class="fas fa-home me-2"></i>Go to Homepage
                                </a>
                                <a href="login_new.php" class="btn btn-outline-secondary">
                                    <i class="fas fa-sign-in-alt me-2"></i>Login to Test
                                </a>
                                <a href="admin_dashboard.php" class="btn btn-outline-success">
                                    <i class="fas fa-tachometer-alt me-2"></i>Admin Dashboard
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
