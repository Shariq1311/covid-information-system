<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Hospitals - COVID-19 Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="index_new.php">
                <i class="fas fa-shield-virus me-2"></i>COVID-19 Portal
            </a>
        </div>
    </nav>

    <div class="container my-5">
        <h1>Hospital Search Test</h1>
        <p>This is a test page to check if the public hospital search works.</p>
        
        <?php
        include 'db.php';
        
        echo "<div class='alert alert-info'>";
        if ($conn->connect_error) {
            echo "❌ Database connection failed: " . $conn->connect_error;
        } else {
            echo "✅ Database connection successful<br>";
            
            // Test simple hospital query
            $result = $conn->query("SELECT hospital_name, city, state FROM hospitals WHERE approval_status = 'approved' LIMIT 3");
            if ($result && $result->num_rows > 0) {
                echo "✅ Found " . $result->num_rows . " hospitals:<br>";
                while ($row = $result->fetch_assoc()) {
                    echo "• " . htmlspecialchars($row['hospital_name']) . " - " . htmlspecialchars($row['city']) . ", " . htmlspecialchars($row['state']) . "<br>";
                }
            } else {
                echo "❌ No hospitals found or query error: " . $conn->error;
            }
        }
        echo "</div>";
        ?>
        
        <div class="mt-4">
            <a href="index_new.php" class="btn btn-primary">← Back to Homepage</a>
            <a href="public_hospital_search.php" class="btn btn-success">Try Full Search Page</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
