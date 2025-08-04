<?php
/**
 * Database Configuration Example
 * 
 * Copy this file to database.php and update with your actual credentials
 * DO NOT commit database.php with real credentials to Git
 */

// Database Configuration
$host = "localhost";
$username = "your_database_username";
$password = "your_database_password";
$database = "covid_db";
$port = 3306;

// Database Connection Options
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

// Create PDO Connection
try {
    $dsn = "mysql:host=$host;dbname=$database;port=$port;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// For testing purposes (remove in production)
// echo "Database connected successfully!";
?>
