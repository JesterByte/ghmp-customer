<?= $this->extend("components/brochure_template"); ?>

<?= $this->section("content") ?>
<div class="container-sm mt-5">
    <div class="row d-flex justify-content-center">
        <div class="col-lg-6 col-md-8">
            <!-- Card Container -->
            <div class="card shadow-sm border-0">
                <div class="card-body p-4 p-md-5">
                    <!-- Logo/Branding -->
                    <div class="text-center mb-4">
                        <img src="<?= base_url('img/ghmp_logo.png') ?>" alt="Company Logo" class="mb-3" style="height: 60px;">
                        <h2 class="h4 mb-3">Welcome Back</h2>
                        <p class="text-muted">Sign in to access your account</p>
                    </div>

                    <!-- Form -->
                    <form class="needs-validation" novalidate action="<?= base_url("signin/submit") ?>" method="post">
                        <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">

                        <!-- Email -->
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control rounded-3" id="email" name="email" 
                                   placeholder="Email Address" required
                                   value="<?= old('email') ?>">
                            <label for="email">Email Address</label>
                            <div class="invalid-feedback">
                                Please provide a valid email address.
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control rounded-3" id="password" name="password" 
                                   placeholder="Password" required>
                            <label for="password">Password</label>
                            <div class="invalid-feedback">
                                Please enter your password.
                            </div>
                        </div>

                        <!-- Remember Me & Forgot Password -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                <label class="form-check-label" for="remember">Remember me</label>
                            </div>
                            <a href="<?= base_url('forgot-password') ?>" class="text-decoration-none">Forgot password?</a>
                        </div>

                        <!-- Submit Button -->
                        <button class="btn btn-primary w-100 py-2 mb-3 rounded-3" type="submit">
                            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            Sign In
                        </button>

                        <!-- Social Login Options -->
                        <!-- <div class="text-center mb-4">
                            <p class="text-muted divider-line">or sign in with</p>
                            <div class="d-flex justify-content-center gap-3">
                                <a href="#" class="btn btn-outline-primary rounded-circle p-2">
                                    <i class="bi bi-google"></i>
                                </a>
                                <a href="#" class="btn btn-outline-primary rounded-circle p-2">
                                    <i class="bi bi-facebook"></i>
                                </a>
                                <a href="#" class="btn btn-outline-primary rounded-circle p-2">
                                    <i class="bi bi-apple"></i>
                                </a>
                            </div>
                        </div> -->
                    </form>

                    <!-- Sign Up Link -->
                    <div class="text-center mt-4">
                        <p class="mb-0">Don't have an account? <a href="<?= base_url('signup') ?>" class="text-decoration-none fw-bold">Sign Up</a></p>
                        <a href="<?= base_url('ownership_transfer/request') ?>" class="btn btn-link text-decoration-none mt-2">Account Ownership Transfer Request</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

<style>
    body {
        background-color: #f8f9fa;
    }
    
    .card {
        border-radius: 12px;
        overflow: hidden;
    }
    
    .form-control {
        transition: all 0.3s ease;
    }
    
    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
    
    .btn-primary {
        background-color: #0d6efd;
        border: none;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        background-color: #0b5ed7;
        transform: translateY(-1px);
    }
    
    .divider-line {
        position: relative;
        margin: 1.5rem 0;
    }
    
    .divider-line::before {
        content: "";
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 1px;
        background-color: #dee2e6;
        z-index: -1;
    }
    
    .divider-line p {
        display: inline-block;
        padding: 0 10px;
        background-color: white;
    }
    
    .rounded-3 {
        border-radius: 8px !important;
    }
    
    .rounded-circle {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>

<script>
    // Form validation and submission handling
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('.needs-validation');
        const submitBtn = form.querySelector('button[type="submit"]');
        
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            } else {
                // Show loading spinner on valid submission
                submitBtn.disabled = true;
                submitBtn.querySelector('.spinner-border').classList.remove('d-none');
            }
            
            form.classList.add('was-validated');
        }, false);
        
        // Add focus styles for floating labels
        const floatInputs = document.querySelectorAll('.form-floating input');
        floatInputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentNode.querySelector('label').style.color = '#0d6efd';
            });
            
            input.addEventListener('blur', function() {
                this.parentNode.querySelector('label').style.color = '';
            });
        });
    });
</script>
<?= $this->endSection() ?>