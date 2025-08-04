<?php
session_start();
include 'db.php';

// Check if user is admin
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header("Location: login_new.php");
    exit();
}

// Handle hospital approval/rejection
if ($_POST && isset($_POST['action'])) {
    $hospital_id = $_POST['hospital_id'];
    $action = $_POST['action'];
    $admin_id = $_SESSION['user_id'] ?? 1; // Default to 1 if not set
    
    if ($action == 'approve') {
        $status = 'approved';
    } elseif ($action == 'reject') {
        $status = 'rejected';
    }
    
    $stmt = $conn->prepare("UPDATE hospitals SET approval_status = ?, approved_by = ?, approved_at = NOW() WHERE id = ?");
    $stmt->bind_param("sii", $status, $admin_id, $hospital_id);
    
    if ($stmt->execute()) {
        $success_msg = "Hospital " . $status . " successfully!";
        
        // Update user status as well
        $user_status = $status == 'approved' ? 'active' : 'inactive';
        $conn->query("UPDATE users u JOIN hospitals h ON u.id = h.user_id SET u.status = '$user_status' WHERE h.id = $hospital_id");
    } else {
        $error_msg = "Failed to update hospital status.";
    }
}

// Get hospitals list with filters
$filter = $_GET['filter'] ?? 'all';
$search = $_GET['search'] ?? '';

$query = "SELECT h.*, u.username, u.email, u.created_at as registration_date 
          FROM hospitals h 
          JOIN users u ON h.user_id = u.id 
          WHERE 1=1";

if ($filter != 'all') {
    $query .= " AND h.approval_status = '$filter'";
}

if ($search) {
    $query .= " AND (h.hospital_name LIKE '%$search%' OR h.city LIKE '%$search%' OR h.registration_number LIKE '%$search%')";
}

$query .= " ORDER BY h.created_at DESC";
$hospitals = $conn->query($query);

if (!$hospitals) {
    die("Error executing query: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Management - COVID Portal Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-light">
    <?php include 'layout/admin_header.php'; ?>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 bg-dark p-0">
                <div class="d-flex flex-column p-3">
                    <h5 class="text-white mb-4">Admin Panel</h5>
                    <ul class="nav nav-pills flex-column">
                        <li class="nav-item mb-2">
                            <a href="admin_dashboard.php" class="nav-link text-white">
                                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a href="admin_patients.php" class="nav-link text-white">
                                <i class="fas fa-users me-2"></i>Patients
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a href="admin_hospitals.php" class="nav-link text-white active">
                                <i class="fas fa-hospital me-2"></i>Hospitals
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a href="admin_tests.php" class="nav-link text-white">
                                <i class="fas fa-vial me-2"></i>COVID Tests
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a href="admin_vaccinations.php" class="nav-link text-white">
                                <i class="fas fa-syringe me-2"></i>Vaccinations
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a href="admin_reports.php" class="nav-link text-white">
                                <i class="fas fa-chart-bar me-2"></i>Reports
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Hospital Management</h2>
                </div>

                <?php if (isset($success_msg)): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <?php echo $success_msg; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($error_msg)): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <?php echo $error_msg; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Filters -->
                <div class="card mb-4">
                    <div class="card-body">
                        <form method="GET" class="row g-3">
                            <div class="col-md-3">
                                <select name="filter" class="form-select">
                                    <option value="all" <?php echo $filter == 'all' ? 'selected' : ''; ?>>All Hospitals</option>
                                    <option value="pending" <?php echo $filter == 'pending' ? 'selected' : ''; ?>>Pending Approval</option>
                                    <option value="approved" <?php echo $filter == 'approved' ? 'selected' : ''; ?>>Approved</option>
                                    <option value="rejected" <?php echo $filter == 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="search" class="form-control" placeholder="Search by hospital name, city, or registration number..." value="<?php echo htmlspecialchars($search); ?>">
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search me-2"></i>Search
                                </button>
                                <a href="admin_hospitals.php" class="btn btn-secondary">Reset</a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Hospitals Table -->
                <div class="card">
                    <div class="card-header">
                        <h5>Hospitals List</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Hospital Name</th>
                                        <th>Registration No.</th>
                                        <th>Contact Person</th>
                                        <th>Location</th>
                                        <th>Services</th>
                                        <th>Status</th>
                                        <th>Registration Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($hospitals && $hospitals->num_rows > 0): ?>
                                        <?php while ($hospital = $hospitals->fetch_assoc()): ?>
                                        <tr>
                                            <td>
                                                <strong><?php echo htmlspecialchars($hospital['hospital_name']); ?></strong><br>
                                                <small class="text-muted"><?php echo htmlspecialchars($hospital['email']); ?></small>
                                            </td>
                                            <td><?php echo htmlspecialchars($hospital['registration_number'] ?? 'N/A'); ?></td>
                                            <td>
                                                <?php echo htmlspecialchars($hospital['contact_person'] ?? 'N/A'); ?><br>
                                                <small class="text-muted"><?php echo htmlspecialchars($hospital['phone']); ?></small>
                                            </td>
                                            <td>
                                                <?php echo htmlspecialchars($hospital['city']); ?>, <?php echo htmlspecialchars($hospital['state']); ?>
                                            </td>
                                            <td>
                                                <?php if ($hospital['covid_testing']): ?>
                                                    <span class="badge bg-info">COVID Testing</span>
                                                <?php endif; ?>
                                                <?php if ($hospital['vaccination_available']): ?>
                                                    <span class="badge bg-success">Vaccination</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?php 
                                                    echo match($hospital['approval_status']) {
                                                        'approved' => 'success',
                                                        'rejected' => 'danger',
                                                        'pending' => 'warning',
                                                        default => 'secondary'
                                                    };
                                                ?>">
                                                    <?php echo ucfirst($hospital['approval_status']); ?>
                                                </span>
                                            </td>
                                            <td><?php echo date('M d, Y', strtotime($hospital['registration_date'])); ?></td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#viewModal<?php echo $hospital['id']; ?>">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <?php if ($hospital['approval_status'] == 'pending'): ?>
                                                        <form method="POST" style="display: inline;">
                                                            <input type="hidden" name="hospital_id" value="<?php echo $hospital['id']; ?>">
                                                            <input type="hidden" name="action" value="approve">
                                                            <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Approve this hospital?')">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                        </form>
                                                        <form method="POST" style="display: inline;">
                                                            <input type="hidden" name="hospital_id" value="<?php echo $hospital['id']; ?>">
                                                            <input type="hidden" name="action" value="reject">
                                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Reject this hospital?')">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </form>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- View Modal -->
                                        <div class="modal fade" id="viewModal<?php echo $hospital['id']; ?>" tabindex="-1">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Hospital Details</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <h6>Basic Information</h6>
                                                                <p><strong>Name:</strong> <?php echo htmlspecialchars($hospital['hospital_name']); ?></p>
                                                                <p><strong>Registration Number:</strong> <?php echo htmlspecialchars($hospital['registration_number'] ?? 'N/A'); ?></p>
                                                                <p><strong>License Number:</strong> <?php echo htmlspecialchars($hospital['license_number'] ?? 'N/A'); ?></p>
                                                                <p><strong>Contact Person:</strong> <?php echo htmlspecialchars($hospital['contact_person'] ?? 'N/A'); ?></p>
                                                                <p><strong>Phone:</strong> <?php echo htmlspecialchars($hospital['phone']); ?></p>
                                                                <p><strong>Email:</strong> <?php echo htmlspecialchars($hospital['email']); ?></p>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <h6>Location & Services</h6>
                                                                <p><strong>Address:</strong> <?php echo htmlspecialchars($hospital['address']); ?></p>
                                                                <p><strong>City:</strong> <?php echo htmlspecialchars($hospital['city']); ?></p>
                                                                <p><strong>State:</strong> <?php echo htmlspecialchars($hospital['state']); ?></p>
                                                                <p><strong>Pincode:</strong> <?php echo htmlspecialchars($hospital['pincode'] ?? 'N/A'); ?></p>
                                                                <p><strong>Bed Capacity:</strong> <?php echo $hospital['bed_capacity']; ?></p>
                                                                <p><strong>Available Beds:</strong> <?php echo $hospital['available_beds']; ?></p>
                                                            </div>
                                                        </div>
                                                        <?php if ($hospital['facilities']): ?>
                                                            <h6>Facilities</h6>
                                                            <p><?php echo htmlspecialchars($hospital['facilities']); ?></p>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="8" class="text-center text-muted">No hospitals found</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
