-- Complete COVID-19 Portal Database Schema
-- Drop existing database and recreate with complete structure

DROP DATABASE IF EXISTS covid_portal;
CREATE DATABASE covid_portal;
USE covid_portal;

-- Users table with extended roles
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'hospital', 'patient') NOT NULL DEFAULT 'patient',
    full_name VARCHAR(150) NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    city VARCHAR(100),
    state VARCHAR(100),
    status ENUM('active', 'inactive', 'pending') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Hospitals table
CREATE TABLE hospitals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    hospital_name VARCHAR(200) NOT NULL,
    registration_number VARCHAR(100) UNIQUE,
    license_number VARCHAR(100),
    contact_person VARCHAR(150),
    phone VARCHAR(20),
    email VARCHAR(150),
    address TEXT NOT NULL,
    city VARCHAR(100) NOT NULL,
    state VARCHAR(100) NOT NULL,
    pincode VARCHAR(10),
    latitude DECIMAL(10, 8),
    longitude DECIMAL(11, 8),
    facilities TEXT,
    specializations TEXT,
    bed_capacity INT DEFAULT 0,
    available_beds INT DEFAULT 0,
    covid_testing BOOLEAN DEFAULT TRUE,
    vaccination_available BOOLEAN DEFAULT TRUE,
    approval_status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    approved_by INT,
    approved_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (approved_by) REFERENCES users(id)
);

-- Patients table (extended)
CREATE TABLE patients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    patient_id VARCHAR(50) UNIQUE,
    full_name VARCHAR(150) NOT NULL,
    date_of_birth DATE,
    age INT,
    gender ENUM('Male', 'Female', 'Other') NOT NULL,
    blood_group VARCHAR(5),
    phone VARCHAR(20),
    email VARCHAR(150),
    address TEXT,
    city VARCHAR(100),
    state VARCHAR(100),
    pincode VARCHAR(10),
    emergency_contact_name VARCHAR(150),
    emergency_contact_phone VARCHAR(20),
    medical_history TEXT,
    allergies TEXT,
    current_medications TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Vaccines table
CREATE TABLE vaccines (
    id INT AUTO_INCREMENT PRIMARY KEY,
    vaccine_name VARCHAR(100) NOT NULL,
    manufacturer VARCHAR(100),
    vaccine_type VARCHAR(50),
    doses_required INT DEFAULT 1,
    gap_between_doses INT DEFAULT 0, -- in days
    efficacy_rate DECIMAL(5,2),
    storage_temperature VARCHAR(50),
    description TEXT,
    side_effects TEXT,
    availability_status ENUM('available', 'limited', 'unavailable') DEFAULT 'available',
    price DECIMAL(10,2) DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Hospital Vaccine Inventory
CREATE TABLE hospital_vaccines (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hospital_id INT,
    vaccine_id INT,
    stock_quantity INT DEFAULT 0,
    price DECIMAL(10,2) DEFAULT 0.00,
    batch_number VARCHAR(100),
    expiry_date DATE,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (hospital_id) REFERENCES hospitals(id) ON DELETE CASCADE,
    FOREIGN KEY (vaccine_id) REFERENCES vaccines(id) ON DELETE CASCADE
);

-- COVID Tests
CREATE TABLE covid_tests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT,
    hospital_id INT,
    test_type ENUM('RT-PCR', 'Rapid Antigen', 'Antibody') NOT NULL,
    test_date DATE NOT NULL,
    sample_collection_date DATE,
    result ENUM('positive', 'negative', 'pending', 'inconclusive') DEFAULT 'pending',
    result_date DATE,
    symptoms TEXT,
    notes TEXT,
    test_cost DECIMAL(10,2) DEFAULT 0.00,
    payment_status ENUM('pending', 'paid', 'cancelled') DEFAULT 'pending',
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE,
    FOREIGN KEY (hospital_id) REFERENCES hospitals(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id)
);

-- Vaccinations
CREATE TABLE vaccinations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT,
    hospital_id INT,
    vaccine_id INT,
    dose_number INT NOT NULL,
    vaccination_date DATE NOT NULL,
    next_due_date DATE,
    batch_number VARCHAR(100),
    administered_by VARCHAR(150),
    site_of_injection VARCHAR(50),
    reactions TEXT,
    certificate_number VARCHAR(100) UNIQUE,
    status ENUM('scheduled', 'completed', 'missed', 'cancelled') DEFAULT 'scheduled',
    notes TEXT,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE,
    FOREIGN KEY (hospital_id) REFERENCES hospitals(id) ON DELETE CASCADE,
    FOREIGN KEY (vaccine_id) REFERENCES vaccines(id),
    FOREIGN KEY (created_by) REFERENCES users(id)
);

-- Appointments
CREATE TABLE appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT,
    hospital_id INT,
    appointment_type ENUM('covid_test', 'vaccination', 'consultation', 'follow_up') NOT NULL,
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    purpose TEXT,
    symptoms TEXT,
    priority ENUM('low', 'medium', 'high', 'urgent') DEFAULT 'medium',
    status ENUM('pending', 'confirmed', 'completed', 'cancelled', 'no_show') DEFAULT 'pending',
    notes TEXT,
    created_by INT,
    confirmed_by INT,
    confirmation_date TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE,
    FOREIGN KEY (hospital_id) REFERENCES hospitals(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id),
    FOREIGN KEY (confirmed_by) REFERENCES users(id)
);

-- Hospital Working Hours
CREATE TABLE hospital_hours (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hospital_id INT,
    day_of_week ENUM('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'),
    opening_time TIME,
    closing_time TIME,
    is_closed BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (hospital_id) REFERENCES hospitals(id) ON DELETE CASCADE
);

-- Hospital Services
CREATE TABLE hospital_services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hospital_id INT,
    service_name VARCHAR(100) NOT NULL,
    service_type ENUM('covid_test', 'vaccination', 'consultation', 'emergency') NOT NULL,
    description TEXT,
    price DECIMAL(10,2) DEFAULT 0.00,
    availability BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (hospital_id) REFERENCES hospitals(id) ON DELETE CASCADE
);

-- System Settings
CREATE TABLE system_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    description TEXT,
    updated_by INT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (updated_by) REFERENCES users(id)
);

-- Audit Log
CREATE TABLE audit_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    action VARCHAR(100) NOT NULL,
    table_name VARCHAR(50),
    record_id INT,
    old_values TEXT,
    new_values TEXT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Insert default admin user
INSERT INTO users (username, email, password, role, full_name, phone, status) VALUES 
('admin', 'admin@covidportal.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'System Administrator', '+1234567890', 'active');

-- Insert sample vaccines
INSERT INTO vaccines (vaccine_name, manufacturer, vaccine_type, doses_required, gap_between_doses, efficacy_rate, storage_temperature, description) VALUES
('Pfizer-BioNTech COVID-19', 'Pfizer-BioNTech', 'mRNA', 2, 21, 95.00, '-70°C', 'mRNA-based COVID-19 vaccine'),
('Moderna COVID-19', 'Moderna', 'mRNA', 2, 28, 94.10, '-20°C', 'mRNA-based COVID-19 vaccine'),
('AstraZeneca COVID-19', 'AstraZeneca', 'Viral Vector', 2, 84, 76.00, '2-8°C', 'Viral vector-based COVID-19 vaccine'),
('Johnson & Johnson COVID-19', 'Johnson & Johnson', 'Viral Vector', 1, 0, 66.30, '2-8°C', 'Single-dose viral vector vaccine');

-- Insert default system settings
INSERT INTO system_settings (setting_key, setting_value, description) VALUES
('site_name', 'COVID-19 Portal', 'Website name'),
('max_appointments_per_day', '50', 'Maximum appointments per hospital per day'),
('test_result_days', '2', 'Days to get test results'),
('vaccination_certificate_prefix', 'COVID-CERT-', 'Vaccination certificate number prefix'),
('appointment_advance_days', '30', 'How many days in advance appointments can be booked');
