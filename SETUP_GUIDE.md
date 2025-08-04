# COVID-19 Portal Setup Guide

## Database Setup

1. **Import the Clean Database Schema**
   - Use the file: `clean_database_schema.sql` 
   - This file contains ONLY valid SQL commands
   - Import this file in phpMyAdmin or run it via MySQL command line

2. **Default Login Credentials**
   - **Admin Login:**
     - Username: `admin`
     - Email: `admin@covidportal.com`
     - Password: `password`

## System Architecture

### User Roles & Access
1. **Admin** (`admin_dashboard.php`)
   - Approve/reject hospital registrations
   - View all patients, hospitals, tests, vaccinations
   - Generate reports
   - Manage system settings

2. **Hospital** (`hospital_dashboard.php`)
   - Register hospital details
   - Manage patient appointments
   - Update test results
   - Record vaccinations
   - Manage inventory

3. **Patient** (`patient_dashboard.php`)
   - Search hospitals by location/services
   - Book appointments
   - View test results
   - Track vaccination history

4. **Doctor** (`doctor_dashboard.php`)
   - Basic medical dashboard (existing)

### Key Features Implemented

#### 🏥 Hospital Management
- **Registration**: Complete hospital profile with services
- **Approval Workflow**: Admin approval required
- **Service Management**: COVID testing & vaccination flags
- **Inventory Tracking**: Vaccine stock management

#### 👥 Patient Features
- **Hospital Search**: Filter by city, services, hospital name
- **Appointment Booking**: Real-time slot availability
- **Test Results**: View COVID test history
- **Vaccination Records**: Track doses and certificates

#### 📊 Admin Features
- **Hospital Approval**: Approve/reject registrations
- **Statistics Dashboard**: Real-time counts and analytics
- **Patient Management**: View all patient records
- **Report Generation**: Date-wise reports ready

### Database Tables Created
1. `users` - All user accounts with roles
2. `hospitals` - Hospital profiles and services
3. `patients` - Patient medical information
4. `appointments` - Appointment booking system
5. `covid_tests` - Test records and results
6. `vaccinations` - Vaccination history
7. `vaccines` - Available vaccine types
8. `hospital_vaccines` - Hospital inventory
9. `hospital_hours` - Operating schedules
10. `hospital_services` - Service offerings
11. `system_settings` - Configuration
12. `audit_log` - Activity tracking

## Getting Started

### Step 1: Database Setup
```sql
-- Import clean_database_schema.sql in phpMyAdmin
-- This creates the database and sample data
```

### Step 2: Test the System
1. **Admin Access**: Go to `login.php` → Use admin credentials
2. **Hospital Registration**: Go to `hospital_register.php`
3. **Patient Registration**: Go to `register.php` → Select "Patient"

### Step 3: Workflow Testing
1. Register a hospital → Admin approves → Hospital can login
2. Register a patient → Search hospitals → Book appointment
3. Hospital confirms appointment → Updates test results

## File Structure
```
/covid-master/
├── admin_dashboard.php          # Admin main dashboard
├── admin_hospitals.php          # Hospital management
├── hospital_dashboard.php       # Hospital main dashboard  
├── hospital_register.php        # Hospital registration
├── patient_dashboard_new.php    # Enhanced patient dashboard
├── patient_search.php           # Hospital search & filter
├── patient_appointment.php      # Appointment booking
├── clean_database_schema.sql    # CLEAN SQL file to import
├── layout/
│   ├── admin_header.php         # Admin navigation
│   ├── hospital_header.php      # Hospital navigation
│   └── patient_header.php       # Patient navigation
└── (existing files...)
```

## Next Steps to Complete
1. **Test Management**: Hospital staff update test results
2. **Vaccination Module**: Record vaccination doses
3. **Reports Module**: Generate PDF/Excel reports
4. **Email Notifications**: Appointment confirmations
5. **Payment Integration**: Online payment for tests

## Troubleshooting
- **SQL Error**: Use `clean_database_schema.sql` instead of `complete_database_schema.sql`
- **Login Issues**: Check if user status is 'active'
- **Permission Errors**: Verify role-based access in session
- **Missing Data**: Check if foreign key relationships are properly set

## Sample Data Included
- 1 Admin user (ready to use)
- 4 Sample vaccines (Pfizer, Moderna, AstraZeneca, J&J)
- System settings with default values
- Hospital working hours template

The system is now ready for testing and further development!
