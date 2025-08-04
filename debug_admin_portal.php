<?php
session_start();
require_once 'db.php';

$page_title = "Admin Portal Debug - COVID Portal";
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
                        <h3 class="mb-0">🐛 Admin Portal Debug Test</h3>
                        <small class="text-muted">Debug information for admin portal access issues</small>
                    </div>
                    <div class="card-body">
                        <h5>🔍 Current Session Status</h5>
                        <div class="alert alert-info">
                            <h6>Session Variables:</h6>
                            <ul class="mb-0">
                                <li><strong>User ID:</strong> <?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'Not Set'; ?></li>
                                <li><strong>User:</strong> <?php echo isset($_SESSION['user']) ? $_SESSION['user'] : 'Not Set'; ?></li>
                                <li><strong>Role:</strong> <?php echo isset($_SESSION['role']) ? $_SESSION['role'] : 'Not Set'; ?></li>
                                <li><strong>Status:</strong> <?php echo isset($_SESSION['user']) ? 'Logged In' : 'Logged Out'; ?></li>
                            </ul>
                        </div>

                        <h5>📁 File Existence Check</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Login Files:</h6>
                                <ul class="list-unstyled">
                                    <li><?php echo file_exists('login.php') ? '✅' : '❌'; ?> login.php</li>
                                    <li><?php echo file_exists('login_new.php') ? '✅' : '❌'; ?> login_new.php</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6>Admin Files:</h6>
                                <ul class="list-unstyled">
                                    <li><?php echo file_exists('admin_dashboard.php') ? '✅' : '❌'; ?> admin_dashboard.php</li>
                                    <li><?php echo file_exists('admin_patients.php') ? '✅' : '❌'; ?> admin_patients.php</li>
                                    <li><?php echo file_exists('admin_hospitals.php') ? '✅' : '❌'; ?> admin_hospitals.php</li>
                                    <li><?php echo file_exists('admin_settings.php') ? '✅' : '❌'; ?> admin_settings.php</li>
                                </ul>
                            </div>
                        </div>

                        <h5>🔗 Portal Access Tests</h5>
                        <div class="alert alert-warning">
                            <p><strong>Instructions:</strong> While logged out, click the buttons below to test where the errors occur:</p>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="card border-primary">
                                    <div class="card-body text-center">
                                        <h6>Test 1: Admin Dashboard Direct</h6>
                                        <p class="small">Should redirect to login_new.php</p>
                                        <a href="admin_dashboard.php" class="btn btn-primary btn-sm" target="_blank">
                                            <i class="fas fa-external-link-alt me-1"></i>Test Admin Dashboard
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card border-success">
                                    <div class="card-body text-center">
                                        <h6>Test 2: Login New Page</h6>
                                        <p class="small">Should load login form</p>
                                        <a href="login_new.php" class="btn btn-success btn-sm" target="_blank">
                                            <i class="fas fa-external-link-alt me-1"></i>Test Login Page
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card border-warning">
                                    <div class="card-body text-center">
                                        <h6>Test 3: Admin Portal Link</h6>
                                        <p class="small">From navigation dropdown</p>
                                        <a href="login_new.php?role=admin" class="btn btn-warning btn-sm" target="_blank">
                                            <i class="fas fa-external-link-alt me-1"></i>Test Portal Link
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card border-info">
                                    <div class="card-body text-center">
                                        <h6>Test 4: Admin Settings</h6>
                                        <p class="small">Should redirect to login_new.php</p>
                                        <a href="admin_settings.php" class="btn btn-info btn-sm" target="_blank">
                                            <i class="fas fa-external-link-alt me-1"></i>Test Settings
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h5 class="mt-4">📋 Expected vs Actual Behavior</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Action</th>
                                        <th>Expected Result</th>
                                        <th>Possible Issues</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Click Admin Portal (logged out)</td>
                                        <td>Redirect to login_new.php</td>
                                        <td>File not found, redirect loop</td>
                                    </tr>
                                    <tr>
                                        <td>Direct admin page access</td>
                                        <td>Redirect to login_new.php</td>
                                        <td>Session check fails, wrong redirect</td>
                                    </tr>
                                    <tr>
                                        <td>Login successful</td>
                                        <td>Redirect to appropriate dashboard</td>
                                        <td>Dashboard file missing</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="alert alert-danger">
                            <h6><i class="fas fa-exclamation-triangle me-2"></i>Common Issues</h6>
                            <ul class="mb-0">
                                <li><strong>File Path Issues:</strong> Check if all admin files exist in the same directory</li>
                                <li><strong>Session Problems:</strong> Clear browser cache and cookies</li>
                                <li><strong>Redirect Loops:</strong> Check if login_new.php is working correctly</li>
                                <li><strong>Server Configuration:</strong> Check if .htaccess is affecting URLs</li>
                            </ul>
                        </div>

                        <div class="text-center mt-4">
                            <div class="btn-group" role="group">
                                <a href="index_new.php" class="btn btn-outline-primary">
                                    <i class="fas fa-home me-2"></i>Go to Homepage
                                </a>
                                <button class="btn btn-secondary" onclick="location.reload()">
                                    <i class="fas fa-sync me-2"></i>Refresh Debug
                                </button>
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
