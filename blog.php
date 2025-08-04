<?php
$page_title = "Health Blog - COVID-19 Portal";
$additional_css = "
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
    
    .blog-card {
        transition: transform 0.3s ease;
        border: none;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    .blog-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }
</style>
";
include('./layout/app_header.php'); 
?>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="display-4 fw-bold mb-4">Health Blog & Resources</h1>
                    <p class="lead mb-4">
                        Stay informed with the latest COVID-19 news, health tips, and medical insights 
                        from healthcare professionals.
                    </p>
                    <div class="d-flex flex-wrap justify-content-center gap-3">
                        <a href="register.php" class="btn btn-light btn-lg">
                            <i class="fas fa-user-plus me-2"></i>Join Our Community
                        </a>
                        <a href="patient_search.php" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-search me-2"></i>Find Healthcare Services
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Blog Articles Section -->
    <section class="py-5">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-8 mx-auto">
                    <h2 class="display-6 fw-bold">Latest Health Articles</h2>
                    <p class="lead text-muted">
                        Stay updated with expert insights and latest research on COVID-19
                    </p>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <div class="blog-card card h-100">
                        <img src="images/hero_1.jpg" class="card-img-top" alt="COVID-19 Prevention">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <span class="badge bg-primary me-2">Prevention</span>
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>July 28, 2025
                                </small>
                            </div>
                            <h5 class="card-title">Essential COVID-19 Prevention Guidelines</h5>
                            <p class="card-text text-muted">
                                Learn the most effective ways to protect yourself and your loved ones from COVID-19 with these expert-recommended prevention strategies.
                            </p>
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-user-md me-2 text-primary"></i>
                                    <small>Dr. Sarah Ahmed</small>
                                </div>
                                <a href="prevention.php" class="btn btn-sm btn-outline-primary">Read More</a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="blog-card card h-100">
                        <img src="images/hero_2.jpg" class="card-img-top" alt="COVID-19 Symptoms">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <span class="badge bg-warning me-2">Symptoms</span>
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>July 26, 2025
                                </small>
                            </div>
                            <h5 class="card-title">Recognizing COVID-19 Symptoms</h5>
                            <p class="card-text text-muted">
                                Understanding the signs and symptoms of COVID-19 can help you seek timely medical attention and prevent further spread.
                            </p>
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-user-md me-2 text-primary"></i>
                                    <small>Dr. Ahmed Khan</small>
                                </div>
                                <a href="symptoms.php" class="btn btn-sm btn-outline-primary">Read More</a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="blog-card card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <span class="badge bg-success me-2">Vaccination</span>
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>July 25, 2025
                                </small>
                            </div>
                            <h5 class="card-title">COVID-19 Vaccination: What You Need to Know</h5>
                            <p class="card-text text-muted">
                                Get the facts about COVID-19 vaccines, their effectiveness, side effects, and how to book your vaccination appointment.
                            </p>
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-user-md me-2 text-primary"></i>
                                    <small>Dr. Fatima Ali</small>
                                </div>
                                <a href="patient_search.php?service=vaccination" class="btn btn-sm btn-outline-primary">Book Now</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Additional Resources Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-8 mx-auto">
                    <h2 class="fw-bold">Stay Connected</h2>
                    <p class="text-muted">Join our community for the latest health updates and resources</p>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card text-center border-0 bg-white h-100">
                        <div class="card-body">
                            <i class="fas fa-bell fa-3x text-primary mb-3"></i>
                            <h5>Newsletter</h5>
                            <p class="text-muted">Get weekly health tips and COVID-19 updates</p>
                            <a href="register.php" class="btn btn-primary">Subscribe</a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card text-center border-0 bg-white h-100">
                        <div class="card-body">
                            <i class="fas fa-user-md fa-3x text-success mb-3"></i>
                            <h5>Expert Consultations</h5>
                            <p class="text-muted">Connect with healthcare professionals</p>
                            <a href="patient_search.php" class="btn btn-success">Find Doctors</a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card text-center border-0 bg-white h-100">
                        <div class="card-body">
                            <i class="fas fa-mobile-alt fa-3x text-info mb-3"></i>
                            <h5>Mobile App</h5>
                            <p class="text-muted">Access health services on the go</p>
                            <a href="#" class="btn btn-info">Coming Soon</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php include('./layout/app_footer.php'); ?>