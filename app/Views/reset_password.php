<?= $this->extend("components/brochure_template") ?>

<?= $this->section("content") ?>
<div class="container-sm mt-5">
    <div class="row d-flex justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <h2 class="h4 mb-3">Reset Password</h2>
                        <p class="text-muted">Create your new password</p>
                    </div>

                    <?php if (session()->has('error')): ?>
                        <div class="alert alert-danger"><?= session('error') ?></div>
                    <?php endif; ?>

                    <?php if (session()->has('success')): ?>
                        <div class="alert alert-success"><?= session('success') ?></div>
                    <?php endif; ?>

                    <form action="<?= base_url('reset-password/update') ?>" method="post" class="needs-validation" novalidate>
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="password" class="form-label">New Password</label>
                            <div class="input-group">
                                <input type="password" 
                                       class="form-control form-control-lg" 
                                       id="password" 
                                       name="password" 
                                       required 
                                       minlength="8">
                                <button class="btn btn-outline-secondary toggle-password" type="button">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            <div class="password-strength mt-2">
                                <div class="progress" style="height: 5px;">
                                    <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                                </div>
                                <small class="password-strength-text text-muted">Password strength</small>
                            </div>
                            <div class="form-text">
                                Password must contain:<br>
                                - At least 8 characters<br>
                                - At least one uppercase letter<br>
                                - At least one lowercase letter<br>
                                - At least one number<br>
                                - At least one special character (@$!%*#?&_)
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="confirm_password" class="form-label">Confirm Password</label>
                            <div class="input-group">
                                <input type="password" 
                                       class="form-control form-control-lg" 
                                       id="confirm_password" 
                                       name="confirm_password" 
                                       required>
                                <button class="btn btn-outline-secondary toggle-password" type="button">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            <div class="invalid-feedback">Passwords do not match</div>
                        </div>

                        <button class="btn btn-primary w-100 py-2 mb-3 rounded-3" type="submit">
                            <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                            Reset Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirm_password');
    const submitBtn = form.querySelector('button[type="submit"]');

    // Password strength checker
    function calculatePasswordStrength(password) {
        let strength = 0;
        if (password.length >= 8) strength += 20;
        if (password.match(/[A-Z]/)) strength += 20;
        if (password.match(/[a-z]/)) strength += 20;
        if (password.match(/[0-9]/)) strength += 20;
        if (password.match(/[^A-Za-z0-9]/)) strength += 20;

        let colorClass = '';
        let text = '';

        if (strength <= 40) {
            colorClass = 'bg-danger';
            text = 'Weak';
        } else if (strength <= 80) {
            colorClass = 'bg-warning';
            text = 'Moderate';
        } else {
            colorClass = 'bg-success';
            text = 'Strong';
        }

        return { strength, colorClass, text };
    }

    // Password strength indicator
    password.addEventListener('input', function() {
        const result = calculatePasswordStrength(this.value);
        const progressBar = document.querySelector('.progress-bar');
        const strengthText = document.querySelector('.password-strength-text');

        progressBar.style.width = result.strength + '%';
        progressBar.className = 'progress-bar ' + result.colorClass;
        strengthText.textContent = 'Password strength: ' + result.text;
    });

    // Password visibility toggle
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function() {
            const input = this.previousElementSibling;
            const icon = this.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        });
    });

    // Form validation
    form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        } else if (password.value !== confirmPassword.value) {
            event.preventDefault();
            confirmPassword.setCustomValidity('Passwords do not match');
        } else {
            submitBtn.disabled = true;
            submitBtn.querySelector('.spinner-border').classList.remove('d-none');
        }
        
        form.classList.add('was-validated');
    });

    // Clear custom validity on input
    confirmPassword.addEventListener('input', function() {
        this.setCustomValidity('');
    });
});
</script>
<?= $this->endSection() ?>