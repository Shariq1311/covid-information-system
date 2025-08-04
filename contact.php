<?php
include 'db_contact.php';

$success = '';
$error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fname = trim($_POST['fname']);
    $lname = trim($_POST['lname']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    $stmt = $conn_contact->prepare("INSERT INTO messages (fname, lname, email, subject, message) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $fname, $lname, $email, $subject, $message);

    if ($stmt->execute()) {
        $success = "Your message has been sent successfully!";
    } else {
        $error = "Failed to send your message. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - COVID-19 Portal</title>
    
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
        
        .contact-card {
            transition: transform 0.3s ease;
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .contact-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }
        
        .contact-icon {
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
                    <h1 class="display-4 fw-bold mb-4">Contact Us</h1>
                    <p class="lead mb-4">
                        We're here to help you with your COVID-19 healthcare needs. 
                        Reach out to us for support, questions, or feedback.
                    </p>
                    <div class="d-flex flex-wrap justify-content-center gap-3">
                        <a href="register.php" class="btn btn-light btn-lg">
                            <i class="fas fa-user-plus me-2"></i>Get Started
                        </a>
                        <a href="patient_search.php" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-search me-2"></i>Find Services
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Contact Info Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-8 mx-auto">
                    <h2 class="display-6 fw-bold">Get In Touch</h2>
                    <p class="lead text-muted">
                        Multiple ways to reach us for your COVID-19 healthcare needs
                    </p>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="contact-card card h-100 text-center p-4">
                        <div class="contact-icon bg-primary bg-opacity-10">
                            <i class="fas fa-map-marker-alt fa-2x text-primary"></i>
                        </div>
                        <h5 class="fw-bold">Visit Us</h5>
                        <p class="text-muted mb-0">123 Healthcare Street<br>Medical District<br>Karachi, Pakistan</p>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3">
                    <div class="contact-card card h-100 text-center p-4">
                        <div class="contact-icon bg-success bg-opacity-10">
                            <i class="fas fa-phone fa-2x text-success"></i>
                        </div>
                        <h5 class="fw-bold">Call Us</h5>
                        <p class="text-muted mb-0">
                            <a href="tel:+921234567890" class="text-decoration-none">+92 123 456 7890</a><br>
                            <small>Mon-Fri 9AM-6PM</small>
                        </p>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3">
                    <div class="contact-card card h-100 text-center p-4">
                        <div class="contact-icon bg-info bg-opacity-10">
                            <i class="fas fa-envelope fa-2x text-info"></i>
                        </div>
                        <h5 class="fw-bold">Email Us</h5>
                        <p class="text-muted mb-0">
                            <a href="mailto:info@covidportal.com" class="text-decoration-none">info@covidportal.com</a><br>
                            <a href="mailto:support@covidportal.com" class="text-decoration-none">support@covidportal.com</a>
                        </p>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3">
                    <div class="contact-card card h-100 text-center p-4">
                        <div class="contact-icon bg-warning bg-opacity-10">
                            <i class="fas fa-clock fa-2x text-warning"></i>
                        </div>
                        <h5 class="fw-bold">Support Hours</h5>
                        <p class="text-muted mb-0">
                            24/7 Emergency Support<br>
                            <small>General queries: 9AM-6PM</small>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Form Section -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card shadow-lg border-0">
                        <div class="card-body p-5">
                            <div class="text-center mb-4">
                                <h3 class="fw-bold">Send Us a Message</h3>
                                <p class="text-muted">We'll get back to you within 24 hours</p>
                            </div>
                            
                            <?php if ($success): ?>
                                <div class="alert alert-success d-flex align-items-center">
                                    <i class="fas fa-check-circle me-2"></i>
                                    <?php echo $success; ?>
                                </div>
                            <?php elseif ($error): ?>
                                <div class="alert alert-danger d-flex align-items-center">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <?php echo $error; ?>
                                </div>
                            <?php endif; ?>
                            
                            <form method="post" class="needs-validation" novalidate>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="fname" class="form-label fw-semibold">First Name <span class="text-danger">*</span></label>
                                        <input type="text" id="fname" name="fname" class="form-control form-control-lg" required pattern="[A-Za-z\s]+" maxlength="100">
                                        <div class="invalid-feedback">Please enter a valid first name.</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="lname" class="form-label fw-semibold">Last Name <span class="text-danger">*</span></label>
                                        <input type="text" id="lname" name="lname" class="form-control form-control-lg" required pattern="[A-Za-z\s]+" maxlength="100">
                                        <div class="invalid-feedback">Please enter a valid last name.</div>
                                    </div>
                                </div>
                                
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label for="email" class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                                        <input type="email" id="email" name="email" class="form-control form-control-lg" required maxlength="150">
                                        <div class="invalid-feedback">Please enter a valid email address.</div>
                                    </div>
                                </div>
                                
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label for="subject" class="form-label fw-semibold">Subject <span class="text-danger">*</span></label>
                                        <input type="text" id="subject" name="subject" class="form-control form-control-lg" required maxlength="255">
                                        <div class="invalid-feedback">Please enter a subject.</div>
                                    </div>
                                </div>
                                
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label for="message" class="form-label fw-semibold">Message <span class="text-danger">*</span></label>
                                        <textarea name="message" id="message" class="form-control" rows="6" required maxlength="1000" placeholder="Tell us how we can help you..."></textarea>
                                        <div class="invalid-feedback">Please enter your message.</div>
                                    </div>
                                </div>
                                
                                <div class="d-grid gap-2 mt-4">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-paper-plane me-2"></i>Send Message
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-8 mx-auto">
                    <h2 class="display-6 fw-bold">Frequently Asked Questions</h2>
                    <p class="lead text-muted">
                        Quick answers to common questions about our COVID-19 portal
                    </p>
                </div>
            </div>
            
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="accordion" id="faqAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                    How do I book a COVID-19 test?
                                </button>
                            </h2>
                            <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Register on our platform, search for nearby hospitals or labs, select your preferred test type, 
                                    choose a convenient time slot, and confirm your booking. You'll receive a confirmation email with all details.
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                    Is vaccination booking free on your platform?
                                </button>
                            </h2>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Yes, our platform is completely free to use. You only pay for the actual medical services 
                                    (tests, vaccinations) directly to the healthcare providers.
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                    How can I cancel or reschedule my appointment?
                                </button>
                            </h2>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Log into your patient dashboard, go to "My Appointments," and click on the appointment you want to modify. 
                                    You can cancel or reschedule up to 2 hours before your appointment time.
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                    Are all hospitals on your platform verified?
                                </button>
                            </h2>
                            <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Yes, all healthcare providers on our platform are thoroughly verified and approved by relevant health authorities. 
                                    We regularly audit our partners to ensure they maintain the highest standards of care.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        // Bootstrap validation
        document.addEventListener("DOMContentLoaded", function() {
            var forms = document.querySelectorAll('.needs-validation');
            Array.prototype.slice.call(forms).forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        });
    </script>

    <?php include('./layout/app_footer.php'); ?>