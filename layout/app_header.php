<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : 'COVID-19 Portal'; ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
    
    <?php if(isset($additional_css)) echo $additional_css; ?>
</head>
<body>

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary" href="index_new.php">
            <i class="fas fa-shield-virus me-2"></i>
            COVID-19 Portal
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index_new.php">
                        <i class="fas fa-home me-1"></i>Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="public_hospital_search.php">
                        <i class="fas fa-search me-1"></i>Find Hospitals
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="infoDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-info-circle me-1"></i>COVID Info
                    </a>
                    <ul class="dropdown-menu">
                        <li><h6 class="dropdown-header">Health Information</h6></li>
                        <li><a class="dropdown-item" href="symptoms.php">
                            <i class="fas fa-thermometer-half me-2"></i>Symptoms & Signs
                        </a></li>
                        <li><a class="dropdown-item" href="prevention.php">
                            <i class="fas fa-shield-alt me-2"></i>Prevention Guidelines
                        </a></li>
                        <li><a class="dropdown-item" href="about.php">
                            <i class="fas fa-info-circle me-2"></i>About COVID-19
                        </a></li>
                        
                        <li><hr class="dropdown-divider"></li>
                        <li><h6 class="dropdown-header">Services & Testing</h6></li>
                        <li><a class="dropdown-item" href="patient_search.php?service=testing">
                            <i class="fas fa-vial me-2"></i>COVID-19 Testing
                        </a></li>
                        <li><a class="dropdown-item" href="patient_search.php?service=vaccination">
                            <i class="fas fa-syringe me-2"></i>Vaccination Centers
                        </a></li>
                        <li><a class="dropdown-item" href="patient_search.php">
                            <i class="fas fa-hospital me-2"></i>Find Hospitals
                        </a></li>
                        
                        <li><hr class="dropdown-divider"></li>
                        <li><h6 class="dropdown-header">Resources</h6></li>
                        <li><a class="dropdown-item" href="blog.php">
                            <i class="fas fa-newspaper me-2"></i>Health Blog
                        </a></li>
                        <li><a class="dropdown-item" href="#" onclick="showQuickStats()">
                            <i class="fas fa-chart-bar me-2"></i>Live Statistics
                        </a></li>
                        <li><a class="dropdown-item" href="#" onclick="showEmergencyContacts()">
                            <i class="fas fa-phone-alt me-2"></i>Emergency Contacts
                        </a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="contact.php">
                        <i class="fas fa-envelope me-1"></i>Contact
                    </a>
                </li>
            </ul>
            
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle fw-bold" href="#" id="authDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-sign-in-alt me-1"></i>Portal Access
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><h6 class="dropdown-header">Patient Portal</h6></li>
                        <li><a class="dropdown-item" href="login_new.php">
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
                        <li><a class="dropdown-item" href="login_new.php">
                            <i class="fas fa-user-md me-2"></i>Doctor Login
                        </a></li>
                        <li><a class="dropdown-item" href="login_new.php">
                            <i class="fas fa-building me-2"></i>Hospital Login
                        </a></li>
                        
                        <li><hr class="dropdown-divider"></li>
                        <li><h6 class="dropdown-header">Administration</h6></li>
                        <li><a class="dropdown-item" href="login_new.php?role=admin">
                            <i class="fas fa-user-shield me-2"></i>Admin Portal
                        </a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
