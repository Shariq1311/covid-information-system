<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - COVID-19 Portal</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            line-height: 1.6;
        }
        
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 80px 0;
        }
        
        .team-card {
            transition: transform 0.3s ease;
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .team-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }
        
        .value-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }
    </style>
</head>
<body>
    <?php include('./layout/app_header.php'); ?>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="display-4 fw-bold mb-4">About Our COVID-19 Portal</h1>
                    <p class="lead mb-4">
                        We're dedicated to providing accessible, reliable COVID-19 healthcare services 
                        to communities across the region through our comprehensive digital platform.
                    </p>
                    <div class="d-flex flex-wrap justify-content-center gap-3">
                        <a href="register.php" class="btn btn-light btn-lg">
                            <i class="fas fa-user-plus me-2"></i>Join Our Platform
                        </a>
                        <a href="patient_search.php" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-search me-2"></i>Find Services
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Mission Section -->
    <section class="py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h2 class="display-6 fw-bold mb-4">Our Mission</h2>
                    <p class="lead text-muted mb-4">
                        To provide accessible, reliable, and comprehensive COVID-19 healthcare services 
                        through innovative digital solutions that connect patients with trusted healthcare providers.
                    </p>
                    <ul class="list-unstyled">
                        <li class="mb-3">
                            <i class="fas fa-check-circle text-success me-3"></i>
                            <strong>Accessibility:</strong> Easy access to COVID-19 testing and vaccination
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-check-circle text-success me-3"></i>
                            <strong>Reliability:</strong> Partnered with verified healthcare providers
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-check-circle text-success me-3"></i>
                            <strong>Comprehensive:</strong> Complete healthcare ecosystem in one platform
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-check-circle text-success me-3"></i>
                            <strong>Innovation:</strong> Cutting-edge technology for better healthcare
                        </li>
                    </ul>
                </div>
                <div class="col-lg-6 text-center">
                    <div class="position-relative">
                        <i class="fas fa-globe-americas display-1 text-primary opacity-25"></i>
                        <div class="position-absolute top-50 start-50 translate-middle">
                            <i class="fas fa-heart text-danger fa-4x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Values Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-8 mx-auto">
                    <h2 class="display-6 fw-bold">Our Core Values</h2>
                    <p class="lead text-muted">
                        The principles that guide everything we do
                    </p>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-md-6 col-lg-3 text-center">
                    <div class="value-icon bg-primary bg-opacity-10">
                        <i class="fas fa-shield-alt fa-2x text-primary"></i>
                    </div>
                    <h5 class="fw-bold">Safety First</h5>
                    <p class="text-muted">
                        Ensuring the highest safety standards in all our healthcare services and partnerships.
                    </p>
                </div>
                
                <div class="col-md-6 col-lg-3 text-center">
                    <div class="value-icon bg-success bg-opacity-10">
                        <i class="fas fa-users fa-2x text-success"></i>
                    </div>
                    <h5 class="fw-bold">Community</h5>
                    <p class="text-muted">
                        Building a strong community focused on collective health and wellbeing.
                    </p>
                </div>
                
                <div class="col-md-6 col-lg-3 text-center">
                    <div class="value-icon bg-info bg-opacity-10">
                        <i class="fas fa-lightbulb fa-2x text-info"></i>
                    </div>
                    <h5 class="fw-bold">Innovation</h5>
                    <p class="text-muted">
                        Leveraging technology to make healthcare more accessible and efficient.
                    </p>
                </div>
                
                <div class="col-md-6 col-lg-3 text-center">
                    <div class="value-icon bg-warning bg-opacity-10">
                        <i class="fas fa-handshake fa-2x text-warning"></i>
                    </div>
                    <h5 class="fw-bold">Trust</h5>
                    <p class="text-muted">
                        Building trust through transparency, reliability, and excellent service.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- What We Do Section -->
    <section class="py-5">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-8 mx-auto">
                    <h2 class="display-6 fw-bold">What We Do</h2>
                    <p class="lead text-muted">
                        Comprehensive COVID-19 healthcare services for everyone
                    </p>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                                    <i class="fas fa-vial fa-2x text-primary"></i>
                                </div>
                                <h4 class="card-title mb-0">COVID-19 Testing</h4>
                            </div>
                            <p class="card-text text-muted">
                                We facilitate booking of RT-PCR, Rapid Antigen, and Antibody tests at certified 
                                laboratories. Get accurate results delivered securely to your portal.
                            </p>
                            <a href="patient_search.php?service=testing" class="btn btn-primary">
                                <i class="fas fa-calendar me-2"></i>Book Test
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-success bg-opacity-10 rounded-circle p-3 me-3">
                                    <i class="fas fa-syringe fa-2x text-success"></i>
                                </div>
                                <h4 class="card-title mb-0">Vaccination Services</h4>
                            </div>
                            <p class="card-text text-muted">
                                Schedule your COVID-19 vaccination at approved healthcare centers. 
                                Track your vaccination history and download certificates instantly.
                            </p>
                            <a href="patient_search.php?service=vaccination" class="btn btn-success">
                                <i class="fas fa-calendar-plus me-2"></i>Book Vaccination
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-info bg-opacity-10 rounded-circle p-3 me-3">
                                    <i class="fas fa-hospital fa-2x text-info"></i>
                                </div>
                                <h4 class="card-title mb-0">Hospital Network</h4>
                            </div>
                            <p class="card-text text-muted">
                                Access our extensive network of verified hospitals and healthcare providers. 
                                Find the best care options in your area with real-time availability.
                            </p>
                            <a href="patient_search.php" class="btn btn-info">
                                <i class="fas fa-search me-2"></i>Find Hospitals
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-warning bg-opacity-10 rounded-circle p-3 me-3">
                                    <i class="fas fa-user-md fa-2x text-warning"></i>
                                </div>
                                <h4 class="card-title mb-0">Healthcare Providers</h4>
                            </div>
                            <p class="card-text text-muted">
                                Connect with qualified doctors and healthcare professionals. 
                                Get expert consultations and follow-up care through our platform.
                            </p>
                            <a href="login.php" class="btn btn-warning">
                                <i class="fas fa-sign-in-alt me-2"></i>Provider Portal
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics Section -->
    <section class="py-5 bg-primary text-white">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-8 mx-auto">
                    <h2 class="display-6 fw-bold text-white">Our Impact</h2>
                    <p class="lead text-white-50">
                        Making a difference in COVID-19 healthcare across the region
                    </p>
                </div>
            </div>
            
            <div class="row text-center">
                <div class="col-md-3 mb-4">
                    <div class="mb-3">
                        <i class="fas fa-users fa-3x"></i>
                    </div>
                    <h3 class="fw-bold">10,000+</h3>
                    <p class="text-white-50">Registered Patients</p>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="mb-3">
                        <i class="fas fa-hospital fa-3x"></i>
                    </div>
                    <h3 class="fw-bold">200+</h3>
                    <p class="text-white-50">Partner Hospitals</p>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="mb-3">
                        <i class="fas fa-vial fa-3x"></i>
                    </div>
                    <h3 class="fw-bold">50,000+</h3>
                    <p class="text-white-50">Tests Conducted</p>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="mb-3">
                        <i class="fas fa-syringe fa-3x"></i>
                    </div>
                    <h3 class="fw-bold">25,000+</h3>
                    <p class="text-white-50">Vaccinations Given</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row text-center">
                <div class="col-lg-8 mx-auto">
                    <h2 class="display-6 fw-bold mb-4">Join Our Mission</h2>
                    <p class="lead text-muted mb-4">
                        Be part of the solution in fighting COVID-19. Whether you're a patient seeking care 
                        or a healthcare provider wanting to help, we have a place for you.
                    </p>
                    <div class="d-flex flex-wrap justify-content-center gap-3">
                        <a href="register.php" class="btn btn-primary btn-lg px-5">
                            <i class="fas fa-user-plus me-2"></i>Register as Patient
                        </a>
                        <a href="hospital_register.php" class="btn btn-success btn-lg px-5">
                            <i class="fas fa-hospital me-2"></i>Register Hospital
                        </a>
                        <a href="login.php" class="btn btn-outline-primary btn-lg px-5">
                            <i class="fas fa-sign-in-alt me-2"></i>Portal Login
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include('./layout/app_footer.php'); ?>
  <script src="js/owl.carousel.min.js"></script>
  <script src="js/jquery.countdown.min.js"></script>
  <script src="js/jquery.easing.1.3.js"></script>
  <script src="js/aos.js"></script>
  <script src="js/jquery.fancybox.min.js"></script>
  <script src="js/jquery.sticky.js"></script>
  <script src="js/isotope.pkgd.min.js"></script>


  <script src="js/main.js"></script>


</body>
</html>