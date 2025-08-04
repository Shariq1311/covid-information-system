<?php
$page_title = "COVID Info Test - COVID-19 Portal";
include('./layout/app_header.php');
?>

<!-- Test Section for COVID Info Features -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="fas fa-vial me-2"></i>COVID Info Test Page</h4>
                    </div>
                    <div class="card-body">
                        <p class="lead">Test the enhanced COVID Info dropdown and modal functionality:</p>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <h5>Navigation Links</h5>
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <a href="symptoms.php" class="text-decoration-none">
                                            <i class="fas fa-thermometer-half me-2"></i>Symptoms & Signs
                                        </a>
                                    </li>
                                    <li class="list-group-item">
                                        <a href="prevention.php" class="text-decoration-none">
                                            <i class="fas fa-shield-alt me-2"></i>Prevention Guidelines
                                        </a>
                                    </li>
                                    <li class="list-group-item">
                                        <a href="about.php" class="text-decoration-none">
                                            <i class="fas fa-info-circle me-2"></i>About COVID-19
                                        </a>
                                    </li>
                                    <li class="list-group-item">
                                        <a href="blog.php" class="text-decoration-none">
                                            <i class="fas fa-newspaper me-2"></i>Health Blog
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            
                            <div class="col-md-6">
                                <h5>Interactive Features</h5>
                                <div class="d-grid gap-2">
                                    <button class="btn btn-primary" onclick="showQuickStats()">
                                        <i class="fas fa-chart-bar me-2"></i>Live Statistics
                                    </button>
                                    <button class="btn btn-danger" onclick="showEmergencyContacts()">
                                        <i class="fas fa-phone-alt me-2"></i>Emergency Contacts
                                    </button>
                                </div>
                                
                                <div class="mt-3">
                                    <h6>Service Booking Links:</h6>
                                    <div class="btn-group-vertical d-grid gap-1">
                                        <a href="patient_search.php?service=testing" class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-vial me-2"></i>COVID-19 Testing
                                        </a>
                                        <a href="patient_search.php?service=vaccination" class="btn btn-sm btn-outline-success">
                                            <i class="fas fa-syringe me-2"></i>Vaccination Centers
                                        </a>
                                        <a href="patient_search.php" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-hospital me-2"></i>Find Hospitals
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="alert alert-info mt-4">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Test Instructions:</strong>
                            <ol class="mb-0 mt-2">
                                <li>Click on "COVID Info" in the navigation bar to see the enhanced dropdown</li>
                                <li>Try the "Live Statistics" and "Emergency Contacts" buttons above</li>
                                <li>Test the navigation links to different information pages</li>
                                <li>Verify that service booking links work correctly</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include('./layout/app_footer.php'); ?>
