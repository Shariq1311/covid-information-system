<nav class="navbar navbar-expand-lg navbar-dark bg-success mb-0">
    <div class="container-fluid">
        <a class="navbar-brand" href="doctor_dashboard.php">
            <i class="fas fa-user-md me-2"></i>COVID Portal Doctor
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="doctor_dashboard.php">
                        <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="patientsDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-users me-1"></i>Patients
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="doctor_patients.php">
                            <i class="fas fa-list me-2"></i>All Patients
                        </a></li>
                        <li><a class="dropdown-item" href="doctor_appointments.php">
                            <i class="fas fa-calendar-alt me-2"></i>Appointments
                        </a></li>
                        <li><a class="dropdown-item" href="doctor_schedule.php">
                            <i class="fas fa-clock me-2"></i>My Schedule
                        </a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="medicalDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-stethoscope me-1"></i>Medical Records
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="doctor_test_results.php">
                            <i class="fas fa-vial me-2"></i>Test Results
                        </a></li>
                        <li><a class="dropdown-item" href="doctor_prescriptions.php">
                            <i class="fas fa-prescription-bottle me-2"></i>Prescriptions
                        </a></li>
                        <li><a class="dropdown-item" href="doctor_vaccinations.php">
                            <i class="fas fa-syringe me-2"></i>Vaccinations
                        </a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="doctor_reports.php">
                        <i class="fas fa-chart-line me-1"></i>Reports
                    </a>
                </li>
            </ul>
            
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle me-1"></i>
                        Dr. <?php echo htmlspecialchars($_SESSION['user']); ?>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="doctor_profile.php">
                            <i class="fas fa-user me-2"></i>Profile
                        </a></li>
                        <li><a class="dropdown-item" href="doctor_settings.php">
                            <i class="fas fa-cog me-2"></i>Settings
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="logout.php">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
