<?php
$page_title = "COVID-19 Symptoms - COVID-19 Portal";
include 'layout/app_header.php';
?>

<style>
    .hero-symptoms {
        background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
        color: white;
        padding: 80px 0;
    }
    .symptom-card {
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .symptom-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    .severity-indicator {
        width: 100%;
        height: 6px;
        border-radius: 3px;
        margin-top: 10px;
    }
    .severity-mild { background: linear-gradient(90deg, #28a745, #20c997); }
    .severity-moderate { background: linear-gradient(90deg, #ffc107, #fd7e14); }
    .severity-severe { background: linear-gradient(90deg, #dc3545, #c82333); }
</style>

<!-- Hero Section -->
<section class="hero-symptoms">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">
                    <i class="fas fa-thermometer-half me-3"></i>
                    COVID-19 Symptoms
                </h1>
                <p class="lead mb-4">
                    Learn to recognize the signs and symptoms of COVID-19. Early detection can help protect you and others.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="patient_search.php?service=testing" class="btn btn-light btn-lg px-4">
                        <i class="fas fa-vial me-2"></i>Get Tested
                    </a>
                    <a href="prevention.php" class="btn btn-outline-light btn-lg px-4">
                        <i class="fas fa-shield-alt me-2"></i>Prevention Tips
                    </a>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <img src="images/symptoms.png" alt="COVID-19 Symptoms" class="img-fluid" style="max-height: 400px;">
            </div>
        </div>
    </div>
</section>

<!-- Alert Banner -->
<div class="alert alert-danger alert-dismissible fade show m-0" role="alert">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <strong><i class="fas fa-exclamation-triangle me-2"></i>Important:</strong>
                If you experience severe symptoms like difficulty breathing, persistent chest pain, or confusion, seek emergency medical care immediately.
            </div>
            <div class="col-md-4 text-md-end">
                <a href="patient_search.php" class="btn btn-outline-danger btn-sm">
                    <i class="fas fa-hospital me-1"></i>Find Emergency Care
                </a>
            </div>
        </div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>


    <!-- MAIN -->
<!-- Main Symptoms Section -->
<section class="py-5">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-lg-8 mx-auto">
                <h2 class="display-5 fw-bold">COVID-19 Symptoms</h2>
                <p class="lead text-muted">
                    COVID-19 affects different people in different ways. Here are the most common symptoms to watch for.
                </p>
            </div>
        </div>
        
        <!-- Common Symptoms -->
        <div class="row mb-5">
            <div class="col-12 mb-4">
                <h3 class="text-primary mb-3">
                    <i class="fas fa-exclamation-circle me-2"></i>Most Common Symptoms
                </h3>
            </div>
            
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="symptom-card card h-100 p-4">
                    <div class="text-center mb-3">
                        <i class="fas fa-thermometer-full fa-3x text-danger"></i>
                    </div>
                    <h5 class="card-title text-center">Fever</h5>
                    <p class="card-text text-center text-muted">
                        High temperature (100.4°F or higher). Often the first symptom to appear.
                    </p>
                    <div class="severity-indicator severity-severe"></div>
                    <small class="text-muted mt-2 d-block text-center">High Priority</small>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="symptom-card card h-100 p-4">
                    <div class="text-center mb-3">
                        <i class="fas fa-lungs fa-3x text-info"></i>
                    </div>
                    <h5 class="card-title text-center">Dry Cough</h5>
                    <p class="card-text text-center text-muted">
                        Persistent cough that doesn't produce phlegm or mucus.
                    </p>
                    <div class="severity-indicator severity-severe"></div>
                    <small class="text-muted mt-2 d-block text-center">High Priority</small>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="symptom-card card h-100 p-4">
                    <div class="text-center mb-3">
                        <i class="fas fa-tired fa-3x text-warning"></i>
                    </div>
                    <h5 class="card-title text-center">Fatigue</h5>
                    <p class="card-text text-center text-muted">
                        Unusual tiredness or exhaustion that doesn't improve with rest.
                    </p>
                    <div class="severity-indicator severity-moderate"></div>
                    <small class="text-muted mt-2 d-block text-center">Moderate Priority</small>
                </div>
            </div>
        </div>
        
        <!-- Other Symptoms -->
        <div class="row mb-5">
            <div class="col-12 mb-4">
                <h3 class="text-info mb-3">
                    <i class="fas fa-list-ul me-2"></i>Other Common Symptoms
                </h3>
            </div>
            
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="symptom-card card h-100 p-3">
                    <div class="text-center mb-2">
                        <i class="fas fa-head-side-cough fa-2x text-primary"></i>
                    </div>
                    <h6 class="card-title text-center">Shortness of Breath</h6>
                    <div class="severity-indicator severity-severe"></div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="symptom-card card h-100 p-3">
                    <div class="text-center mb-2">
                        <i class="fas fa-eye-slash fa-2x text-secondary"></i>
                    </div>
                    <h6 class="card-title text-center">Loss of Taste/Smell</h6>
                    <div class="severity-indicator severity-moderate"></div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="symptom-card card h-100 p-3">
                    <div class="text-center mb-2">
                        <i class="fas fa-head-side-virus fa-2x text-danger"></i>
                    </div>
                    <h6 class="card-title text-center">Headache</h6>
                    <div class="severity-indicator severity-mild"></div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="symptom-card card h-100 p-3">
                    <div class="text-center mb-2">
                        <i class="fas fa-throat fa-2x text-warning"></i>
                    </div>
                    <h6 class="card-title text-center">Sore Throat</h6>
                    <div class="severity-indicator severity-mild"></div>
                </div>
            </div>
        </div>
        
        <!-- Emergency Symptoms -->
        <div class="row">
            <div class="col-12">
                <div class="alert alert-danger border-0 shadow-sm">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="alert-heading">
                                <i class="fas fa-ambulance me-2"></i>Emergency Symptoms - Seek Immediate Care
                            </h4>
                            <ul class="mb-0">
                                <li>Difficulty breathing or shortness of breath</li>
                                <li>Persistent chest pain or pressure</li>
                                <li>New confusion or inability to stay awake</li>
                                <li>Bluish lips or face</li>
                            </ul>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <a href="patient_search.php" class="btn btn-light btn-lg">
                                <i class="fas fa-hospital me-2"></i>Find Emergency Care
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Action Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row text-center">
            <div class="col-lg-8 mx-auto">
                <h3 class="mb-4">What to Do If You Have Symptoms</h3>
                <p class="lead text-muted mb-4">
                    If you think you might have COVID-19, take these important steps to protect yourself and others.
                </p>
                
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-4">
                                <i class="fas fa-vial fa-3x text-info mb-3"></i>
                                <h5>Get Tested</h5>
                                <p class="text-muted">Book a COVID-19 test immediately to confirm your status.</p>
                                <a href="patient_search.php?service=testing" class="btn btn-info">
                                    <i class="fas fa-calendar me-2"></i>Book Test
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-4">
                                <i class="fas fa-home fa-3x text-warning mb-3"></i>
                                <h5>Stay Home</h5>
                                <p class="text-muted">Isolate yourself to prevent spreading the virus to others.</p>
                                <a href="prevention.php" class="btn btn-warning">
                                    <i class="fas fa-shield-alt me-2"></i>Learn More
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-4">
                                <i class="fas fa-phone fa-3x text-success mb-3"></i>
                                <h5>Contact Doctor</h5>
                                <p class="text-muted">Call your healthcare provider for guidance and treatment.</p>
                                <a href="patient_search.php" class="btn btn-success">
                                    <i class="fas fa-search me-2"></i>Find Doctor
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
      </div>
    </div>


    <div class="site-section">
      <div class="container">
        <div class="row mb-5">
          <div class="col-lg-7 mx-auto text-center">
            <span class="subheading">What you need to do</span>
            <h2 class="mb-4 section-heading">How To Protect Yourself</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ex officia quas, modi sit eligendi numquam!</p>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-6 ">
            <div class="row mt-5 pt-5">
              <div class="col-lg-6 do ">
                <h3>You should do</h3>
                <ul class="list-unstyled check">
                  <li>Stay at home</li>
                  <li>Wear mask</li>
                  <li>Use Sanitizer</li>
                  <li>Disinfect your home</li>
                  <li>Wash your hands</li>
                  <li>Frequent self-isolation</li>
                </ul>
              </div>
              <div class="col-lg-6 dont ">
                <h3>You should avoid</h3>
                <ul class="list-unstyled cross">
                  <li>Avoid infected people</li>
                  <li>Avoid animals</li>
                  <li>Avoid handshaking</li>
                  <li>Aviod infected surfaces</li>
                  <li>Don't touch your face</li>
                  <li>Avoid droplets</li>
                </ul>
              </div>
            </div>
          </div>
          <div class="col-lg-6">
            <img src="images/protect.png" alt="Image" class="img-fluid">
          </div>
        </div>
      </div>
    </div>

<?php include 'layout/app_footer.php'; ?>