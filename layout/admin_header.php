<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-0">
    <div class="container-fluid">
        <a class="navbar-brand" href="admin_dashboard.php">
            <i class="fas fa-shield-virus me-2"></i>COVID Portal Admin
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="admin_dashboard.php">
                        <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="hospitalDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-hospital me-1"></i>Hospitals
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="admin_hospitals.php">
                            <i class="fas fa-list me-2"></i>All Hospitals
                        </a></li>
                        <li><a class="dropdown-item" href="admin_hospitals.php?status=pending">
                            <i class="fas fa-clock me-2"></i>Pending Approval
                        </a></li>
                        <li><a class="dropdown-item" href="admin_hospitals.php?status=approved">
                            <i class="fas fa-check-circle me-2"></i>Approved
                        </a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="usersDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-users me-1"></i>Users
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="admin_patients.php">
                            <i class="fas fa-user-injured me-2"></i>Patients
                        </a></li>
                        <li><a class="dropdown-item" href="admin_doctors.php">
                            <i class="fas fa-user-md me-2"></i>Doctors
                        </a></li>
                        <li><a class="dropdown-item" href="admin_users.php">
                            <i class="fas fa-users-cog me-2"></i>All Users
                        </a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="reportsDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-chart-bar me-1"></i>Reports
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="admin_reports.php?type=appointments">
                            <i class="fas fa-calendar-check me-2"></i>Appointments
                        </a></li>
                        <li><a class="dropdown-item" href="admin_reports.php?type=tests">
                            <i class="fas fa-vial me-2"></i>COVID Tests
                        </a></li>
                        <li><a class="dropdown-item" href="admin_reports.php?type=vaccinations">
                            <i class="fas fa-syringe me-2"></i>Vaccinations
                        </a></li>
                    </ul>
                </li>
            </ul>
            
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle me-1"></i>
                        <?php echo htmlspecialchars($_SESSION['user']); ?>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="admin_profile.php">
                            <i class="fas fa-user me-2"></i>Profile
                        </a></li>
                        <li><a class="dropdown-item" href="admin_settings.php">
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
