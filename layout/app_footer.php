    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- COVID Info Modal -->
    <div class="modal fade" id="covidInfoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">COVID-19 Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalBody">
                    <!-- Content will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Custom JS -->
    <script>
        // Show Quick Statistics Modal
        function showQuickStats() {
            document.getElementById('modalTitle').innerHTML = '<i class="fas fa-chart-bar me-2"></i>Live COVID-19 Statistics';
            document.getElementById('modalBody').innerHTML = `
                <div class="row text-center">
                    <div class="col-md-3 mb-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <i class="fas fa-users fa-2x mb-2"></i>
                                <h4>10,247</h4>
                                <small>Registered Patients</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <i class="fas fa-hospital fa-2x mb-2"></i>
                                <h4>156</h4>
                                <small>Active Hospitals</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <i class="fas fa-vial fa-2x mb-2"></i>
                                <h4>52,891</h4>
                                <small>Tests Conducted</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <i class="fas fa-syringe fa-2x mb-2"></i>
                                <h4>28,456</h4>
                                <small>Vaccinations Given</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="alert alert-info mt-3">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Note:</strong> Statistics are updated in real-time and reflect data from all partner healthcare facilities.
                </div>
            `;
            new bootstrap.Modal(document.getElementById('covidInfoModal')).show();
        }
        
        // Show Emergency Contacts Modal
        function showEmergencyContacts() {
            document.getElementById('modalTitle').innerHTML = '<i class="fas fa-phone-alt me-2"></i>Emergency Contacts';
            document.getElementById('modalBody').innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <div class="card border-danger">
                            <div class="card-header bg-danger text-white">
                                <h6 class="mb-0"><i class="fas fa-ambulance me-2"></i>Emergency Services</h6>
                            </div>
                            <div class="card-body">
                                <p><strong>Emergency Hotline:</strong><br>
                                <a href="tel:+92115" class="text-danger fs-4">115</a></p>
                                <p><strong>COVID-19 Helpline:</strong><br>
                                <a href="tel:+921166" class="text-danger fs-4">1166</a></p>
                                <p><strong>Ambulance Service:</strong><br>
                                <a href="tel:+921122" class="text-danger fs-4">1122</a></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-primary">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0"><i class="fas fa-hospital me-2"></i>COVID Support</h6>
                            </div>
                            <div class="card-body">
                                <p><strong>Testing Information:</strong><br>
                                <a href="tel:+92213456789" class="text-primary">+92 21 3456 789</a></p>
                                <p><strong>Vaccination Queries:</strong><br>
                                <a href="tel:+92214567890" class="text-primary">+92 21 4567 890</a></p>
                                <p><strong>General Support:</strong><br>
                                <a href="tel:+92215678901" class="text-primary">+92 21 5678 901</a></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="alert alert-warning mt-3">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Emergency Symptoms:</strong> If you experience severe difficulty breathing, persistent chest pain, 
                    confusion, or bluish lips/face, call emergency services immediately.
                </div>
            `;
            new bootstrap.Modal(document.getElementById('covidInfoModal')).show();
        }
        
        // Auto-hide alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                if (alert.querySelector('.btn-close')) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }
            });
        }, 5000);
        
        // Add smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>
    
    <?php if(isset($additional_js)) echo $additional_js; ?>
</body>
</html>
