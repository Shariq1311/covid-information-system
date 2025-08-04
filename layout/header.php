<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-0 shadow">
  <div class="container">
    <a class="navbar-brand fw-bold" href="index.php">
      <i class="fas fa-shield-virus me-2"></i>COVID-19 Portal
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
        <li class="nav-item"><a class="nav-link" href="symptoms.php">Symptoms</a></li>
        <li class="nav-item"><a class="nav-link" href="prevention.php">Prevention</a></li>
        <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
      </ul>
      <ul class="navbar-nav">
        <?php if (isset($_SESSION['user'])): ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
              <i class="fas fa-user-circle me-1"></i>
              <?php echo htmlspecialchars($_SESSION['user']); ?>
            </a>
            <ul class="dropdown-menu">
              <?php if ($_SESSION['role'] === 'admin'): ?>
                <li><a class="dropdown-item" href="admin_dashboard.php">
                  <i class="fas fa-tachometer-alt me-2"></i>Admin Dashboard
                </a></li>
              <?php elseif ($_SESSION['role'] === 'hospital'): ?>
                <li><a class="dropdown-item" href="hospital_dashboard.php">
                  <i class="fas fa-hospital me-2"></i>Hospital Dashboard
                </a></li>
              <?php elseif ($_SESSION['role'] === 'doctor'): ?>
                <li><a class="dropdown-item" href="doctor_dashboard.php">
                  <i class="fas fa-user-md me-2"></i>Doctor Dashboard
                </a></li>
              <?php else: ?>
                <li><a class="dropdown-item" href="patient_dashboard_new.php">
                  <i class="fas fa-user me-2"></i>Patient Dashboard
                </a></li>
                <li><a class="dropdown-item" href="patient_search.php">
                  <i class="fas fa-search me-2"></i>Search Hospitals
                </a></li>
              <?php endif; ?>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="logout.php">
                <i class="fas fa-sign-out-alt me-2"></i>Logout
              </a></li>
            </ul>
          </li>
        <?php else: ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="portalDropdown" role="button" data-bs-toggle="dropdown">
              <i class="fas fa-sign-in-alt me-1"></i>Portal Access
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><h6 class="dropdown-header">Patient Portal</h6></li>
              <li><a class="dropdown-item" href="login.php">
                <i class="fas fa-user me-2"></i>Patient Login
              </a></li>
              <li><a class="dropdown-item" href="register.php">
                <i class="fas fa-user-plus me-2"></i>Patient Register
              </a></li>
              
              <li><hr class="dropdown-divider"></li>
              <li><h6 class="dropdown-header">Healthcare Providers</h6></li>
              <li><a class="dropdown-item" href="hospital_register.php">
                <i class="fas fa-hospital me-2"></i>Hospital Registration
              </a></li>
              <li><a class="dropdown-item" href="login.php?role=doctor">
                <i class="fas fa-user-md me-2"></i>Doctor Login
              </a></li>
              <li><a class="dropdown-item" href="login.php?role=hospital">
                <i class="fas fa-building me-2"></i>Hospital Login
              </a></li>
              
              <li><hr class="dropdown-divider"></li>
              <li><h6 class="dropdown-header">Administration</h6></li>
              <li><a class="dropdown-item" href="login.php?role=admin">
                <i class="fas fa-user-shield me-2"></i>Admin Portal
              </a></li>
            </ul>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

