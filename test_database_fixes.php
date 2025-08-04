<?php
// Test Database Connection and Fixed Queries
include 'db.php';

echo "<h2>Testing Database Connection and Fixed Queries</h2>";

// Test database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "✅ Database connection successful<br><br>";

// Test 1: Check if hospitals table structure is correct
echo "<h3>Test 1: Hospitals Table Structure</h3>";
$result = $conn->query("DESCRIBE hospitals");
if ($result) {
    echo "✅ Hospitals table exists<br>";
    $columns = [];
    while ($row = $result->fetch_assoc()) {
        $columns[] = $row['Field'];
    }
    
    $required_columns = ['id', 'hospital_name', 'address', 'city', 'state'];
    $missing_columns = array_diff($required_columns, $columns);
    
    if (empty($missing_columns)) {
        echo "✅ All required columns exist: " . implode(', ', $required_columns) . "<br>";
    } else {
        echo "❌ Missing columns: " . implode(', ', $missing_columns) . "<br>";
    }
    echo "Available columns: " . implode(', ', $columns) . "<br>";
} else {
    echo "❌ Error checking hospitals table: " . $conn->error . "<br>";
}

// Test 2: Check vaccines table structure
echo "<br><h3>Test 2: Vaccines Table Structure</h3>";
$result = $conn->query("DESCRIBE vaccines");
if ($result) {
    echo "✅ Vaccines table exists<br>";
    $columns = [];
    while ($row = $result->fetch_assoc()) {
        $columns[] = $row['Field'];
    }
    echo "Available columns: " . implode(', ', $columns) . "<br>";
} else {
    echo "❌ Error checking vaccines table: " . $conn->error . "<br>";
}

// Test 3: Check hospital_vaccines table structure
echo "<br><h3>Test 3: Hospital_Vaccines Table Structure</h3>";
$result = $conn->query("DESCRIBE hospital_vaccines");
if ($result) {
    echo "✅ Hospital_vaccines table exists<br>";
    $columns = [];
    while ($row = $result->fetch_assoc()) {
        $columns[] = $row['Field'];
    }
    echo "Available columns: " . implode(', ', $columns) . "<br>";
} else {
    echo "❌ Error checking hospital_vaccines table: " . $conn->error . "<br>";
}

// Test 4: Test the fixed appointment query (without executing)
echo "<br><h3>Test 4: Fixed Appointment Query</h3>";
$test_query = "
    SELECT a.*, h.hospital_name, CONCAT(h.address, ', ', h.city, ', ', h.state) as location 
    FROM appointments a 
    JOIN hospitals h ON a.hospital_id = h.id 
    WHERE a.patient_id = 1 
    ORDER BY a.appointment_date DESC, a.appointment_time DESC 
    LIMIT 5
";

$stmt = $conn->prepare($test_query);
if ($stmt) {
    echo "✅ Appointment query syntax is valid<br>";
} else {
    echo "❌ Appointment query error: " . $conn->error . "<br>";
}

// Test 5: Test the fixed vaccination query (without executing)
echo "<br><h3>Test 5: Fixed Vaccination Query</h3>";
$test_query = "
    SELECT v.*, vac.vaccine_name, h.hospital_name 
    FROM vaccinations v 
    JOIN vaccines vac ON v.vaccine_id = vac.id 
    JOIN hospitals h ON v.hospital_id = h.id 
    WHERE v.patient_id = 1 
    ORDER BY v.vaccination_date DESC
";

$stmt = $conn->prepare($test_query);
if ($stmt) {
    echo "✅ Vaccination query syntax is valid<br>";
} else {
    echo "❌ Vaccination query error: " . $conn->error . "<br>";
}

// Test 6: Test the fixed vaccine stock insert query
echo "<br><h3>Test 6: Fixed Vaccine Stock Insert Query</h3>";
$test_query = "INSERT INTO hospital_vaccines (vaccine_id, hospital_id, stock_quantity) VALUES (?, ?, ?)";

$stmt = $conn->prepare($test_query);
if ($stmt) {
    echo "✅ Vaccine stock insert query syntax is valid<br>";
} else {
    echo "❌ Vaccine stock insert query error: " . $conn->error . "<br>";
}

echo "<br><h3>Summary</h3>";
echo "All database queries have been fixed to use the correct column names and foreign key references.<br>";
echo "• Fixed h.location to CONCAT(h.address, ', ', h.city, ', ', h.state)<br>";
echo "• Fixed h.hospital_id to h.id (hospitals table primary key)<br>";
echo "• Fixed vac.vaccine_id to vac.id (vaccines table primary key)<br>";
echo "• Fixed INSERT INTO hospital_vaccines parameter binding<br>";
echo "• Added proper error handling<br>";

$conn->close();
?>
