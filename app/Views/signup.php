<?= $this->extend("components/brochure_template") ?>

<?= $this->section("content") ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Card Container -->
            <div class="card shadow-lg border-0 rounded-lg overflow-hidden">
                <div class="row g-0">
                    <!-- Left Side - Form -->
                    <div class="col-lg-8 p-5">
                        <!-- Header -->
                        <div class="text-center mb-4">
                            <h2 class="fw-bold text-primary mb-3"><?= $pageTitle ?></h2>
                            <p class="text-muted">Create your account to manage your memorial park services</p>
                        </div>

                        <!-- Progress Steps -->
                        <div class="mb-5">
                            <div class="progress-steps">
                                <div class="step active" data-step="1">
                                    <div class="step-circle">1</div>
                                    <div class="step-label">Personal Info</div>
                                </div>
                                <div class="step" data-step="2">
                                    <div class="step-circle">2</div>
                                    <div class="step-label">Beneficiary</div>
                                </div>
                                <div class="step" data-step="3">
                                    <div class="step-circle">3</div>
                                    <div class="step-label">Complete</div>
                                </div>
                            </div>
                        </div>

                        <!-- Form -->
                        <form class="needs-validation" novalidate action="<?= base_url('signup/submit') ?>" method="post" id="signupForm">
                            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">

                            <!-- Personal Information Section -->
                            <div class="form-section active" id="section-1">
                                <h4 class="mb-4 text-primary">
                                    <i class="bi bi-person-circle me-2"></i>Personal Information
                                </h4>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="firstName" class="form-label">First Name <span class="text-danger">*</span></label>
                                        <input type="text" name="first_name" required id="firstName" class="form-control form-control-lg" placeholder="Juan">
                                        <div class="invalid-feedback">Please enter your first name.</div>
                                        <small class="form-text text-muted">Legal first name as on official documents</small>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="middleName" class="form-label">Middle Name</label>
                                        <input type="text" name="middle_name" id="middleName" class="form-control form-control-lg" placeholder="Dela Cruz">
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="lastName" class="form-label">Last Name <span class="text-danger">*</span></label>
                                        <input type="text" name="last_name" required id="lastName" class="form-control form-control-lg" placeholder="Santos">
                                        <div class="invalid-feedback">Please enter your last name.</div>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="suffix" class="form-label">Suffix</label>
                                        <select name="suffix" id="suffix" class="form-select form-select-lg">
                                            <option value="" selected>None</option>
                                            <option value="Sr.">Sr.</option>
                                            <option value="Jr.">Jr.</option>
                                            <option value="I">I</option>
                                            <option value="II">II</option>
                                            <option value="III">III</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" name="email" required id="email" class="form-control form-control-lg" placeholder="juansantos@example.com">
                                    <div class="invalid-feedback">Please enter a valid email address.</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="contactNumber" class="form-label">Contact Number <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">+63</span>
                                        <input type="text" name="contact_number" required id="contactNumber" class="form-control form-control-lg" placeholder="9123456789">
                                    </div>
                                    <div class="invalid-feedback">Please enter a valid Philippine mobile number.</div>
                                    <small class="form-text text-muted">Format: 9123456789 (no spaces or dashes)</small>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="password" name="password" required id="password" class="form-control form-control-lg">
                                            <button class="btn btn-outline-secondary toggle-password" type="button">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </div>
                                        <div class="invalid-feedback">Password must be at least 8 characters with special characters.</div>
                                        <div class="password-strength mt-2">
                                            <div class="progress" style="height: 5px;">
                                                <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                                            </div>
                                            <small class="text-muted password-strength-text">Password strength</small>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="confirmPassword" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="password" name="confirm_password" required id="confirmPassword" class="form-control form-control-lg">
                                            <button class="btn btn-outline-secondary toggle-password" type="button">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </div>
                                        <div class="invalid-feedback">Passwords do not match.</div>
                                    </div>
                                </div>
                                
                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="btn btn-outline-primary btn-lg disabled">Previous</button>
                                    <button type="button" class="btn btn-primary btn-lg next-section" data-next="2">Next: Beneficiary Info</button>
                                </div>
                            </div>

                            <!-- Beneficiary Information Section -->
                            <div class="form-section" id="section-2">
                                <h4 class="mb-4 text-primary">
                                    <i class="bi bi-people-fill me-2"></i>Beneficiary Information
                                </h4>
                                <p class="text-muted mb-4">Please provide details of your primary beneficiary. You can add more later.</p>
                                
                                <div class="mb-3">
                                    <label for="beneficiaryRelationship" class="form-label">Relationship <span class="text-danger">*</span></label>
                                    <select name="beneficiary_relationship" id="beneficiaryRelationship" class="form-select form-select-lg" required>
                                        <option value="" selected disabled>Select relationship</option>
                                        <option value="Spouse">Spouse</option>
                                        <option value="Child">Child</option>
                                        <option value="Parent">Parent</option>
                                        <option value="Sibling">Sibling</option>
                                        <option value="Friend">Friend</option>
                                        <option value="Other">Other</option>
                                    </select>
                                    <div class="invalid-feedback">Please select a relationship.</div>
                                </div>
                                
                                <div class="mb-3" id="otherRelationshipContainer" style="display: none;">
                                    <label for="beneficiaryOtherRelationship" class="form-label">Specify Relationship</label>
                                    <input type="text" name="beneficiary_other_relationship" id="beneficiaryOtherRelationship" class="form-control form-control-lg">
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="beneficiaryFirstName" class="form-label">First Name <span class="text-danger">*</span></label>
                                        <input type="text" name="beneficiary_first_name" required id="beneficiaryFirstName" class="form-control form-control-lg">
                                        <div class="invalid-feedback">Please enter beneficiary's first name.</div>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="beneficiaryMiddleName" class="form-label">Middle Name</label>
                                        <input type="text" name="beneficiary_middle_name" id="beneficiaryMiddleName" class="form-control form-control-lg">
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="beneficiaryLastName" class="form-label">Last Name <span class="text-danger">*</span></label>
                                        <input type="text" name="beneficiary_last_name" required id="beneficiaryLastName" class="form-control form-control-lg">
                                        <div class="invalid-feedback">Please enter beneficiary's last name.</div>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="beneficiarySuffix" class="form-label">Suffix</label>
                                        <select name="beneficiary_suffix" id="beneficiarySuffix" class="form-select form-select-lg">
                                            <option value="" selected>None</option>
                                            <option value="Sr.">Sr.</option>
                                            <option value="Jr.">Jr.</option>
                                            <option value="I">I</option>
                                            <option value="II">II</option>
                                            <option value="III">III</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="beneficiaryContactNumber" class="form-label">Contact Number <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">+63</span>
                                        <input type="text" name="beneficiary_contact_number" required id="beneficiaryContactNumber" class="form-control form-control-lg" placeholder="9123456789">
                                    </div>
                                    <div class="invalid-feedback">Please enter a valid Philippine mobile number.</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="beneficiaryEmail" class="form-label">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" name="beneficiary_email" required id="beneficiaryEmail" class="form-control form-control-lg">
                                    <div class="invalid-feedback">Please enter a valid email address.</div>
                                </div>
                                
                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="btn btn-outline-primary btn-lg prev-section" data-prev="1">
                                        <i class="bi bi-arrow-left me-2"></i>Previous
                                    </button>
                                    <button type="submit" class="btn btn-success btn-lg">
                                        <i class="bi bi-check-circle me-2"></i>Submit
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Right Side - Visual -->
                    <div class="col-lg-4 d-none d-lg-flex bg-primary-light align-items-center justify-content-center p-5">
                        <div class="text-center welcome-panel">
                            <img src="<?= base_url('img/ghmp_logo.png') ?>" alt="Sign Up" class="img-fluid mb-4" style="max-width: 200px;">
                            <h3 class="mb-3">Welcome to Green Haven Memorial Park</h3>
                            <p class="mb-4">Join our community to manage memorial services with ease and peace of mind.</p>
                            <div class="d-flex align-items-center justify-content-center">
                                <i class="bi bi-check-circle-fill me-2"></i>
                                <span>Secure account management</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-center my-2">
                                <i class="bi bi-check-circle-fill me-2"></i>
                                <span>24/7 customer support</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-center">
                                <i class="bi bi-check-circle-fill me-2"></i>
                                <span>Easy beneficiary management</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-4">
                <p>Already have an account? <a href="<?= base_url('signin') ?>" class="text-primary fw-bold">Sign In</a></p>
                <a href="<?= base_url('ownership_transfer/request') ?>" class="btn btn-link text-decoration-none">
                    <i class="bi bi-arrow-repeat me-1"></i>Account Ownership Transfer Request
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Add Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

<style>
    :root {
        --primary-color: #2e7d32;
        --primary-light: #e8f5e9;
        --secondary-color: #689f38;
        --dark-green: #1b5e20;
    }
    
    body {
        background-color: #f8f9fa;
    }
    
    .card {
        border: none;
        border-radius: 15px;
        overflow: hidden;
    }
    
    .bg-primary-light {
        background-color: var(--primary-light);
    }
    
    .text-primary {
        color: var(--primary-color) !important;
    }
    
    .btn-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }
    
    .btn-outline-primary {
        color: var(--primary-color);
        border-color: var(--primary-color);
    }
    
    .btn-outline-primary:hover {
        background-color: var(--primary-color);
        color: white;
    }
    
    .form-control, .form-select {
        border-radius: 8px;
        padding: 12px 15px;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.25rem rgba(46, 125, 50, 0.25);
    }
    
    .form-control-lg {
        font-size: 1rem;
    }
    
    .progress-steps {
        display: flex;
        justify-content: space-between;
        position: relative;
    }
    
    .progress-steps::before {
        content: "";
        position: absolute;
        top: 15px;
        left: 0;
        right: 0;
        height: 2px;
        background-color: #dee2e6;
        z-index: 0;
    }
    
    .step {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        z-index: 1;
    }
    
    .step-circle {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background-color: #dee2e6;
        color: #6c757d;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin-bottom: 5px;
    }
    
    .step.active .step-circle {
        background-color: var(--primary-color);
        color: white;
    }
    
    .step-label {
        font-size: 0.85rem;
        color: #6c757d;
    }
    
    .step.active .step-label {
        color: var(--primary-color);
        font-weight: 500;
    }
    
    .form-section {
        display: none;
    }
    
    .form-section.active {
        display: block;
        animation: fadeIn 0.5s ease;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .password-strength .progress-bar {
        transition: width 0.3s ease;
    }
    
    .toggle-password {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }
    
    .input-group-text {
        background-color: #f8f9fa;
    }
    
    /* Welcome Panel Styling */
    .welcome-panel h3 {
        color: var(--dark-green) !important;
        font-weight: 600;
        font-size: 1.5rem;
    }
    
    .welcome-panel p, 
    .welcome-panel span {
        color: var(--primary-color) !important;
        font-size: 1rem;
    }
    
    .welcome-panel .bi {
        color: var(--primary-color) !important;
    }
    
    .welcome-panel .d-flex {
        margin-bottom: 0.5rem;
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Show first section by default
        $('.form-section').first().addClass('active');
        
        // Next section button
        $('.next-section').click(function() {
            const currentSection = $(this).closest('.form-section');
            const nextSectionId = $(this).data('next');
            
            // Validate current section before proceeding
            let isValid = true;
            currentSection.find('[required]').each(function() {
                if (!$(this).val()) {
                    $(this).addClass('is-invalid');
                    isValid = false;
                }
            });
            
            if (isValid) {
                currentSection.removeClass('active');
                $(`#section-${nextSectionId}`).addClass('active');
                
                // Update progress steps
                $('.step').removeClass('active');
                $(`.step[data-step="${nextSectionId}"]`).addClass('active');
            }
        });
        
        // Previous section button
        $('.prev-section').click(function() {
            const prevSectionId = $(this).data('prev');
            $(this).closest('.form-section').removeClass('active');
            $(`#section-${prevSectionId}`).addClass('active');
            
            // Update progress steps
            $('.step').removeClass('active');
            $(`.step[data-step="${prevSectionId}"]`).addClass('active');
        });
        
        // Show/hide other relationship field
        $("#beneficiaryRelationship").change(function() {
            if ($(this).val() === "Other") {
                $("#otherRelationshipContainer").show();
                $("#beneficiaryOtherRelationship").attr("required", true);
            } else {
                $("#otherRelationshipContainer").hide();
                $("#beneficiaryOtherRelationship").removeAttr("required");
            }
        });
        
        // Password visibility toggle
        $(".toggle-password").click(function() {
            const input = $(this).siblings('input');
            const icon = $(this).find('i');
            
            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
                icon.removeClass('bi-eye').addClass('bi-eye-slash');
            } else {
                input.attr('type', 'password');
                icon.removeClass('bi-eye-slash').addClass('bi-eye');
            }
        });
        
        // Password strength indicator
        $("#password").on('input', function() {
            const password = $(this).val();
            const strength = calculatePasswordStrength(password);
            const progressBar = $(this).closest('.mb-3').find('.progress-bar');
            const strengthText = $(this).closest('.mb-3').find('.password-strength-text');
            
            progressBar.css('width', strength.percentage + '%');
            progressBar.removeClass('bg-danger bg-warning bg-success').addClass(strength.class);
            
            strengthText.text(strength.text);
            strengthText.removeClass('text-danger text-warning text-success').addClass(strength.textClass);
        });
        
        function calculatePasswordStrength(password) {
            let strength = 0;
            let text = 'Very Weak';
            let className = 'bg-danger';
            let textClass = 'text-danger';
            
            // Length check
            if (password.length > 0) strength += 10;
            if (password.length >= 4) strength += 10;
            if (password.length >= 8) strength += 20;
            
            // Character type checks
            if (/[A-Z]/.test(password)) strength += 10;
            if (/[a-z]/.test(password)) strength += 10;
            if (/\d/.test(password)) strength += 10;
            if (/[^A-Za-z0-9]/.test(password)) strength += 10;
            
            // Duplicate checks
            if (/([A-Za-z0-9])\1/.test(password)) strength -= 10;
            
            // Determine strength level
            if (strength >= 70) {
                text = 'Strong';
                className = 'bg-success';
                textClass = 'text-success';
            } else if (strength >= 40) {
                text = 'Medium';
                className = 'bg-warning';
                textClass = 'text-warning';
            } else if (strength >= 20) {
                text = 'Weak';
                className = 'bg-danger';
                textClass = 'text-danger';
            }
            
            return {
                percentage: Math.min(strength, 100),
                text: text,
                class: className,
                textClass: textClass
            };
        }
        
        // Form validation
        $("#signupForm").on('submit', function(e) {
            let isValid = true;
            
            // Validate all required fields in active section
            $(this).find('[required]').each(function() {
                if (!$(this).val()) {
                    $(this).addClass('is-invalid');
                    isValid = false;
                }
            });
            
            // Validate email format
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test($("#email").val())) {
                $("#email").addClass('is-invalid');
                isValid = false;
            }
            
            // Validate contact number format (Philippine mobile)
            const contactRegex = /^9\d{9}$/;
            if (!contactRegex.test($("#contactNumber").val())) {
                $("#contactNumber").addClass('is-invalid');
                isValid = false;
            }
            
            // Validate password strength
            const passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/;
            if (!passwordRegex.test($("#password").val())) {
                $("#password").addClass('is-invalid');
                isValid = false;
            }
            
            // Validate password match
            if ($("#password").val() !== $("#confirmPassword").val()) {
                $("#confirmPassword").addClass('is-invalid');
                isValid = false;
            }
            
            if (!isValid) {
                e.preventDefault();
                // Scroll to first invalid field
                $('html, body').animate({
                    scrollTop: $(".is-invalid").first().offset().top - 100
                }, 500);
            }
        });
        
        // Real-time validation for required fields
        $('[required]').on('input', function() {
            if ($(this).val()) {
                $(this).removeClass('is-invalid');
            }
        });
    });
</script>
<?= $this->endSection() ?>