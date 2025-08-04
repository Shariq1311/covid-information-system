<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login_new.php");
    exit();
}
include 'db.php';

// Check database connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Handle new patient form submission
$alert = '';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_patient'])) {
    $name = trim($_POST['name']);
    $age = intval($_POST['age']);
    $gender = $_POST['gender'];
    $diagnosis_date = $_POST['diagnosis_date'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("INSERT INTO patients (name, age, gender, diagnosis_date, status) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sisss", $name, $age, $gender, $diagnosis_date, $status);
    if ($stmt->execute()) {
        $alert = '<div class="alert alert-success">Patient added successfully.</div>';
    } else {
        $alert = '<div class="alert alert-danger">Failed to add patient.</div>';
    }
}

// Fetch all patients
$patients = $conn->query("SELECT * FROM patients ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
    <div class="container">
        <a class="navbar-brand" href="#">Covid Portal Dashboard</a>
        <div class="d-flex">
            <span class="navbar-text me-3">Welcome, <?php echo htmlspecialchars($_SESSION['user']); ?></span>
            <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
        </div>
    </div>
</nav>
<div class="container">
    <?php echo $alert; ?>
    <div class="row">
        <div class="col-lg-5 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    Add New Patient
                </div>
                <div class="card-body">
                    <form method="post">
                        <input type="hidden" name="add_patient" value="1">
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Age</label>
                            <input type="number" name="age" min="0" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Gender</label>
                            <select name="gender" class="form-select" required>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Diagnosis Date</label>
                            <input type="date" name="diagnosis_date" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="Active">Active</option>
                                <option value="Recovered">Recovered</option>
                                <option value="Deceased">Deceased</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Add Patient</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-7 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    Patient List
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Age</th>
                                    <th>Gender</th>
                                    <th>Diagnosis Date</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $patients->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['age']); ?></td>
                                    <td><?php echo htmlspecialchars($row['gender']); ?></td>
                                    <td><?php echo htmlspecialchars($row['diagnosis_date']); ?></td>
                                    <td>
                                        <?php
                                        $badge = 'secondary';
                                        if ($row['status'] == 'Active') $badge = 'warning';
                                        if ($row['status'] == 'Recovered') $badge = 'success';
                                        if ($row['status'] == 'Deceased') $badge = 'danger';
                                        ?>
                                        <span class="badge bg-<?php echo $badge; ?>">
                                            <?php echo htmlspecialchars($row['status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                                </tr>
                                <?php endwhile; ?>
                                <?php if ($patients->num_rows == 0): ?>
                                <tr>
                                    <td colspan="6" class="text-center">No patients found.</td>
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
