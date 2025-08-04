<nav class="navbar navbar-expand-lg navbar-dark bg-info mb-0">
    <div class="container-fluid">
        <a class="navbar-brand" href="patient_dashboard.php">
            <i class="fas fa-user-shield me-2"></i>Patient Portal
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="patient_dashboard.php">
                        <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="patient_search.php">
                        <i class="fas fa-search me-1"></i>Search Hospitals
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="patient_appointments.php">
                        <i class="fas fa-calendar me-1"></i>My Appointments
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="patient_results.php">
                        <i class="fas fa-file-medical me-1"></i>Test Results
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="patient_vaccination.php">
                        <i class="fas fa-syringe me-1"></i>Vaccination
                    </a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle me-1"></i>
                        <?php echo htmlspecialchars($_SESSION['user']); ?>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="patient_profile.php">
                            <i class="fas fa-user me-2"></i>My Profile
                        </a></li>
                        <li><a class="dropdown-item" href="patient_medical_history.php">
                            <i class="fas fa-notes-medical me-2"></i>Medical History
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
