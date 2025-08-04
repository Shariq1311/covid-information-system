<?php
$page_title = "COVID-19 Portal - Online Testing & Vaccination Booking";

// Check for logout success message
$logout_success = isset($_GET['logout']) && $_GET['logout'] === 'success';

$additional_css = "
<style>
    body {
        font-family: 'Roboto', sans-serif;
        line-height: 1.6;
    }
    .hero-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 100px }
    .feature-card {
        transition: transform 0.3s ease;
        border: none;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .feature-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    .stats-section {
        background: #f8f9fa;
        padding: 60px 0;
    }
    .cta-section {
        background: linear-gradient(45deg, #28a745, #20c997);
        color: white;
        padding: 80px 0;
    }
</style>
";
include('./layout/app_header.php'); 
?>

    <?php if ($logout_success): ?>
    <!-- Logout Success Message -->
    <div class="container mt-3">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>You have been successfully logged out.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
    <?php endif; ?>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">
                        Fight COVID-19 Together
                    </h1>
                    <p class="lead mb-4">
                        Your one-stop portal for COVID-19 testing, vaccination booking, and health monitoring. 
                        Connect with approved hospitals and healthcare providers in your area.
                    </p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="register.php" class="btn btn-light btn-lg px-4">
                            <i class="fas fa-user-plus me-2"></i>Get Started
                        </a>
                        <a href="public_hospital_search.php" class="btn btn-outline-light btn-lg px-4">
                            <i class="fas fa-search me-2"></i>Find Hospitals
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <div class="position-relative">
                        <i class="fas fa-shield-virus display-1 text-white-50"></i>
                        <div class="position-absolute top-50 start-50 translate-middle">
                            <i class="fas fa-heartbeat text-danger fa-3x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-8 mx-auto">
                    <h2 class="display-5 fw-bold">Our Services</h2>
                    <p class="lead text-muted">
                        Comprehensive COVID-19 healthcare services at your fingertips
                    </p>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card feature-card h-100 text-center p-4">
                        <div class="card-body">
                            <div class="mb-3">
                                <i class="fas fa-vial fa-3x text-info"></i>
                            </div>
                            <h4 class="card-title">COVID-19 Testing</h4>
                            <p class="card-text">
                                Book RT-PCR, Rapid Antigen, and Antibody tests at approved laboratories. 
                                Get results quickly and securely.
                            </p>
                            <a href="public_hospital_search.php?service=testing" class="btn btn-info">
                                <i class="fas fa-calendar me-2"></i>Book Test
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card feature-card h-100 text-center p-4">
                        <div class="card-body">
                            <div class="mb-3">
                                <i class="fas fa-syringe fa-3x text-success"></i>
                            </div>
                            <h4 class="card-title">Vaccination</h4>
                            <p class="card-text">
                                Schedule your COVID-19 vaccination at nearby hospitals. 
                                Track your vaccination history and certificates.
                            </p>
                            <a href="public_hospital_search.php?service=vaccination" class="btn btn-success">
                                <i class="fas fa-calendar-plus me-2"></i>Book Vaccination
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card feature-card h-100 text-center p-4">
                        <div class="card-body">
                            <div class="mb-3">
                                <i class="fas fa-hospital fa-3x text-primary"></i>
                            </div>
                            <h4 class="card-title">Hospital Network</h4>
                            <p class="card-text">
                                Access a wide network of approved hospitals and healthcare providers. 
                                Find the best care in your area.
                            </p>
                            <a href="public_hospital_search.php" class="btn btn-primary">
                                <i class="fas fa-search me-2"></i>Find Hospitals
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-3 mb-4">
                    <div class="mb-3">
                        <i class="fas fa-users fa-3x text-primary"></i>
                    </div>
                    <h3 class="fw-bold">10,000+</h3>
                    <p class="text-muted">Registered Patients</p>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="mb-3">
                        <i class="fas fa-hospital fa-3x text-success"></i>
                    </div>
                    <h3 class="fw-bold">200+</h3>
                    <p class="text-muted">Partner Hospitals</p>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="mb-3">
                        <i class="fas fa-vial fa-3x text-info"></i>
                    </div>
                    <h3 class="fw-bold">50,000+</h3>
                    <p class="text-muted">Tests Conducted</p>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="mb-3">
                        <i class="fas fa-syringe fa-3x text-warning"></i>
                    </div>
                    <h3 class="fw-bold">25,000+</h3>
                    <p class="text-muted">Vaccinations Given</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="py-5">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-8 mx-auto">
                    <h2 class="display-5 fw-bold">How It Works</h2>
                    <p class="lead text-muted">Simple steps to get the care you need</p>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-md-3 text-center">
                    <div class="mb-3">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <span class="fw-bold">1</span>
                        </div>
                    </div>
                    <h5>Register</h5>
                    <p class="text-muted">Create your account with basic information</p>
                </div>
                <div class="col-md-3 text-center">
                    <div class="mb-3">
                        <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <span class="fw-bold">2</span>
                        </div>
                    </div>
                    <h5>Search</h5>
                    <p class="text-muted">Find hospitals and services in your area</p>
                </div>
                <div class="col-md-3 text-center">
                    <div class="mb-3">
                        <div class="bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <span class="fw-bold">3</span>
                        </div>
                    </div>
                    <h5>Book</h5>
                    <p class="text-muted">Schedule your appointment online</p>
                </div>
                <div class="col-md-3 text-center">
                    <div class="mb-3">
                        <div class="bg-warning text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <span class="fw-bold">4</span>
                        </div>
                    </div>
                    <h5>Get Care</h5>
                    <p class="text-muted">Receive your test or vaccination</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Safety Guidelines Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-8 mx-auto">
                    <h2 class="display-5 fw-bold">Stay Safe, Stay Protected</h2>
                    <p class="lead text-muted">Follow these guidelines to protect yourself and others</p>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-md-3 text-center">
                    <div class="mb-3">
                        <i class="fas fa-head-side-mask fa-4x text-primary"></i>
                    </div>
                    <h5>Wear Masks</h5>
                    <p class="text-muted">Always wear a mask in public places and around others</p>
                </div>
                <div class="col-md-3 text-center">
                    <div class="mb-3">
                        <i class="fas fa-hands-wash fa-4x text-success"></i>
                    </div>
                    <h5>Wash Hands</h5>
                    <p class="text-muted">Wash your hands frequently with soap and water for 20 seconds</p>
                </div>
                <div class="col-md-3 text-center">
                    <div class="mb-3">
                        <i class="fas fa-people-arrows fa-4x text-info"></i>
                    </div>
                    <h5>Social Distance</h5>
                    <p class="text-muted">Maintain at least 6 feet distance from others</p>
                </div>
                <div class="col-md-3 text-center">
                    <div class="mb-3">
                        <i class="fas fa-shield-virus fa-4x text-warning"></i>
                    </div>
                    <h5>Get Vaccinated</h5>
                    <p class="text-muted">Get your COVID-19 vaccination to protect yourself and others</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action Section -->
    <section class="cta-section">
        <div class="container">
            <div class="row text-center">
                <div class="col-lg-8 mx-auto">
                    <h2 class="display-5 fw-bold mb-4">Ready to Get Started?</h2>
                    <p class="lead mb-4">
                        Join thousands of people who trust our platform for their COVID-19 healthcare needs.
                    </p>
                    <div class="d-flex flex-wrap justify-content-center gap-3">
                        <a href="register.php" class="btn btn-light btn-lg px-5">
                            <i class="fas fa-user-plus me-2"></i>Register Now
                        </a>
                        <a href="hospital_register.php" class="btn btn-outline-light btn-lg px-5">
                            <i class="fas fa-hospital me-2"></i>Register Hospital
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php 
$additional_js = "
<script>
    // Add smooth scrolling
    document.querySelectorAll('a[href^=\"#\"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });

    // Add animation on scroll
    window.addEventListener('scroll', () => {
        const elements = document.querySelectorAll('.feature-card');
        elements.forEach(element => {
            const elementTop = element.getBoundingClientRect().top;
            if (elementTop < window.innerHeight - 100) {
                element.style.opacity = '1';
                element.style.transform = 'translateY(0)';
            }
        });
    });
</script>
";
include('./layout/app_footer.php'); 
?>
