<?php
include 'db.php';

echo "Checking patients table structure:\n";
$result = $conn->query('DESCRIBE patients');
if ($result) {
    while ($row = $result->fetch_assoc()) {
        echo $row['Field'] . "\n";
    }
} else {
    echo 'Error: ' . $conn->error . "\n";
}

$conn->close();
?>
