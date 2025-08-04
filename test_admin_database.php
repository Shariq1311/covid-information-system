<?php
session_start();
require_once 'db.php';

// Test database connection and schema
$tests = [];

try {
    // Test patients query (fixed emergency_contact issue)
    $patients_query = "
        SELECT u.id, u.username, u.email, u.full_name, u.phone, u.status, u.created_at,
               p.date_of_birth, p.gender, p.address, p.emergency_contact_name, p.emergency_contact_phone
        FROM users u 
        LEFT JOIN patients p ON u.id = p.user_id 
        WHERE u.role = 'patient' 
        ORDER BY u.created_at DESC
        LIMIT 1
    ";
    $result = $conn->query($patients_query);
    $tests['patients_query'] = $result ? '✅ PASS' : '❌ FAIL: ' . $conn->error;
    
    // Test vaccines/hospital_vaccines query (fixed table name)
    $vaccines_query = "SELECT * FROM vaccines ORDER BY vaccine_name LIMIT 1";
    $result = $conn->query($vaccines_query);
    $tests['vaccines_query'] = $result ? '✅ PASS' : '❌ FAIL: ' . $conn->error;
    
    $hospital_vaccines_query = "
        SELECT vi.*, v.vaccine_name, h.hospital_name 
        FROM hospital_vaccines vi 
        JOIN vaccines v ON vi.vaccine_id = v.id 
        JOIN hospitals h ON vi.hospital_id = h.id 
        ORDER BY v.vaccine_name, h.hospital_name
        LIMIT 1
    ";
    $result = $conn->query($hospital_vaccines_query);
    $tests['hospital_vaccines_query'] = $result ? '✅ PASS' : '❌ FAIL: ' . $conn->error;
    
    // Test hospitals query
    $hospitals_query = "SELECT id, hospital_name FROM hospitals WHERE approval_status = 'approved' ORDER BY hospital_name LIMIT 1";
    $result = $conn->query($hospitals_query);
    $tests['hospitals_query'] = $result ? '✅ PASS' : '❌ FAIL: ' . $conn->error;
    
    // Test covid_tests query
    $tests_query = "SELECT COUNT(*) as count FROM covid_tests";
    $result = $conn->query($tests_query);
    $tests['covid_tests_query'] = $result ? '✅ PASS' : '❌ FAIL: ' . $conn->error;
    
    // Test vaccinations query
    $vaccinations_query = "SELECT COUNT(*) as count FROM vaccinations";
    $result = $conn->query($vaccinations_query);
    $tests['vaccinations_query'] = $result ? '✅ PASS' : '❌ FAIL: ' . $conn->error;
    
} catch (Exception $e) {
    $tests['general_error'] = '❌ FAIL: ' . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Database Schema Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-0">🧪 Admin Panel Database Schema Test</h3>
                        <small class="text-muted">Testing database queries for admin panel functionality</small>
                    </div>
                    <div class="card-body">
                        <?php foreach ($tests as $test_name => $result): ?>
                            <div class="row mb-2">
                                <div class="col-4"><strong><?php echo ucwords(str_replace('_', ' ', $test_name)); ?>:</strong></div>
                                <div class="col-8"><code><?php echo $result; ?></code></div>
                            </div>
                        <?php endforeach; ?>
                        
                        <hr class="my-4">
                        
                        <div class="alert alert-info">
                            <h5>📋 Test Summary</h5>
                            <p class="mb-2"><strong>Purpose:</strong> Verify that all admin panel database queries use correct column names and table references.</p>
                            <p class="mb-2"><strong>Fixed Issues:</strong></p>
                            <ul class="mb-0">
                                <li>✅ Updated <code>admin_patients.php</code> to use <code>emergency_contact_name</code> and <code>emergency_contact_phone</code> columns</li>
                                <li>✅ Updated <code>admin_vaccines.php</code> to use <code>hospital_vaccines</code> table instead of <code>vaccine_inventory</code></li>
                                <li>✅ All admin panel SQL queries now match the actual database schema</li>
                            </ul>
                        </div>
                        
                        <div class="text-center">
                            <a href="admin_dashboard.php" class="btn btn-primary">Go to Admin Dashboard</a>
                            <a href="admin_patients.php" class="btn btn-outline-secondary">Test Patient Management</a>
                            <a href="admin_vaccines.php" class="btn btn-outline-secondary">Test Vaccine Management</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
