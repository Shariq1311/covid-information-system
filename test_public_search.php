<?php
echo "Testing public hospital search functionality...\n";

// Test database connection
include 'db.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "✅ Database connection successful\n";

// Test hospital query
$search_query = "SELECT h.*, 
                        CASE 
                            WHEN h.covid_testing = 1 AND h.vaccination_available = 1 THEN 'Both Testing & Vaccination' 
                            WHEN h.covid_testing = 1 THEN 'COVID Testing' 
                            WHEN h.vaccination_available = 1 THEN 'Vaccination' 
                            ELSE 'General Services' 
                        END as services_offered
                 FROM hospitals h 
                 WHERE h.approval_status = 'approved'
                 ORDER BY h.city, h.hospital_name
                 LIMIT 5";

$stmt = $conn->prepare($search_query);
if ($stmt) {
    echo "✅ Query preparation successful\n";
    $stmt->execute();
    $hospitals = $stmt->get_result();
    
    echo "Found " . $hospitals->num_rows . " hospitals\n";
    
    while ($hospital = $hospitals->fetch_assoc()) {
        echo "- " . $hospital['hospital_name'] . " (" . $hospital['city'] . ")\n";
    }
} else {
    echo "❌ Query preparation failed: " . $conn->error . "\n";
}

// Test cities query
$cities_query = "SELECT DISTINCT city FROM hospitals WHERE approval_status = 'approved' ORDER BY city";
$cities_result = $conn->query($cities_query);

if ($cities_result) {
    echo "✅ Cities query successful\n";
    echo "Available cities: ";
    $cities = [];
    while ($city = $cities_result->fetch_assoc()) {
        $cities[] = $city['city'];
    }
    echo implode(", ", $cities) . "\n";
} else {
    echo "❌ Cities query failed: " . $conn->error . "\n";
}

$conn->close();
echo "\nTest completed successfully!\n";
?>
