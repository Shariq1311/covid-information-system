<?php
$page_title = "COVID-19 Prevention - COVID-19 Portal";
include 'layout/app_header.php';
?>

<style>
    .hero-prevention {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        padding: 80px 0;
    }
    .prevention-card {
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .prevention-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    .step-number {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1.2rem;
        margin: 0 auto 1rem;
    }
</style>

<!-- Hero Section -->
<section class="hero-prevention">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">
                    <i class="fas fa-shield-alt me-3"></i>
                    COVID-19 Prevention
                </h1>
                <p class="lead mb-4">
                    Protect yourself and others by following these proven prevention strategies. Together, we can stop the spread.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="patient_search.php?service=vaccination" class="btn btn-light btn-lg px-4">
                        <i class="fas fa-syringe me-2"></i>Get Vaccinated
                    </a>
                    <a href="symptoms.php" class="btn btn-outline-light btn-lg px-4">
                        <i class="fas fa-thermometer-half me-2"></i>Know Symptoms
                    </a>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <img src="images/protect.png" alt="COVID-19 Prevention" class="img-fluid" style="max-height: 400px;">
            </div>
        </div>
    </div>
</section>

<!-- Essential Prevention Steps -->
<section class="py-5">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-lg-8 mx-auto">
                <h2 class="display-5 fw-bold">Essential Prevention Steps</h2>
                <p class="lead text-muted">
                    Follow these key steps to protect yourself and others from COVID-19.
                </p>
            </div>
        </div>
        
        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="prevention-card card h-100 p-4 text-center">
                    <div class="step-number bg-primary text-white">1</div>
                    <i class="fas fa-head-side-mask fa-3x text-primary mb-3"></i>
                    <h5>Wear a Mask</h5>
                    <p class="text-muted">
                        Wear a well-fitted mask that covers your nose and mouth in public settings.
                    </p>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-3">
                <div class="prevention-card card h-100 p-4 text-center">
                    <div class="step-number bg-success text-white">2</div>
                    <i class="fas fa-hands-wash fa-3x text-success mb-3"></i>
                    <h5>Wash Your Hands</h5>
                    <p class="text-muted">
                        Wash hands frequently with soap and water for at least 20 seconds.
                    </p>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-3">
                <div class="prevention-card card h-100 p-4 text-center">
                    <div class="step-number bg-info text-white">3</div>
                    <i class="fas fa-people-arrows fa-3x text-info mb-3"></i>
                    <h5>Social Distance</h5>
                    <p class="text-muted">
                        Maintain at least 6 feet distance from others who don't live with you.
                    </p>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-3">
                <div class="prevention-card card h-100 p-4 text-center">
                    <div class="step-number bg-warning text-white">4</div>
                    <i class="fas fa-syringe fa-3x text-warning mb-3"></i>
                    <h5>Get Vaccinated</h5>
                    <p class="text-muted">
                        Get your COVID-19 vaccine when it's available to you.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Detailed Prevention Guidelines -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <h3 class="text-center mb-5">Detailed Prevention Guidelines</h3>
                
                <div class="accordion" id="preventionAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#mask-guidelines">
                                <i class="fas fa-head-side-mask me-3 text-primary"></i>
                                <strong>Mask Guidelines</strong>
                            </button>
                        </h2>
                        <div id="mask-guidelines" class="accordion-collapse collapse show" data-bs-parent="#preventionAccordion">
                            <div class="accordion-body">
                                <ul>
                                    <li>Wear masks in public settings, especially indoors</li>
                                    <li>Choose masks with multiple layers of fabric</li>
                                    <li>Ensure the mask covers both nose and mouth</li>
                                    <li>Wash cloth masks after each use</li>
                                    <li>Don't touch the mask while wearing it</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#hygiene-guidelines">
                                <i class="fas fa-hands-wash me-3 text-success"></i>
                                <strong>Hand Hygiene</strong>
                            </button>
                        </h2>
                        <div id="hygiene-guidelines" class="accordion-collapse collapse" data-bs-parent="#preventionAccordion">
                            <div class="accordion-body">
                                <ul>
                                    <li>Wash hands with soap and water for 20+ seconds</li>
                                    <li>Use hand sanitizer with at least 60% alcohol</li>
                                    <li>Avoid touching your face, nose, and mouth</li>
                                    <li>Clean hands before eating or drinking</li>
                                    <li>Wash hands after using the bathroom</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#distance-guidelines">
                                <i class="fas fa-people-arrows me-3 text-info"></i>
                                <strong>Social Distancing</strong>
                            </button>
                        </h2>
                        <div id="distance-guidelines" class="accordion-collapse collapse" data-bs-parent="#preventionAccordion">
                            <div class="accordion-body">
                                <ul>
                                    <li>Stay 6 feet away from people outside your household</li>
                                    <li>Avoid crowded places and mass gatherings</li>
                                    <li>Shop during off-peak hours when possible</li>
                                    <li>Work from home if possible</li>
                                    <li>Use contactless payment methods</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#cleaning-guidelines">
                                <i class="fas fa-spray-can me-3 text-danger"></i>
                                <strong>Cleaning & Disinfecting</strong>
                            </button>
                        </h2>
                        <div id="cleaning-guidelines" class="accordion-collapse collapse" data-bs-parent="#preventionAccordion">
                            <div class="accordion-body">
                                <ul>
                                    <li>Clean and disinfect frequently touched surfaces daily</li>
                                    <li>Use EPA-approved disinfectants</li>
                                    <li>Clean phones, keyboards, and doorknobs regularly</li>
                                    <li>Disinfect groceries and packages if concerned</li>
                                    <li>Increase ventilation in indoor spaces</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Vaccination Information -->
<section class="py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h3 class="mb-4">Get Your COVID-19 Vaccination</h3>
                <p class="lead text-muted mb-4">
                    Vaccination is one of the most effective ways to protect yourself and others from COVID-19.
                </p>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        Safe and effective vaccines available
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        Reduces risk of severe illness
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        Helps protect your community
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        Free at participating locations
                    </li>
                </ul>
                <a href="patient_search.php?service=vaccination" class="btn btn-success btn-lg">
                    <i class="fas fa-syringe me-2"></i>Book Vaccination
                </a>
            </div>
            <div class="col-lg-6 text-center">
                <i class="fas fa-syringe display-1 text-success"></i>
            </div>
        </div>
<!-- Custom CSS for Prevention Cards -->
<style>
    .prevention-card {
        transition: transform 0.3s ease;
        border: none;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        position: relative;
    }
    
    .prevention-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }
    
    .step-number {
        position: absolute;
        top: -15px;
        right: 20px;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 0.9rem;
    }
    
    .accordion-button:not(.collapsed) {
        background-color: #f8f9fa;
        border-color: #dee2e6;
    }
    
    .accordion-button i {
        flex-shrink: 0;
    }
</style>

<?php include('./layout/app_footer.php'); ?>