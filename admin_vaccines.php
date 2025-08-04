<?php
session_start();
include 'db.php';

// Check if user is admin
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header("Location: login_new.php");
    exit();
}

// Handle vaccine operations
if ($_POST && isset($_POST['action'])) {
    if ($_POST['action'] === 'add_vaccine') {
        $vaccine_name = trim($_POST['vaccine_name']);
        $manufacturer = trim($_POST['manufacturer']);
        $vaccine_type = trim($_POST['vaccine_type']);
        $doses_required = intval($_POST['doses_required']);
        $gap_between_doses = intval($_POST['gap_between_doses']);
        $efficacy_rate = floatval($_POST['efficacy_rate']);
        $storage_temperature = trim($_POST['storage_temperature']);
        $description = trim($_POST['description']);
        
        $stmt = $conn->prepare("INSERT INTO vaccines (vaccine_name, manufacturer, vaccine_type, doses_required, gap_between_doses, efficacy_rate, storage_temperature, description) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssiidss", $vaccine_name, $manufacturer, $vaccine_type, $doses_required, $gap_between_doses, $efficacy_rate, $storage_temperature, $description);
        
        if ($stmt->execute()) {
            $success = "Vaccine added successfully.";
        } else {
            $error = "Error adding vaccine: " . $stmt->error;
        }
    }
    
    if ($_POST['action'] === 'update_stock') {
        $vaccine_id = intval($_POST['vaccine_id']);
        $hospital_id = intval($_POST['hospital_id']);
        $quantity = intval($_POST['quantity']);
        
        // Check if stock entry exists
        $check_stmt = $conn->prepare("SELECT id FROM hospital_vaccines WHERE vaccine_id = ? AND hospital_id = ?");
        $check_stmt->bind_param("ii", $vaccine_id, $hospital_id);
        $check_stmt->execute();
        $result = $check_stmt->get_result();
        
        if ($result->num_rows > 0) {
            // Update existing stock
            $update_stmt = $conn->prepare("UPDATE hospital_vaccines SET stock_quantity = stock_quantity + ? WHERE vaccine_id = ? AND hospital_id = ?");
            $update_stmt->bind_param("iii", $quantity, $vaccine_id, $hospital_id);
            if ($update_stmt->execute()) {
                $success = "Stock updated successfully.";
            } else {
                $error = "Error updating stock: " . $update_stmt->error;
            }
        } else {
            // Add new stock entry
            $insert_stmt = $conn->prepare("INSERT INTO hospital_vaccines (vaccine_id, hospital_id, stock_quantity) VALUES (?, ?, ?)");
            $insert_stmt->bind_param("iii", $vaccine_id, $hospital_id, $quantity);
            if ($insert_stmt->execute()) {
                $success = "Stock added successfully.";
            } else {
                $error = "Error adding stock: " . $insert_stmt->error;
            }
        }
    }
}

// Get all vaccines
$vaccines_result = $conn->query("SELECT * FROM vaccines ORDER BY vaccine_name");

// Get hospitals for stock management
$hospitals_result = $conn->query("SELECT id, hospital_name FROM hospitals WHERE approval_status = 'approved' ORDER BY hospital_name");

// Get vaccine inventory
$inventory_query = "
    SELECT vi.*, vi.stock_quantity as quantity, v.vaccine_name, h.hospital_name 
    FROM hospital_vaccines vi 
    JOIN vaccines v ON vi.vaccine_id = v.id 
    JOIN hospitals h ON vi.hospital_id = h.id 
    ORDER BY v.vaccine_name, h.hospital_name
";
$inventory_result = $conn->query($inventory_query);

$page_title = "Vaccine Management - Admin Portal";
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
                            <a href="admin_patients.php" class="nav-link text-white">
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
                            <a href="admin_vaccines.php" class="nav-link text-white active">
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
                    <h1 class="h2"><i class="fas fa-pills me-2"></i>Vaccine Management</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addVaccineModal">
                            <i class="fas fa-plus me-1"></i>Add New Vaccine
                        </button>
                    </div>
                </div>

                <?php if (isset($success)): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i><?php echo $success; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Vaccine Types -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Available Vaccines</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-primary">
                                    <tr>
                                        <th>Vaccine Name</th>
                                        <th>Manufacturer</th>
                                        <th>Type</th>
                                        <th>Doses Required</th>
                                        <th>Gap (Days)</th>
                                        <th>Efficacy Rate</th>
                                        <th>Storage</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($vaccines_result && $vaccines_result->num_rows > 0): ?>
                                        <?php while ($vaccine = $vaccines_result->fetch_assoc()): ?>
                                            <tr>
                                                <td><strong><?php echo htmlspecialchars($vaccine['vaccine_name']); ?></strong></td>
                                                <td><?php echo htmlspecialchars($vaccine['manufacturer']); ?></td>
                                                <td><?php echo htmlspecialchars($vaccine['vaccine_type']); ?></td>
                                                <td><?php echo $vaccine['doses_required']; ?></td>
                                                <td><?php echo $vaccine['gap_between_doses']; ?></td>
                                                <td><?php echo $vaccine['efficacy_rate']; ?>%</td>
                                                <td><?php echo htmlspecialchars($vaccine['storage_temperature']); ?></td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#stockModal" 
                                                            onclick="setVaccineId(<?php echo $vaccine['id']; ?>, '<?php echo htmlspecialchars($vaccine['vaccine_name']); ?>')">
                                                        <i class="fas fa-plus"></i> Add Stock
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="8" class="text-center text-muted">No vaccines available.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Vaccine Inventory -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Vaccine Inventory by Hospital</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-secondary">
                                    <tr>
                                        <th>Vaccine</th>
                                        <th>Hospital</th>
                                        <th>Quantity</th>
                                        <th>Last Updated</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($inventory_result && $inventory_result->num_rows > 0): ?>
                                        <?php while ($item = $inventory_result->fetch_assoc()): ?>
                                            <tr>
                                                <td><strong><?php echo htmlspecialchars($item['vaccine_name']); ?></strong></td>
                                                <td><?php echo htmlspecialchars($item['hospital_name']); ?></td>
                                                <td>
                                                    <?php 
                                                    $quantity = $item['quantity'] ?? 0;
                                                    $badge_class = $quantity > 100 ? 'bg-success' : ($quantity > 20 ? 'bg-warning' : 'bg-danger');
                                                    ?>
                                                    <span class="badge <?php echo $badge_class; ?>">
                                                        <?php echo $quantity; ?> doses
                                                    </span>
                                                </td>
                                                <td><?php echo date('M d, Y', strtotime($item['last_updated'])); ?></td>
                                                <td>
                                                    <?php 
                                                    $quantity = $item['quantity'] ?? 0;
                                                    if ($quantity > 100): ?>
                                                        <span class="badge bg-success">Well Stocked</span>
                                                    <?php elseif ($quantity > 20): ?>
                                                        <span class="badge bg-warning">Low Stock</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-danger">Critical</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">No vaccine inventory data available.</td>
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

    <!-- Add Vaccine Modal -->
    <div class="modal fade" id="addVaccineModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Vaccine</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="add_vaccine">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="vaccine_name" class="form-label">Vaccine Name</label>
                                    <input type="text" class="form-control" id="vaccine_name" name="vaccine_name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="manufacturer" class="form-label">Manufacturer</label>
                                    <input type="text" class="form-control" id="manufacturer" name="manufacturer" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="vaccine_type" class="form-label">Vaccine Type</label>
                                    <select class="form-control" id="vaccine_type" name="vaccine_type" required>
                                        <option value="">Select Type</option>
                                        <option value="mRNA">mRNA</option>
                                        <option value="Viral Vector">Viral Vector</option>
                                        <option value="Protein Subunit">Protein Subunit</option>
                                        <option value="Inactivated">Inactivated</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="doses_required" class="form-label">Doses Required</label>
                                    <input type="number" class="form-control" id="doses_required" name="doses_required" min="1" max="4" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="gap_between_doses" class="form-label">Gap Between Doses (Days)</label>
                                    <input type="number" class="form-control" id="gap_between_doses" name="gap_between_doses" min="0">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="efficacy_rate" class="form-label">Efficacy Rate (%)</label>
                                    <input type="number" class="form-control" id="efficacy_rate" name="efficacy_rate" min="0" max="100" step="0.1">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="storage_temperature" class="form-label">Storage Temperature</label>
                                    <input type="text" class="form-control" id="storage_temperature" name="storage_temperature" placeholder="-70°C">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Vaccine</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Stock Management Modal -->
    <div class="modal fade" id="stockModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Vaccine Stock</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="update_stock">
                        <input type="hidden" name="vaccine_id" id="stock_vaccine_id">
                        <div class="mb-3">
                            <label class="form-label">Vaccine</label>
                            <input type="text" class="form-control" id="stock_vaccine_name" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="hospital_id" class="form-label">Hospital</label>
                            <select class="form-control" id="hospital_id" name="hospital_id" required>
                                <option value="">Select Hospital</option>
                                <?php 
                                if ($hospitals_result) {
                                    $hospitals_result->data_seek(0); // Reset pointer
                                    while ($hospital = $hospitals_result->fetch_assoc()): 
                                ?>
                                    <option value="<?php echo $hospital['id']; ?>">
                                        <?php echo htmlspecialchars($hospital['hospital_name']); ?>
                                    </option>
                                <?php endwhile; } ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="quantity" class="form-label">Quantity (doses)</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" min="1" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Add Stock</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function setVaccineId(id, name) {
            document.getElementById('stock_vaccine_id').value = id;
            document.getElementById('stock_vaccine_name').value = name;
        }
    </script>
</body>
</html>
