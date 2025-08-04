<?php
session_start();
include 'db.php';

// Check if user is admin
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header("Location: login_new.php");
    exit();
}

// Handle patient status updates
if ($_POST && isset($_POST['action'])) {
    $patient_id = intval($_POST['patient_id']);
    $action = $_POST['action'];
    
    if ($action === 'activate') {
        $stmt = $conn->prepare("UPDATE users SET status = 'active' WHERE id = ?");
        $stmt->bind_param("i", $patient_id);
        if ($stmt->execute()) {
            $success = "Patient activated successfully.";
        }
    } elseif ($action === 'deactivate') {
        $stmt = $conn->prepare("UPDATE users SET status = 'inactive' WHERE id = ?");
        $stmt->bind_param("i", $patient_id);
        if ($stmt->execute()) {
            $success = "Patient deactivated successfully.";
        }
    }
}

// Get all patients
$patients_query = "
    SELECT u.id, u.username, u.email, u.full_name, u.phone, u.status, u.created_at,
           p.date_of_birth, p.gender, p.address, p.emergency_contact_name, p.emergency_contact_phone
    FROM users u 
    LEFT JOIN patients p ON u.id = p.user_id 
    WHERE u.role = 'patient' 
    ORDER BY u.created_at DESC
";
$patients_result = $conn->query($patients_query);

$page_title = "Patient Management - Admin Portal";
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
    <?php include 'layout/admin_header.php'; ?>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar bg-primary">
                <div class="sidebar-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a href="admin_dashboard.php" class="nav-link text-white">
                                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="admin_patients.php" class="nav-link text-white active">
                                <i class="fas fa-users me-2"></i>Patients
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="admin_hospitals.php" class="nav-link text-white">
                                <i class="fas fa-hospital me-2"></i>Hospitals
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="admin_tests.php" class="nav-link text-white">
                                <i class="fas fa-vial me-2"></i>COVID Tests
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="admin_vaccinations.php" class="nav-link text-white">
                                <i class="fas fa-syringe me-2"></i>Vaccinations
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="admin_vaccines.php" class="nav-link text-white">
                                <i class="fas fa-pills me-2"></i>Vaccine Inventory
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="admin_reports.php" class="nav-link text-white">
                                <i class="fas fa-chart-bar me-2"></i>Reports
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2"><i class="fas fa-users me-2"></i>Patient Management</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.print()">
                            <i class="fas fa-print me-1"></i>Print Report
                        </button>
                    </div>
                </div>

                <?php if (isset($success)): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i><?php echo $success; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Patients Table -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Registered Patients</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-primary">
                                    <tr>
                                        <th>ID</th>
                                        <th>Full Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Gender</th>
                                        <th>Status</th>
                                        <th>Registration Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($patients_result && $patients_result->num_rows > 0): ?>
                                        <?php while ($patient = $patients_result->fetch_assoc()): ?>
                                            <tr>
                                                <td><?php echo $patient['id']; ?></td>
                                                <td>
                                                    <strong><?php echo htmlspecialchars($patient['full_name'] ?: $patient['username']); ?></strong>
                                                </td>
                                                <td><?php echo htmlspecialchars($patient['email']); ?></td>
                                                <td><?php echo htmlspecialchars($patient['phone'] ?: 'N/A'); ?></td>
                                                <td><?php echo htmlspecialchars(ucfirst($patient['gender'] ?: 'N/A')); ?></td>
                                                <td>
                                                    <?php if ($patient['status'] === 'active'): ?>
                                                        <span class="badge bg-success">Active</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-danger">Inactive</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?php echo date('M d, Y', strtotime($patient['created_at'])); ?></td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <button type="button" class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#viewModal<?php echo $patient['id']; ?>">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                        
                                                        <?php if ($patient['status'] === 'active'): ?>
                                                            <form method="POST" style="display: inline;">
                                                                <input type="hidden" name="patient_id" value="<?php echo $patient['id']; ?>">
                                                                <input type="hidden" name="action" value="deactivate">
                                                                <button type="submit" class="btn btn-outline-warning" onclick="return confirm('Deactivate this patient?')">
                                                                    <i class="fas fa-ban"></i>
                                                                </button>
                                                            </form>
                                                        <?php else: ?>
                                                            <form method="POST" style="display: inline;">
                                                                <input type="hidden" name="patient_id" value="<?php echo $patient['id']; ?>">
                                                                <input type="hidden" name="action" value="activate">
                                                                <button type="submit" class="btn btn-outline-success" onclick="return confirm('Activate this patient?')">
                                                                    <i class="fas fa-check"></i>
                                                                </button>
                                                            </form>
                                                        <?php endif; ?>
                                                    </div>

                                                    <!-- Patient Details Modal -->
                                                    <div class="modal fade" id="viewModal<?php echo $patient['id']; ?>" tabindex="-1">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">Patient Details</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="row">
                                                                        <div class="col-sm-4"><strong>Full Name:</strong></div>
                                                                        <div class="col-sm-8"><?php echo htmlspecialchars($patient['full_name'] ?: $patient['username']); ?></div>
                                                                    </div>
                                                                    <div class="row mt-2">
                                                                        <div class="col-sm-4"><strong>Email:</strong></div>
                                                                        <div class="col-sm-8"><?php echo htmlspecialchars($patient['email']); ?></div>
                                                                    </div>
                                                                    <div class="row mt-2">
                                                                        <div class="col-sm-4"><strong>Phone:</strong></div>
                                                                        <div class="col-sm-8"><?php echo htmlspecialchars($patient['phone'] ?: 'N/A'); ?></div>
                                                                    </div>
                                                                    <div class="row mt-2">
                                                                        <div class="col-sm-4"><strong>Date of Birth:</strong></div>
                                                                        <div class="col-sm-8"><?php echo $patient['date_of_birth'] ? date('M d, Y', strtotime($patient['date_of_birth'])) : 'N/A'; ?></div>
                                                                    </div>
                                                                    <div class="row mt-2">
                                                                        <div class="col-sm-4"><strong>Gender:</strong></div>
                                                                        <div class="col-sm-8"><?php echo htmlspecialchars(ucfirst($patient['gender'] ?: 'N/A')); ?></div>
                                                                    </div>
                                                                    <div class="row mt-2">
                                                                        <div class="col-sm-4"><strong>Address:</strong></div>
                                                                        <div class="col-sm-8"><?php echo htmlspecialchars($patient['address'] ?: 'N/A'); ?></div>
                                                                    </div>
                                                                    <div class="row mt-2">
                                                                        <div class="col-sm-4"><strong>Emergency Contact:</strong></div>
                                                                        <div class="col-sm-8">
                                                                            <?php 
                                                                            $emergency_contact = '';
                                                                            if ($patient['emergency_contact_name']) {
                                                                                $emergency_contact = htmlspecialchars($patient['emergency_contact_name']);
                                                                                if ($patient['emergency_contact_phone']) {
                                                                                    $emergency_contact .= ' - ' . htmlspecialchars($patient['emergency_contact_phone']);
                                                                                }
                                                                            } else {
                                                                                $emergency_contact = 'N/A';
                                                                            }
                                                                            echo $emergency_contact;
                                                                            ?>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row mt-2">
                                                                        <div class="col-sm-4"><strong>Registration Date:</strong></div>
                                                                        <div class="col-sm-8"><?php echo date('M d, Y g:i A', strtotime($patient['created_at'])); ?></div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="8" class="text-center text-muted">No patients registered yet.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
