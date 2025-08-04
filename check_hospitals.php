<?php
include 'db.php';

echo "Current hospitals in database:\n";
$result = $conn->query('SELECT hospital_name, city, state FROM hospitals LIMIT 10');
if ($result) {
    while ($row = $result->fetch_assoc()) {
        echo $row['hospital_name'] . ' - ' . $row['city'] . ', ' . $row['state'] . "\n";
    }
} else {
    echo 'Error: ' . $conn->error . "\n";
}

$conn->close();
?>
