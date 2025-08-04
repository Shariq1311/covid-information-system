CREATE DATABASE IF NOT EXISTS covid_portal;
USE covid_portal;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS patients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    age INT,
    gender ENUM('Male', 'Female', 'Other'),
    diagnosis_date DATE,
    status ENUM('Active', 'Recovered', 'Deceased'),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
